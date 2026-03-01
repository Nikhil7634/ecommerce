<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat\ChatRoom;
use App\Models\Chat\ChatMessage;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SupportChatController extends Controller
{
    /**
     * Display chat interface
     */
    public function index(Request $request)
    {
        $roomId = $request->get('room');
        $userId = $request->get('user');

        // Get all active chat rooms
        $chatRooms = ChatRoom::with(['user', 'product'])
            ->with(['messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->where('type', 'admin_support')
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Get unread counts for each room
        foreach ($chatRooms as $room) {
            $room->unread_count = ChatMessage::where('chat_room_id', $room->id)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();
                
            // Get last message
            $room->last_message = ChatMessage::where('chat_room_id', $room->id)
                ->latest()
                ->first();
        }

        // Get current room and messages
        $currentRoom = null;
        $messages = collect([]);

        if ($roomId) {
            $currentRoom = ChatRoom::with(['user', 'product'])->find($roomId);
            
            if ($currentRoom) {
                $messages = ChatMessage::where('chat_room_id', $roomId)
                    ->with(['sender', 'receiver'])
                    ->orderBy('created_at', 'asc')
                    ->get();

                // Mark messages as read for current admin
                ChatMessage::where('chat_room_id', $roomId)
                    ->where('receiver_id', Auth::id())
                    ->where('is_read', false)
                    ->update([
                        'is_read' => true,
                        'read_at' => now()
                    ]);

                // Reset unread count for this room
                $currentRoom->update(['unread_count' => 0]);
            }
        } elseif ($userId) {
            // Create new chat room with specific user
            $user = User::find($userId);
            
            if ($user) {
                $currentRoom = ChatRoom::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'type' => 'admin_support',
                        'status' => 'active'
                    ],
                    [
                        'admin_id' => Auth::id(),
                        'last_message_at' => now(),
                    ]
                );
                
                return redirect()->route('admin.support.chat', ['room' => $currentRoom->id]);
            }
        }

        // Get online users (using updated_at as fallback)
        $onlineUsers = User::whereIn('role', ['buyer', 'seller'])
            ->where('updated_at', '>=', now()->subMinutes(30))
            ->limit(10)
            ->get();

        return view('admin.support.chat', compact(
            'chatRooms', 
            'currentRoom', 
            'messages', 
            'onlineUsers', 
            'roomId'
        ));
    }

    /**
     * Send a message (AJAX)
     */
    public function sendMessage(Request $request)
    {
        try {
            $request->validate([
                'room_id' => 'required|exists:chat_rooms,id',
                'message' => 'required|string|max:2000',
            ]);

            $room = ChatRoom::findOrFail($request->room_id);
            
            // Determine receiver (if admin is sending, receiver is user, and vice versa)
            $receiverId = ($room->user_id == Auth::id()) ? $room->admin_id : $room->user_id;

            $message = ChatMessage::create([
                'chat_room_id' => $room->id,
                'sender_id' => Auth::id(),
                'receiver_id' => $receiverId,
                'message' => $request->message,
                'type' => 'text',
                'is_read' => false,
            ]);

            // Update room's last message time
            $room->update(['last_message_at' => now()]);

            // Increment unread count for receiver
            if ($receiverId && $receiverId != Auth::id()) {
                $room->increment('unread_count');
            }

            // Load sender relationship
            $message->load('sender');

            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'text' => $message->message,
                    'time' => $message->created_at->format('h:i A'),
                    'date' => $message->created_at->format('d M Y'),
                    'sender_id' => $message->sender_id,
                    'sender_name' => $message->sender->name,
                    'sender_avatar' => $message->sender->avatar_url ?? asset('admin-assets/assets/images/admin.png'),
                    'is_admin' => $message->sender_id == Auth::id(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Send message error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error sending message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get messages for a room (AJAX)
     */
    public function getMessages($roomId)
    {
        try {
            $room = ChatRoom::findOrFail($roomId);

            $messages = ChatMessage::where('chat_room_id', $roomId)
                ->with('sender')
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function($msg) {
                    return [
                        'id' => $msg->id,
                        'text' => $msg->message,
                        'time' => $msg->created_at->format('h:i A'),
                        'date' => $msg->created_at->format('d M Y'),
                        'sender_id' => $msg->sender_id,
                        'sender_name' => $msg->sender->name,
                        'sender_avatar' => $msg->sender->avatar_url ?? asset('admin-assets/assets/images/avatar.png'),
                        'is_admin' => $msg->sender_id == Auth::id(),
                        'is_read' => $msg->is_read,
                    ];
                });

            return response()->json([
                'success' => true,
                'messages' => $messages,
            ]);

        } catch (\Exception $e) {
            Log::error('Get messages error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading messages'
            ], 500);
        }
    }

    /**
     * Mark messages as read for a room (AJAX)
     */
    public function markAsRead($roomId)
    {
        try {
            $room = ChatRoom::findOrFail($roomId);
            
            $updated = ChatMessage::where('chat_room_id', $roomId)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now()
                ]);

            if ($updated) {
                $room->update(['unread_count' => 0]);
            }

            return response()->json([
                'success' => true,
                'updated' => $updated
            ]);

        } catch (\Exception $e) {
            Log::error('Mark as read error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error marking messages as read'
            ], 500);
        }
    }

    /**
     * Get unread message count (AJAX)
     */
    public function unreadCount()
    {
        try {
            $count = ChatMessage::where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'count' => 0
            ], 500);
        }
    }

    /**
     * Close chat room
     */
    public function closeRoom($roomId)
    {
        try {
            $room = ChatRoom::findOrFail($roomId);
            $room->update([
                'status' => 'closed',
            ]);

            return redirect()->route('admin.support.chat')
                ->with('success', 'Chat room closed successfully.');

        } catch (\Exception $e) {
            Log::error('Close room error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error closing chat room.');
        }
    }

     /**
     * Upload attachment (AJAX)
     */
    public function uploadAttachment(Request $request)
    {
        try {
            $request->validate([
                'room_id' => 'required|exists:chat_rooms,id',
                'attachment' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx|max:5120',
            ]);

            $room = ChatRoom::findOrFail($request->room_id);
            
            // Store the file
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('chat-attachments', $fileName, 'public');
            
            // Determine receiver
            $receiverId = ($room->user_id == Auth::id()) ? $room->admin_id : $room->user_id;

            // Create message
            $message = ChatMessage::create([
                'chat_room_id' => $room->id,
                'sender_id' => Auth::id(),
                'receiver_id' => $receiverId,
                'message' => 'Sent an attachment: ' . $file->getClientOriginalName(),
                'type' => 'file',
                'attachment_path' => $path,
                'is_read' => false,
            ]);

            // Update room's last message time
            $room->update(['last_message_at' => now()]);

            // Increment unread count for receiver
            if ($receiverId && $receiverId != Auth::id()) {
                $room->increment('unread_count');
            }

            // Load sender relationship
            $message->load('sender');

            return response()->json([
                'success' => true,
                'message' => 'Attachment uploaded successfully',
                'data' => [
                    'id' => $message->id,
                    'text' => $message->message,
                    'time' => $message->created_at->format('h:i A'),
                    'date' => $message->created_at->format('d M Y'),
                    'sender_id' => $message->sender_id,
                    'sender_name' => $message->sender->name,
                    'sender_avatar' => $message->sender->avatar_url ?? asset('admin-assets/assets/images/admin.png'),
                    'is_admin' => $message->sender_id == Auth::id(),
                    'type' => 'file',
                    'file_path' => asset('storage/' . $path),
                    'file_name' => $file->getClientOriginalName(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Upload attachment error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error uploading attachment: ' . $e->getMessage()
            ], 500);
        }
    }
}