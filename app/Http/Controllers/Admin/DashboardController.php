<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalBuyers' => User::where('role', 'buyer')->count(),
            'totalSellers' => User::where('role', 'seller')->count(),
            'pendingSellers' => User::where('role', 'seller')->where('status', 'inactive')->count(),
            'totalProducts' => Product::count(),
            'totalOrders' => Order::count(),
         ];

        return view('admin.dashboard', compact('data'));
    }
}