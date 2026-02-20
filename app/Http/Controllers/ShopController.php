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

        // Search with intelligent matching
        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower(trim($request->search));
            
            $query->where(function($q) use ($search) {
                // Original case-insensitive search
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(description) LIKE ?', ["%{$search}%"])
                  ->orWhere('sku', 'like', "%{$search}%");
                
                // If search ends with 's', also search without 's'
                if (substr($search, -1) === 's') {
                    $singular = substr($search, 0, -1);
                    if (strlen($singular) >= 2) {
                        $q->orWhereRaw('LOWER(name) LIKE ?', ["%{$singular}%"])
                          ->orWhereRaw('LOWER(description) LIKE ?', ["%{$singular}%"]);
                    }
                }
                
                // If search doesn't end with 's', also search with 's'
                if (substr($search, -1) !== 's') {
                    $plural = $search . 's';
                    $q->orWhereRaw('LOWER(name) LIKE ?', ["%{$plural}%"])
                      ->orWhereRaw('LOWER(description) LIKE ?', ["%{$plural}%"]);
                }
                
                // Search in categories
                $q->orWhereHas('categories', function($q) use ($search) {
                    $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
                    if (substr($search, -1) === 's') {
                        $singular = substr($search, 0, -1);
                        $q->orWhereRaw('LOWER(name) LIKE ?', ["%{$singular}%"]);
                    }
                });
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

        // Price range filter (using base price without commission)
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
                $query->orderBy('reviews_avg_rating', 'desc');
                break;
            case 'popular':
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
            ->with(['images'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderBy('reviews_avg_rating', 'desc')
            ->take(4)
            ->get();

        // Pagination
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);
        
        // Calculate final prices with commission for all products
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

        return view('shop', compact('products', 'categories', 'topRatedProducts', 'commissionRate'));
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

        // Get related products with rating data
        $relatedProducts = Product::where('status', 'published')
            ->where('id', '!=', $product->id)
            ->whereHas('seller', function($query) {
                $query->where('status', 'active');
            })
            ->with(['images'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->inRandomOrder()
            ->take(6) // Get 6 to ensure we have enough after removing any category matches
            ->get()
            ->unique('id') // Remove duplicates
            ->take(4); // Take only 4
            
        // Calculate final prices for related products
        $relatedProducts->transform(function ($relatedProduct) use ($commissionRate) {
            $relatedProduct->final_base_price = $this->calculatePriceWithCommission($relatedProduct->base_price, $commissionRate);
            $relatedProduct->final_sale_price = $relatedProduct->sale_price ? 
                $this->calculatePriceWithCommission($relatedProduct->sale_price, $commissionRate) : null;
            return $relatedProduct;
        });

        // If no related products found by category, get random products
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
                
            // Calculate final prices for random products
            $randomProducts->transform(function ($randomProduct) use ($commissionRate) {
                $randomProduct->final_base_price = $this->calculatePriceWithCommission($randomProduct->base_price, $commissionRate);
                $randomProduct->final_sale_price = $randomProduct->sale_price ? 
                    $this->calculatePriceWithCommission($randomProduct->sale_price, $commissionRate) : null;
                return $randomProduct;
            });

            $relatedProducts = $relatedProducts->concat($randomProducts);
        }

        // Increment view count
        $product->increment('views');

        return view('product.show', compact('product', 'relatedProducts', 'commissionRate'));
    }
}