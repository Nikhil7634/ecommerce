<?php

namespace App\Events;

use App\Models\Chat\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class AdminChatNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $roomId;

    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
        $this->roomId = $message->chat_room_id;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.chat.notifications'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'chat.notification';
    }

    public function broadcastWith(): array
    {
        return [
            'room_id' => $this->roomId,
            'message_preview' => Str::limit($this->message->message, 50),
            'sender_name' => $this->message->sender->name,
            'product_name' => $this->message->room->product->name ?? 'General Inquiry',
            'time' => $this->message->created_at->diffForHumans(),
        ];
    }
}