<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'review',
        'is_approved',
        'order_id',           // Add if you need to track which order
        'seller_reply',       // Add for seller responses
        'replied_at',         // Add for reply timestamp
        'images',             // Add for review images
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'replied_at' => 'datetime',
        'images' => 'array',
    ];

    // Product relationship
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // User relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Order relationship (if needed)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Scope for approved reviews
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    // Scope for pending reviews
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    // Scope for recent reviews
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get images as an array safely
     */
    public function getImagesAttribute($value)
    {
        if (empty($value)) {
            return [];
        }
        
        if (is_array($value)) {
            return $value;
        }
        
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Set images as JSON
     */
    public function setImagesAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['images'] = null;
        } elseif (is_array($value)) {
            $this->attributes['images'] = json_encode($value);
        } else {
            $this->attributes['images'] = $value;
        }
    }

    /**
     * Get status text based on is_approved
     */
    public function getStatusAttribute()
    {
        return $this->is_approved ? 'approved' : 'pending';
    }

    /**
     * Check if review has images
     */
    public function hasImages()
    {
        $images = $this->images;
        return !empty($images) && count($images) > 0;
    }
}