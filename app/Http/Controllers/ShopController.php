<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;

class ShopController extends Controller
{
    private function getCommissionRate()
    {
        return Setting::where('key', 'commission_rate')->value('value') ?? 20;
    }

    private function calculatePriceWithCommission($price, $commissionRate)
    {
        if (!$price) return 0;
        
        $commissionAmount = ($price * $commissionRate) / 100;
        return round($price + $commissionAmount, 2);
    }

    public function index(Request $request)
    {
        // Get commission rate from settings
        $commissionRate = $this->getCommissionRate();

        // Start query
        $query = Product::with(['images', 'categories'])
            ->where('status', 'published')
            ->whereHas('seller', function($q) {
                $q->where('status', 'active');
            })
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        // ============ FIXED SEARCH LOGIC ============
        if ($request->has('search') && !empty($request->search)) {
            $search = trim($request->search);
            
            $query->where(function($q) use ($search) {
                // Split search into individual words for better matching
                $words = explode(' ', $search);
                
                foreach ($words as $word) {
                    if (strlen($word) > 1) {
                        $q->where(function($subQuery) use ($word) {
                            $subQuery->where('name', 'LIKE', "%{$word}%")
                                     ->orWhere('description', 'LIKE', "%{$word}%");
                        });
                    }
                }
                
                // Also search for the full phrase
                $q->orWhere('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
                
                // Search in categories
                $q->orWhereHas('categories', function($catQuery) use ($search) {
                    $catQuery->where('name', 'LIKE', "%{$search}%");
                });
            });
        }

        // ============ FIXED CATEGORY FILTER (using slug) ============
        if ($request->has('category') && !empty($request->category)) {
            $categorySlug = $request->category;
            
            // Find category by slug and get all child category IDs
            $category = Category::where('slug', $categorySlug)->first();
            
            if ($category) {
                // Get all subcategory IDs including the main category
                $categoryIds = $this->getAllCategoryIds($category);
                
                $query->whereHas('categories', function($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                });
            }
        }

        // Multiple categories filter (if using array of IDs)
        if ($request->has('categories') && is_array($request->categories)) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->whereIn('categories.id', $request->categories);
            });
        }

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

        // Get categories with product counts (using slugs for URLs)
        $categories = Category::whereNull('parent_id')
            ->with(['children' => function($q) {
                $q->withCount(['products' => function($query) {
                    $query->where('status', 'published')
                          ->whereHas('seller', function($sq) {
                              $sq->where('status', 'active');
                          });
                }]);
            }])
            ->withCount(['products' => function($query) {
                $query->where('status', 'published')
                      ->whereHas('seller', function($sq) {
                          $sq->where('status', 'active');
                      });
            }])
            ->get();

        // Get top rated products
        $topRatedProducts = Product::where('status', 'published')
            ->whereHas('seller', function($q) {
                $q->where('status', 'active');
            })
            ->with(['images'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderBy('reviews_avg_rating', 'desc')
            ->take(4)
            ->get();

        // Pagination
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage)->appends($request->query());
        
        // Calculate final prices with commission for paginated products
        $products->getCollection()->transform(function ($product) use ($commissionRate) {
            $product->final_base_price = $this->calculatePriceWithCommission($product->base_price, $commissionRate);
            $product->final_sale_price = $product->sale_price ? 
                $this->calculatePriceWithCommission($product->sale_price, $commissionRate) : null;
            return $product;
        });
        
        // Calculate for top rated products
        $topRatedProducts->transform(function ($product) use ($commissionRate) {
            $product->final_base_price = $this->calculatePriceWithCommission($product->base_price, $commissionRate);
            $product->final_sale_price = $product->sale_price ? 
                $this->calculatePriceWithCommission($product->sale_price, $commissionRate) : null;
            return $product;
        });

        // Debug: Log the search query and results
        if ($request->has('search') && !empty($request->search)) {
            \Log::info('Search query: ' . $request->search);
            \Log::info('Products found: ' . $products->total());
        }

        return view('shop', compact('products', 'categories', 'topRatedProducts', 'commissionRate'));
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

    public function show($slug)
    {
        // Get commission rate from settings
        $commissionRate = $this->getCommissionRate();
        
        $product = Product::with([
            'images',
            'categories',
            'seller',
            'variants',
            'tiers',
            'reviews.user'
        ])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->whereHas('seller', function($query) {
            $query->where('status', 'active');
        })
        ->firstOrFail();
        
        // Calculate final prices with commission
        $product->final_base_price = $this->calculatePriceWithCommission($product->base_price, $commissionRate);
        $product->final_sale_price = $product->sale_price ? 
            $this->calculatePriceWithCommission($product->sale_price, $commissionRate) : null;
        
        // Calculate for tiers if exists
        if ($product->tiers->count() > 0) {
            $product->tiers->transform(function ($tier) use ($commissionRate) {
                $tier->final_price = $this->calculatePriceWithCommission($tier->price, $commissionRate);
                return $tier;
            });
        }

        // Get related products
        $categoryIds = $product->categories->pluck('id')->toArray();
        
        $relatedProducts = collect();
        
        if (!empty($categoryIds)) {
            $relatedProducts = Product::where('status', 'published')
                ->where('id', '!=', $product->id)
                ->whereHas('seller', function($query) {
                    $query->where('status', 'active');
                })
                ->whereHas('categories', function($query) use ($categoryIds) {
                    $query->whereIn('categories.id', $categoryIds);
                })
                ->with(['images'])
                ->withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->inRandomOrder()
                ->take(4)
                ->get();
        }

        // If not enough related products by category, get random products
        if ($relatedProducts->count() < 4) {
            $randomProducts = Product::where('status', 'published')
                ->where('id', '!=', $product->id)
                ->whereHas('seller', function($query) {
                    $query->where('status', 'active');
                })
                ->with(['images'])
                ->withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->inRandomOrder()
                ->take(4 - $relatedProducts->count())
                ->get();
                
            $relatedProducts = $relatedProducts->concat($randomProducts);
        }
        
        // Calculate final prices for related products
        $relatedProducts->transform(function ($relatedProduct) use ($commissionRate) {
            $relatedProduct->final_base_price = $this->calculatePriceWithCommission($relatedProduct->base_price, $commissionRate);
            $relatedProduct->final_sale_price = $relatedProduct->sale_price ? 
                $this->calculatePriceWithCommission($relatedProduct->sale_price, $commissionRate) : null;
            return $relatedProduct;
        });

        // Increment view count
        $product->increment('views');

        return view('product.show', compact('product', 'relatedProducts', 'commissionRate'));
    }

    /**
     * AJAX search for live search
     */
    public function searchAjax(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        $query = $request->query;
        $commissionRate = $this->getCommissionRate();

        $products = Product::where('status', 'published')
            ->whereHas('seller', function($q) {
                $q->where('status', 'active');
            })
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhereHas('categories', function($cq) use ($query) {
                      $cq->where('name', 'LIKE', "%{$query}%");
                  });
            })
            ->with(['images'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->take(5)
            ->get();

        // Calculate final prices
        $products->transform(function ($product) use ($commissionRate) {
            $product->final_base_price = $this->calculatePriceWithCommission($product->base_price, $commissionRate);
            $product->final_sale_price = $product->sale_price ? 
                $this->calculatePriceWithCommission($product->sale_price, $commissionRate) : null;
            return $product;
        });

        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }
}