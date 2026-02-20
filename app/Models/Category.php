<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'image',
        'status',
        'order'
    ];

    // Parent category relationship
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Child categories relationship
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Products relationship (Many-to-Many)
    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product')
                    ->withTimestamps();
    }

    // Get only published products
    public function publishedProducts()
    {
        return $this->belongsToMany(Product::class, 'category_product')
                    ->where('status', 'published')
                    ->whereHas('seller', function($query) {
                        $query->where('status', 'active');
                    })
                    ->withTimestamps();
    }

    // Scope for active categories
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for main categories (no parent)
    public function scopeMain($query)
    {
        return $query->whereNull('parent_id');
    }
}