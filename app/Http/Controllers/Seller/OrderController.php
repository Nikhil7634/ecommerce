<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $seller = Auth::user();
         return view('seller.orders', compact('seller'));
    }

    public function show($id)
    {
        $seller = Auth::user();
        $order = $seller->sellerOrders()->with('items.product')->findOrFail($id);
        return view('seller.order-show', compact('seller', 'order'));
    }
}