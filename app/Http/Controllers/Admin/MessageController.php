<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat\ChatMessage;
use App\Models\Chat\ChatRoom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    /**
     * Display all messages
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');
        
        $query = ChatMessage::with(['sender', 'receiver', 'room.user'])
            ->where(function($q) {
                $q->where('sender_id', Auth::id())
                  ->orWhere('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'desc');

        if ($type == 'unread') {
            $query->where('is_read', false)
                  ->where('receiver_id', Auth::id());
        }

        $messages = $query->paginate(20);

        $unreadCount = ChatMessage::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return view('admin.messages.index', compact('messages', 'unreadCount', 'type'));
    }

    /**
     * Show single message
     */
    public function show($id)
    {
        $message = ChatMessage::with(['sender', 'receiver', 'room.user'])
            ->where(function($q) {
                $q->where('sender_id', Auth::id())
                  ->orWhere('receiver_id', Auth::id());
            })
            ->findOrFail($id);

        // Mark as read if this user is the receiver
        if ($message->receiver_id == Auth::id() && !$message->is_read) {
            $message->update([
                'is_read' => true,
                'read_at' => now()
            ]);

            // Update room unread count
            if ($message->room) {
                $message->room->decrement('unread_count');
            }
        }

        return view('admin.messages.show', compact('message'));
    }

    /**
     * Get recent messages for dropdown (AJAX)
     */
    public function getRecentMessages()
    {
        try {
            $messages = ChatMessage::with(['sender', 'receiver', 'room'])
                ->where(function($q) {
                    $q->where('sender_id', Auth::id())
                      ->orWhere('receiver_id', Auth::id());
                })
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get()
                ->map(function($message) {
                    $otherUser = $message->sender_id == Auth::id() ? $message->receiver : $message->sender;
                    
                    return [
                        'id' => $message->id,
                        'message' => $message->message,
                        'time' => $message->created_at->diffForHumans(),
                        'is_read' => $message->is_read,
                        'is_outgoing' => $message->sender_id == Auth::id(),
                        'sender' => [
                            'id' => $message->sender_id,
                            'name' => $message->sender->name ?? 'Unknown',
                            'avatar' => $message->sender->avatar_url ?? asset('admin-assets/assets/images/avatar.png'),
                        ],
                        'room_id' => $message->chat_room_id,
                    ];
                });

            $unreadCount = ChatMessage::where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();

            return response()->json([
                'success' => true,
                'messages' => $messages,
                'unread_count' => $unreadCount,
            ]);

        } catch (\Exception $e) {
            Log::error('Get recent messages error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'messages' => [],
                'unread_count' => 0,
            ], 500);
        }
    }

    /**
     * Mark all messages as read
     */
    public function markAllAsRead()
    {
        try {
            $updated = ChatMessage::where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now()
                ]);

            // Reset unread counts for all rooms
            ChatRoom::where('admin_id', Auth::id())
                ->orWhere('user_id', Auth::id())
                ->update(['unread_count' => 0]);

            return response()->json([
                'success' => true,
                'updated' => $updated,
            ]);

        } catch (\Exception $e) {
            Log::error('Mark all as read error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error marking messages as read'
            ], 500);
        }
    }
}