<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SellerEarning;
use App\Models\Setting;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of seller's orders
     */
    public function index(Request $request)
    {
        $sellerId = Auth::id();
        
        // Get all order items for this seller with order and product details
        $query = OrderItem::where('seller_id', $sellerId)
            ->with([
                'order.user',
                'product' => function($q) {
                    $q->with('images');
                }
            ])
            ->orderBy('created_at', 'desc');
        
        // Filter by order status
        if ($request->has('status') && $request->status != 'all') {
            $query->whereHas('order', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }
        
        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereHas('order', function($q) use ($request) {
                $q->whereDate('created_at', '>=', Carbon::parse($request->date_from));
            });
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereHas('order', function($q) use ($request) {
                $q->whereDate('created_at', '<=', Carbon::parse($request->date_to));
            });
        }
        
        // Search by order number or customer name
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('order', function($oq) use ($search) {
                    $oq->where('order_number', 'like', "%{$search}%")
                      ->orWhere('shipping_name', 'like', "%{$search}%")
                      ->orWhere('shipping_email', 'like', "%{$search}%")
                      ->orWhere('shipping_phone', 'like', "%{$search}%");
                })->orWhereHas('product', function($pq) use ($search) {
                    $pq->where('name', 'like', "%{$search}%");
                });
            });
        }
        
        $orderItems = $query->paginate(15)->withQueryString();
        
        // Get order statistics
        $statistics = $this->getOrderStatistics($sellerId);
        
        // Get status counts for filter badges
        $statusCounts = $this->getStatusCounts($sellerId);
        
        // Get commission rate
        $commissionRate = $this->getCommissionRate();
        
        // Check earnings status for each item
        foreach ($orderItems as $item) {
            $earning = SellerEarning::where('order_item_id', $item->id)->first();
            $item->earnings_status = $earning ? 'added' : 'pending';
            $item->earnings_amount = $earning ? $earning->net_amount : 0;
            $item->commission_rate = $commissionRate;
        }
        
        return view('seller.orders.index', compact(
            'orderItems',
            'statistics',
            'statusCounts',
            'commissionRate'
        ));
    }

    /**
     * Display the specified order details
     */
    public function show($orderId)
    {
        $sellerId = Auth::id();
        
        $order = Order::with([
                'user',
                'items' => function($q) use ($sellerId) {
                    $q->where('seller_id', $sellerId)
                      ->with('product.images');
                }
            ])
            ->findOrFail($orderId);
        
        // Verify that this order has items from this seller
        if ($order->items->isEmpty()) {
            abort(404, 'Order not found for this seller.');
        }
        
        // Get earnings for each item
        foreach ($order->items as $item) {
            $item->earning = SellerEarning::where('order_item_id', $item->id)->first();
        }
        
        return view('seller.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:100',
            'tracking_url' => 'nullable|url|max:255',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $sellerId = Auth::id();
        
        DB::beginTransaction();
        
        try {
            // Verify that this order has items from this seller
            $order = Order::with(['items' => function($q) use ($sellerId) {
                    $q->where('seller_id', $sellerId);
                }])
                ->findOrFail($orderId);
            
            if ($order->items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found for this seller.'
                ], 404);
            }
            
            $oldStatus = $order->status;
            
            // Update order status
            $order->status = $request->status;
            
            // Add tracking information if provided
            if ($request->tracking_number) {
                $trackingData = [
                    'number' => $request->tracking_number,
                    'url' => $request->tracking_url,
                    'updated_at' => now(),
                ];
                $order->tracking_info = json_encode($trackingData);
            }
            
            $order->save();
            
            // If status changed to delivered, create earnings records
            if ($request->status == 'delivered' && $oldStatus != 'delivered') {
                $this->createSellerEarnings($order, $sellerId);
            }
            
            // If status changed from delivered, remove earnings (optional)
            if ($oldStatus == 'delivered' && $request->status != 'delivered') {
                $this->removeSellerEarnings($order);
            }
            
            DB::commit();
            
            $message = 'Order status updated successfully.';
            if ($request->status == 'delivered') {
                $message .= ' Earnings have been added to your account.';
            }
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'status' => $order->status,
                    'status_badge' => $this->getStatusBadge($order->status)
                ]);
            }
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order status update failed: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating order status: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error updating order status: ' . $e->getMessage());
        }
    }

    /**
     * Create seller earnings for delivered orders
     */
    private function createSellerEarnings($order, $sellerId)
    {
        $commissionRate = $this->getCommissionRate();
        $earningsCount = 0;
        
        foreach ($order->items as $item) {
            // Check if earning already exists
            $existingEarning = SellerEarning::where('order_item_id', $item->id)->first();
            
            if (!$existingEarning) {
                // Calculate commission and net amount
                $commission = ($item->total_price * $commissionRate) / 100;
                $netAmount = $item->total_price - $commission;
                
                // Create earning record
                SellerEarning::create([
                    'seller_id' => $sellerId,
                    'order_id' => $order->id,
                    'order_item_id' => $item->id,
                    'amount' => $item->total_price,
                    'commission' => $commission,
                    'net_amount' => $netAmount,
                    'type' => 'sale',
                    'status' => 'pending', // Will become available after return period
                    'description' => 'Earnings from order #' . $order->order_number . ' - ' . ($item->product_name ?? 'Product'),
                    'available_at' => Carbon::now()->addDays(7), // 7 days return period
                ]);
                
                $earningsCount++;
            }
        }
        
        if ($earningsCount > 0) {
            Log::info("Created {$earningsCount} earnings records for order #{$order->order_number}");
        }
        
        return $earningsCount;
    }

    /**
     * Remove seller earnings (if order status changed from delivered)
     */
    private function removeSellerEarnings($order)
    {
        $deleted = SellerEarning::where('order_id', $order->id)->delete();
        
        if ($deleted > 0) {
            Log::info("Removed {$deleted} earnings records for order #{$order->order_number}");
        }
        
        return $deleted;
    }

    /**
     * Bulk update order status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);
        
        $sellerId = Auth::id();
        
        DB::beginTransaction();
        
        try {
            // Get only orders that have items from this seller
            $orders = Order::whereIn('id', $request->order_ids)
                ->whereHas('items', function($q) use ($sellerId) {
                    $q->where('seller_id', $sellerId);
                })
                ->with(['items' => function($q) use ($sellerId) {
                    $q->where('seller_id', $sellerId);
                }])
                ->get();
            
            $updatedCount = 0;
            $earningsCount = 0;
            
            foreach ($orders as $order) {
                $oldStatus = $order->status;
                $order->status = $request->status;
                $order->save();
                $updatedCount++;
                
                // Handle earnings for delivered status
                if ($request->status == 'delivered' && $oldStatus != 'delivered') {
                    $earningsCount += $this->createSellerEarnings($order, $sellerId);
                }
                
                // Remove earnings if status changed from delivered
                if ($oldStatus == 'delivered' && $request->status != 'delivered') {
                    $this->removeSellerEarnings($order);
                }
            }
            
            DB::commit();
            
            $message = "{$updatedCount} orders updated successfully.";
            if ($request->status == 'delivered') {
                $message .= " Earnings added for {$earningsCount} items.";
            }
            
            return redirect()->back()->with('success', $message)
                ->with('earnings_added', "Earnings added for {$earningsCount} items.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk update failed: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Error updating orders: ' . $e->getMessage());
        }
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $sellerId = Auth::id();
        $commissionRate = $this->getCommissionRate();
        
        $query = OrderItem::where('seller_id', $sellerId)
            ->with(['order', 'product'])
            ->orderBy('created_at', 'desc');
        
        // Apply filters
        if ($request->has('status') && $request->status != 'all') {
            $query->whereHas('order', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $query->whereHas('order', function($q) use ($request) {
                $q->whereDate('created_at', '>=', Carbon::parse($request->date_from));
            });
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereHas('order', function($q) use ($request) {
                $q->whereDate('created_at', '<=', Carbon::parse($request->date_to));
            });
        }
        
        $orderItems = $query->get();
        
        // Generate CSV
        $filename = 'orders-export-' . Carbon::now()->format('Y-m-d') . '.csv';
        $handle = fopen('php://temp', 'w+');
        
        // Add headers
        fputcsv($handle, [
            'Order Number',
            'Order Date',
            'Customer Name',
            'Customer Email',
            'Customer Phone',
            'Product Name',
            'Quantity',
            'Unit Price',
            'Total Price',
            'Commission',
            'Net Earnings',
            'Order Status',
            'Payment Status',
            'Shipping Address',
            'Tracking Number'
        ]);
        
        // Add data
        foreach ($orderItems as $item) {
            $commission = ($item->total_price * $commissionRate) / 100;
            $netEarnings = $item->total_price - $commission;
            
            fputcsv($handle, [
                $item->order->order_number,
                $item->created_at->format('Y-m-d H:i:s'),
                $item->order->shipping_name,
                $item->order->shipping_email,
                $item->order->shipping_phone,
                $item->product_name ?? $item->product->name,
                $item->quantity,
                $item->unit_price,
                $item->total_price,
                $commission,
                $netEarnings,
                $item->order->status,
                $item->order->payment_status,
                $item->order->shipping_address . ', ' . $item->order->shipping_city . ', ' . $item->order->shipping_state . ' - ' . $item->order->shipping_zip,
                json_decode($item->order->tracking_info ?? '{}', true)['number'] ?? ''
            ]);
        }
        
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);
        
        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Get order statistics
     */
    private function getOrderStatistics($sellerId)
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();
        
        // Get earnings statistics
        $totalEarnings = SellerEarning::where('seller_id', $sellerId)->sum('net_amount');
        $monthEarnings = SellerEarning::where('seller_id', $sellerId)
            ->where('created_at', '>=', $thisMonth)
            ->sum('net_amount');
        $pendingEarnings = SellerEarning::where('seller_id', $sellerId)
            ->where('status', 'pending')
            ->sum('net_amount');
        
        return [
            'total_orders' => OrderItem::where('seller_id', $sellerId)
                ->distinct('order_id')
                ->count('order_id'),
            
            'total_revenue' => OrderItem::where('seller_id', $sellerId)
                ->whereHas('order', function($q) {
                    $q->whereIn('status', ['delivered', 'confirmed', 'completed']);
                })
                ->sum('total_price'),
            
            'pending_orders' => OrderItem::where('seller_id', $sellerId)
                ->whereHas('order', function($q) {
                    $q->where('status', 'pending');
                })
                ->distinct('order_id')
                ->count('order_id'),
            
            'processing_orders' => OrderItem::where('seller_id', $sellerId)
                ->whereHas('order', function($q) {
                    $q->where('status', 'processing');
                })
                ->distinct('order_id')
                ->count('order_id'),
            
            'shipped_orders' => OrderItem::where('seller_id', $sellerId)
                ->whereHas('order', function($q) {
                    $q->where('status', 'shipped');
                })
                ->distinct('order_id')
                ->count('order_id'),
            
            'delivered_orders' => OrderItem::where('seller_id', $sellerId)
                ->whereHas('order', function($q) {
                    $q->where('status', 'delivered');
                })
                ->distinct('order_id')
                ->count('order_id'),
            
            'cancelled_orders' => OrderItem::where('seller_id', $sellerId)
                ->whereHas('order', function($q) {
                    $q->where('status', 'cancelled');
                })
                ->distinct('order_id')
                ->count('order_id'),
            
            'today_orders' => OrderItem::where('seller_id', $sellerId)
                ->whereHas('order', function($q) use ($today) {
                    $q->whereDate('created_at', $today);
                })
                ->distinct('order_id')
                ->count('order_id'),
            
            'today_revenue' => OrderItem::where('seller_id', $sellerId)
                ->whereHas('order', function($q) use ($today) {
                    $q->whereDate('created_at', $today)
                      ->whereIn('status', ['delivered', 'confirmed', 'completed']);
                })
                ->sum('total_price'),
            
            'month_orders' => OrderItem::where('seller_id', $sellerId)
                ->whereHas('order', function($q) use ($thisMonth) {
                    $q->where('created_at', '>=', $thisMonth);
                })
                ->distinct('order_id')
                ->count('order_id'),
            
            'month_revenue' => OrderItem::where('seller_id', $sellerId)
                ->whereHas('order', function($q) use ($thisMonth) {
                    $q->where('created_at', '>=', $thisMonth)
                      ->whereIn('status', ['delivered', 'confirmed', 'completed']);
                })
                ->sum('total_price'),
            
            'avg_order_value' => OrderItem::where('seller_id', $sellerId)
                ->whereHas('order', function($q) {
                    $q->whereIn('status', ['delivered', 'confirmed', 'completed']);
                })
                ->avg('total_price') ?: 0,
            
            // Add earnings statistics
            'total_earnings' => $totalEarnings,
            'month_earnings' => $monthEarnings,
            'pending_earnings' => $pendingEarnings,
        ];
    }

    /**
     * Get status counts for filter badges
     */
    private function getStatusCounts($sellerId)
    {
        return [
            'all' => OrderItem::where('seller_id', $sellerId)
                ->distinct('order_id')
                ->count('order_id'),
            
            'pending' => OrderItem::where('seller_id', $sellerId)
                ->whereHas('order', function($q) {
                    $q->where('status', 'pending');
                })
                ->distinct('order_id')
                ->count('order_id'),
            
            'processing' => OrderItem::where('seller_id', $sellerId)
                ->whereHas('order', function($q) {
                    $q->where('status', 'processing');
                })
                ->distinct('order_id')
                ->count('order_id'),
            
            'shipped' => OrderItem::where('seller_id', $sellerId)
                ->whereHas('order', function($q) {
                    $q->where('status', 'shipped');
                })
                ->distinct('order_id')
                ->count('order_id'),
            
            'delivered' => OrderItem::where('seller_id', $sellerId)
                ->whereHas('order', function($q) {
                    $q->where('status', 'delivered');
                })
                ->distinct('order_id')
                ->count('order_id'),
            
            'cancelled' => OrderItem::where('seller_id', $sellerId)
                ->whereHas('order', function($q) {
                    $q->where('status', 'cancelled');
                })
                ->distinct('order_id')
                ->count('order_id'),
        ];
    }

    /**
     * Get commission rate from settings
     */
    private function getCommissionRate()
    {
        $rate = Setting::where('key', 'commission_rate')->first();
        return $rate ? floatval($rate->value) : 10;
    }

    /**
     * Get status badge HTML
     */
    private function getStatusBadge($status)
    {
        $badges = [
            'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
            'processing' => '<span class="badge bg-info">Processing</span>',
            'shipped' => '<span class="badge bg-primary">Shipped</span>',
            'delivered' => '<span class="badge bg-success">Delivered</span>',
            'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
            'confirmed' => '<span class="badge bg-success">Confirmed</span>',
            'payment_failed' => '<span class="badge bg-danger">Payment Failed</span>',
        ];
        
        return $badges[$status] ?? '<span class="badge bg-secondary">' . ucfirst($status) . '</span>';
    }
}