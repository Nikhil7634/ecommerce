<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Order;
use App\Models\Product;

class ReviewController extends Controller
{
    // Show all reviews by the user
    public function index()
    {
        $reviews = Review::where('user_id', Auth::id())
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('buyer.reviews.index', compact('reviews'));
    }
    
    // Show form to create a review for a specific order
    public function create(Order $order)
    {
        // Verify the order belongs to the user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Check if order is eligible for review (completed/delivered)
        if (!in_array($order->status, ['confirmed', 'delivered'])) {
            return redirect()->back()->with('error', 'You can only review completed orders.');
        }
        
        // Get order items that haven't been reviewed yet
        $orderItems = $order->items()->whereDoesntHave('review', function($query) use ($order) {
            $query->where('user_id', Auth::id());
        })->get();
        
        return view('buyer.reviews.create', compact('order', 'orderItems'));
    }
    
    // Store a new review
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
        ]);
        
        // Verify order belongs to user
        $order = Order::findOrFail($validated['order_id']);
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Check if user already reviewed this product from this order
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->where('order_id', $validated['order_id'])
            ->first();
            
        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already reviewed this product from this order.');
        }
        
        // Create the review
        Review::create([
            'user_id' => Auth::id(),
            'order_id' => $validated['order_id'],
            'product_id' => $validated['product_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);
        
        return redirect()->route('buyer.reviews')
            ->with('success', 'Review submitted successfully!');
    }
    
    // Show form to edit a review
    public function edit(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('buyer.reviews.edit', compact('review'));
    }
    
    // Update a review
    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
        ]);
        
        $review->update($validated);
        
        return redirect()->route('buyer.reviews')
            ->with('success', 'Review updated successfully!');
    }
    
    // Delete a review
    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }
        
        $review->delete();
        
        return redirect()->route('buyer.reviews')
            ->with('success', 'Review deleted successfully!');
    }
}