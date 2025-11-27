<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', 'published')
                        ->with(['seller', 'category']);

        // Filter by category
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->firstOrFail();
            $childIds = $category->children->pluck('id')->toArray();
            $query->whereIn('category_id', array_merge([$category->id], $childIds));
        }

        // Search
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort by search rank (boosted sellers appear higher)
        $products = $query->orderByDesc('search_rank')
                          ->paginate(20)
                          ->withQueryString();

        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('buyer.products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::with(['seller', 'reviews.user'])
                          ->where('slug', $slug)
                          ->where('status', 'published')
                          ->firstOrFail();

        $related = Product::where('category_id', $product->category_id)
                          ->where('id', '!=', $product->id)
                          ->where('status', 'published')
                          ->inRandomOrder()
                          ->limit(6)
                          ->get();

        return view('buyer.products.show', compact('product', 'related'));
    }
}