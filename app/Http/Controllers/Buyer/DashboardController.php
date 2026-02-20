<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Wishlist;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        
        // Get total orders count
        $totalOrders = Order::where('user_id', $userId)->count();
        
        // Get completed orders count (assuming 'confirmed' status means completed)
        $completedOrders = Order::where('user_id', $userId)
            ->where('status', 'confirmed')
            ->count();
            
        // Get pending orders count
        $pendingOrders = Order::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();
            
        // Get cancelled orders count
        $cancelledOrders = Order::where('user_id', $userId)
            ->where('status', 'cancelled')
            ->count();
            
        // Get wishlist count
        $totalWishlist = Wishlist::where('user_id', $userId)->count();
        
        // Get reviews count
        $totalReviews = Review::where('user_id', $userId)->count();
        
        // Get recent orders (last 5 orders)
        $recentOrders = Order::where('user_id', $userId)
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get recent reviews
        $recentReviews = Review::where('user_id', $userId)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        return view('buyer.dashboard', compact(
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'cancelledOrders',
            'totalWishlist',
            'totalReviews',
            'recentOrders',
            'recentReviews',
            'user'
        ));
    }
}