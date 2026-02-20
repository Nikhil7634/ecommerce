<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'seller_id',
        'variant_id',
        'product_name',
        'variant_details',
        'quantity',
        'unit_price',
        'total_price',
    ];

    // Relationship with Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship with Seller
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // Casting
    protected $casts = [
        'variant_details' => 'array',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Accessor for variant details
    public function getVariantDetailsAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    // Mutator for variant details
    public function setVariantDetailsAttribute($value)
    {
        $this->attributes['variant_details'] = $value ? json_encode($value) : null;
    }
}