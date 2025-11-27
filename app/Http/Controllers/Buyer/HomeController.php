<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Product;      // Your model
use App\Models\Category;     // For navigation
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the homepage with featured, latest products & categories
     */
    public function index(Request $request)
    {
        // === FEATURED PRODUCTS ===
        // Show: featured OR boosted (search_rank > 1.0)
        $featured = Product::where('status', 'published')
            ->where(function ($query) {
                $query->where('is_featured', true)
                      ->orWhere('search_rank', '>', 1.0);
            })
            ->inRandomOrder()
            ->limit(8)
            ->get();

        // === LATEST PRODUCTS ===
        $latest = Product::where('status', 'published')
            ->latest()
            ->limit(12)
            ->get();

        // === CATEGORIES (Parent only) ===
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->get();

        // === BANNER (optional) ===
        $banner = [
            'title'    => 'Summer Mega Sale!',
            'subtitle' => 'Up to 60% Off on All Items',
            'image'    => asset('images/banner.jpg'),
            'link'     => route('products.index')
        ];

        return view('buyer.dashboard', compact(
            'featured',
            'latest',
            'categories',
            'banner'
        ));
    }
}