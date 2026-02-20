<?php

namespace App\Events;

use App\Models\Chat\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $roomId;
    public $userId;
    private $chatMessage;

    /**
     * Create a new event instance.
     */
    public function __construct(ChatMessage $message)
    {
        $this->chatMessage = $message;
        $this->roomId = $message->chat_room_id;
        $this->userId = $message->receiver_id;

        // Don't send sensitive data
        $this->message = $message->only([
            'id',
            'message',
            'sender_id',
            'receiver_id',
            'type',
            'created_at',
        ]);

        // Load sender info
        $this->message['sender'] = $message->sender->only(['id', 'name', 'avatar']);
        $this->message['time'] = $message->created_at->format('h:i A');
        $this->message['date'] = $message->created_at->format('d M Y');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Broadcast to private channel for the specific chat room
        return [
            new PrivateChannel('chat.' . $this->roomId),
            // Also broadcast to admin channel if needed
            new PrivateChannel('admin.chat'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'room_id' => $this->roomId,
            'sent_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * Determine if this event should broadcast.
     */
    public function broadcastWhen(): bool
    {
        // Only broadcast if the chat room is active
        return $this->chatMessage->room->isActive();
    }
}