<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Store;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index()
    {
        $seller = Auth::user();
        
        // Eager load the store relationship
        $seller->load('store');
        $store = $seller->store;
        
        return view('seller.settings', compact('seller', 'store'));
    }

    public function update(Request $request)
    {
        $seller = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);
        
        // Find existing store or create new one
        $store = Store::where('user_id', $seller->id)->first();
        
        if (!$store) {
            // Create new store
            $store = new Store();
            $store->user_id = $seller->id;
            $store->status = 'active';
        }
        
        // Generate unique slug if needed
        $slug = $this->generateUniqueSlug($request->slug, $store->id);
        
        // Update store details
        $store->name = $request->name;
        $store->slug = $slug;
        $store->description = $request->description;
        $store->address = $request->address;
        $store->phone = $request->phone;
        $store->email = $request->email;
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($store->logo && Storage::exists('public/' . $store->logo)) {
                Storage::delete('public/' . $store->logo);
            }
            
            $logoPath = $request->file('logo')->store('store-logos', 'public');
            $store->logo = $logoPath;
        }
        
        // Handle banner upload
        if ($request->hasFile('banner')) {
            // Delete old banner if exists
            if ($store->banner && Storage::exists('public/' . $store->banner)) {
                Storage::delete('public/' . $store->banner);
            }
            
            $bannerPath = $request->file('banner')->store('store-banners', 'public');
            $store->banner = $bannerPath;
        }
        
        $store->save();
        
        // Refresh the store relationship
        $seller->refresh();
        
        return redirect()->route('seller.settings')->with('success', 'Store settings saved successfully!');
    }

    private function generateUniqueSlug($baseSlug, $storeId = null)
    {
        $slug = Str::slug($baseSlug);
        $originalSlug = $slug;
        $count = 1;

        while (Store::where('slug', $slug)
                    ->when($storeId, function($query) use ($storeId) {
                        return $query->where('id', '!=', $storeId);
                    })
                    ->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function checkSlug(Request $request)
    {
        $request->validate([
            'slug' => 'required|string',
            'current' => 'nullable|string'
        ]);

        $seller = Auth::user();
        $store = Store::where('user_id', $seller->id)->first();
        
        $slug = Str::slug($request->slug);
        $currentSlug = $request->current;

        if ($currentSlug && $slug === $currentSlug) {
            return response()->json(['slug' => $slug]);
        }

        $availableSlug = $this->generateUniqueSlug($slug, $store ? $store->id : null);
        return response()->json(['slug' => $availableSlug]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);
        
        $seller = Auth::user();
        
        if (!$seller->password) {
            $seller->password = Hash::make($request->new_password);
            $seller->save();
            return redirect()->back()->with('success', 'Password set successfully!');
        }
        
        if (!Hash::check($request->current_password, $seller->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }
        
        $seller->password = Hash::make($request->new_password);
        $seller->save();
        return redirect()->back()->with('success', 'Password updated successfully!');
    }
}