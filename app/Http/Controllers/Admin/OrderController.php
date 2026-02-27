<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        try {
            $status = $request->get('status', 'all');
            $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
            $search = $request->get('search', '');

            $query = Order::with(['user', 'items.product'])
                ->withCount('items');

            // Apply status filter
            if ($status != 'all') {
                $query->where('status', $status);
            }

            // Apply date filter
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);

            // Apply search filter
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                      ->orWhere('shipping_name', 'like', "%{$search}%")
                      ->orWhere('shipping_email', 'like', "%{$search}%")
                      ->orWhere('shipping_phone', 'like', "%{$search}%");
                });
            }

            $orders = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

            // Get statistics
            $statistics = $this->getOrderStatistics($startDate, $endDate);

            // Get status counts for filter badges
            $statusCounts = $this->getStatusCounts();

            return view('admin.orders.index', compact(
                'orders',
                'statistics',
                'statusCounts',
                'status',
                'startDate',
                'endDate',
                'search'
            ));

        } catch (\Exception $e) {
            Log::error('Orders index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading orders: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order
     */
    public function show($id)
    {
        try {
            $order = Order::with([
                'user',
                'items' => function($q) {
                    $q->with(['product.images', 'product.seller']);
                }
            ])->findOrFail($id);

            return view('admin.orders.show', compact('order'));

        } catch (\Exception $e) {
            Log::error('Order show error: ' . $e->getMessage());
            return redirect()->route('admin.orders.index')
                ->with('error', 'Order not found.');
        }
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,processing,confirmed,shipped,delivered,cancelled',
                'tracking_number' => 'nullable|string|max:100',
                'tracking_url' => 'nullable|url|max:255',
                'notes' => 'nullable|string|max:500',
            ]);

            $order = Order::findOrFail($id);

            $oldStatus = $order->status;

            // Update order
            $order->status = $request->status;

            if ($request->tracking_number) {
                $trackingData = [
                    'number' => $request->tracking_number,
                    'url' => $request->tracking_url,
                    'updated_at' => now(),
                ];
                $order->tracking_info = json_encode($trackingData);
            }

            $order->save();

            // If status changed to delivered, update earnings status
            if ($request->status == 'delivered' && $oldStatus != 'delivered') {
                $this->updateEarningsStatus($order);
            }

            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', 'Order status updated successfully.');

        } catch (\Exception $e) {
            Log::error('Update order status error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error updating order status: ' . $e->getMessage());
        }
    }

    /**
     * Update earnings status when order is delivered
     */
    private function updateEarningsStatus($order)
    {
        try {
            DB::table('seller_earnings')
                ->where('order_id', $order->id)
                ->update([
                    'status' => 'available',
                    'available_at' => now(),
                ]);
        } catch (\Exception $e) {
            Log::error('Update earnings status error: ' . $e->getMessage());
        }
    }

    /**
     * Get orders for today
     */
    public function today()
    {
        try {
            $today = Carbon::today();

            $orders = Order::with(['user'])
                ->whereDate('created_at', $today)
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return view('admin.orders.index', [
                'orders' => $orders,
                'statistics' => $this->getOrderStatistics($today->format('Y-m-d'), $today->format('Y-m-d')),
                'statusCounts' => $this->getStatusCounts(),
                'status' => 'all',
                'startDate' => $today->format('Y-m-d'),
                'endDate' => $today->format('Y-m-d'),
                'search' => '',
            ]);

        } catch (\Exception $e) {
            Log::error('Today orders error: ' . $e->getMessage());
            return redirect()->route('admin.orders.index')
                ->with('error', 'Error loading today\'s orders.');
        }
    }

    /**
     * Get pending orders
     */
    public function pending()
    {
        try {
            $orders = Order::with(['user'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return view('admin.orders.index', [
                'orders' => $orders,
                'statistics' => $this->getOrderStatistics(),
                'statusCounts' => $this->getStatusCounts(),
                'status' => 'pending',
                'startDate' => Carbon::now()->startOfMonth()->format('Y-m-d'),
                'endDate' => Carbon::now()->endOfMonth()->format('Y-m-d'),
                'search' => '',
            ]);

        } catch (\Exception $e) {
            Log::error('Pending orders error: ' . $e->getMessage());
            return redirect()->route('admin.orders.index')
                ->with('error', 'Error loading pending orders.');
        }
    }

    /**
     * Get completed orders
     */
    public function completed()
    {
        try {
            $orders = Order::with(['user'])
                ->whereIn('status', ['delivered', 'confirmed'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return view('admin.orders.index', [
                'orders' => $orders,
                'statistics' => $this->getOrderStatistics(),
                'statusCounts' => $this->getStatusCounts(),
                'status' => 'completed',
                'startDate' => Carbon::now()->startOfMonth()->format('Y-m-d'),
                'endDate' => Carbon::now()->endOfMonth()->format('Y-m-d'),
                'search' => '',
            ]);

        } catch (\Exception $e) {
            Log::error('Completed orders error: ' . $e->getMessage());
            return redirect()->route('admin.orders.index')
                ->with('error', 'Error loading completed orders.');
        }
    }

    /**
     * Get cancelled orders
     */
    public function cancelled()
    {
        try {
            $orders = Order::with(['user'])
                ->whereIn('status', ['cancelled', 'payment_failed'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return view('admin.orders.index', [
                'orders' => $orders,
                'statistics' => $this->getOrderStatistics(),
                'statusCounts' => $this->getStatusCounts(),
                'status' => 'cancelled',
                'startDate' => Carbon::now()->startOfMonth()->format('Y-m-d'),
                'endDate' => Carbon::now()->endOfMonth()->format('Y-m-d'),
                'search' => '',
            ]);

        } catch (\Exception $e) {
            Log::error('Cancelled orders error: ' . $e->getMessage());
            return redirect()->route('admin.orders.index')
                ->with('error', 'Error loading cancelled orders.');
        }
    }

    /**
     * Process refund for an order
     */
    public function refund(Request $request, $id)
    {
        try {
            $request->validate([
                'reason' => 'required|string|min:10|max:500',
                'amount' => 'nullable|numeric|min:0',
            ]);

            $order = Order::findOrFail($id);

            if (!in_array($order->status, ['delivered', 'confirmed'])) {
                return redirect()->back()
                    ->with('error', 'Only delivered or confirmed orders can be refunded.');
            }

            $refundAmount = $request->amount ?? $order->total_amount;

            // Process refund logic here
            // This would integrate with your payment gateway

            $order->update([
                'status' => 'refunded',
                'refunded_at' => now(),
                'refund_amount' => $refundAmount,
                'refund_reason' => $request->reason,
            ]);

            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', 'Refund processed successfully.');

        } catch (\Exception $e) {
            Log::error('Refund error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error processing refund: ' . $e->getMessage());
        }
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        try {
            $status = $request->get('status', 'all');
            $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

            $query = Order::with(['user', 'items']);

            if ($status != 'all') {
                $query->where('status', $status);
            }

            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);

            $orders = $query->orderBy('created_at', 'desc')->get();

            $filename = 'orders-' . $startDate . '-to-' . $endDate . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($orders) {
                $handle = fopen('php://output', 'w');
                
                // Add UTF-8 BOM for Excel compatibility
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

                // Headers
                fputcsv($handle, [
                    'Order ID',
                    'Order Number',
                    'Customer Name',
                    'Customer Email',
                    'Customer Phone',
                    'Order Date',
                    'Status',
                    'Payment Status',
                    'Payment Method',
                    'Subtotal',
                    'Shipping',
                    'Tax',
                    'Total',
                    'Items Count',
                    'Shipping Address',
                ]);

                // Data
                foreach ($orders as $order) {
                    $itemsCount = $order->items->sum('quantity');

                    fputcsv($handle, [
                        $order->id,
                        $order->order_number,
                        $order->shipping_name,
                        $order->shipping_email,
                        $order->shipping_phone,
                        $order->created_at->format('Y-m-d H:i:s'),
                        $order->status,
                        $order->payment_status,
                        $order->payment_method,
                        $order->subtotal,
                        $order->shipping_charge,
                        $order->tax_amount,
                        $order->total_amount,
                        $itemsCount,
                        $order->shipping_address . ', ' . $order->shipping_city . ', ' . $order->shipping_state . ' - ' . $order->shipping_zip,
                    ]);
                }

                fclose($handle);
            };

            return Response::stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Export orders error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting orders: ' . $e->getMessage());
        }
    }

    /**
     * Get order statistics
     */
    private function getOrderStatistics($startDate = null, $endDate = null)
    {
        try {
            $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
            $end = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfMonth();

            $stats = [
                'total_orders' => Order::whereBetween('created_at', [$start, $end])->count() ?: 0,
                'total_revenue' => Order::whereBetween('created_at', [$start, $end])
                    ->whereIn('status', ['delivered', 'confirmed', 'completed'])
                    ->sum('total_amount') ?: 0,
                'pending_orders' => Order::whereBetween('created_at', [$start, $end])
                    ->where('status', 'pending')
                    ->count() ?: 0,
                'processing_orders' => Order::whereBetween('created_at', [$start, $end])
                    ->where('status', 'processing')
                    ->count() ?: 0,
                'shipped_orders' => Order::whereBetween('created_at', [$start, $end])
                    ->where('status', 'shipped')
                    ->count() ?: 0,
                'delivered_orders' => Order::whereBetween('created_at', [$start, $end])
                    ->where('status', 'delivered')
                    ->count() ?: 0,
                'cancelled_orders' => Order::whereBetween('created_at', [$start, $end])
                    ->whereIn('status', ['cancelled', 'payment_failed'])
                    ->count() ?: 0,
                'avg_order_value' => 0,
            ];

            if ($stats['total_orders'] > 0) {
                $stats['avg_order_value'] = $stats['total_revenue'] / $stats['total_orders'];
            }

            return $stats;

        } catch (\Exception $e) {
            Log::error('Order statistics error: ' . $e->getMessage());
            return [
                'total_orders' => 0,
                'total_revenue' => 0,
                'pending_orders' => 0,
                'processing_orders' => 0,
                'shipped_orders' => 0,
                'delivered_orders' => 0,
                'cancelled_orders' => 0,
                'avg_order_value' => 0,
            ];
        }
    }

    /**
     * Get status counts for filter badges
     */
    private function getStatusCounts()
    {
        try {
            return [
                'all' => Order::count() ?: 0,
                'pending' => Order::where('status', 'pending')->count() ?: 0,
                'processing' => Order::where('status', 'processing')->count() ?: 0,
                'confirmed' => Order::where('status', 'confirmed')->count() ?: 0,
                'shipped' => Order::where('status', 'shipped')->count() ?: 0,
                'delivered' => Order::where('status', 'delivered')->count() ?: 0,
                'cancelled' => Order::whereIn('status', ['cancelled', 'payment_failed'])->count() ?: 0,
            ];
        } catch (\Exception $e) {
            Log::error('Status counts error: ' . $e->getMessage());
            return [
                'all' => 0,
                'pending' => 0,
                'processing' => 0,
                'confirmed' => 0,
                'shipped' => 0,
                'delivered' => 0,
                'cancelled' => 0,
            ];
        }
    }
}