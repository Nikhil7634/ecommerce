<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Store;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Review;

class StoreController extends Controller
{
    public function index()
    {
        $seller = Auth::user();
        $store = $seller->store;
        
        // If store doesn't exist, create one
        if (!$store) {
            $store = Store::create([
                'user_id' => $seller->id,
                'name' => $seller->business_name ?? $seller->name . "'s Store",
                'slug' => $this->generateUniqueSlug($seller->name),
                'email' => $seller->email,
                'phone' => $seller->phone,
                'status' => 'pending',
            ]);
        }
        
        // Get store statistics
        $totalProducts = Product::where('seller_id', $seller->id)->count();
        
        $totalOrders = OrderItem::where('seller_id', $seller->id)
            ->distinct('order_id')
            ->count('order_id');
        
        $totalReviews = Review::whereIn('product_id', 
            Product::where('seller_id', $seller->id)->pluck('id')
        )->count();
        
        $averageRating = Review::whereIn('product_id', 
            Product::where('seller_id', $seller->id)->pluck('id')
        )->avg('rating') ?? 0;
        
        return view('seller.store.index', compact(
            'store',
            'totalProducts',
            'totalOrders',
            'totalReviews',
            'averageRating'
        ));
    }
    
    public function update(Request $request, $section)
    {
        $seller = Auth::user();
        $store = $seller->store;
        
        if (!$store) {
            return redirect()->back()->with('error', 'Store not found.');
        }
        
        switch ($section) {
            case 'general':
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'slug' => 'nullable|string|max:255|unique:stores,slug,' . $store->id,
                    'description' => 'nullable|string',
                    'email' => 'nullable|email|max:255',
                    'phone' => 'nullable|string|max:20',
                    'address' => 'nullable|string|max:500',
                    'city' => 'nullable|string|max:100',
                    'state' => 'nullable|string|max:100',
                    'zip' => 'nullable|string|max:20',
                    'country' => 'nullable|string|max:100',
                    'gst' => 'nullable|string|max:50',
                ]);
                
                // Generate slug if not provided
                if (empty($validated['slug'])) {
                    $validated['slug'] = $this->generateUniqueSlug($validated['name'], $store->id);
                }
                
                $store->update($validated);
                $message = 'Store information updated successfully.';
                break;
                
            case 'logo':
                $request->validate([
                    'logo' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048',
                ]);
                
                // Delete old logo
                if ($store->logo && Storage::disk('public')->exists($store->logo)) {
                    Storage::disk('public')->delete($store->logo);
                }
                
                $path = $request->file('logo')->store('stores/logos', 'public');
                $store->update(['logo' => $path]);
                $message = 'Store logo uploaded successfully.';
                break;
                
            case 'banner':
                $request->validate([
                    'banner' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048',
                ]);
                
                // Delete old banner
                if ($store->banner && Storage::disk('public')->exists($store->banner)) {
                    Storage::disk('public')->delete($store->banner);
                }
                
                $path = $request->file('banner')->store('stores/banners', 'public');
                $store->update(['banner' => $path]);
                $message = 'Store banner uploaded successfully.';
                break;
                
            case 'logo-remove':
                if ($store->logo && Storage::disk('public')->exists($store->logo)) {
                    Storage::disk('public')->delete($store->logo);
                    $store->update(['logo' => null]);
                }
                $message = 'Store logo removed successfully.';
                break;
                
            case 'banner-remove':
                if ($store->banner && Storage::disk('public')->exists($store->banner)) {
                    Storage::disk('public')->delete($store->banner);
                    $store->update(['banner' => null]);
                }
                $message = 'Store banner removed successfully.';
                break;
                
            case 'seo':
                $validated = $request->validate([
                    'meta_title' => 'nullable|string|max:255',
                    'meta_description' => 'nullable|string|max:500',
                    'meta_keywords' => 'nullable|string|max:500',
                ]);
                
                $store->update($validated);
                $message = 'SEO settings updated successfully.';
                break;
                
            case 'shipping':
                $validated = $request->validate([
                    'shipping_policy' => 'nullable|string',
                    'return_policy' => 'nullable|string',
                    'free_shipping_threshold' => 'nullable|numeric|min:0',
                    'shipping_rate' => 'nullable|numeric|min:0',
                    'delivery_days' => 'nullable|string|max:100',
                ]);
                
                $store->update($validated);
                $message = 'Shipping & Returns updated successfully.';
                break;
                
            case 'social':
                $validated = $request->validate([
                    'facebook_url' => 'nullable|url|max:255',
                    'instagram_url' => 'nullable|url|max:255',
                    'twitter_url' => 'nullable|url|max:255',
                    'youtube_url' => 'nullable|url|max:255',
                    'pinterest_url' => 'nullable|url|max:255',
                    'linkedin_url' => 'nullable|url|max:255',
                ]);
                
                $store->update($validated);
                $message = 'Social links updated successfully.';
                break;
                
            default:
                return redirect()->back()->with('error', 'Invalid update section.');
        }
        
        return redirect()->route('seller.store', ['#' . $section])
            ->with('success', $message);
    }
    
    private function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = str()->slug($name);
        $originalSlug = $slug;
        $count = 1;
        
        while (Store::where('slug', $slug)
            ->when($ignoreId, function($query, $ignoreId) {
                return $query->where('id', '!=', $ignoreId);
            })
            ->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        
        return $slug;
    }
}