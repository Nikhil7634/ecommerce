<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get date range from request
        $startDate = $request->start ? Carbon::parse($request->start) : Carbon::now()->startOfMonth();
        $endDate = $request->end ? Carbon::parse($request->end) : Carbon::now()->endOfMonth();
        $period = $request->period ?? 'year';
        
        // Current year for chart
        $currentYear = Carbon::now()->year;
        
        // Total Revenue
        $totalRevenue = Order::whereIn('status', ['delivered', 'confirmed'])
            ->sum('total_amount');
        
        // Previous period revenue for growth calculation
        $previousRevenue = Order::whereIn('status', ['delivered', 'confirmed'])
            ->where('created_at', '<', $startDate)
            ->sum('total_amount');
        $revenueGrowth = $previousRevenue > 0 ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;
        
        // Total Orders
        $totalOrders = Order::count();
        $previousOrders = Order::where('created_at', '<', $startDate)->count();
        $orderGrowth = $previousOrders > 0 ? (($totalOrders - $previousOrders) / $previousOrders) * 100 : 0;
        
        // Total Customers (Buyers)
        $totalCustomers = User::where('role', 'buyer')->count();
        $previousCustomers = User::where('role', 'buyer')->where('created_at', '<', $startDate)->count();
        $customerGrowth = $previousCustomers > 0 ? (($totalCustomers - $previousCustomers) / $previousCustomers) * 100 : 0;
        
        // Total Sellers (Active)
        $totalSellers = User::where('role', 'seller')->where('status', 'active')->count();
        $previousSellers = User::where('role', 'seller')->where('status', 'active')->where('created_at', '<', $startDate)->count();
        $sellerGrowth = $previousSellers > 0 ? (($totalSellers - $previousSellers) / $previousSellers) * 100 : 0;
        
        // Subscription Plans
        $subscriptionPlans = SubscriptionPlan::where('is_active', true)->get();
        
        // Recent Sellers (last 5)
        $recentSellers = User::where('role', 'seller')
            ->withCount('orders')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Recent Buyers (last 5)
        $recentBuyers = User::where('role', 'buyer')
            ->withCount('orders')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Recent Orders (last 10)
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Order Status Summary
        $orderStats = [
            'pending' => Order::where('status', 'pending')->count(),
            'pending_amount' => Order::where('status', 'pending')->sum('total_amount'),
            'processing' => Order::whereIn('status', ['confirmed', 'processing'])->count(),
            'processing_amount' => Order::whereIn('status', ['confirmed', 'processing'])->sum('total_amount'),
            'shipped' => Order::where('status', 'shipped')->count(),
            'shipped_amount' => Order::where('status', 'shipped')->sum('total_amount'),
            'delivered' => Order::where('status', 'delivered')->count(),
            'delivered_amount' => Order::where('status', 'delivered')->sum('total_amount'),
            'cancelled' => Order::whereIn('status', ['cancelled', 'payment_failed'])->count(),
            'cancelled_amount' => Order::whereIn('status', ['cancelled', 'payment_failed'])->sum('total_amount'),
        ];
        
        // Pending Sellers Count
        $pendingSellersCount = User::where('role', 'seller')->where('status', 'pending')->count();
        
        // Monthly Sales Data for Chart
        $monthlySales = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlySales[] = Order::whereIn('status', ['delivered', 'confirmed'])
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', $currentYear)
                ->sum('total_amount');
        }
        
        return view('admin.dashboard', compact(
            'totalRevenue',
            'revenueGrowth',
            'totalOrders',
            'orderGrowth',
            'totalCustomers',
            'customerGrowth',
            'totalSellers',
            'sellerGrowth',
            'subscriptionPlans',
            'recentSellers',
            'recentBuyers',
            'recentOrders',
            'orderStats',
            'pendingSellersCount',
            'monthlySales',
            'currentYear'
        ));
    }
}