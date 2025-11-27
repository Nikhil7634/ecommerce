<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $seller = Auth::user();
        
        return view('seller.products' , compact('seller'));
    }

    public function create()
    {
        $seller = Auth::user();
        return view('seller.product-create', compact('seller'));
    }

    public function store()
    {
        // Your product store logic here
        return redirect()->route('seller.products.index')->with('success', 'Product added successfully!');
    }

    public function edit($id)
    {
        $seller = Auth::user();
        $product = $seller->products()->findOrFail($id);
        return view('seller.product-edit', compact('seller', 'product'));
    }

    public function update($id)
    {
        $product = Auth::user()->products()->findOrFail($id);
        // Update logic
        return redirect()->route('seller.products.index')->with('success', 'Product updated!');
    }

    public function destroy($id)
    {
        $product = Auth::user()->products()->findOrFail($id);
        $product->delete();
        return back()->with('success', 'Product deleted!');
    }
}