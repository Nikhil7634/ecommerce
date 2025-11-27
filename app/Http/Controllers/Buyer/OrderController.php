<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('buyer.orders');
    }

    public function show($id)
    {
        // Fetch order details
        return view('buyer.orders', compact('id'));
    }
}


 
 
