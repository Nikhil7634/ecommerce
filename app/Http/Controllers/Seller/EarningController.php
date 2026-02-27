<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItem;
use App\Models\SellerEarning;
use App\Models\SellerWithdrawal;
use App\Models\Setting;
use App\Models\Product;
use Carbon\Carbon;

class EarningController extends Controller
{
    protected $commissionRate;
    
    public function __construct()
    {
        $this->commissionRate = $this->getCommissionRate();
    }

    /**
     * Display seller earnings dashboard
     */
    public function index(Request $request)
    {
        $sellerId = Auth::id();
        
        // Get date range from request or default to current year
        $year = $request->get('year', Carbon::now()->year);
        
        // Get all earnings statistics
        $statistics = $this->getEarningsStatistics($sellerId);
        
        // Get monthly earnings for chart
        $monthlyEarnings = $this->getMonthlyEarnings($sellerId, $year);
        
        // Get recent earnings (last 10 transactions)
        $recentEarnings = SellerEarning::where('seller_id', $sellerId)
            ->with(['order', 'orderItem.product'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Calculate balances
        $availableBalance = SellerEarning::where('seller_id', $sellerId)
            ->where('status', 'available')
            ->sum('net_amount');
        
        $pendingEarnings = SellerEarning::where('seller_id', $sellerId)
            ->where('status', 'pending')
            ->sum('net_amount');
        
        $lifetimeEarnings = SellerEarning::where('seller_id', $sellerId)
            ->sum('net_amount');
        
        $totalWithdrawn = SellerWithdrawal::where('seller_id', $sellerId)
            ->where('status', 'completed')
            ->sum('amount');
        
        // Get top selling products
        $topProducts = OrderItem::where('seller_id', $sellerId)
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total_price) as total_revenue'))
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_revenue', 'desc')
            ->take(5)
            ->get();
        
        return view('seller.earnings.index', compact(
            'statistics',
            'monthlyEarnings',
            'year',
            'recentEarnings',
            'availableBalance',
            'pendingEarnings',
            'lifetimeEarnings',
            'totalWithdrawn',
            'topProducts'
        ));
    }

    /**
     * Show detailed earnings report
     */
    public function report(Request $request)
    {
        $sellerId = Auth::id();
        
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }
        
        $earnings = SellerEarning::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['order', 'orderItem.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $summary = [
            'total_sales' => $earnings->sum('amount'),
            'total_commission' => $earnings->sum('commission'),
            'total_net' => $earnings->sum('net_amount'),
            'order_count' => $earnings->pluck('order_id')->unique()->count(),
            'item_count' => $earnings->count(),
        ];
        
        $dailyBreakdown = SellerEarning::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total_sales'),
                DB::raw('SUM(commission) as total_commission'),
                DB::raw('SUM(net_amount) as total_net'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
        
        return view('seller.earnings.report', compact(
            'earnings',
            'summary',
            'dailyBreakdown',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export earnings report to CSV
     */
    public function export(Request $request)
    {
        $sellerId = Auth::id();
        
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }
        
        $earnings = SellerEarning::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['order', 'orderItem.product'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $filename = 'earnings-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.csv';
        $handle = fopen('php://temp', 'w+');
        
        fputcsv($handle, [
            'Date',
            'Order Number',
            'Product',
            'Quantity',
            'Sale Amount (₹)',
            'Commission (₹)',
            'Net Earnings (₹)',
            'Status'
        ]);
        
        foreach ($earnings as $earning) {
            fputcsv($handle, [
                $earning->created_at->format('Y-m-d H:i:s'),
                $earning->order ? $earning->order->order_number : 'N/A',
                $earning->orderItem && $earning->orderItem->product ? $earning->orderItem->product->name : 'N/A',
                $earning->orderItem ? $earning->orderItem->quantity : 1,
                number_format($earning->amount, 2),
                number_format($earning->commission, 2),
                number_format($earning->net_amount, 2),
                ucfirst($earning->status),
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
     * Get earnings statistics
     */
    private function getEarningsStatistics($sellerId)
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();
        
        return [
            // Today
            'today_sales' => SellerEarning::where('seller_id', $sellerId)
                ->whereDate('created_at', $today)
                ->sum('amount') ?: 0,
            'today_net' => SellerEarning::where('seller_id', $sellerId)
                ->whereDate('created_at', $today)
                ->sum('net_amount') ?: 0,
            'today_orders' => SellerEarning::where('seller_id', $sellerId)
                ->whereDate('created_at', $today)
                ->distinct('order_id')
                ->count('order_id') ?: 0,
            
            // Yesterday
            'yesterday_sales' => SellerEarning::where('seller_id', $sellerId)
                ->whereDate('created_at', $yesterday)
                ->sum('amount') ?: 0,
            
            // This Week
            'week_sales' => SellerEarning::where('seller_id', $sellerId)
                ->where('created_at', '>=', $thisWeek)
                ->sum('amount') ?: 0,
            'week_net' => SellerEarning::where('seller_id', $sellerId)
                ->where('created_at', '>=', $thisWeek)
                ->sum('net_amount') ?: 0,
            
            // This Month
            'month_sales' => SellerEarning::where('seller_id', $sellerId)
                ->where('created_at', '>=', $thisMonth)
                ->sum('amount') ?: 0,
            'month_net' => SellerEarning::where('seller_id', $sellerId)
                ->where('created_at', '>=', $thisMonth)
                ->sum('net_amount') ?: 0,
            'month_orders' => SellerEarning::where('seller_id', $sellerId)
                ->where('created_at', '>=', $thisMonth)
                ->distinct('order_id')
                ->count('order_id') ?: 0,
            
            // Last Month
            'last_month_sales' => SellerEarning::where('seller_id', $sellerId)
                ->whereBetween('created_at', [$lastMonth, $thisMonth->subDay()])
                ->sum('amount') ?: 0,
            
            // This Year
            'year_sales' => SellerEarning::where('seller_id', $sellerId)
                ->where('created_at', '>=', $thisYear)
                ->sum('amount') ?: 0,
            'year_net' => SellerEarning::where('seller_id', $sellerId)
                ->where('created_at', '>=', $thisYear)
                ->sum('net_amount') ?: 0,
            
            // Totals
            'total_commission' => SellerEarning::where('seller_id', $sellerId)
                ->sum('commission') ?: 0,
        ];
    }

    /**
     * Get monthly earnings for chart
     */
    private function getMonthlyEarnings($sellerId, $year)
    {
        $monthlyData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();
            
            $sales = SellerEarning::where('seller_id', $sellerId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount');
            
            $net = SellerEarning::where('seller_id', $sellerId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('net_amount');
            
            $monthlyData[] = [
                'month' => $startDate->format('M'),
                'sales' => $sales ?: 0,
                'net' => $net ?: 0,
            ];
        }
        
        return $monthlyData;
    }

    /**
     * Get commission rate from settings
     */
    private function getCommissionRate()
    {
        $commissionRate = Setting::where('key', 'commission_rate')->first();
        return $commissionRate ? floatval($commissionRate->value) : 10;
    }
}