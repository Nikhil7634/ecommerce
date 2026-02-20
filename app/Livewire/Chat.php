<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Illuminate\Support\Facades\Auth;

class Chat extends Component
{
    public $roomId;
    public $message = '';
    public $messages = [];
    public $receiverId;
    public $receiver;
    public $productId;
    public $product;

    protected $listeners = [
        'messageReceived' => 'loadMessages',
        'echo:chat.{roomId},MessageSent' => 'messageReceived'
    ];

    public function mount($receiverId = null, $productId = null)
    {
        if (Auth::guest()) {
            return redirect()->route('login');
        }

        $this->receiverId = $receiverId ?: User::where('role', 'admin')->first()->id;
        $this->receiver = User::find($this->receiverId);
        $this->productId = $productId;
        
        if ($this->productId) {
            $this->product = \App\Models\Product::find($productId);
        }

        // Get or create chat room
        $room = ChatRoom::firstOrCreate([
            'buyer_id' => Auth::id(),
            'admin_id' => $this->receiverId,
            'product_id' => $this->productId,
        ]);

        $this->roomId = $room->id;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = ChatMessage::where('room_id', $this->roomId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'content' => $message->message,
                    'sender_id' => $message->user_id,
                    'sender_name' => $message->user->name,
                    'is_self' => $message->user_id == Auth::id(),
                    'time' => $message->created_at->format('h:i A'),
                    'date' => $message->created_at->format('M d'),
                ];
            })->toArray();
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = ChatMessage::create([
            'room_id' => $this->roomId,
            'user_id' => Auth::id(),
            'message' => $this->message,
            'type' => 'text',
        ]);

        // Notify admin about new message
        $this->receiver->notify(new NewMessageNotification($message));

        // Broadcast the message
        broadcast(new \App\Events\MessageSent($message))->toOthers();

        $this->message = '';
        $this->loadMessages();

        // Scroll to bottom
        $this->dispatchBrowserEvent('scrollToBottom');
    }

    public function render()
    {
        return view('livewire.chat');
    }
}