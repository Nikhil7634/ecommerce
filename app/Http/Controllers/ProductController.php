<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /**
     * Display the shop page with filters
     */
    public function index(Request $request)
    {
        $query = Product::with(['images', 'categories', 'reviews'])
            ->where('status', 'active')
            ->withCount(['reviews', 'wishlists'])
            ->withAvg('reviews', 'rating');

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price) {
            $query->where('base_price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('base_price', '<=', $request->max_price);
        }

        // On sale filter
        if ($request->has('on_sale') && $request->on_sale) {
            $query->whereNotNull('sale_price')
                  ->where('sale_price', '>', 0);
        }

        // In stock filter
        if ($request->has('in_stock') && $request->in_stock) {
            $query->where('stock', '>', 0);
        }

        // Rating filter
        if ($request->has('rating') && $request->rating) {
            $query->whereHas('reviews', function($q) use ($request) {
                $q->select('product_id')
                  ->groupBy('product_id')
                  ->havingRaw('AVG(rating) >= ?', [$request->rating]);
            });
        }

        // Sorting
        $sort = $request->get('sort', 'featured');
        switch ($sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'price_low':
                $query->orderBy(DB::raw('COALESCE(sale_price, base_price)'), 'asc');
                break;
            case 'price_high':
                $query->orderBy(DB::raw('COALESCE(sale_price, base_price)'), 'desc');
                break;
            case 'rating':
                $query->orderBy('reviews_avg_rating', 'desc');
                break;
            case 'popular':
                $query->orderBy('reviews_count', 'desc');
                break;
            default: // featured
                $query->orderBy('featured', 'desc')
                      ->orderBy('created_at', 'desc');
                break;
        }

        // Pagination
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        // Get categories for filter
        $categories = Category::withCount('products')
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        // Get top rated products for sidebar
        $topRatedProducts = Product::with(['images'])
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->withCount(['reviews'])
            ->withAvg('reviews', 'rating')
            ->orderBy('reviews_avg_rating', 'desc')
            ->take(5)
            ->get();

        return view('shop', compact('products', 'categories', 'topRatedProducts'));
    }

    /**
     * Display the product details page
     */
    public function show($slug)
    {
        // Get product with all relationships
        $product = Product::with([
            'images',
            'categories',
            'seller',
            'reviews.user',
            'variants',
            'tiers'
        ])
        ->withCount(['reviews', 'wishlists'])
        ->withAvg('reviews', 'rating')
        ->where('slug', $slug)
        ->where('status', 'active')
        ->firstOrFail();

        // Get seller reviews if seller exists
        if ($product->seller) {
            $product->seller->loadCount('reviews');
            $product->seller->loadAvg('reviews', 'rating');
        }

        // Get related products (same category)
        $relatedProducts = Product::with(['images'])
            ->where('status', 'active')
            ->where('id', '!=', $product->id)
            ->whereHas('categories', function($query) use ($product) {
                $query->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->withCount(['reviews'])
            ->withAvg('reviews', 'rating')
            ->inRandomOrder()
            ->take(8)
            ->get();

        // Increment view count
        $product->increment('views');

        // Add to recently viewed products
        $this->addToRecentlyViewed($product->id);

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Check product stock via AJAX
     */
    public function checkStock($id)
    {
        $product = Product::findOrFail($id);
        
        return response()->json([
            'stock' => $product->stock,
            'available' => $product->stock > 0
        ]);
    }

    /**
     * Add product to recently viewed
     */
    private function addToRecentlyViewed($productId)
    {
        $recentlyViewed = session()->get('recently_viewed', []);

        // Remove if already exists
        if (($key = array_search($productId, $recentlyViewed)) !== false) {
            unset($recentlyViewed[$key]);
        }

        // Add to beginning
        array_unshift($recentlyViewed, $productId);

        // Keep only last 10 products
        $recentlyViewed = array_slice($recentlyViewed, 0, 10);

        session()->put('recently_viewed', $recentlyViewed);
    }

    /**
     * Get recently viewed products
     */
    public function getRecentlyViewed()
    {
        $recentlyViewedIds = session()->get('recently_viewed', []);
        
        if (empty($recentlyViewedIds)) {
            return collect();
        }

        $products = Product::with(['images'])
            ->whereIn('id', $recentlyViewedIds)
            ->where('status', 'active')
            ->withCount(['reviews'])
            ->withAvg('reviews', 'rating')
            ->get()
            ->sortBy(function ($product) use ($recentlyViewedIds) {
                return array_search($product->id, $recentlyViewedIds);
            });

        return $products;
    }

    /**
     * Get products by category
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $products = Product::with(['images', 'categories', 'reviews'])
            ->where('status', 'active')
            ->whereHas('categories', function($query) use ($category) {
                $query->where('categories.id', $category->id)
                      ->orWhereIn('categories.id', $category->children->pluck('id'));
            })
            ->withCount(['reviews'])
            ->withAvg('reviews', 'rating')
            ->paginate(12);

        $categories = Category::withCount('products')
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        $topRatedProducts = Product::with(['images'])
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->withCount(['reviews'])
            ->withAvg('reviews', 'rating')
            ->orderBy('reviews_avg_rating', 'desc')
            ->take(5)
            ->get();

        return view('shop', compact('products', 'categories', 'topRatedProducts', 'category'));
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);

        $query = $request->q;
        
        $products = Product::with(['images', 'categories', 'reviews'])
            ->where('status', 'active')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhereHas('categories', function($q2) use ($query) {
                      $q2->where('name', 'like', "%{$query}%");
                  });
            })
            ->withCount(['reviews'])
            ->withAvg('reviews', 'rating')
            ->paginate(12);

        $categories = Category::withCount('products')
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        $topRatedProducts = Product::with(['images'])
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->withCount(['reviews'])
            ->withAvg('reviews', 'rating')
            ->orderBy('reviews_avg_rating', 'desc')
            ->take(5)
            ->get();

        return view('shop', compact('products', 'categories', 'topRatedProducts', 'query'));
    }

    /**
     * Get products on sale
     */
    public function onSale()
    {
        $products = Product::with(['images', 'categories', 'reviews'])
            ->where('status', 'active')
            ->whereNotNull('sale_price')
            ->where('sale_price', '>', 0)
            ->whereColumn('sale_price', '<', 'base_price')
            ->withCount(['reviews'])
            ->withAvg('reviews', 'rating')
            ->orderBy(DB::raw('(base_price - sale_price) / base_price * 100'), 'desc')
            ->paginate(12);

        $categories = Category::withCount('products')
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        $topRatedProducts = Product::with(['images'])
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->withCount(['reviews'])
            ->withAvg('reviews', 'rating')
            ->orderBy('reviews_avg_rating', 'desc')
            ->take(5)
            ->get();

        return view('shop', compact('products', 'categories', 'topRatedProducts'));
    }

    /**
     * Get new arrivals
     */
    public function newArrivals()
    {
        $products = Product::with(['images', 'categories', 'reviews'])
            ->where('status', 'active')
            ->where('created_at', '>=', now()->subDays(30))
            ->withCount(['reviews'])
            ->withAvg('reviews', 'rating')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = Category::withCount('products')
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        $topRatedProducts = Product::with(['images'])
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->withCount(['reviews'])
            ->withAvg('reviews', 'rating')
            ->orderBy('reviews_avg_rating', 'desc')
            ->take(5)
            ->get();

        return view('shop', compact('products', 'categories', 'topRatedProducts'));
    }

    /**
     * Get featured products
     */
    public function featured()
    {
        $products = Product::with(['images', 'categories', 'reviews'])
            ->where('status', 'active')
            ->where('featured', true)
            ->withCount(['reviews'])
            ->withAvg('reviews', 'rating')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = Category::withCount('products')
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        $topRatedProducts = Product::with(['images'])
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->withCount(['reviews'])
            ->withAvg('reviews', 'rating')
            ->orderBy('reviews_avg_rating', 'desc')
            ->take(5)
            ->get();

        return view('shop', compact('products', 'categories', 'topRatedProducts'));
    }

    /**
     * Compare products
     */
    public function compare(Request $request)
    {
        $productIds = $request->get('products', []);
        
        if (count($productIds) < 2 || count($productIds) > 4) {
            return back()->with('error', 'Please select 2 to 4 products to compare.');
        }

        $products = Product::with([
            'images',
            'categories',
            'variants',
            'tiers'
        ])
        ->whereIn('id', $productIds)
        ->where('status', 'active')
        ->withCount(['reviews'])
        ->withAvg('reviews', 'rating')
        ->get();

        if ($products->count() < 2) {
            return back()->with('error', 'Unable to find selected products.');
        }

        return view('products.compare', compact('products'));
    }

    /**
     * Quick view product
     */
    public function quickView($id)
    {
        $product = Product::with(['images', 'categories', 'variants'])
            ->where('id', $id)
            ->where('status', 'active')
            ->withCount(['reviews'])
            ->withAvg('reviews', 'rating')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'html' => view('products.partials.quick-view', compact('product'))->render()
        ]);
    }

    /**
     * Get product specifications
     */
    public function specifications($id)
    {
        $product = Product::with(['variants', 'tiers'])
            ->where('id', $id)
            ->firstOrFail(['id', 'name', 'specifications']);

        return response()->json([
            'success' => true,
            'specifications' => $product->specifications ? json_decode($product->specifications, true) : [],
            'variants' => $product->variants,
            'tiers' => $product->tiers
        ]);
    }

    /**
     * Get product variants with prices
     */
    public function getVariants($id)
    {
        $product = Product::with(['variants'])
            ->where('id', $id)
            ->firstOrFail(['id', 'base_price', 'sale_price']);

        $variants = $product->variants->map(function($variant) use ($product) {
            $variant->final_price = ($product->sale_price ?: $product->base_price) + $variant->price_adjustment;
            return $variant;
        });

        return response()->json([
            'success' => true,
            'variants' => $variants
        ]);
    }
}