<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    protected $fillable = [
        'seller_id',
        'name',
        'slug',
        'description',
        'base_price',
        'sale_price',
        'stock',
        'low_stock_threshold',
        'allow_backorder',
        'weight',
        'length',
        'width',
        'height',
        'status',
    ];

    // Seller relationship
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // Categories relationship
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product')
                    ->withTimestamps();
    }

    // Images relationship
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    // Variants relationship
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Tiers relationship
    public function tiers(): HasMany
    {
        return $this->hasMany(ProductTier::class);
    }

    // ============ ADD THESE MISSING RELATIONSHIPS ============
    
    // Order Items relationship
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Reviews relationship
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Wishlist relationship
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    // Cart relationship
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    // ========================================================

    // Get only approved reviews
    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    // Calculate average rating
    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?: 0;
    }

    // Calculate total reviews count
    public function getTotalReviewsAttribute()
    {
        return $this->approvedReviews()->count();
    }

    // Get primary image
    public function getPrimaryImageAttribute()
    {
        $primary = $this->images->where('is_primary', true)->first();
        return $primary ?: $this->images->first();
    }

    // Check if product is on sale
    public function getIsOnSaleAttribute()
    {
        return !is_null($this->sale_price) && $this->sale_price > 0;
    }

    // Calculate discount percentage
    public function getDiscountPercentageAttribute()
    {
        if (!$this->is_on_sale) {
            return 0;
        }
        
        return round((($this->base_price - $this->sale_price) / $this->base_price) * 100);
    }

    // Check stock status
    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) {
            return 'out_of_stock';
        } elseif ($this->stock < 10) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    public function isInWishlist()
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        
        return Wishlist::where('user_id', $user->id)
            ->where('product_id', $this->id)
            ->exists();
    }

    public function isInCart()
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        
        return Cart::where('user_id', $user->id)
            ->where('product_id', $this->id)
            ->exists();
    }
}