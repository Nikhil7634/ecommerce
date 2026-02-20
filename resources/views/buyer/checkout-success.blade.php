<!-- resources/views/buyer/checkout-success.blade.php -->
<x-header
    title="Order Successful - {{ config('app.name', 'eCommerce') }}"
    description="Your order has been placed successfully"
/>

<x-navbar />

<section class="page_banner" style="background: url({{ asset('assets/images/page_banner_bg.jpg') }});">
    <div class="page_banner_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page_banner_text wow fadeInUp">
                        <h1>Order Confirmed</h1>
                        <ul>
                            <li><a href="{{ route('home') }}"><i class="fal fa-home-lg"></i> Home</a></li>
                            <li><a href="{{ route('buyer.orders') }}">My Orders</a></li>
                            <li class="active">Order #{{ $order->order_number }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="checkout_page mt_100 mb_100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="checkout_card">
                    <div class="text-center checkout_card_body">
                        <div class="mb-4">
                            <i class="mb-3 fas fa-check-circle fa-4x text-success"></i>
                            <h3 class="mb-3">Thank You!</h3>
                            <p class="text-muted">Your order has been placed successfully.</p>
                        </div>
                        
                        <div class="mb-4 alert alert-success">
                            <h5 class="mb-2">Order #{{ $order->order_number }}</h5>
                            <p class="mb-0">Order Total: ₹{{ number_format($order->total_amount, 2) }}</p>
                            <p class="mb-0">Payment Status: 
                                <span class="badge bg-success">Paid</span>
                            </p>
                        </div>
                        
                        <div class="p-4 mb-4 rounded order-details bg-light">
                            <h5 class="mb-3">Order Details</h5>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <strong>Order Date:</strong><br>
                                    {{ $order->created_at->format('d M Y, h:i A') }}
                                </div>
                                <div class="mb-3 col-md-6">
                                    <strong>Payment Method:</strong><br>
                                    {{ ucfirst($order->payment_method) }}
                                </div>
                                <div class="col-md-12">
                                    <strong>Shipping Address:</strong><br>
                                    {{ $order->shipping_name }}<br>
                                    {{ $order->shipping_address }}, {{ $order->shipping_city }}<br>
                                    {{ $order->shipping_state }} - {{ $order->shipping_zip }}<br>
                                    Phone: {{ $order->shipping_phone }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="gap-3 d-grid">
                            <a href="{{ route('buyer.order.show', $order->id) }}" class="py-3 btn btn-primary">
                                <i class="fas fa-eye me-2"></i> View Order Details
                            </a>
                            <a href="{{ route('home') }}" class="py-3 btn btn-outline-primary">
                                <i class="fas fa-home me-2"></i> Continue Shopping
                            </a>
                        </div>
                        
                        <div class="pt-4 mt-4 border-top">
                            <small class="text-muted">
                                An order confirmation email has been sent to {{ $order->shipping_email }}<br>
                                Need help? <a href="{{ route('contact') }}">Contact Support</a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<x-footer />