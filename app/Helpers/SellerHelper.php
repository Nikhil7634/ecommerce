<?php

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

if (!function_exists('getConfirmedOrdersCount')) {
    function getConfirmedOrdersCount() {
        if (!Auth::check() || !Auth::user()->isSeller()) {
            return 0;
        }
        
        $sellerId = Auth::id();
        return Order::whereHas('items', function($query) use ($sellerId) {
                $query->where('seller_id', $sellerId);
            })
            ->where('status', 'confirmed')
            ->count();
    }
}