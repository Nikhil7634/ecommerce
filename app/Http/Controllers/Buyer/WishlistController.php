<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        // Example: you can later replace this with DB data
        return view('buyer.wishlist');
    }

    public function toggle(Request $request)
    {
        // Example toggle logic (you can replace it with your DB logic)
        // Product ID will come from $request->id
        return response()->json(['success' => true, 'message' => 'Wishlist updated!']);
    }
}
