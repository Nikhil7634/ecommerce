<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;

class CheckoutController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)
            ->with('product.images')
            ->get();
            
        if ($cartItems->isEmpty()) {
            return redirect()->route('buyer.cart')->with('error', 'Your cart is empty');
        }
        
        // Calculate totals
        $subtotal = $cartItems->sum(function($item) {
            $price = $item->price ?? ($item->product->sale_price ?? $item->product->base_price);
            return $price * $item->quantity;
        });
        
        $shipping = $subtotal >= 999 ? 0 : 50; // Free shipping above ₹999
        $tax = ($subtotal + $shipping) * 0.18; // 18% GST
        $total = $subtotal + $shipping + $tax;
        
        return view('buyer.checkout', compact(
            'user', 
            'cartItems', 
            'subtotal', 
            'shipping', 
            'tax', 
            'total'
        ));
    }

    public function createOrder(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Validate shipping details
            $validated = $request->validate([
                'shipping_name' => 'required|string|max:255',
                'shipping_email' => 'required|email|max:255',
                'shipping_phone' => 'required|string|max:20',
                'shipping_address' => 'required|string',
                'shipping_city' => 'required|string|max:100',
                'shipping_state' => 'required|string|max:100',
                'shipping_zip' => 'required|string|max:20',
                'subtotal' => 'required|numeric',
                'shipping_charge' => 'required|numeric',
                'tax_amount' => 'required|numeric',
                'total_amount' => 'required|numeric',
                'payment_method' => 'required|string|in:razorpay,cod',
            ]);
            
            // Get cart items
            $cartItems = Cart::where('user_id', Auth::id())
                ->with('product')
                ->get();
                
            if ($cartItems->isEmpty()) {
                return redirect()->route('buyer.cart')->with('error', 'Your cart is empty');
            }
            
            // Check stock availability
            foreach ($cartItems as $item) {
                $product = Product::find($item->product_id);
                if (!$product || $product->stock < $item->quantity) {
                    return redirect()->route('buyer.checkout')
                        ->with('error', "Insufficient stock for {$product->name}");
                }
            }
            
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . time() . '-' . strtoupper(uniqid()),
                'total_amount' => $validated['total_amount'],
                'subtotal' => $validated['subtotal'],
                'shipping_charge' => $validated['shipping_charge'],
                'tax_amount' => $validated['tax_amount'],
                'status' => 'pending',
                'payment_status' => $validated['payment_method'] == 'cod' ? 'pending' : 'pending',
                'payment_method' => $validated['payment_method'],
                'shipping_name' => $validated['shipping_name'],
                'shipping_email' => $validated['shipping_email'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_state' => $validated['shipping_state'],
                'shipping_zip' => $validated['shipping_zip'],
            ]);
            
            // Create order items and update stock
            foreach ($cartItems as $cartItem) {
                $price = $cartItem->price ?? ($cartItem->product->sale_price ?? $cartItem->product->base_price);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'seller_id' => $cartItem->product->seller_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $price,
                    'total_price' => $price * $cartItem->quantity,
                    'variant_color' => $cartItem->variant_color,
                    'variant_size' => $cartItem->variant_size,
                ]);
                
                // Update product stock
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }
            
            // Clear cart
            Cart::where('user_id', Auth::id())->delete();
            
            DB::commit();
            
            // If COD, redirect to success page
            if ($validated['payment_method'] == 'cod') {
                return redirect()->route('buyer.orders.show', $order->id)
                    ->with('success', 'Order placed successfully! Payment to be collected on delivery.');
            }
            
            // If Razorpay, create payment order
            return $this->createRazorpayOrder($order);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order creation failed: ' . $e->getMessage());
            
            return redirect()->route('buyer.checkout')
                ->with('error', 'Order creation failed: ' . $e->getMessage());
        }
    }
    
    private function createRazorpayOrder($order)
    {
        try {
            $api = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );
            
            $razorpayOrder = $api->order->create([
                'receipt' => $order->order_number,
                'amount' => $order->total_amount * 100, // Convert to paise
                'currency' => 'INR',
                'payment_capture' => 1,
                'notes' => [
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                ]
            ]);
            
            // Update order with Razorpay order ID
            $order->update(['razorpay_order_id' => $razorpayOrder->id]);
            
            // Return to payment page
            return view('buyer.payment', [
                'order' => $order,
                'razorpay_key' => config('services.razorpay.key'),
                'razorpay_order_id' => $razorpayOrder->id,
                'amount' => $order->total_amount,
                'user_name' => Auth::user()->name,
                'user_email' => Auth::user()->email,
                'user_phone' => Auth::user()->phone,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Razorpay order creation failed: ' . $e->getMessage());
            
            return redirect()->route('buyer.checkout')
                ->with('error', 'Payment gateway error. Please try again.');
        }
    }
    
    public function verifyPayment(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validate([
                'razorpay_order_id' => 'required',
                'razorpay_payment_id' => 'required',
                'razorpay_signature' => 'required',
                'order_id' => 'required|exists:orders,id',
            ]);
            
            // Verify payment signature
            $api = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );
            
            $attributes = [
                'razorpay_order_id' => $validated['razorpay_order_id'],
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature' => $validated['razorpay_signature']
            ];
            
            $api->utility->verifyPaymentSignature($attributes);
            
            // Update order payment status
            $order = Order::find($validated['order_id']);
            $order->update([
                'payment_status' => 'completed',
                'payment_id' => $validated['razorpay_payment_id'],
                'status' => 'confirmed',
            ]);
            
            DB::commit();
            
            return redirect()->route('buyer.orders.show', $order->id)
                ->with('success', 'Payment successful! Order confirmed.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment verification failed: ' . $e->getMessage());
            
            // Update order as payment failed
            Order::find($request->order_id)->update([
                'payment_status' => 'failed',
                'status' => 'payment_failed',
            ]);
            
            return redirect()->route('buyer.orders.show', $request->order_id)
                ->with('error', 'Payment verification failed. Please try again.');
        }
    }
    
    public function paymentFailed(Request $request)
    {
        $order = Order::find($request->order_id);
        if ($order) {
            $order->update([
                'payment_status' => 'failed',
                'status' => 'payment_failed',
            ]);
        }
        
        return redirect()->route('buyer.checkout')
            ->with('error', 'Payment was cancelled or failed. Please try again.');
    }
}