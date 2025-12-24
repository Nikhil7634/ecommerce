<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'seller_id', 
        'name', 
        'slug', 
        'base_price',  // Changed from 'price' to 'base_price'
        'sale_price',   // Added if you have this field
        'stock', 
        'description', 
        'status', 
        'low_stock_threshold', // Added
        'allow_backorder',     // Added
        'weight',              // Added
        'length',              // Added
        'width',               // Added
        'height',              // Added
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function tiers()
    {
        return $this->hasMany(ProductTier::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}