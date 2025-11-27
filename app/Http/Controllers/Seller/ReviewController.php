<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $seller = Auth::user();
        $reviews = $seller->reviews()->latest()->get();
        return view('seller.reviews', compact('seller', 'reviews'));
    }
}