<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;

class ReportController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::where('status', 'delivered')->sum('total');
        $totalOrders = Order::count();
        $totalSellers = User::where('role', 'seller')->where('status', 'active')->count();
        $totalBuyers = User::where('role', 'buyer')->count();

        $monthlySales = Order::selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->get();

        return view('admin.reports.index', compact('totalRevenue', 'totalOrders', 'totalSellers', 'totalBuyers', 'monthlySales'));
    }
}