<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Product;

class StoreController extends Controller
{
    /**
     * Display all vendors/stores
     */
    public function index()
    {
        // Get only active stores with their user information
        $vendors = Store::where('status', 'active')
            ->with(['user' => function($query) {
                $query->select('id', 'name', 'avatar');
            }])
            ->orderBy('total_sales', 'desc')
            ->paginate(8); // 8 vendors per page
        
        return view('stores', compact('vendors'));
    }
    
    /**
     * Display single vendor details
     */
 
    public function show($slug)
    {
        $store = Store::where('slug', $slug)
            ->where('status', 'active')
            ->with(['user'])
            ->firstOrFail();
        
        // Get store products with pagination
        $products = Product::where('seller_id', $store->user_id)
            ->where('status', 'published')
            ->with(['images'])
            ->orderBy('created_at', 'desc') // Sort by created_at instead of rating
            ->paginate(12);
        
        // Get top products for sidebar (by views instead of rating)
        $topProducts = Product::where('seller_id', $store->user_id)
            ->where('status', 'published')
            ->with(['images'])
            ->orderBy('views', 'desc')
            ->limit(4)
            ->get();
        
        return view('vendor-details', compact('store', 'products', 'topProducts'));
    }
}