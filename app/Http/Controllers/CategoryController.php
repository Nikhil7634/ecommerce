<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display all categories
     */
    public function index(Request $request)
    {
        // Start query for categories
        $query = Category::withCount('products')
            ->with('children')
            ->orderBy('name');
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }
        
        // Get paginated categories
        $categories = $query->paginate(24)->withQueryString();
        
        return view('category', compact('categories'));
    }

    /**
     * Display products in a specific category
     */
    public function show($slug, Request $request)
    {
        // Find the category by slug
        $category = Category::where('slug', $slug)
            ->with(['children' => function($q) {
                $q->withCount('products');
            }])
            ->firstOrFail();
        
        // Get category and all subcategory IDs
        $categoryIds = $this->getAllCategoryIds($category);
        
        // Start query for products in this category and subcategories
        $query = Product::where('status', 'published')
            ->whereHas('seller', function($q) {
                $q->where('status', 'active');
            })
            ->whereHas('categories', function($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            })
            ->with(['images', 'categories'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        // ============ APPLY FILTERS ============
        
        // Price range filter
        if ($request->has('min_price') && $request->min_price !== null && $request->min_price !== '') {
            $minPrice = floatval($request->min_price);
            $query->where('base_price', '>=', $minPrice);
        }
        
        if ($request->has('max_price') && $request->max_price !== null && $request->max_price !== '') {
            $maxPrice = floatval($request->max_price);
            $query->where('base_price', '<=', $maxPrice);
        }

        // On sale filter
        if ($request->has('on_sale') && $request->on_sale == 1) {
            $query->whereNotNull('sale_price')
                  ->where('sale_price', '>', 0)
                  ->whereColumn('sale_price', '<', 'base_price');
        }

        // In stock filter
        if ($request->has('in_stock') && $request->in_stock == 1) {
            $query->where('stock', '>', 0);
        }

        // Rating filter
        if ($request->has('rating') && !empty($request->rating)) {
            $rating = intval($request->rating);
            $query->having('reviews_avg_rating', '>=', $rating);
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
                $query->orderBy('reviews_avg_rating', 'desc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            default: // featured
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Pagination
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage)->appends($request->query());

        // Get commission rate for price calculation
        $commissionRate = $this->getCommissionRate();

        // Calculate final prices with commission
        $products->getCollection()->transform(function ($product) use ($commissionRate) {
            $product->final_base_price = $this->calculatePriceWithCommission($product->base_price, $commissionRate);
            $product->final_sale_price = $product->sale_price ? 
                $this->calculatePriceWithCommission($product->sale_price, $commissionRate) : null;
            return $product;
        });

        // Debug: Log the query and results
        \Log::info('Category: ' . $category->name . ' (ID: ' . $category->id . ')');
        \Log::info('Category IDs including children: ' . implode(', ', $categoryIds));
        \Log::info('Products found: ' . $products->total());

        return view('category-show', compact('category', 'products'));
    }

    /**
     * Get all category IDs including children recursively
     */
    private function getAllCategoryIds($category)
    {
        $ids = [$category->id];
        
        foreach ($category->children as $child) {
            $ids = array_merge($ids, $this->getAllCategoryIds($child));
        }
        
        return $ids;
    }

    /**
     * Get commission rate from settings
     */
    private function getCommissionRate()
    {
        return \App\Models\Setting::where('key', 'commission_rate')->value('value') ?? 20;
    }

    /**
     * Calculate price with commission
     */
    private function calculatePriceWithCommission($price, $commissionRate)
    {
        if (!$price) return 0;
        
        $commissionAmount = ($price * $commissionRate) / 100;
        return round($price + $commissionAmount, 2);
    }
}