<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;

class AnalyticsController extends Controller
{
    public function index() { return view('seller.analytics.index'); }
    public function sales() { return view('seller.analytics.sales'); }
    public function products() { return view('seller.analytics.products'); }
    public function customers() { return view('seller.analytics.customers'); }
}
