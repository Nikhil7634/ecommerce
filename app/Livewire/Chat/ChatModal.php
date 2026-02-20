<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use App\Models\Chat\ChatRoom;
use App\Models\Chat\ChatMessage;
use App\Models\Product;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class ChatModal extends Component
{
    public $isOpen = false;
    public $productId;
    public $message = '';
    public $messages = [];
    public $product;
    public $roomId;
    public $adminUser;
    public $loading = false;
    public $sending = false;

    // Remove problematic dynamic listener
    // protected $listeners = [
    //     'openChatModal' => 'openModal',
    //     'echo:chat.{roomId},MessageSent' => 'messageReceived',
    //     'refreshMessages' => 'loadMessages',
    // ];

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->product = Product::with('seller')->find($productId);
        $this->adminUser = $this->getAdminUser();
    }

    protected function getListeners()
    {
        // Use dynamic roomId in listeners
        $listeners = [
            'openChatModal' => 'openModal',
            'refreshMessages' => 'loadMessages',
        ];

        // Add dynamic Echo listener if roomId exists
        if ($this->roomId) {
            $listeners["echo-private:chat.{$this->roomId},MessageSent"] = 'messageReceived';
        }

        return $listeners;
    }

    private function getAdminUser()
    {
        // Try to get an admin user
        $admin = User::where('role', 'admin')->first();
        
        // If no admin exists, get the first user or create a default admin
        if (!$admin) {
            $admin = User::first();
            
            // If still no user exists, create a default admin
            if (!$admin) {
                $admin = User::create([
                    'name' => 'Administrator',
                    'email' => 'admin@example.com',
                    'password' => bcrypt('password'),
                    'role' => 'admin',
                    'status' => 'active',
                ]);
            } else {
                // Update first user to admin if needed
                $admin->update(['role' => 'admin']);
            }
        }
        
        return $admin;
    }

    public function openModal($data = null)
    {
       
        
        $this->initializeChat();
        $this->isOpen = true;
        $this->dispatch('open-chat-modal');
    }

    private function initializeChat()
    {
        // Check if user is logged in
        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
        } else {
            // Handle guest user - create temporary session ID
            $userId = session()->getId(); // or generate a guest ID
            session(['guest_user_id' => $userId]);
        }
        
        if (!$this->adminUser) {
            $this->adminUser = $this->getAdminUser();
        }

        // Find or create chat room - handle guest users differently
        if (Auth::check()) {
            $room = ChatRoom::firstOrCreate([
                'user_id' => $user->id,
                'product_id' => $this->productId,
                'type' => 'admin_support'
            ], [
                'admin_id' => $this->adminUser->id,
                'status' => 'active',
                'last_message_at' => now(),
            ]);
        } else {
            // For guests, create room with session ID
            $room = ChatRoom::firstOrCreate([
                'session_id' => session()->getId(),
                'product_id' => $this->productId,
                'type' => 'guest_support'
            ], [
                'admin_id' => $this->adminUser->id,
                'status' => 'active',
                'last_message_at' => now(),
            ]);
        }

        $this->roomId = $room->id;
        $this->loadMessages();
        
        // Mark messages as read for current user/session
        if (Auth::check()) {
            $room->markAsRead($user->id);
        }
    }

    public function loadMessages()
    {
        if (!$this->roomId) {
            return;
        }

        $this->loading = true;
        
        $userId = Auth::id();
        $messages = ChatMessage::where('chat_room_id', $this->roomId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->take(100)
            ->get();

        $this->messages = $messages->map(function ($message) use ($userId) {
            return [
                'id' => $message->id,
                'message' => $message->message,
                'sender_id' => $message->sender_id,
                'sender_name' => $message->sender->name,
                'sender_avatar' => $message->sender->avatar,
                'is_sent_by_me' => $message->sender_id === $userId,
                'type' => $message->type,
                'is_read' => $message->is_read,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                'time' => $message->created_at->format('h:i A'),
                'date' => $message->created_at->format('d M Y'),
            ];
        })->toArray();

        $this->loading = false;
        $this->dispatch('scroll-to-bottom');
    }

    public function sendMessage()
    {
        // Validate message
        $this->validate([
            'message' => 'required|string|max:1000|min:1'
        ]);

        $this->sending = true;

        try {
            if (!$this->roomId) {
                $this->initializeChat();
            }

            $message = ChatMessage::create([
                'chat_room_id' => $this->roomId,
                'sender_id' => Auth::id(),
                'receiver_id' => $this->adminUser->id,
                'message' => trim($this->message),
                'type' => 'text',
                'is_read' => false,
            ]);

            // Update room last message time
            $room = ChatRoom::find($this->roomId);
            if ($room) {
                $room->update([
                    'last_message_at' => now(),
                    'unread_count' => $room->messages()
                        ->where('receiver_id', $this->adminUser->id)
                        ->where('is_read', false)
                        ->count(),
                ]);
            }

            // Add the new message to messages array
            $this->messages[] = [
                'id' => $message->id,
                'message' => $message->message,
                'sender_id' => $message->sender_id,
                'sender_name' => Auth::user()->name,
                'sender_avatar' => Auth::user()->avatar,
                'is_sent_by_me' => true,
                'type' => $message->type,
                'is_read' => false,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                'time' => $message->created_at->format('h:i A'),
                'date' => $message->created_at->format('d M Y'),
            ];

            // Clear the input
            $this->reset('message');
            
            // Scroll to bottom
            $this->dispatch('scroll-to-bottom');
            
            // Broadcast event for real-time
            // broadcast(new MessageSent($message))->toOthers();

        } catch (\Exception $e) {
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'Failed to send message: ' . $e->getMessage()
            ]);
        } finally {
            $this->sending = false;
        }
    }

    public function messageReceived($payload)
    {
        // Only process if it's for our room and not from current user
        if (isset($payload['room_id']) && $payload['room_id'] == $this->roomId) {
            
            $this->messages[] = [
                'id' => $payload['message']['id'] ?? uniqid(),
                'message' => $payload['message']['message'] ?? '',
                'sender_id' => $payload['message']['sender_id'] ?? null,
                'sender_name' => $payload['message']['sender']['name'] ?? 'Admin',
                'sender_avatar' => $payload['message']['sender']['avatar'] ?? null,
                'is_sent_by_me' => false,
                'type' => $payload['message']['type'] ?? 'text',
                'is_read' => false,
                'created_at' => $payload['sent_at'] ?? now()->format('Y-m-d H:i:s'),
                'time' => $payload['message']['time'] ?? now()->format('h:i A'),
                'date' => $payload['message']['date'] ?? now()->format('d M Y'),
            ];

            $this->dispatch('scroll-to-bottom');
            $this->dispatch('play-notification-sound');
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['message', 'messages', 'roomId']);
        $this->dispatch('close-chat-modal');
    }

    public function render()
    {
        return view('livewire.chat.chat-modal');
    }
}