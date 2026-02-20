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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class)->latestOfMany();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    // FIXED: Now requires userId parameter
    public function markAsRead($userId)
    {
        if (!$userId) {
            return $this;
        }

        $this->messages()
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        
        // Update unread count
        $this->updateUnreadCount($userId);
        
        return $this;
    }

    // Helper to update unread count for specific user
    public function updateUnreadCount($userId = null)
    {
        if ($userId) {
            $unreadCount = $this->messages()
                ->where('receiver_id', $userId)
                ->where('is_read', false)
                ->count();
                
            $this->update(['unread_count' => $unreadCount]);
        }
        
        return $this;
    }

    // Get unread count for specific user
    public function getUnreadCountForUser($userId)
    {
        return $this->messages()
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    // Check if user can access this room
    public function canAccess($userId)
    {
        return $this->user_id == $userId || $this->admin_id == $userId;
    }
}