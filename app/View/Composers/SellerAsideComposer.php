<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class SellerAsideComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        $confirmedOrdersCount = 0;
        
        if (Auth::check() && Auth::user()->isSeller()) {
            $sellerId = Auth::id();
            
            $confirmedOrdersCount = Order::whereHas('items', function($query) use ($sellerId) {
                    $query->where('seller_id', $sellerId);
                })
                ->where('status', 'confirmed')
                ->count();
        }
        
        $view->with('confirmedOrdersCount', $confirmedOrdersCount);
    }
}