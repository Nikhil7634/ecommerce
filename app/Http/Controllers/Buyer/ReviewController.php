<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Review;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class ReviewController extends Controller
{
    protected $reviewTableColumns;

    public function __construct()
    {
        // Get actual columns from reviews table
        $this->reviewTableColumns = Schema::getColumnListing('reviews');
    }

    /**
     * Display a listing of the user's reviews
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        // Build query
        $query = Review::where('user_id', $userId)
            ->with(['product' => function($q) {
                $q->with('images');
            }])
            ->orderBy('created_at', 'desc');
        
        // Filter by status - using the actual column name
        if ($request->has('status') && $request->status != 'all') {
            if (in_array('is_approved', $this->reviewTableColumns)) {
                // If is_approved exists
                if ($request->status == 'approved') {
                    $query->where('is_approved', true);
                } elseif ($request->status == 'pending') {
                    $query->where('is_approved', false);
                }
            } elseif (in_array('status', $this->reviewTableColumns)) {
                // If status column exists
                $query->where('status', $request->status);
            }
            // If neither exists, skip filtering
        }
        
        // Filter by rating
        if ($request->has('rating') && !empty($request->rating)) {
            $rating = intval($request->rating);
            $query->where('rating', '>=', $rating);
        }
        
        // Search by product name
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }
        
        $reviews = $query->paginate(10)->appends($request->query());
        
        // Calculate statistics using the actual column names
        $totalReviews = Review::where('user_id', $userId)->count();
        $averageRating = Review::where('user_id', $userId)->avg('rating') ?? 0;
        
        // Count pending reviews based on available columns
        $pendingReviews = 0;
        if (in_array('is_approved', $this->reviewTableColumns)) {
            $pendingReviews = Review::where('user_id', $userId)
                ->where('is_approved', false)
                ->count();
        } elseif (in_array('status', $this->reviewTableColumns)) {
            $pendingReviews = Review::where('user_id', $userId)
                ->where('status', 'pending')
                ->count();
        }
        
        $productsReviewed = Review::where('user_id', $userId)
            ->distinct('product_id')
            ->count('product_id');
        
        return view('buyer.reviews.index', compact(
            'reviews',
            'totalReviews',
            'averageRating',
            'pendingReviews',
            'productsReviewed'
        ));
    }

    /**
     * Show the form for creating a new review
     */
    public function create($orderId)
    {
        $userId = Auth::id();
        
        // Find the order with the given ID
        $order = Order::where('user_id', $userId)
            ->where('id', $orderId)
            ->whereIn('status', ['delivered', 'completed', 'confirmed'])
            ->with('items.product')
            ->firstOrFail();
        
        // Get product ID from query parameter
        $productId = request('product_id');
        
        if ($productId) {
            // Verify this product was in the order
            $orderItem = $order->items()
                ->where('product_id', $productId)
                ->first();
            
            if (!$orderItem) {
                // FIXED: Changed from 'buyer.orders.show' to 'buyer.order.show'
                return redirect()->route('buyer.order.show', $orderId)
                    ->with('error', 'This product was not found in your order.');
            }
            
            // Check if already reviewed
            $existingReview = Review::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();
            
            if ($existingReview) {
                return redirect()->route('buyer.reviews.edit', $existingReview->id)
                    ->with('info', 'You have already reviewed this product. You can edit your review.');
            }
            
            $product = Product::findOrFail($productId);
            
            return view('buyer.reviews.create', compact('product', 'orderId'));
        }
        
        // If no product ID specified, show list of products to review
        $reviewableProducts = [];
        
        foreach ($order->items as $item) {
            $existingReview = Review::where('user_id', $userId)
                ->where('product_id', $item->product_id)
                ->first();
            
            if (!$existingReview) {
                $reviewableProducts[] = $item->product;
            }
        }
        
        if (empty($reviewableProducts)) {
            // FIXED: Changed from 'buyer.orders.show' to 'buyer.order.show'
            return redirect()->route('buyer.order.show', $orderId)
                ->with('info', 'You have already reviewed all products in this order.');
        }
        
        return view('buyer.reviews.select-product', compact('order', 'reviewableProducts'));
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10|max:500',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $userId = Auth::id();
        
        // Verify order belongs to user
        $order = Order::where('user_id', $userId)
            ->where('id', $request->order_id)
            ->firstOrFail();
        
        // Verify product was in the order
        $orderItem = $order->items()
            ->where('product_id', $request->product_id)
            ->firstOrFail();
        
        // Check for duplicate
        $existingReview = Review::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();
        
        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already reviewed this product.');
        }
        
        // Handle image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews/' . $userId, 'public');
                $images[] = $path;
            }
        }
        
        // Prepare review data based on available columns
        $reviewData = [
            'user_id' => $userId,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'review' => $request->review,
        ];
        
        // Add order_id if column exists
        if (in_array('order_id', $this->reviewTableColumns)) {
            $reviewData['order_id'] = $request->order_id;
        }
        
        // Add approval status based on available columns
        if (in_array('is_approved', $this->reviewTableColumns)) {
            $reviewData['is_approved'] = false;
        } elseif (in_array('status', $this->reviewTableColumns)) {
            $reviewData['status'] = 'pending';
        }
        
        // Add images if column exists
        if (!empty($images) && in_array('images', $this->reviewTableColumns)) {
            $reviewData['images'] = $images;
        }
        
        $review = Review::create($reviewData);
        
        return redirect()->route('buyer.reviews')
            ->with('success', 'Thank you for your review! It has been submitted for approval.');
    }

    /**
     * Show the form for editing a review
     */
    public function edit($id)
    {
        $userId = Auth::id();
        
        $review = Review::where('user_id', $userId)
            ->with('product')
            ->findOrFail($id);
        
        return view('buyer.reviews.edit', compact('review'));
    }

    /**
     * Update the specified review
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10|max:500',
        ]);
        
        $userId = Auth::id();
        
        $review = Review::where('user_id', $userId)->findOrFail($id);
        
        // Prepare update data
        $updateData = [
            'rating' => $request->rating,
            'review' => $request->review,
        ];
        
        // Reset approval status based on available columns
        if (in_array('is_approved', $this->reviewTableColumns)) {
            $updateData['is_approved'] = false;
        } elseif (in_array('status', $this->reviewTableColumns)) {
            $updateData['status'] = 'pending';
        }
        
        $review->update($updateData);
        
        return redirect()->route('buyer.reviews')
            ->with('success', 'Your review has been updated and submitted for approval.');
    }

    /**
     * Remove the specified review
     */
    public function destroy($id)
    {
        $userId = Auth::id();
        
        $review = Review::where('user_id', $userId)->findOrFail($id);
        
        // Delete associated images if column exists
        if (in_array('images', $this->reviewTableColumns) && $review->images) {
            $images = is_array($review->images) ? $review->images : json_decode($review->images, true);
            if (is_array($images)) {
                foreach ($images as $image) {
                    if (\Storage::disk('public')->exists($image)) {
                        \Storage::disk('public')->delete($image);
                    }
                }
            }
        }
        
        $review->delete();
        
        return redirect()->route('buyer.reviews')
            ->with('success', 'Review deleted successfully.');
    }

    /**
     * Get products eligible for review from a specific order
     */
    public function getReviewableProducts($orderId)
    {
        $userId = Auth::id();
        
        $order = Order::where('user_id', $userId)
            ->where('id', $orderId)
            ->whereIn('status', ['delivered', 'completed'])
            ->firstOrFail();
        
        $orderItems = $order->items()->with('product')->get();
        
        $reviewableItems = [];
        
        foreach ($orderItems as $item) {
            $existingReview = Review::where('user_id', $userId)
                ->where('product_id', $item->product_id)
                ->first();
            
            $reviewableItems[] = [
                'product' => $item->product,
                'can_review' => !$existingReview,
                'existing_review' => $existingReview,
            ];
        }
        
        return response()->json([
            'success' => true,
            'items' => $reviewableItems,
        ]);
    }
}