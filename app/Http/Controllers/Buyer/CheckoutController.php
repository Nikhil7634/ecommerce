<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function create()
    {
        $cart = Cart::where('user_id', auth()->id())->with('product')->get();
        if ($cart->isEmpty()) return redirect()->route('buyer.cart.index');

        $total = $cart->sum(fn($i) => $i->quantity * ($i->product->discount_price ?? $i->product->price));

        return view('buyer.checkout.create', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $cart = Cart::where('user_id', auth()->id())->with('product')->get();
        if ($cart->isEmpty()) return redirect()->route('buyer.cart.index');

        $request->validate([
            'shipping_address' => 'required|array',
            'payment_method' => 'required|in:razorpay,cod'
        ]);

        $total = $cart->sum(fn($i) => $i->quantity * ($i->product->discount_price ?? $i->product->price));

        DB::transaction(function () use ($cart, $request, $total) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'total_amount' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'shipping_address' => $request->shipping_address,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'seller_id' => $item->product->seller_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->discount_price ?? $item->product->price,
                    'total_price' => $item->quantity * ($item->product->discount_price ?? $item->product->price),
                ]);
                $item->product->decrement('stock', $item->quantity);
            }

            Cart::where('user_id', auth()->id())->delete();
        });

        return redirect()->route('buyer.checkout.pay', $order->id);
    }

    public function pay($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        if ($order->user_id !== auth()->id()) abort(403);

        return view('buyer.checkout.pay', compact('order'));
    }
}