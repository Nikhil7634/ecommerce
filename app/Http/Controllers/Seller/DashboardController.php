<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $seller = Auth::user();
        $sellerId = $seller->id;
         
        
        // Total Orders (orders containing seller's products)
        $totalOrders = OrderItem::where('seller_id', $sellerId)
            ->distinct('order_id')
            ->count('order_id');
        
        // Total Sales (sum of all order items total_price)
        $totalSales = OrderItem::where('seller_id', $sellerId)
            ->whereHas('order', function($q) {
                $q->whereIn('status', ['confirmed', 'delivered', 'completed']);
            })
            ->sum('total_price');
        
        // Total Products
        $totalProducts = Product::where('seller_id', $sellerId)->count();
        
        // Total Visits
        $totalVisits = Product::where('seller_id', $sellerId)->sum('views') ?? 0;
        
        // Bounce Rate (placeholder)
        $bounceRate = 24.6;
        
        // ============ SALES TARGET ============
        
        // Current month sales
        $currentMonthSales = OrderItem::where('seller_id', $sellerId)
            ->whereHas('order', function($q) {
                $q->whereIn('status', ['confirmed', 'delivered', 'completed'])
                  ->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
            })
            ->sum('total_price');
        
        // Monthly target
        $monthlyTarget = 300000;
        $salesPercentage = $monthlyTarget > 0 ? round(($currentMonthSales / $monthlyTarget) * 100) : 0;
        
        // ============ ORDER STATUS ============
        
        $totalSalesAmount = OrderItem::where('seller_id', $sellerId)
            ->whereHas('order', function($q) {
                $q->whereIn('status', ['confirmed', 'delivered', 'completed']);
            })
            ->sum('total_price');
        
        $totalOrderValue = OrderItem::where('seller_id', $sellerId)
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->sum('orders.total_amount');
        
        $salesPercentageTotal = $totalOrderValue > 0 ? round(($totalSalesAmount / $totalOrderValue) * 100) : 0;
        
         
        
        // Last 7 days sales data
        $salesData = [];
        $viewsData = [];
        $days = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('D');
            
            // Sales for this day
            $daySales = OrderItem::where('seller_id', $sellerId)
                ->whereHas('order', function($q) use ($date) {
                    $q->whereDate('created_at', $date->toDateString())
                      ->whereIn('status', ['confirmed', 'delivered', 'completed']);
                })
                ->sum('total_price');
            $salesData[] = $daySales ?: 0;
            
            // Views for this day (placeholder)
            $viewsData[] = rand(50, 200);
        }
        
        // ============ POPULAR PRODUCTS ============
        
        $popularProducts = Product::where('seller_id', $sellerId)
            ->with(['images' => function($q) {
                $q->where('is_primary', true)->orWhereIn('id', function($sub) {
                    $sub->selectRaw('MIN(id)')->from('product_images')->groupBy('product_id');
                });
            }])
            ->get()
            ->map(function($product) use ($sellerId) {
                // Calculate total sales manually
                $totalSales = OrderItem::where('product_id', $product->id)
                    ->where('seller_id', $sellerId)
                    ->sum('quantity');
                
                $product->total_sales = $totalSales ?: 0;
                return $product;
            })
            ->sortByDesc('total_sales')
            ->take(5)
            ->values();
        
        // ============ TOP VENDORS - FIXED ============
        // Using the existing products() relationship
        
        $topVendors = User::where('role', 'seller')
            ->where('id', '!=', $sellerId)
            ->with(['products' => function($q) {
                $q->select('id', 'seller_id', 'name');
            }])
            ->get()
            ->map(function($vendor) {
                // Get all product IDs for this vendor
                $productIds = $vendor->products->pluck('id')->toArray();
                
                // Calculate total sales quantity for this vendor's products
                $totalSales = 0;
                if (!empty($productIds)) {
                    $totalSales = OrderItem::whereIn('product_id', $productIds)
                        ->whereHas('order', function($q) {
                            $q->whereIn('status', ['confirmed', 'delivered', 'completed']);
                        })
                        ->sum('quantity') ?: 0;
                }
                
                $vendor->total_sales = $totalSales;
                return $vendor;
            })
            ->sortByDesc('total_sales')
            ->take(5)
            ->values();
        
        // ============ SOCIAL REVENUE ============
        
        $socialRevenue = [
            'facebook' => ['amount' => 45689, 'change' => 28.5, 'trend' => 'up'],
            'twitter' => ['amount' => 34248, 'change' => 14.5, 'trend' => 'down'],
            'tiktok' => ['amount' => 45689, 'change' => 28.5, 'trend' => 'up'],
            'instagram' => ['amount' => 67249, 'change' => 43.5, 'trend' => 'down'],
            'snapchat' => ['amount' => 89178, 'change' => 24.7, 'trend' => 'up'],
        ];
        
        // ============ RECENT TRANSACTIONS ============
        
        $recentTransactions = OrderItem::where('seller_id', $sellerId)
            ->with(['order', 'product.images' => function($q) {
                $q->where('is_primary', true);
            }])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
        
        // ============ MONTHLY STATS ============
        
        $messagesCount = 986;
        $messagesChange = 34.7;
        $totalProfit = $totalSales * 0.15;
        $profitChange = 12.5;
        $monthlyBudget = 84256;
        
        // ============ ORDER STATUS COUNTS ============
        
        $pendingOrders = OrderItem::where('seller_id', $sellerId)
            ->whereHas('order', function($q) {
                $q->where('status', 'pending');
            })
            ->count();
            
        $processingOrders = OrderItem::where('seller_id', $sellerId)
            ->whereHas('order', function($q) {
                $q->where('status', 'processing');
            })
            ->count();
            
        $deliveredOrders = OrderItem::where('seller_id', $sellerId)
            ->whereHas('order', function($q) {
                $q->whereIn('status', ['delivered', 'confirmed', 'completed']);
            })
            ->count();
        
        return view('seller.dashboard', compact(
            'seller',
            'totalOrders',
            'totalSales',
            'totalProducts',
            'totalVisits',
            'bounceRate',
            'currentMonthSales',
            'monthlyTarget',
            'salesPercentage',
            'totalSalesAmount',
            'salesPercentageTotal',
            'salesData',
            'viewsData',
            'days',
            'socialRevenue',
            'popularProducts',
            'topVendors',
            'recentTransactions',
            'messagesCount',
            'messagesChange',
            'totalProfit',
            'profitChange',
            'monthlyBudget',
            'pendingOrders',
            'processingOrders',
            'deliveredOrders'
        ));
    }
}