<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Display chat messages between users for a product
     */
    public function index(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'receiver_id' => 'required|exists:users,id'
        ]);

        $product = Product::findOrFail($request->product_id);
        $receiver = User::findOrFail($request->receiver_id);
        
        // Mark previous messages as read
        Chat::where('sender_id', $receiver->id)
            ->where('receiver_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        // Get messages
        $messages = Chat::with(['sender', 'receiver'])
            ->where('product_id', $product->id)
            ->where(function($query) use ($receiver) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $receiver->id);
            })
            ->orWhere(function($query) use ($receiver) {
                $query->where('sender_id', $receiver->id)
                      ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages,
            'product' => $product,
            'receiver' => $receiver
        ]);
    }

    /**
     * Send a new message
     */
    public function send(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|min:1|max:1000'
        ]);

        try {
            $chat = Chat::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $request->receiver_id,
                'product_id' => $request->product_id,
                'message' => $request->message,
                'is_read' => false
            ]);

            // Load relationships
            $chat->load(['sender', 'receiver', 'product']);

            // Broadcast event for real-time chat (if using websockets)
            // broadcast(new NewMessage($chat))->toOthers();

            return response()->json([
                'success' => true,
                'message' => $chat,
                'html' => view('chats.partials.message', ['message' => $chat])->render()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unread message count
     */
    public function unreadCount()
    {
        $count = Chat::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Mark all messages as read
     */
    public function markAllAsRead(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id'
        ]);

        Chat::where('sender_id', $request->sender_id)
            ->where('receiver_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Get chat conversations for the current user
     */
    public function conversations()
    {
        // Get unique conversations (grouped by other user and product)
        $conversations = Chat::select(DB::raw('
                product_id,
                CASE 
                    WHEN sender_id = ? THEN receiver_id 
                    ELSE sender_id 
                END as other_user_id,
                MAX(created_at) as last_message_at
            ', [Auth::id()]))
            ->where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->groupBy('product_id', 'other_user_id')
            ->orderBy('last_message_at', 'desc')
            ->with(['product.images', 'product.seller'])
            ->get()
            ->map(function($item) {
                // Get the other user
                $otherUser = User::find($item->other_user_id);
                
                // Get last message
                $lastMessage = Chat::betweenUsers(Auth::id(), $otherUser->id)
                    ->forProduct($item->product_id)
                    ->latest()
                    ->first();
                
                // Get unread count
                $unreadCount = Chat::where('sender_id', $otherUser->id)
                    ->where('receiver_id', Auth::id())
                    ->where('product_id', $item->product_id)
                    ->where('is_read', false)
                    ->count();

                return [
                    'product' => $item->product,
                    'other_user' => $otherUser,
                    'last_message' => $lastMessage,
                    'unread_count' => $unreadCount,
                    'last_message_at' => $lastMessage->created_at
                ];
            });

        return response()->json([
            'success' => true,
            'conversations' => $conversations
        ]);
    }

    /**
     * Delete a message
     */
    public function destroy($id)
    {
        $chat = Chat::where('id', $id)
            ->where('sender_id', Auth::id())
            ->firstOrFail();

        $chat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully'
        ]);
    }

    /**
     * Clear entire conversation
     */
    public function clearConversation(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'other_user_id' => 'required|exists:users,id'
        ]);

        Chat::betweenUsers(Auth::id(), $request->other_user_id)
            ->forProduct($request->product_id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Conversation cleared successfully'
        ]);
    }
}