<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Setting;
use App\Models\SellerEarning; // Add this import
use Razorpay\Api\Api;
use Carbon\Carbon;

class CartController extends Controller
{
    // Show the cart page
    public function index()
    {
        $userId = Auth::id();
        $cartItems = Cart::with([
            'product.images',
            'product.seller'
        ])->where('user_id', $userId)->get();
        
        // Calculate totals
        $subtotal = 0;

        foreach ($cartItems as $item) {
            $price = $this->calculateItemPrice($item);
            $subtotal += $price * $item->quantity;
        }
        
        // Shipping and tax
        $shipping = $this->calculateShipping($subtotal);
        $tax = $subtotal * 0.18;
        $total = $subtotal + $shipping + $tax;
        
        return view('buyer.cart', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total'));
    }

    // Show checkout page
    public function checkout()
    {
        $userId = Auth::id();
        $cartItems = Cart::with([
            'product.images',
            'product.seller'
        ])->where('user_id', $userId)->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('buyer.cart')->with('error', 'Your cart is empty.');
        }
        
        // Get user address from users table
        $user = Auth::user();
        
        // Calculate totals and add price to each cart item
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $this->calculateItemPrice($item);
            $item->calculated_price = $price; // Add calculated price to cart item
            $subtotal += $price * $item->quantity;
        }
        
        $shipping = $this->calculateShipping($subtotal);
        $tax = $subtotal * 0.18;
        $total = $subtotal + $shipping + $tax;
        
        $isBuyNow = session('is_buy_now', false);
        
        return view('buyer.checkout', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total', 'user', 'isBuyNow'));
    }

    // Show checkout success page
    public function checkoutSuccess(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Load order with items and products
        $order->load(['items.product']);
        
        return view('buyer.checkout-success', compact('order'));
    }

    // Show checkout cancel page
    public function checkoutCancel()
    {
        return view('buyer.checkout-cancel');
    }

    // Show checkout failed page
    public function checkoutFailed()
    {
        return view('buyer.checkout-failed');
    }

    // Calculate item price
    private function calculateItemPrice($cartItem)
    {
        // Use the price stored in cart if available
        if ($cartItem->price) {
            return $cartItem->price;
        }
        
        // Otherwise calculate from product
        $price = $cartItem->product->sale_price ?? $cartItem->product->base_price;
        
        return $price;
    }
    
    // Calculate shipping
    private function calculateShipping($subtotal)
    {
        // Free shipping above ₹999, otherwise ₹50
        return $subtotal >= 999 ? 0 : 50;
    }
    
    // Add item to cart
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_color' => 'nullable|string',
            'variant_size' => 'nullable|string',
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;
        $quantity = $request->quantity;
        $variantColor = $request->variant_color;
        $variantSize = $request->variant_size;
        
        // Get product
        $product = Product::findOrFail($productId);
        
        // Check stock
        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Insufficient stock available.');
        }
        
        // Calculate price
        $price = $product->sale_price ?? $product->base_price;
        
        // Check if item already in cart
        $existingCart = Cart::where('user_id', $userId)
                            ->where('product_id', $productId)
                            ->where('variant_color', $variantColor)
                            ->where('variant_size', $variantSize)
                            ->first();
        
        if ($existingCart) {
            // Check total stock
            $newQuantity = $existingCart->quantity + $quantity;
            if ($product->stock < $newQuantity) {
                return redirect()->back()->with('error', 
                    'Only ' . $product->stock . ' items available in stock. You already have ' . 
                    $existingCart->quantity . ' in cart.'
                );
            }
            
            $existingCart->quantity = $newQuantity;
            $existingCart->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'variant_color' => $variantColor,
                'variant_size' => $variantSize,
                'quantity' => $quantity,
                'price' => $price,
                'original_price' => $product->base_price,
            ]);
        }
        
        // Check if it's a buy now request
        if ($request->has('buy_now') && $request->buy_now == true) {
            session(['is_buy_now' => true]);
            return redirect()->route('buyer.checkout');
        }

        return redirect()->back()->with('success', 'Item added to cart!');
    }
    
    // Buy Now - Direct checkout
    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_color' => 'nullable|string',
            'variant_size' => 'nullable|string',
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;
        $quantity = $request->quantity;
        $variantColor = $request->variant_color;
        $variantSize = $request->variant_size;
        
        // Get product
        $product = Product::findOrFail($productId);
        
        // Check stock
        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Insufficient stock available.');
        }
        
        // Calculate price
        $price = $product->sale_price ?? $product->base_price;
        
        // Clear existing cart
        Cart::where('user_id', $userId)->delete();
        
        // Add single item to cart
        Cart::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'variant_color' => $variantColor,
            'variant_size' => $variantSize,
            'quantity' => $quantity,
            'price' => $price,
            'original_price' => $product->base_price,
        ]);
        
        // Set buy now flag
        session(['is_buy_now' => true]);
        
        // Redirect to checkout
        return redirect()->route('buyer.checkout');
    }

    // Remove item from cart
    public function remove($id)
    {
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->where('id', $id)->first();
        
        if ($cart) {
            $cart->delete();
        }

        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    // Update cart item quantity
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        $userId = Auth::id();
        $cart = Cart::with('product')->where('user_id', $userId)->where('id', $id)->first();
        
        if ($cart) {
            $product = $cart->product;
            
            // Check stock
            if ($product->stock < $request->quantity) {
                return redirect()->back()->with('error', 
                    'Only ' . $product->stock . ' items available in stock.'
                );
            }
            
            $cart->quantity = $request->quantity;
            $cart->save();
        }

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }
    
    // Get cart count
    public function getCount()
    {
        $userId = Auth::id();
        $count = Cart::where('user_id', $userId)->sum('quantity');
        
        return response()->json(['count' => $count]);
    }
    
    // Clear cart
    public function clear()
    {
        $userId = Auth::id();
        Cart::where('user_id', $userId)->delete();
        
        return redirect()->back()->with('success', 'Cart cleared successfully!');
    }

    /**
     * Process checkout and create Razorpay payment
     */
    public function processCheckout(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Validate shipping details
            $validated = $request->validate([
                'shipping_name' => 'required|string|max:255',
                'shipping_email' => 'required|email',
                'shipping_phone' => 'required|string|max:20',
                'shipping_address' => 'required|string',
                'shipping_city' => 'required|string',
                'shipping_state' => 'required|string',
                'shipping_zip' => 'required|string',
                'terms' => 'required|accepted',
                'subtotal' => 'required|numeric',
                'shipping_charge' => 'required|numeric',
                'tax_amount' => 'required|numeric',
                'total_amount' => 'required|numeric',
            ]);
            
            $userId = Auth::id();
            $cartItems = Cart::with(['product'])
                            ->where('user_id', $userId)
                            ->get();
            
            if ($cartItems->isEmpty()) {
                return redirect()->route('buyer.cart')->with('error', 'Your cart is empty.');
            }
            
            // Check stock for all items
            foreach ($cartItems as $item) {
                if ($item->product->stock < $item->quantity) {
                    return redirect()->back()->with('error', 
                        "Insufficient stock for {$item->product->name}. Only {$item->product->stock} available."
                    );
                }
            }
            
            // Get Razorpay credentials
            $razorpayKey = Setting::where('key', 'razorpay_key')->first()->value ?? '';
            $razorpaySecret = Setting::where('key', 'razorpay_secret')->first()->value ?? '';
            
            if (empty($razorpayKey) || empty($razorpaySecret)) {
                return redirect()->back()->with('error', 'Payment gateway not configured.');
            }
            
            // Create the order FIRST (before payment)
            $order = Order::create([
                'user_id' => $userId,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'total_amount' => $validated['total_amount'],
                'subtotal' => $validated['subtotal'],
                'shipping_charge' => $validated['shipping_charge'],
                'tax_amount' => $validated['tax_amount'],
                
                // Shipping Details
                'shipping_name' => $validated['shipping_name'],
                'shipping_email' => $validated['shipping_email'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_state' => $validated['shipping_state'],
                'shipping_zip' => $validated['shipping_zip'],
                
                // Payment details - Razorpay only
                'payment_method' => 'razorpay',
                'payment_status' => 'pending',
                'status' => 'pending',
            ]);
            
            // Create order items with ALL required fields including product_name
            foreach ($cartItems as $item) {
                $price = $this->calculateItemPrice($item);
                $totalPrice = $price * $item->quantity;
                
                // Prepare variant details if any
                $variantDetails = null;
                if ($item->variant_color || $item->variant_size) {
                    $variantDetailsArray = [];
                    if ($item->variant_color) {
                        $variantDetailsArray['color'] = $item->variant_color;
                    }
                    if ($item->variant_size) {
                        $variantDetailsArray['size'] = $item->variant_size;
                    }
                    $variantDetails = json_encode($variantDetailsArray);
                }
                
                // Create order item with product_name field
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'seller_id' => $item->product->seller_id,
                    'product_name' => $item->product->name, // REQUIRED FIELD
                    'variant_details' => $variantDetails,
                    'quantity' => $item->quantity,
                    'unit_price' => $price,
                    'total_price' => $totalPrice,
                ]);
                
                // Update product stock
                $item->product->decrement('stock', $item->quantity);
            }
            
            // Clear cart
            Cart::where('user_id', $userId)->delete();
            
            // Create Razorpay order
            $api = new Api($razorpayKey, $razorpaySecret);
            $razorpayOrder = $api->order->create([
                'receipt' => $order->order_number,
                'amount' => $order->total_amount * 100, // Convert to paise
                'currency' => 'INR',
                'payment_capture' => 1,
                'notes' => [
                    'order_id' => $order->id,
                    'user_id' => $userId,
                ]
            ]);
            
            // Update order with Razorpay order ID
            $order->update(['razorpay_order_id' => $razorpayOrder->id]);
            
            // Update user profile with shipping address
            $user = Auth::user();
            $user->update([
                'name' => $validated['shipping_name'],
                'email' => $validated['shipping_email'],
                'phone' => $validated['shipping_phone'],
                'address' => $validated['shipping_address'],
                'city' => $validated['shipping_city'],
                'state' => $validated['shipping_state'],
                'zip' => $validated['shipping_zip'],
            ]);
            
            DB::commit();
            
            Log::info('Order created successfully: ' . $order->id);
            
            // Redirect to Razorpay payment page
            return redirect()->route('buyer.razorpay.payment', ['order' => $order->id])
                ->with('success', 'Order created. Please complete the payment.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return redirect()->back()->with('error', 'Error processing checkout: ' . $e->getMessage());
        }
    }
    
    /**
     * Show Razorpay payment page
     */
    public function razorpayPaymentPage($orderId)
    {
        $order = Order::where('id', $orderId)
                    ->where('user_id', Auth::id())
                    ->where('payment_status', 'pending')
                    ->firstOrFail();
        
        // Get Razorpay key
        $razorpayKey = Setting::where('key', 'razorpay_key')->first()->value ?? '';
        
        if (empty($razorpayKey)) {
            return redirect()->route('buyer.checkout.failed')
                ->with('error', 'Payment gateway not configured.');
        }
        
        return view('buyer.razorpay-payment', [
            'order' => $order,
            'razorpayKey' => $razorpayKey,
            'razorpayOrderId' => $order->razorpay_order_id,
            'amount' => $order->total_amount * 100, // In paise
            'user' => Auth::user(),
        ]);
    }
    
    /**
     * Handle Razorpay payment callback
     */
    public function razorpayCallback(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Validate request
            $validated = $request->validate([
                'razorpay_payment_id' => 'required|string',
                'razorpay_order_id' => 'required|string',
                'razorpay_signature' => 'required|string',
            ]);
            
            // Find order by razorpay_order_id
            $order = Order::where('razorpay_order_id', $validated['razorpay_order_id'])
                        ->where('payment_status', 'pending')
                        ->first();
            
            if (!$order) {
                // Try to find by order ID from notes or session
                $order = Order::where('id', session('pending_order_id'))
                            ->where('payment_status', 'pending')
                            ->first();
                
                if (!$order) {
                    throw new \Exception('Order not found or already processed.');
                }
            }
            
            // Verify user ownership
            if ($order->user_id !== Auth::id()) {
                throw new \Exception('Unauthorized access to this order.');
            }
            
            // Verify payment signature
            $razorpaySecret = Setting::where('key', 'razorpay_secret')->first()->value ?? '';
            
            if (empty($razorpaySecret)) {
                throw new \Exception('Payment gateway not configured.');
            }
            
            // Generate signature
            $generatedSignature = hash_hmac('sha256', 
                $validated['razorpay_order_id'] . '|' . $validated['razorpay_payment_id'], 
                $razorpaySecret
            );
            
            if ($generatedSignature !== $validated['razorpay_signature']) {
                throw new \Exception('Payment verification failed. Signature mismatch.');
            }
            
            // Update order payment status with ALL required fields
            $order->update([
                'payment_status' => 'paid',
                'payment_id' => $validated['razorpay_payment_id'],
                'razorpay_order_id' => $validated['razorpay_order_id'], // Ensure it's set
                'status' => 'confirmed',
                'paid_at' => now(),
            ]);
            
            // ============ CREATE SELLER EARNINGS ============
            $this->createSellerEarnings($order);
            
            DB::commit();
            
            Log::info('Payment successful for order: ' . $order->id, [
                'order_id' => $order->id,
                'payment_id' => $validated['razorpay_payment_id'],
                'user_id' => Auth::id()
            ]);
            
            // Clear any session data
            session()->forget(['is_buy_now', 'pending_order_id']);
            
            // Send email notification (optional)
            // $this->sendOrderConfirmationEmail($order);
            
            // Redirect to success page
            return redirect()->route('buyer.checkout.success', $order)
                ->with('success', 'Payment successful! Order confirmed.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Razorpay Callback Error: ' . $e->getMessage());
            Log::error('Request Data: ' . json_encode($request->all()));
            
            // Update order status if we have the order
            if (isset($order)) {
                try {
                    $order->update([
                        'payment_status' => 'failed',
                        'status' => 'payment_failed',
                    ]);
                } catch (\Exception $updateError) {
                    Log::error('Failed to update order status: ' . $updateError->getMessage());
                }
            }
            
            return redirect()->route('buyer.checkout.failed')
                ->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Create seller earnings from order
     * This calculates the commission based on the product's base price
     */
    private function createSellerEarnings($order)
    {
        // Get all order items
        $orderItems = OrderItem::where('order_id', $order->id)->get();
        
        // Get commission rate from settings
        $commissionRate = $this->getCommissionRate();
        
        foreach ($orderItems as $item) {
            // Get the product to find its base price
            $product = Product::find($item->product_id);
            
            if (!$product) {
                Log::warning('Product not found for order item: ' . $item->id);
                continue;
            }
            
            // Calculate what the seller actually earns
            // Since the displayed price already includes commission,
            // we need to extract the commission from the total price
            
            // Option 1: If the product has a base_price (seller's price before commission)
            // and sale_price (final price including commission)
            if ($product->base_price > 0) {
                // Commission is the difference between sale price and base price
                $unitCommission = ($item->unit_price - $product->base_price) * $item->quantity;
                $sellerAmount = $product->base_price * $item->quantity;
                
                Log::info('Commission calculation using base_price', [
                    'unit_price' => $item->unit_price,
                    'base_price' => $product->base_price,
                    'quantity' => $item->quantity,
                    'commission' => $unitCommission,
                    'seller_amount' => $sellerAmount
                ]);
            } 
            // Option 2: If you have a fixed commission percentage
            else {
                // Calculate commission based on percentage
                $commissionAmount = ($item->total_price * $commissionRate) / 100;
                $sellerAmount = $item->total_price - $commissionAmount;
                
                Log::info('Commission calculation using percentage', [
                    'total_price' => $item->total_price,
                    'commission_rate' => $commissionRate,
                    'commission' => $commissionAmount,
                    'seller_amount' => $sellerAmount
                ]);
            }
            
            // Check if earning already exists for this order item
            $existingEarning = SellerEarning::where('order_item_id', $item->id)->first();
            
            if (!$existingEarning) {
                // Create seller earning
                SellerEarning::create([
                    'seller_id' => $item->seller_id,
                    'order_id' => $order->id,
                    'order_item_id' => $item->id,
                    'amount' => $item->total_price, // Total paid by customer
                    'commission' => $item->total_price - $sellerAmount, // Platform commission
                    'net_amount' => $sellerAmount, // Seller's actual earnings
                    'type' => 'sale',
                    'status' => 'pending', // Initially pending, will become available after delivery
                    'description' => 'Earnings from order #' . $order->order_number,
                    'available_at' => Carbon::now()->addDays(7), // Available after 7 days (return period)
                ]);
                
                Log::info('Seller earning created for seller: ' . $item->seller_id, [
                    'amount' => $item->total_price,
                    'commission' => $item->total_price - $sellerAmount,
                    'net_amount' => $sellerAmount
                ]);
            }
        }
    }
    
    /**
     * Get commission rate from settings
     */
    private function getCommissionRate()
    {
        // Try to get from settings table
        $commissionRate = Setting::where('key', 'commission_rate')->first();
        
        if ($commissionRate && $commissionRate->value) {
            return floatval($commissionRate->value);
        }
        
        // Default commission rate (10%)
        return 10;
    }
    
    /**
     * Create Razorpay order (for AJAX if needed)
     */
    public function createRazorpayOrder(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:1'
            ]);
            
            // Get Razorpay credentials
            $razorpayKey = Setting::where('key', 'razorpay_key')->first()->value ?? '';
            $razorpaySecret = Setting::where('key', 'razorpay_secret')->first()->value ?? '';
            
            if (empty($razorpayKey) || empty($razorpaySecret)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment gateway not configured'
                ], 400);
            }
            
            // Convert to paise
            $amountInPaise = $request->amount * 100;
            
            // Create Razorpay order
            $api = new Api($razorpayKey, $razorpaySecret);
            $razorpayOrder = $api->order->create([
                'receipt' => 'order_' . time() . '_' . uniqid(),
                'amount' => $amountInPaise,
                'currency' => 'INR',
                'payment_capture' => 1
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'razorpay_key' => $razorpayKey,
                    'razorpay_order_id' => $razorpayOrder->id,
                    'amount' => $request->amount,
                    'amount_in_paise' => $amountInPaise,
                    'currency' => 'INR',
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Create Razorpay Order Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}