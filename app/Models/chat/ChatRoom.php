<?php

namespace App\Models\Chat;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'chat_rooms';

    protected $fillable = [
        'user_id',
        'admin_id',
        'product_id',
        'type',
        'status',
        'last_message_at',
        'unread_count',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'unread_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user who owns this chat room
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the admin assigned to this chat room
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the product associated with this chat room
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get all messages in this chat room
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_room_id');
    }

    /**
     * Get the last message in this chat room
     */
    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class, 'chat_room_id')->latest();
    }

    /**
     * Get unread messages count for a specific user
     */
    public function unreadCountForUser($userId)
    {
        return $this->messages()
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Scope a query to only include active rooms
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include admin support rooms
     */
    public function scopeAdminSupport($query)
    {
        return $query->where('type', 'admin_support');
    }

    /**
     * Scope a query to only include product inquiry rooms
     */
    public function scopeProductInquiry($query)
    {
        return $query->where('type', 'product_inquiry');
    }

    /**
     * Mark all messages as read for a user
     */
    public function markAsReadForUser($userId)
    {
        $updated = $this->messages()
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        if ($updated) {
            $this->update(['unread_count' => 0]);
        }

        return $updated;
    }

    /**
     * Get the other participant in the chat (not the current user)
     */
    public function otherParticipant($currentUserId)
    {
        if ($this->user_id == $currentUserId) {
            return $this->admin;
        }
        return $this->user;
    }

    /**
     * Check if room has unread messages for a user
     */
    public function hasUnreadForUser($userId)
    {
        return $this->messages()
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->exists();
    }

    /**
     * Get room name (for display)
     */
    public function getRoomNameAttribute()
    {
        if ($this->product) {
            return $this->product->name . ' - Inquiry';
        }
        return 'Support Chat';
    }

    /**
     * Get last message preview
     */
    public function getLastMessagePreviewAttribute()
    {
        $lastMessage = $this->lastMessage;
        if (!$lastMessage) {
            return 'No messages yet';
        }
        
        return strlen($lastMessage->message) > 50 
            ? substr($lastMessage->message, 0, 50) . '...' 
            : $lastMessage->message;
    }

    /**
     * Get last message time
     */
    public function getLastMessageTimeAttribute()
    {
        return $this->last_message_at ? $this->last_message_at->diffForHumans() : null;
    }
}