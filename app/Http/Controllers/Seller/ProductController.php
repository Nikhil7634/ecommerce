<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductTier;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $seller = Auth::user();
        
        // Start query
        $query = $seller->products()->with(['images', 'categories']);
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Apply category filter
        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }
        
        // Apply status filter
        if ($request->has('status') && !empty($request->status) && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Get counts for stats
        $totalCount = $seller->products()->count();
        $publishedCount = $seller->products()->where('status', 'published')->count();
        $draftCount = $seller->products()->where('status', 'draft')->count();
        
        // Get categories for filter dropdown
        $categories = Category::whereNull('parent_id')->with('children')->get();
        
        // Get paginated products
        $products = $query->latest()->paginate(15);
        
        // Pass all variables to view
        return view('seller.products', compact(
            'seller', 
            'products', 
            'categories',
            'totalCount',
            'publishedCount',
            'draftCount'
        ));
    }

    public function create()
    {
        $seller = Auth::user();
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('seller.product-create', compact('seller', 'categories'));
    }

    public function store(Request $request)
    {
        $seller = Auth::user();

        DB::beginTransaction();

        try {
            // Basic validation
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'base_price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'low_stock_threshold' => 'nullable|integer|min:0',
                'weight' => 'nullable|numeric|min:0',
                'length' => 'nullable|numeric|min:0',
                'width' => 'nullable|numeric|min:0',
                'height' => 'nullable|numeric|min:0',
                'status' => 'required|in:published,draft',
                'category_ids' => 'required|array|min:1',
                'category_ids.*' => 'exists:categories,id',
                'images' => 'required|array|min:1',
                'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]);

            // Additional validation for tiers if present
            if ($request->has('tiers')) {
                foreach ($request->tiers as $index => $tier) {
                    $request->validate([
                        "tiers.$index.min_qty" => 'required|integer|min:1',
                        "tiers.$index.max_qty" => 'nullable|integer|gt:tiers.' . $index . '.min_qty',
                        "tiers.$index.price" => 'required|numeric|min:0.01',
                    ]);
                }
            }

            // Additional validation for variants if present
            if ($request->has('variants')) {
                foreach ($request->variants as $index => $variant) {
                    $request->validate([
                        "variants.$index.color" => 'nullable|string|max:50',
                        "variants.$index.size" => 'nullable|string|max:50',
                        "variants.$index.price_adjustment" => 'nullable|numeric',
                        "variants.$index.stock" => 'nullable|integer|min:0',
                    ]);
                }
            }

            // Create product
            $product = $seller->products()->create([
                'name' => $request->name,
                'slug' => \Str::slug($request->name) . '-' . uniqid(),
                'description' => $request->description,
                'base_price' => (float) $request->base_price,
                'sale_price' => $request->filled('sale_price') ? (float) $request->sale_price : null,
                'stock' => (int) $request->stock,
                'low_stock_threshold' => $request->filled('low_stock_threshold') ? (int) $request->low_stock_threshold : 10,
                'allow_backorder' => $request->has('allow_backorder') ? 1 : 0,
                'weight' => $request->filled('weight') ? (float) $request->weight : null,
                'length' => $request->filled('length') ? (float) $request->length : null,
                'width' => $request->filled('width') ? (float) $request->width : null,
                'height' => $request->filled('height') ? (float) $request->height : null,
                'status' => $request->status,
            ]);

            // Attach categories
            $product->categories()->sync($request->category_ids);

            // Upload images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $index === 0,
                    ]);
                }
            }

            // Save tiered pricing
            if ($request->filled('tiers')) {
                foreach ($request->tiers as $tier) {
                    // Skip if min_qty or price is empty
                    if (empty($tier['min_qty']) || empty($tier['price'])) {
                        continue;
                    }

                    ProductTier::create([
                        'product_id' => $product->id,
                        'min_qty' => (int) $tier['min_qty'],
                        'max_qty' => isset($tier['max_qty']) && !empty($tier['max_qty']) ? (int) $tier['max_qty'] : null,
                        'price' => (float) $tier['price'],
                    ]);
                }
            }

            // Save variants
            if ($request->filled('variants')) {
                foreach ($request->variants as $variant) {
                    // Skip if all fields are empty
                    if (empty($variant['color']) && empty($variant['size']) && 
                        empty($variant['price_adjustment']) && empty($variant['stock'])) {
                        continue;
                    }

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color' => !empty($variant['color']) ? $variant['color'] : null,
                        'size' => !empty($variant['size']) ? $variant['size'] : null,
                        'price_adjustment' => !empty($variant['price_adjustment']) ? (float) $variant['price_adjustment'] : 0,
                        'stock' => !empty($variant['stock']) ? (int) $variant['stock'] : 0,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('seller.products.index')
                            ->with('success', 'Product added successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e; // Laravel will handle validation errors automatically
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $seller = Auth::user();
        $product = $seller->products()->with(['images', 'categories', 'tiers', 'variants'])->findOrFail($id);
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('seller.product-edit', compact('seller', 'product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $seller = Auth::user();
        $product = $seller->products()->findOrFail($id);

        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'base_price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'low_stock_threshold' => 'nullable|integer|min:0',
                'weight' => 'nullable|numeric|min:0',
                'length' => 'nullable|numeric|min:0',
                'width' => 'nullable|numeric|min:0',
                'height' => 'nullable|numeric|min:0',
                'category_ids' => 'required|array|min:1',
                'category_ids.*' => 'exists:categories,id',
                'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
                'status' => 'required|in:published,draft',
            ]);

            $product->update([
                'name' => $request->name,
                'slug' => \Str::slug($request->name) . '-' . $product->id,
                'description' => $request->description,
                'base_price' => (float) $request->base_price,
                'sale_price' => $request->filled('sale_price') ? (float) $request->sale_price : null,
                'stock' => (int) $request->stock,
                'low_stock_threshold' => $request->filled('low_stock_threshold') ? (int) $request->low_stock_threshold : 10,
                'allow_backorder' => $request->has('allow_backorder') ? 1 : 0,
                'weight' => $request->filled('weight') ? (float) $request->weight : null,
                'length' => $request->filled('length') ? (float) $request->length : null,
                'width' => $request->filled('width') ? (float) $request->width : null,
                'height' => $request->filled('height') ? (float) $request->height : null,
                'status' => $request->status,
            ]);

            // Update categories
            $product->categories()->sync($request->category_ids);

            // Upload new images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                    ]);
                }
            }

            // Update tiered pricing - delete old and create new
            $product->tiers()->delete();
            if ($request->filled('tiers')) {
                foreach ($request->tiers as $tier) {
                    if (empty($tier['min_qty']) || empty($tier['price'])) {
                        continue;
                    }

                    ProductTier::create([
                        'product_id' => $product->id,
                        'min_qty' => (int) $tier['min_qty'],
                        'max_qty' => isset($tier['max_qty']) && !empty($tier['max_qty']) ? (int) $tier['max_qty'] : null,
                        'price' => (float) $tier['price'],
                    ]);
                }
            }

            // Update variants - delete old and create new
            $product->variants()->delete();
            if ($request->filled('variants')) {
                foreach ($request->variants as $variant) {
                    if (empty($variant['color']) && empty($variant['size']) && 
                        empty($variant['price_adjustment']) && empty($variant['stock'])) {
                        continue;
                    }

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color' => !empty($variant['color']) ? $variant['color'] : null,
                        'size' => !empty($variant['size']) ? $variant['size'] : null,
                        'price_adjustment' => !empty($variant['price_adjustment']) ? (float) $variant['price_adjustment'] : 0,
                        'stock' => !empty($variant['stock']) ? (int) $variant['stock'] : 0,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('seller.products.index')
                            ->with('success', 'Product updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $seller = Auth::user();
        $product = $seller->products()->findOrFail($id);

        DB::beginTransaction();

        try {
            // Delete product images from storage
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            // Delete related records
            $product->tiers()->delete();
            $product->variants()->delete();
            $product->categories()->detach();
            
            // Delete product
            $product->delete();

            DB::commit();

            return redirect()->route('seller.products.index')
                            ->with('success', 'Product deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while deleting the product.');
        }
    }

    public function removeImage(Request $request, $productId, $imageId)
    {
        try {
            $seller = Auth::user();
            
            // Find the product owned by the seller
            $product = $seller->products()->findOrFail($productId);
            
            // Find the image
            $image = $product->images()->findOrFail($imageId);
            
            // Don't allow deleting if it's the only image
            if ($product->images()->count() <= 1) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete the only image. Product must have at least one image.'
                    ], 400);
                }
                return back()->with('error', 'Cannot delete the only image. Product must have at least one image.');
            }
            
            // If deleting primary image, set another as primary
            if ($image->is_primary) {
                $nextImage = $product->images()
                    ->where('id', '!=', $image->id)
                    ->first();
                
                if ($nextImage) {
                    $nextImage->update(['is_primary' => true]);
                }
            }
            
            // Delete from storage
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            
            // Delete from database
            $image->delete();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Image deleted successfully.'
                ]);
            }
            
            return back()->with('success', 'Image deleted successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Error removing image: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'An error occurred while deleting the image.');
        }
    }

    public function setPrimaryImage(Request $request, $productId, $imageId)
    {
        try {
            $seller = Auth::user();
            
            // Find the product owned by the seller
            $product = $seller->products()->findOrFail($productId);
            
            // Find the image
            $image = $product->images()->findOrFail($imageId);
            
            // Reset all images to not primary
            $product->images()->update(['is_primary' => false]);
            
            // Set this image as primary
            $image->update(['is_primary' => true]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Primary image set successfully.'
                ]);
            }
            
            return back()->with('success', 'Primary image set successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Error setting primary image: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'An error occurred while setting primary image.');
        }
    }
}