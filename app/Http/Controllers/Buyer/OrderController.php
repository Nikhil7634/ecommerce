<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // List all orders for the buyer
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        // Start query
        $query = Order::where('user_id', $userId)
            ->with(['items.product'])
            ->orderBy('created_at', 'desc');
        
        // Apply status filter
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }
        
        // Paginate results
        $orders = $query->paginate(10)->withQueryString();
        
        // Get statistics
        $totalOrders = Order::where('user_id', $userId)->count();
        $pendingCount = Order::where('user_id', $userId)->where('status', 'pending')->count();
        $confirmedCount = Order::where('user_id', $userId)->where('status', 'confirmed')->count();
        $deliveredCount = Order::where('user_id', $userId)->where('status', 'delivered')->count();
        $processingCount = Order::where('user_id', $userId)->where('status', 'processing')->count();
        $cancelledCount = Order::where('user_id', $userId)->whereIn('status', ['cancelled', 'payment_failed'])->count();
        
        // Get total spent
        $totalSpent = Order::where('user_id', $userId)
            ->whereIn('status', ['confirmed', 'delivered'])
            ->sum('total_amount');
            
        // Get average order value
        $completedOrdersCount = $confirmedCount + $deliveredCount;
        $avgOrderValue = $completedOrdersCount > 0 ? $totalSpent / $completedOrdersCount : 0;
        
        // Get last order date
        $lastOrder = Order::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();
        $lastOrderDate = $lastOrder ? $lastOrder->created_at->format('d M Y') : 'N/A';
        
        return view('buyer.orders.index', compact(
            'orders',
            'totalOrders',
            'pendingCount',
            'confirmedCount',
            'deliveredCount',
            'processingCount',
            'cancelledCount',
            'totalSpent',
            'avgOrderValue',
            'lastOrderDate'
        ));
    }
    
    // Show a single order
    public function show(Order $order)
    {
        // Verify the order belongs to the user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }
        
        $order->load(['items.product.images', 'items.product.seller']);
        
        // Check which items are reviewable
        $reviewableItems = [];
        foreach ($order->items as $item) {
            $existingReview = Review::where('user_id', Auth::id())
                ->where('product_id', $item->product_id)
                ->first();
            
            $reviewableItems[$item->product_id] = [
                'can_review' => !$existingReview && in_array($order->status, ['delivered', 'confirmed']),
                'existing_review' => $existingReview,
            ];
        }
        
        return view('buyer.orders.show', compact('order', 'reviewableItems'));
    }
    
    // Cancel an order
    public function cancel(Request $request, Order $order)
    {
        // Verify the order belongs to the user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }
        
        // Check if order can be cancelled
        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->back()
                ->with('error', 'This order cannot be cancelled. Please contact support.');
        }
        
        // Update order status
        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->input('reason', 'Cancelled by customer')
        ]);
        
        // Restore product stock
        foreach ($order->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }
        
        return redirect()->route('buyer.orders')
            ->with('success', 'Order cancelled successfully.');
    }
    
    // Generate invoice PDF
    public function invoice(Order $order)
    {
        // Verify the order belongs to the user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }
        
        try {
            // Check if DomPDF is available
            if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
                Log::warning('DomPDF not installed. Please run: composer require barryvdh/laravel-dompdf');
                return $this->downloadHtmlInvoice($order);
            }
            
            $order->load(['items.product', 'user']);
            
            $pdf = Pdf::loadView('buyer.orders.invoice', compact('order'));
            
            // Set paper size and orientation
            $pdf->setPaper('A4', 'portrait');
            
            // Download the PDF
            return $pdf->download('invoice-' . $order->order_number . '.pdf');
            
        } catch (\Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            
            // Fallback to HTML invoice
            return $this->downloadHtmlInvoice($order);
        }
    }
    
    /**
     * Fallback method to download HTML invoice
     */
    private function downloadHtmlInvoice(Order $order)
    {
        $order->load(['items.product', 'user']);
        
        $html = view('buyer.orders.invoice-html', compact('order'))->render();
        
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="invoice-' . $order->order_number . '.html"');
    }
    
    // Track order
    public function track(Order $order)
    {
        // Verify the order belongs to the user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }
        
        // Get tracking information based on order status
        $trackingInfo = $this->getTrackingInfo($order);
        
        return view('buyer.orders.track', compact('order', 'trackingInfo'));
    }
    
    /**
     * Get tracking information for an order
     */
    private function getTrackingInfo($order)
    {
        $trackingNumber = 'TRK' . strtoupper(substr($order->order_number, -8));
        
        $statuses = [
            'pending' => 'Order Placed',
            'processing' => 'Processing',
            'confirmed' => 'Confirmed',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'payment_failed' => 'Payment Failed'
        ];
        
        $timeline = [
            [
                'status' => 'Order Placed',
                'date' => $order->created_at->format('d M Y, h:i A'),
                'completed' => true,
                'description' => 'Your order has been placed successfully.'
            ],
            [
                'status' => 'Payment Confirmed',
                'date' => $order->paid_at ? $order->paid_at->format('d M Y, h:i A') : null,
                'completed' => $order->payment_status === 'paid',
                'description' => 'Payment has been confirmed.'
            ],
            [
                'status' => 'Processing',
                'date' => $order->updated_at->format('d M Y, h:i A'),
                'completed' => in_array($order->status, ['processing', 'shipped', 'delivered']),
                'description' => 'Your order is being processed.'
            ],
            [
                'status' => 'Shipped',
                'date' => null,
                'completed' => in_array($order->status, ['shipped', 'delivered']),
                'description' => 'Your order has been shipped.'
            ],
            [
                'status' => 'Delivered',
                'date' => null,
                'completed' => $order->status === 'delivered',
                'description' => 'Your order has been delivered.'
            ]
        ];
        
        return [
            'tracking_number' => $trackingNumber,
            'current_status' => $statuses[$order->status] ?? ucfirst($order->status),
            'estimated_delivery' => $order->created_at->addDays(5)->format('d M Y'),
            'carrier' => 'Standard Shipping',
            'timeline' => $timeline,
            'shipping_address' => $order->shipping_address . ', ' . $order->shipping_city . ', ' . $order->shipping_state . ' - ' . $order->shipping_zip
        ];
    }
}