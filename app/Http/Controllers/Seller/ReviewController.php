<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = Auth::id();
        
        // Get seller's product IDs
        $productIds = Product::where('seller_id', $sellerId)->pluck('id');
        
        // Build query for reviews
        $query = Review::with(['user', 'product'])
            ->whereIn('product_id', $productIds);
        
        // Apply rating filter
        if ($request->has('rating') && $request->rating) {
            $rating = $request->rating;
            $query->where('rating', '>=', $rating);
        }
        
        // Apply status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('product', function($productQuery) use ($search) {
                    $productQuery->where('name', 'like', "%{$search}%");
                })->orWhere('comment', 'like', "%{$search}%");
            });
        }
        
        // Get paginated reviews
        $reviews = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get statistics
        $totalReviews = Review::whereIn('product_id', $productIds)->count();
        $averageRating = Review::whereIn('product_id', $productIds)->avg('rating') ?? 0;
        $productsWithReviews = Review::whereIn('product_id', $productIds)
            ->distinct('product_id')
            ->count('product_id');
        $pendingReviews = Review::whereIn('product_id', $productIds)
            ->where('status', 'pending')
            ->count();
        
        return view('seller.reviews.index', compact(
            'reviews',
            'totalReviews',
            'averageRating',
            'productsWithReviews',
            'pendingReviews'
        ));
    }

    public function approve($id)
    {
        $review = Review::findOrFail($id);
        
        // Verify this review belongs to seller's product
        $sellerId = Auth::id();
        $product = Product::where('id', $review->product_id)
            ->where('seller_id', $sellerId)
            ->first();
            
        if (!$product) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
        
        $review->update(['status' => 'approved']);
        
        return redirect()->back()->with('success', 'Review approved successfully.');
    }

    public function reject($id)
    {
        $review = Review::findOrFail($id);
        
        // Verify this review belongs to seller's product
        $sellerId = Auth::id();
        $product = Product::where('id', $review->product_id)
            ->where('seller_id', $sellerId)
            ->first();
            
        if (!$product) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
        
        $review->update(['status' => 'rejected']);
        
        return redirect()->back()->with('success', 'Review rejected successfully.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'review_ids' => 'required|array',
            'action' => 'required|in:approve,reject,delete'
        ]);
        
        $sellerId = Auth::id();
        $productIds = Product::where('seller_id', $sellerId)->pluck('id');
        
        $reviews = Review::whereIn('id', $request->review_ids)
            ->whereIn('product_id', $productIds)
            ->get();
        
        $count = 0;
        
        foreach ($reviews as $review) {
            if ($request->action == 'approve') {
                $review->update(['status' => 'approved']);
            } elseif ($request->action == 'reject') {
                $review->update(['status' => 'rejected']);
            } elseif ($request->action == 'delete') {
                $review->delete();
            }
            $count++;
        }
        
        return redirect()->back()->with('success', "{$count} reviews {$request->action}d successfully.");
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|max:1000'
        ]);
        
        $review = Review::findOrFail($id);
        
        // Verify this review belongs to seller's product
        $sellerId = Auth::id();
        $product = Product::where('id', $review->product_id)
            ->where('seller_id', $sellerId)
            ->first();
            
        if (!$product) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }
        
        $review->update([
            'seller_reply' => $request->reply,
            'replied_at' => now()
        ]);
        
        return response()->json(['success' => true, 'message' => 'Reply added successfully.']);
    }
}