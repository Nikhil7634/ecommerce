<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist
     */
    public function index()
    {
        $wishlistItems = Wishlist::with(['product.images', 'product.categories', 'product.seller'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('buyer.wishlist.index', compact('wishlistItems'));
    }

    /**
     * Add or remove product from wishlist
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $wishlistItem = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            $message = 'Product removed from wishlist';
            $added = false;
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id
            ]);
            $message = 'Product added to wishlist';
            $added = true;
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'added' => $added,
                'wishlist_count' => Wishlist::where('user_id', Auth::id())->count()
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Remove item from wishlist
     */
    public function destroy($id)
    {
        $wishlistItem = Wishlist::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $wishlistItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from wishlist',
            'wishlist_count' => Wishlist::where('user_id', Auth::id())->count()
        ]);
    }

    /**
     * Move item from wishlist to cart
     */
    public function moveToCart($id)
    {
        $wishlistItem = Wishlist::with('product')
            ->where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        // Check if product is in stock
        if ($wishlistItem->product->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Product is out of stock'
            ], 400);
        }

        // Add to cart (you'll need to implement your cart logic)
        // $cartItem = Cart::updateOrCreate([
        //     'user_id' => Auth::id(),
        //     'product_id' => $wishlistItem->product_id
        // ], [
        //     'quantity' => 1
        // ]);

        // Remove from wishlist
        $wishlistItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product moved to cart',
            'wishlist_count' => Wishlist::where('user_id', Auth::id())->count(),
            'cart_count' => 0 // Replace with actual cart count
        ]);
    }

    /**
     * Clear entire wishlist
     */
    public function clear()
    {
        Wishlist::where('user_id', Auth::id())->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Wishlist cleared successfully'
            ]);
        }

        return back()->with('success', 'Wishlist cleared successfully');
    }

    /**
     * Get wishlist count
     */
    public function getCount()
    {
        $count = Wishlist::where('user_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Add all wishlist items to cart
     */
    public function addAllToCart()
    {
        $wishlistItems = Wishlist::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $addedCount = 0;
        $outOfStock = [];

        foreach ($wishlistItems as $item) {
            if ($item->product->stock > 0) {
                // Add to cart logic here
                // Cart::updateOrCreate([...]);
                $addedCount++;
            } else {
                $outOfStock[] = $item->product->name;
            }
        }

        // Remove all items from wishlist
        Wishlist::where('user_id', Auth::id())->delete();

        $message = $addedCount . ' items moved to cart';
        if (!empty($outOfStock)) {
            $message .= '. ' . count($outOfStock) . ' items were out of stock: ' . implode(', ', $outOfStock);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'added_count' => $addedCount,
            'out_of_stock' => $outOfStock
        ]);
    }
}