<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function index()
    {
        $seller = Auth::user();
        return view('seller.store', compact('seller'));
    }

    public function edit()
    {
        $seller = Auth::user();
        return view('seller.store-edit', compact('seller'));
    }

    public function update()
    {
        request()->validate([
            'business_name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|string|max:10',
        ]);

        Auth::user()->update(request()->only('business_name', 'address', 'city', 'state', 'zip'));

        return redirect()->route('seller.store')->with('success', 'Store details updated!');
    }
}