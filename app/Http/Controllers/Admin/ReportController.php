<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\SellerEarning;
use App\Models\SellerWithdrawal;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Display reports dashboard
     */
    public function index(Request $request)
    {
        try {
            $type = $request->get('type', 'sales');
            $period = $request->get('period', 'month');
            $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

            $data = $this->getReportData($type, $period, $startDate, $endDate);

            return view('admin.reports.index', array_merge([
                'type' => $type,
                'period' => $period,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ], $data));
            
        } catch (\Exception $e) {
            Log::error('Report generation error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error generating report: ' . $e->getMessage());
        }
    }

    /**
     * Get report data based on type
     */
    private function getReportData($type, $period, $startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        switch ($type) {
            case 'sales':
                return $this->getSalesReport($start, $end, $period);
            case 'products':
                return $this->getProductsReport($start, $end);
            case 'users':
                return $this->getUsersReport($start, $end);
            case 'earnings':
                return $this->getEarningsReport($start, $end);
            default:
                return $this->getSalesReport($start, $end, $period);
        }
    }

    /**
     * Sales Report
     */
    private function getSalesReport($start, $end, $period)
    {
        try {
            // Summary statistics with fallbacks
            $summary = [
                'total_orders' => Order::whereBetween('created_at', [$start, $end])->count() ?: 0,
                'total_revenue' => Order::whereBetween('created_at', [$start, $end])
                    ->whereIn('status', ['delivered', 'confirmed', 'completed'])
                    ->sum('total_amount') ?: 0,
                'avg_order_value' => $this->calculateAverageOrderValue($start, $end),
                'total_items_sold' => DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->whereBetween('orders.created_at', [$start, $end])
                    ->sum('order_items.quantity') ?: 0,
            ];

            // Orders by status
            $ordersByStatus = Order::whereBetween('created_at', [$start, $end])
                ->select('status', DB::raw('count(*) as count'), DB::raw('COALESCE(sum(total_amount), 0) as amount'))
                ->groupBy('status')
                ->get();

            // Daily/Monthly trends
            $trends = $this->getSalesTrends($start, $end, $period);

            // Top products
            $topProducts = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->whereBetween('orders.created_at', [$start, $end])
                ->select(
                    'products.id',
                    'products.name',
                    DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_quantity'),
                    DB::raw('COALESCE(SUM(order_items.total_price), 0) as total_revenue')
                )
                ->groupBy('products.id', 'products.name')
                ->orderBy('total_revenue', 'desc')
                ->limit(10)
                ->get();

            // Payment methods
            $paymentMethods = Order::whereBetween('created_at', [$start, $end])
                ->select('payment_method', DB::raw('count(*) as count'), DB::raw('COALESCE(sum(total_amount), 0) as amount'))
                ->groupBy('payment_method')
                ->get();

            return [
                'summary' => $summary,
                'ordersByStatus' => $ordersByStatus,
                'trends' => $trends,
                'topProducts' => $topProducts,
                'paymentMethods' => $paymentMethods,
            ];
            
        } catch (\Exception $e) {
            Log::error('Sales report error: ' . $e->getMessage());
            return $this->getEmptySalesReport();
        }
    }

    /**
     * Products Report
     */
    private function getProductsReport($start, $end)
    {
        try {
            // Product statistics with fallbacks
            $productStats = [
                'total_products' => Product::count() ?: 0,
                'active_products' => Product::where('status', 'published')->count() ?: 0,
                'out_of_stock' => Product::where('stock', '<=', 0)->count() ?: 0,
                'low_stock' => Product::where('stock', '>', 0)->where('stock', '<', 10)->count() ?: 0,
            ];

            // Top selling products
            $topSelling = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('users', 'products.seller_id', '=', 'users.id')
                ->whereBetween('orders.created_at', [$start, $end])
                ->select(
                    'products.id',
                    'products.name',
                    'products.base_price',
                    'products.stock',
                    'users.name as seller_name',
                    DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'),
                    DB::raw('COALESCE(SUM(order_items.total_price), 0) as total_revenue')
                )
                ->groupBy('products.id', 'products.name', 'products.base_price', 'products.stock', 'users.name')
                ->orderBy('total_sold', 'desc')
                ->limit(20)
                ->get();

            // Products by category
            $productsByCategory = DB::table('category_product')
                ->join('categories', 'category_product.category_id', '=', 'categories.id')
                ->join('products', 'category_product.product_id', '=', 'products.id')
                ->select('categories.name', DB::raw('COUNT(DISTINCT products.id) as product_count'))
                ->where('products.status', 'published')
                ->groupBy('categories.name')
                ->orderBy('product_count', 'desc')
                ->get();

            // Seller performance
            $sellerPerformance = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('users', 'order_items.seller_id', '=', 'users.id')
                ->whereBetween('orders.created_at', [$start, $end])
                ->select(
                    'users.id',
                    'users.name',
                    'users.business_name',
                    DB::raw('COUNT(DISTINCT orders.id) as order_count'),
                    DB::raw('COALESCE(SUM(order_items.quantity), 0) as items_sold'),
                    DB::raw('COALESCE(SUM(order_items.total_price), 0) as total_sales')
                )
                ->groupBy('users.id', 'users.name', 'users.business_name')
                ->orderBy('total_sales', 'desc')
                ->limit(15)
                ->get();

            return [
                'productStats' => $productStats,
                'topSelling' => $topSelling,
                'productsByCategory' => $productsByCategory,
                'sellerPerformance' => $sellerPerformance,
            ];
            
        } catch (\Exception $e) {
            Log::error('Products report error: ' . $e->getMessage());
            return $this->getEmptyProductsReport();
        }
    }

    /**
     * Users Report
     */
    private function getUsersReport($start, $end)
    {
        try {
            // User statistics with fallbacks
            $userStats = [
                'total_users' => User::count() ?: 0,
                'total_buyers' => User::where('role', 'buyer')->count() ?: 0,
                'total_sellers' => User::where('role', 'seller')->count() ?: 0,
                'new_users' => User::whereBetween('created_at', [$start, $end])->count() ?: 0,
                'new_buyers' => User::where('role', 'buyer')->whereBetween('created_at', [$start, $end])->count() ?: 0,
                'new_sellers' => User::where('role', 'seller')->whereBetween('created_at', [$start, $end])->count() ?: 0,
                'active_sellers' => User::where('role', 'seller')->where('status', 'active')->count() ?: 0,
                'pending_sellers' => User::where('role', 'seller')->where('status', 'pending')->count() ?: 0,
                'suspended_sellers' => User::where('role', 'seller')->where('status', 'suspended')->count() ?: 0,
            ];

            // User registration trends
            $registrationTrends = DB::table('users')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as total'),
                    DB::raw("SUM(CASE WHEN role = 'buyer' THEN 1 ELSE 0 END) as buyers"),
                    DB::raw("SUM(CASE WHEN role = 'seller' THEN 1 ELSE 0 END) as sellers")
                )
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Top buyers by order value
            $topBuyers = DB::table('orders')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->whereBetween('orders.created_at', [$start, $end])
                ->whereIn('orders.status', ['delivered', 'confirmed', 'completed'])
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    DB::raw('COUNT(DISTINCT orders.id) as order_count'),
                    DB::raw('COALESCE(SUM(orders.total_amount), 0) as total_spent')
                )
                ->groupBy('users.id', 'users.name', 'users.email')
                ->orderBy('total_spent', 'desc')
                ->limit(15)
                ->get();

            // Top sellers by revenue
            $topSellers = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('users', 'order_items.seller_id', '=', 'users.id')
                ->whereBetween('orders.created_at', [$start, $end])
                ->whereIn('orders.status', ['delivered', 'confirmed', 'completed'])
                ->select(
                    'users.id',
                    'users.name',
                    'users.business_name',
                    DB::raw('COUNT(DISTINCT orders.id) as order_count'),
                    DB::raw('COALESCE(SUM(order_items.quantity), 0) as items_sold'),
                    DB::raw('COALESCE(SUM(order_items.total_price), 0) as total_revenue')
                )
                ->groupBy('users.id', 'users.name', 'users.business_name')
                ->orderBy('total_revenue', 'desc')
                ->limit(15)
                ->get();

            return [
                'userStats' => $userStats,
                'registrationTrends' => $registrationTrends,
                'topBuyers' => $topBuyers,
                'topSellers' => $topSellers,
            ];
            
        } catch (\Exception $e) {
            Log::error('Users report error: ' . $e->getMessage());
            return $this->getEmptyUsersReport();
        }
    }

    /**
     * Earnings Report
     */
    private function getEarningsReport($start, $end)
    {
        try {
            // Earnings summary with fallbacks
            $earningsSummary = [
                'total_earnings' => SellerEarning::whereBetween('created_at', [$start, $end])->sum('net_amount') ?: 0,
                'total_commission' => SellerEarning::whereBetween('created_at', [$start, $end])->sum('commission') ?: 0,
                'pending_earnings' => SellerEarning::whereBetween('created_at', [$start, $end])
                    ->where('status', 'pending')
                    ->sum('net_amount') ?: 0,
                'available_earnings' => SellerEarning::whereBetween('created_at', [$start, $end])
                    ->where('status', 'available')
                    ->sum('net_amount') ?: 0,
                'withdrawn_earnings' => SellerEarning::whereBetween('created_at', [$start, $end])
                    ->where('status', 'withdrawn')
                    ->sum('net_amount') ?: 0,
            ];

            // Withdrawals summary with fallbacks
            $withdrawalsSummary = [
                'total_withdrawn' => SellerWithdrawal::whereBetween('created_at', [$start, $end])
                    ->where('status', 'completed')
                    ->sum('amount') ?: 0,
                'pending_withdrawals' => SellerWithdrawal::whereBetween('created_at', [$start, $end])
                    ->whereIn('status', ['pending', 'processing'])
                    ->sum('amount') ?: 0,
                'total_fees' => SellerWithdrawal::whereBetween('created_at', [$start, $end])
                    ->where('status', 'completed')
                    ->sum('fee') ?: 0,
                'withdrawal_count' => SellerWithdrawal::whereBetween('created_at', [$start, $end])->count() ?: 0,
            ];

            // Earnings by seller
            $earningsBySeller = DB::table('seller_earnings')
                ->join('users', 'seller_earnings.seller_id', '=', 'users.id')
                ->whereBetween('seller_earnings.created_at', [$start, $end])
                ->select(
                    'users.id',
                    'users.name',
                    'users.business_name',
                    DB::raw('COUNT(*) as transaction_count'),
                    DB::raw('COALESCE(SUM(seller_earnings.amount), 0) as gross_earnings'),
                    DB::raw('COALESCE(SUM(seller_earnings.commission), 0) as total_commission'),
                    DB::raw('COALESCE(SUM(seller_earnings.net_amount), 0) as net_earnings')
                )
                ->groupBy('users.id', 'users.name', 'users.business_name')
                ->orderBy('net_earnings', 'desc')
                ->limit(15)
                ->get();

            // Monthly earnings trend
            $earningsTrend = DB::table('seller_earnings')
                ->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('COALESCE(SUM(amount), 0) as gross'),
                    DB::raw('COALESCE(SUM(commission), 0) as commission'),
                    DB::raw('COALESCE(SUM(net_amount), 0) as net')
                )
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            return [
                'earningsSummary' => $earningsSummary,
                'withdrawalsSummary' => $withdrawalsSummary,
                'earningsBySeller' => $earningsBySeller,
                'earningsTrend' => $earningsTrend,
            ];
            
        } catch (\Exception $e) {
            Log::error('Earnings report error: ' . $e->getMessage());
            return $this->getEmptyEarningsReport();
        }
    }

    /**
     * Get sales trends for charts
     */
    private function getSalesTrends($start, $end, $period)
    {
        try {
            if ($period == 'daily') {
                return DB::table('orders')
                    ->select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('COUNT(*) as order_count'),
                        DB::raw('COALESCE(SUM(total_amount), 0) as revenue')
                    )
                    ->whereBetween('created_at', [$start, $end])
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
            } elseif ($period == 'weekly') {
                return DB::table('orders')
                    ->select(
                        DB::raw('YEAR(created_at) as year'),
                        DB::raw('WEEK(created_at) as week'),
                        DB::raw('MIN(DATE(created_at)) as start_date'),
                        DB::raw('COUNT(*) as order_count'),
                        DB::raw('COALESCE(SUM(total_amount), 0) as revenue')
                    )
                    ->whereBetween('created_at', [$start, $end])
                    ->groupBy('year', 'week')
                    ->orderBy('year')
                    ->orderBy('week')
                    ->get();
            } else {
                return DB::table('orders')
                    ->select(
                        DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                        DB::raw('COUNT(*) as order_count'),
                        DB::raw('COALESCE(SUM(total_amount), 0) as revenue')
                    )
                    ->whereBetween('created_at', [$start, $end])
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();
            }
        } catch (\Exception $e) {
            Log::error('Sales trends error: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Calculate average order value
     */
    private function calculateAverageOrderValue($start, $end)
    {
        try {
            $totalRevenue = Order::whereBetween('created_at', [$start, $end])
                ->whereIn('status', ['delivered', 'confirmed', 'completed'])
                ->sum('total_amount');
            
            $totalOrders = Order::whereBetween('created_at', [$start, $end])
                ->whereIn('status', ['delivered', 'confirmed', 'completed'])
                ->count();
            
            return $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
            
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Export report to CSV
     */
    public function export(Request $request)
    {
        try {
            $type = $request->get('type', 'sales');
            $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();

            $filename = $type . '-report-' . $startDate . '-to-' . $endDate . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($type, $start, $end) {
                $handle = fopen('php://output', 'w');
                
                // Add UTF-8 BOM for Excel compatibility
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

                switch ($type) {
                    case 'sales':
                        $this->exportSalesReport($handle, $start, $end);
                        break;
                    case 'products':
                        $this->exportProductsReport($handle, $start, $end);
                        break;
                    case 'users':
                        $this->exportUsersReport($handle, $start, $end);
                        break;
                    case 'earnings':
                        $this->exportEarningsReport($handle, $start, $end);
                        break;
                }

                fclose($handle);
            };

            return Response::stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting report: ' . $e->getMessage());
        }
    }

    /**
     * Export Sales Report
     */
    private function exportSalesReport($handle, $start, $end)
    {
        // Headers
        fputcsv($handle, ['SALES REPORT']);
        fputcsv($handle, ['From: ' . $start->format('Y-m-d'), 'To: ' . $end->format('Y-m-d')]);
        fputcsv($handle, []);
        
        // Summary
        $totalRevenue = Order::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['delivered', 'confirmed', 'completed'])
            ->sum('total_amount') ?: 0;
        $totalOrders = Order::whereBetween('created_at', [$start, $end])->count() ?: 0;
        
        fputcsv($handle, ['SUMMARY']);
        fputcsv($handle, ['Total Orders', 'Total Revenue', 'Average Order Value']);
        fputcsv($handle, [
            $totalOrders,
            '₹' . number_format($totalRevenue, 2),
            '₹' . number_format($totalOrders > 0 ? $totalRevenue / $totalOrders : 0, 2)
        ]);
        fputcsv($handle, []);
        
        // Orders List
        fputcsv($handle, ['ORDER DETAILS']);
        fputcsv($handle, ['Order ID', 'Date', 'Customer', 'Status', 'Payment Method', 'Total Amount']);
        
        $orders = Order::with('user')
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($orders as $order) {
            fputcsv($handle, [
                $order->order_number,
                $order->created_at->format('Y-m-d H:i'),
                $order->shipping_name,
                $order->status,
                $order->payment_method,
                $order->total_amount,
            ]);
        }
    }

    /**
     * Export Products Report
     */
    private function exportProductsReport($handle, $start, $end)
    {
        fputcsv($handle, ['PRODUCTS REPORT']);
        fputcsv($handle, ['From: ' . $start->format('Y-m-d'), 'To: ' . $end->format('Y-m-d')]);
        fputcsv($handle, []);
        
        // Summary
        $totalProducts = Product::count() ?: 0;
        $activeProducts = Product::where('status', 'published')->count() ?: 0;
        $outOfStock = Product::where('stock', '<=', 0)->count() ?: 0;
        
        fputcsv($handle, ['SUMMARY']);
        fputcsv($handle, ['Total Products', 'Active Products', 'Out of Stock']);
        fputcsv($handle, [$totalProducts, $activeProducts, $outOfStock]);
        fputcsv($handle, []);
        
        // Products List
        fputcsv($handle, ['PRODUCT SALES']);
        fputcsv($handle, ['Product ID', 'Product Name', 'Seller', 'Price', 'Stock', 'Total Sold', 'Revenue']);
        
        $products = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('users', 'products.seller_id', '=', 'users.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->select(
                'products.id',
                'products.name as product_name',
                'users.name as seller_name',
                'products.base_price',
                'products.stock',
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'),
                DB::raw('COALESCE(SUM(order_items.total_price), 0) as revenue')
            )
            ->groupBy('products.id', 'products.name', 'users.name', 'products.base_price', 'products.stock')
            ->orderBy('revenue', 'desc')
            ->get();

        foreach ($products as $product) {
            fputcsv($handle, [
                $product->id,
                $product->product_name,
                $product->seller_name,
                '₹' . number_format($product->base_price, 2),
                $product->stock,
                $product->total_sold,
                '₹' . number_format($product->revenue, 2),
            ]);
        }
    }

    /**
     * Export Users Report
     */
    private function exportUsersReport($handle, $start, $end)
    {
        fputcsv($handle, ['USERS REPORT']);
        fputcsv($handle, ['From: ' . $start->format('Y-m-d'), 'To: ' . $end->format('Y-m-d')]);
        fputcsv($handle, []);
        
        // Summary
        $totalUsers = User::count() ?: 0;
        $totalBuyers = User::where('role', 'buyer')->count() ?: 0;
        $totalSellers = User::where('role', 'seller')->count() ?: 0;
        $newUsers = User::whereBetween('created_at', [$start, $end])->count() ?: 0;
        
        fputcsv($handle, ['SUMMARY']);
        fputcsv($handle, ['Total Users', 'Total Buyers', 'Total Sellers', 'New Users']);
        fputcsv($handle, [$totalUsers, $totalBuyers, $totalSellers, $newUsers]);
        fputcsv($handle, []);
        
        // Users List
        fputcsv($handle, ['USER DETAILS']);
        fputcsv($handle, ['User ID', 'Name', 'Email', 'Role', 'Status', 'Joined Date', 'Orders', 'Total Spent']);
        
        $users = User::withCount('orders')
            ->withSum('orders as total_spent', 'total_amount')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($users as $user) {
            fputcsv($handle, [
                $user->id,
                $user->name,
                $user->email,
                $user->role,
                $user->status,
                $user->created_at->format('Y-m-d'),
                $user->orders_count ?? 0,
                '₹' . number_format($user->total_spent ?? 0, 2),
            ]);
        }
    }

    /**
     * Export Earnings Report
     */
    private function exportEarningsReport($handle, $start, $end)
    {
        fputcsv($handle, ['EARNINGS REPORT']);
        fputcsv($handle, ['From: ' . $start->format('Y-m-d'), 'To: ' . $end->format('Y-m-d')]);
        fputcsv($handle, []);
        
        // Summary
        $totalEarnings = SellerEarning::whereBetween('created_at', [$start, $end])->sum('net_amount') ?: 0;
        $totalCommission = SellerEarning::whereBetween('created_at', [$start, $end])->sum('commission') ?: 0;
        $totalWithdrawn = SellerWithdrawal::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->sum('amount') ?: 0;
        
        fputcsv($handle, ['SUMMARY']);
        fputcsv($handle, ['Total Earnings', 'Total Commission', 'Total Withdrawn']);
        fputcsv($handle, [
            '₹' . number_format($totalEarnings, 2),
            '₹' . number_format($totalCommission, 2),
            '₹' . number_format($totalWithdrawn, 2)
        ]);
        fputcsv($handle, []);
        
        // Earnings List
        fputcsv($handle, ['EARNINGS DETAILS']);
        fputcsv($handle, ['Date', 'Order #', 'Seller', 'Gross Amount', 'Commission', 'Net Amount', 'Status']);
        
        $earnings = SellerEarning::with(['order', 'seller'])
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($earnings as $earning) {
            fputcsv($handle, [
                $earning->created_at->format('Y-m-d'),
                $earning->order ? $earning->order->order_number : 'N/A',
                $earning->seller ? $earning->seller->name : 'N/A',
                '₹' . number_format($earning->amount, 2),
                '₹' . number_format($earning->commission, 2),
                '₹' . number_format($earning->net_amount, 2),
                ucfirst($earning->status),
            ]);
        }
    }

    /**
     * Empty report fallbacks
     */
    private function getEmptySalesReport()
    {
        return [
            'summary' => [
                'total_orders' => 0,
                'total_revenue' => 0,
                'avg_order_value' => 0,
                'total_items_sold' => 0,
            ],
            'ordersByStatus' => collect([]),
            'trends' => collect([]),
            'topProducts' => collect([]),
            'paymentMethods' => collect([]),
        ];
    }

    private function getEmptyProductsReport()
    {
        return [
            'productStats' => [
                'total_products' => 0,
                'active_products' => 0,
                'out_of_stock' => 0,
                'low_stock' => 0,
            ],
            'topSelling' => collect([]),
            'productsByCategory' => collect([]),
            'sellerPerformance' => collect([]),
        ];
    }

    private function getEmptyUsersReport()
    {
        return [
            'userStats' => [
                'total_users' => 0,
                'total_buyers' => 0,
                'total_sellers' => 0,
                'new_users' => 0,
                'new_buyers' => 0,
                'new_sellers' => 0,
                'active_sellers' => 0,
                'pending_sellers' => 0,
                'suspended_sellers' => 0,
            ],
            'registrationTrends' => collect([]),
            'topBuyers' => collect([]),
            'topSellers' => collect([]),
        ];
    }

    private function getEmptyEarningsReport()
    {
        return [
            'earningsSummary' => [
                'total_earnings' => 0,
                'total_commission' => 0,
                'pending_earnings' => 0,
                'available_earnings' => 0,
                'withdrawn_earnings' => 0,
            ],
            'withdrawalsSummary' => [
                'total_withdrawn' => 0,
                'pending_withdrawals' => 0,
                'total_fees' => 0,
                'withdrawal_count' => 0,
            ],
            'earningsBySeller' => collect([]),
            'earningsTrend' => collect([]),
        ];
    }
}