<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $orders = $query->paginate(10);
        
        // Get statistics - ALL VARIABLES MUST BE DEFINED
        $totalOrders = Order::where('user_id', $userId)->count();
        $pendingCount = Order::where('user_id', $userId)->where('status', 'pending')->count();
        $confirmedCount = Order::where('user_id', $userId)->where('status', 'confirmed')->count();
        $deliveredCount = Order::where('user_id', $userId)->where('status', 'delivered')->count();
        $processingCount = Order::where('user_id', $userId)->where('status', 'processing')->count();
        $cancelledCount = Order::where('user_id', $userId)->whereIn('status', ['cancelled', 'payment_failed'])->count();
        
        // Get total spent - FIXED: Add proper calculation
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
        
        return view('buyer.orders.show', compact('order'));
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
        
        $order->load(['items.product', 'user']);
        
        $pdf = Pdf::loadView('buyer.orders.invoice', compact('order'));
        
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
    
    // Track order
    public function track(Order $order)
    {
        // Verify the order belongs to the user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }
        
        // This would integrate with a shipping API
        $trackingInfo = [
            'order_number' => $order->order_number,
            'status' => $order->status,
            'estimated_delivery' => $order->created_at->addDays(5)->format('d M Y'),
            'current_location' => 'In transit',
            'shipping_provider' => 'Standard Shipping',
            'tracking_number' => 'TRK' . strtoupper(uniqid()),
        ];
        
        return view('buyer.orders.track', compact('order', 'trackingInfo'));
    }
}