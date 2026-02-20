<?php

namespace App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_room_id',
        'sender_id',
        'receiver_id',
        'message',
        'type',
        'attachment_path',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Remove the appends attribute for now
    // protected $appends = ['is_sent_by_me'];

    public function room()
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Safer method to check if message was sent by current user
    public function isSentByMe()
    {
        return Auth::check() && $this->sender_id === Auth::id();
    }

    // Alternative: Pass user ID as parameter
    public function isSentByUser($userId = null)
    {
        if ($userId === null && Auth::check()) {
            $userId = Auth::id();
        }
        
        return $userId && $this->sender_id === $userId;
    }

    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
            
            // Update room unread count
            $this->room->decrement('unread_count');
        }
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeSince($query, $datetime)
    {
        return $query->where('created_at', '>', $datetime);
    }

    // Accessor that's safe to use
    public function getFormattedTimeAttribute()
    {
        return $this->created_at->format('h:i A');
    }

    public function getDateAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }
}