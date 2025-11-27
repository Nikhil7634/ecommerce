<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // Show the cart page
    public function index()
    {
        // Retrieve cart items from session
        $cart = Session::get('cart', []);
        return view('buyer.cart', compact('cart'));
    }

    // Add an item to the cart
    public function add(Request $request)
    {
        $cart = Session::get('cart', []);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        // Add or update item in cart
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'quantity' => $quantity,
            ];
        }

        // Save back to session
        Session::put('cart', $cart);

        return redirect()->back()->with('success', 'Item added to cart!');
    }

    // Remove an item from the cart
    public function remove($id)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    // Update the quantity of an item
    public function update(Request $request, $id)
    {
        $cart = Session::get('cart', []);
        $quantity = $request->input('quantity', 1);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            Session::put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }
}
