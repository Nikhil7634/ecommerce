<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportChatController extends Controller
{
    public function index()
    {
        // Show chat interface with users list
        $users = User::where('id', '!=', Auth::id())
            ->orderBy('name')
            ->paginate(20);
            
        return view('admin.support.chat', compact('users'));
    }
    
    public function getMessages($userId)
    {
        $messages = ChatMessage::where(function($query) use ($userId) {
                $query->where('sender_id', Auth::id())
                    ->where('receiver_id', $userId);
            })
            ->orWhere(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();
            
        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }
    
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);
        
        $message = ChatMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'type' => 'text',
            'is_read' => false
        ]);
        
        // TODO: Broadcast event for real-time
        
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
    
    public function markAsRead($userId)
    {
        ChatMessage::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
            
        return response()->json(['success' => true]);
    }
    
    public function unreadCount()
    {
        $count = ChatMessage::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();
            
        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
    
    public function onlineUsers()
    {
        $users = User::where('status', 'active')
            ->where('last_seen', '>=', now()->subMinutes(5))
            ->where('id', '!=', Auth::id())
            ->select('id', 'name', 'profile_photo', 'role', 'last_seen')
            ->get();
            
        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }
}