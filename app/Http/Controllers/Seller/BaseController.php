<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Illuminate\Support\Facades\View;

abstract class BaseController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct();
        
        // Share data with all views
        $this->shareData();
    }
    
    /**
     * Share common data with views
     */
    protected function shareData()
    {
        if (Auth::check() && Auth::user()->isSeller()) {
            $sellerId = Auth::id();
            
            $confirmedOrdersCount = Order::whereHas('items', function($query) use ($sellerId) {
                    $query->where('seller_id', $sellerId);
                })
                ->where('status', 'confirmed')
                ->count();
            
            View::share('confirmedOrdersCount', $confirmedOrdersCount);
        }
    }
}