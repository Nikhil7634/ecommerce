<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // Start query
        $query = Product::with(['images', 'categories'])
            ->where('status', 'published')
            ->whereHas('seller', function($q) {
                $q->where('status', 'active');
            });

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Multiple categories filter
        if ($request->has('categories')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->whereIn('categories.id', $request->categories);
            });
        }

        // Price range filter
        if ($request->has('min_price') || $request->has('max_price')) {
            $minPrice = $request->min_price ?? 0;
            $maxPrice = $request->max_price ?? 10000;
            $query->whereBetween('base_price', [$minPrice, $maxPrice]);
        }

        // On sale filter
        if ($request->has('on_sale') && $request->on_sale == 1) {
            $query->whereNotNull('sale_price')
                  ->where('sale_price', '>', 0);
        }

        // In stock filter
        if ($request->has('in_stock') && $request->in_stock == 1) {
            $query->where('stock', '>', 0);
        }

        // Rating filter
        if ($request->has('rating') && !empty($request->rating)) {
            $query->whereHas('reviews', function($q) use ($request) {
                $q->selectRaw('product_id, AVG(rating) as avg_rating')
                  ->groupBy('product_id')
                  ->having('avg_rating', '>=', $request->rating);
            });
        }

        // Sorting
        $sort = $request->get('sort', 'featured');
        switch ($sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'price_low':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('base_price', 'desc');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')
                      ->orderBy('reviews_avg_rating', 'desc');
                break;
            case 'popular':
                // You can add popularity logic here (e.g., based on orders)
                $query->orderBy('created_at', 'desc');
                break;
            default: // featured
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Get categories with product counts
        $categories = Category::whereNull('parent_id')
            ->with(['children' => function($q) {
                $q->withCount(['products' => function($query) {
                    $query->where('status', 'published');
                }]);
            }])
            ->withCount(['products' => function($query) {
                $query->where('status', 'published');
            }])
            ->get();

        // Get top rated products
        $topRatedProducts = Product::where('status', 'published')
            ->with(['images', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderBy('reviews_avg_rating', 'desc')
            ->take(4)
            ->get();

        // Pagination
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        return view('shop', compact('products', 'categories', 'topRatedProducts'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('shop_details', compact('product'));
    }
}