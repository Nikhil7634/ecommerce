<x-header 
    title="Order Details - {{ config('app.name', 'eCommerce') }}"
    description="View your order details and track shipment"
    ogImage="{{ asset('assets/images/banner/home-og.jpg') }}"
/>

<x-navbar />

<!--=========================
    PAGE BANNER START
==========================-->
<section class="page_banner" style="background: url('{{ asset('assets/images/page_banner_bg.jpg') }}');">
    <div class="page_banner_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page_banner_text wow fadeInUp">
                        <h1>Order Details</h1>
                        <ul>
                            <li><a href="{{ route('home') }}"><i class="fal fa-home-lg"></i> Home</a></li>
                            <li><a href="{{ route('buyer.dashboard') }}">Dashboard</a></li>
                            <li><a href="{{ route('buyer.orders') }}">My Orders</a></li>
                            <li class="active">Order #{{ $order->order_number }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--=========================
    PAGE BANNER END
==========================-->

<!--============================
    ORDER DETAILS START
=============================-->
<section class="dashboard mb_100">
    <div class="container">
        <div class="row">
            <x-aside />
            <div class="col-lg-9">
                <div class="dashboard_content mt_100">
                    
                    <!-- Order Status Banner -->
                    <div class="mb-4 order-status-banner">
                        @php
                            $statusConfig = [
                                'pending' => [
                                    'color' => 'warning',
                                    'icon' => 'fa-clock',
                                    'title' => 'Order Pending',
                                    'message' => 'Your order is being processed and will be confirmed soon.',
                                    'bg' => 'bg-warning'
                                ],
                                'processing' => [
                                    'color' => 'info',
                                    'icon' => 'fa-cog fa-spin',
                                    'title' => 'Order Processing',
                                    'message' => 'We are preparing your items for shipment.',
                                    'bg' => 'bg-info'
                                ],
                                'confirmed' => [
                                    'color' => 'primary',
                                    'icon' => 'fa-check-circle',
                                    'title' => 'Order Confirmed',
                                    'message' => 'Your order has been confirmed and is being prepared.',
                                    'bg' => 'bg-primary'
                                ],
                                'shipped' => [
                                    'color' => 'info',
                                    'icon' => 'fa-truck',
                                    'title' => 'Order Shipped',
                                    'message' => 'Your order is on its way! Track your shipment below.',
                                    'bg' => 'bg-info'
                                ],
                                'delivered' => [
                                    'color' => 'success',
                                    'icon' => 'fa-check-circle',
                                    'title' => 'Order Delivered',
                                    'message' => 'Your order has been delivered. Thank you for shopping with us!',
                                    'bg' => 'bg-success'
                                ],
                                'cancelled' => [
                                    'color' => 'danger',
                                    'icon' => 'fa-times-circle',
                                    'title' => 'Order Cancelled',
                                    'message' => 'This order has been cancelled.',
                                    'bg' => 'bg-danger'
                                ],
                                'payment_failed' => [
                                    'color' => 'danger',
                                    'icon' => 'fa-exclamation-circle',
                                    'title' => 'Payment Failed',
                                    'message' => 'Payment was unsuccessful. Please try again.',
                                    'bg' => 'bg-danger'
                                ]
                            ];
                            
                            $status = $statusConfig[$order->status] ?? [
                                'color' => 'secondary',
                                'icon' => 'fa-question-circle',
                                'title' => ucfirst($order->status),
                                'message' => 'Order status update',
                                'bg' => 'bg-secondary'
                            ];
                        @endphp
                        
                        <div class="p-4 rounded-4 bg-{{ $status['color'] }} bg-opacity-10 border border-{{ $status['color'] }} border-opacity-25">
                            <div class="flex-wrap gap-3 d-flex align-items-center">
                                <div class="status-icon">
                                    <div class="rounded-circle bg-{{ $status['color'] }} p-3" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas {{ $status['icon'] }} fa-2x text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h4 class="mb-1 text-{{ $status['color'] }}">{{ $status['title'] }}</h4>
                                    <p class="mb-0 text-muted">{{ $status['message'] }}</p>
                                </div>
                                <div class="order-number-badge">
                                    <span class="badge {{ $status['bg'] }} p-3 fs-6 rounded-pill">
                                        <i class="fas fa-hashtag me-2"></i>
                                        {{ $order->order_number }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions & Timeline Row -->
                    <div class="mb-4 row g-4">
                        <div class="col-lg-7">
                            <div class="p-4 bg-white shadow-sm rounded-4 h-100">
                                <h5 class="mb-4">
                                    <i class="fas fa-clock text-primary me-2"></i>
                                    Order Timeline
                                </h5>
                                
                                <div class="order-timeline-vertical">
                                    <!-- Order Placed -->
                                    <div class="timeline-item-vertical completed">
                                        <div class="timeline-marker-vertical bg-success">
                                            <i class="text-white fas fa-check"></i>
                                        </div>
                                        <div class="timeline-content-vertical">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-1">Order Placed</h6>
                                                <small class="text-muted">{{ $order->created_at->format('d M, h:i A') }}</small>
                                            </div>
                                            <p class="mb-0 text-muted small">Your order has been placed successfully</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Payment Confirmed -->
                                    <div class="timeline-item-vertical {{ $order->payment_status == 'paid' ? 'completed' : ($order->payment_status == 'pending' ? 'active' : '') }}">
                                        <div class="timeline-marker-vertical {{ $order->payment_status == 'paid' ? 'bg-success' : ($order->payment_status == 'pending' ? 'bg-warning' : 'bg-secondary') }}">
                                            @if($order->payment_status == 'paid')
                                                <i class="text-white fas fa-check"></i>
                                            @elseif($order->payment_status == 'pending')
                                                <i class="text-white fas fa-clock"></i>
                                            @else
                                                <i class="text-white fas fa-times"></i>
                                            @endif
                                        </div>
                                        <div class="timeline-content-vertical">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-1">Payment Confirmed</h6>
                                                @if($order->paid_at)
                                                    <small class="text-muted">{{ $order->paid_at->format('d M, h:i A') }}</small>
                                                @endif
                                            </div>
                                            <p class="mb-0 text-muted small">
                                                @if($order->payment_status == 'paid')
                                                    Payment received via {{ ucfirst($order->payment_method) }}
                                                @elseif($order->payment_status == 'pending')
                                                    Awaiting payment confirmation
                                                @else
                                                    Payment failed
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Order Confirmed -->
                                    <div class="timeline-item-vertical {{ in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']) ? 'completed' : '' }}">
                                        <div class="timeline-marker-vertical {{ in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']) ? 'bg-success' : 'bg-secondary' }}">
                                            @if(in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']))
                                                <i class="text-white fas fa-check"></i>
                                            @else
                                                <i class="text-white fas fa-circle"></i>
                                            @endif
                                        </div>
                                        <div class="timeline-content-vertical">
                                            <h6 class="mb-1">Order Confirmed</h6>
                                            <p class="mb-0 text-muted small">Your order has been confirmed by the seller</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Processing -->
                                    <div class="timeline-item-vertical {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'completed' : '' }}">
                                        <div class="timeline-marker-vertical {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-success' : 'bg-secondary' }}">
                                            @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                                                <i class="text-white fas fa-check"></i>
                                            @else
                                                <i class="text-white fas fa-circle"></i>
                                            @endif
                                        </div>
                                        <div class="timeline-content-vertical">
                                            <h6 class="mb-1">Processing</h6>
                                            <p class="mb-0 text-muted small">Your items are being prepared for shipment</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Shipped -->
                                    <div class="timeline-item-vertical {{ in_array($order->status, ['shipped', 'delivered']) ? 'completed' : '' }}">
                                        <div class="timeline-marker-vertical {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-success' : 'bg-secondary' }}">
                                            @if(in_array($order->status, ['shipped', 'delivered']))
                                                <i class="text-white fas fa-check"></i>
                                            @else
                                                <i class="text-white fas fa-circle"></i>
                                            @endif
                                        </div>
                                        <div class="timeline-content-vertical">
                                            <h6 class="mb-1">Shipped</h6>
                                            <p class="mb-0 text-muted small">Your order is on its way</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Delivered -->
                                    <div class="timeline-item-vertical {{ $order->status == 'delivered' ? 'completed' : '' }}">
                                        <div class="timeline-marker-vertical {{ $order->status == 'delivered' ? 'bg-success' : 'bg-secondary' }}">
                                            @if($order->status == 'delivered')
                                                <i class="text-white fas fa-check"></i>
                                            @else
                                                <i class="text-white fas fa-circle"></i>
                                            @endif
                                        </div>
                                        <div class="timeline-content-vertical">
                                            <h6 class="mb-1">Delivered</h6>
                                            <p class="mb-0 text-muted small">Package delivered successfully</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-5">
                            <div class="p-4 bg-white shadow-sm rounded-4 h-100">
                                <h5 class="mb-4">
                                    <i class="fas fa-cog text-primary me-2"></i>
                                    Order Actions
                                </h5>
                                
                                <div class="gap-3 d-flex flex-column">
                                    <!-- Track Order Button -->
                                    @if(Route::has('buyer.order.track'))
                                    <a href="{{ route('buyer.order.track', $order->id) }}" class="py-3 btn btn-outline-primary btn-lg">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        Track Order
                                        <small class="mt-1 d-block text-muted">See real-time updates</small>
                                    </a>
                                    @endif
                                    
                                    <!-- Download Invoice Button -->
                                    @if(Route::has('buyer.order.invoice'))
                                    <a href="{{ route('buyer.order.invoice', $order->id) }}" class="py-3 btn btn-outline-success btn-lg">
                                        <i class="fas fa-file-pdf me-2"></i>
                                        Download Invoice
                                        <small class="mt-1 d-block text-muted">Save for your records</small>
                                    </a>
                                    @endif
                                    
                                    <!-- Cancel Order Button (only for pending/processing) -->
                                    @if(in_array($order->status, ['pending', 'processing']) && Route::has('buyer.order.cancel'))
                                    <form action="{{ route('buyer.order.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                        @csrf
                                        <button type="submit" class="py-3 btn btn-outline-danger btn-lg w-100">
                                            <i class="fas fa-times-circle me-2"></i>
                                            Cancel Order
                                            <small class="mt-1 d-block text-muted">Only available for pending orders</small>
                                        </button>
                                    </form>
                                    @endif
                                    
                                    <!-- WRITE REVIEW BUTTON - FIXED VERSION -->
                                    @if(in_array($order->status, ['delivered', 'confirmed']))
                                        @php
                                            // Count how many items in this order are reviewable
                                            $reviewableCount = 0;
                                            $reviewableProducts = [];
                                            foreach($order->items as $item) {
                                                $existingReview = \App\Models\Review::where('user_id', Auth::id())
                                                    ->where('product_id', $item->product_id)
                                                    ->first();
                                                if (!$existingReview) {
                                                    $reviewableCount++;
                                                    $reviewableProducts[] = $item;
                                                }
                                            }
                                        @endphp
                                        
                                        @if($reviewableCount > 0)
                                            @if($reviewableCount == 1)
                                                <!-- Single product to review -->
                                                @foreach($reviewableProducts as $item)
                                                <a href="{{ route('buyer.reviews.create', ['order' => $order->id, 'product_id' => $item->product_id]) }}" 
                                                   class="py-3 btn btn-warning btn-lg">
                                                    <i class="fas fa-star me-2"></i>
                                                    Write a Review
                                                    <small class="mt-1 d-block text-muted">Share your experience with this product</small>
                                                </a>
                                                @endforeach
                                            @else
                                                <!-- Multiple products to review -->
                                                <a href="{{ route('buyer.reviews.select-product', $order->id) }}" 
                                                   class="py-3 btn btn-warning btn-lg">
                                                    <i class="fas fa-star me-2"></i>
                                                    Write Reviews ({{ $reviewableCount }})
                                                    <small class="mt-1 d-block text-muted">Review products from this order</small>
                                                </a>
                                            @endif
                                        @else
                                            <!-- All products reviewed -->
                                            <div class="p-3 text-center bg-light rounded-3">
                                                <i class="mb-2 fas fa-check-circle text-success fa-2x"></i>
                                                <p class="mb-0">You've reviewed all products in this order</p>
                                            </div>
                                        @endif
                                    @endif
                                    
                                    <!-- Contact Support Button -->
                                    <a href="#" class="py-3 btn btn-outline-secondary btn-lg" data-bs-toggle="modal" data-bs-target="#supportModal">
                                        <i class="fas fa-headset me-2"></i>
                                        Need Help?
                                        <small class="mt-1 d-block text-muted">Contact customer support</small>
                                    </a>
                                </div>
                                
                                <!-- Order Summary Mini -->
                                <div class="p-3 mt-4 bg-light rounded-3">
                                    <h6 class="mb-3">Order Summary</h6>
                                    <div class="mb-2 d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Subtotal:</span>
                                        <strong>₹{{ number_format($order->subtotal, 2) }}</strong>
                                    </div>
                                    <div class="mb-2 d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Shipping:</span>
                                        <strong>₹{{ number_format($order->shipping_charge, 2) }}</strong>
                                    </div>
                                    <div class="mb-2 d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Tax:</span>
                                        <strong>₹{{ number_format($order->tax_amount, 2) }}</strong>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Total:</span>
                                        <h4 class="mb-0 text-primary">₹{{ number_format($order->total_amount, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items Card -->
                    <div class="mb-4 overflow-hidden bg-white shadow-sm rounded-4">
                        <div class="p-4 border-bottom">
                            <h5 class="mb-0">
                                <i class="fas fa-box text-primary me-2"></i>
                                Order Items ({{ $order->items->count() }})
                            </h5>
                        </div>
                        
                        <div class="p-4">
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th>Review</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->product && $item->product->images->first())
                                                    <div class="flex-shrink-0 me-3">
                                                        <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                                             alt="{{ $item->product->name }}" 
                                                             class="rounded-3" 
                                                             style="max-width: 120px; height: 70px; object-fit: cover; border: 1px solid #eee;">
                                                    </div>
                                                    @endif
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">{{ $item->product_name ?? $item->product->name }}</h6>
                                                        @if($item->variant_details)
                                                        <div class="gap-2 mt-1 d-flex">
                                                            @php
                                                                $variants = json_decode($item->variant_details, true);
                                                            @endphp
                                                            @if(!empty($variants['color']))
                                                            <span class="badge bg-light text-dark">
                                                                <i class="fas fa-palette me-1"></i> {{ $variants['color'] }}
                                                            </span>
                                                            @endif
                                                            @if(!empty($variants['size']))
                                                            <span class="badge bg-light text-dark">
                                                                <i class="fas fa-ruler me-1"></i> {{ $variants['size'] }}
                                                            </span>
                                                            @endif
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>₹{{ number_format($item->unit_price, 2) }}</td>
                                            <td>
                                                <span class="px-3 py-2 badge bg-secondary bg-opacity-10 text-dark">
                                                    {{ $item->quantity }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong class="text-primary">₹{{ number_format($item->total_price, 2) }}</strong>
                                            </td>
                                            <td>
                                                @if(in_array($order->status, ['delivered', 'confirmed']))
                                                    @php
                                                        $existingReview = \App\Models\Review::where('user_id', Auth::id())
                                                            ->where('product_id', $item->product_id)
                                                            ->first();
                                                    @endphp
                                                    
                                                    @if($existingReview)
                                                        <span class="p-2 badge bg-success bg-opacity-10 text-success">
                                                            <i class="fas fa-check-circle me-1"></i> Reviewed
                                                        </span>
                                                    @else
                                                        <a href="{{ route('buyer.reviews.create', ['order' => $order->id, 'product_id' => $item->product_id]) }}" 
                                                           class="btn btn-sm btn-warning">
                                                            <i class="fas fa-star me-1"></i> Review
                                                        </a>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping & Payment Info -->
                    <div class="row g-4">
                        <!-- Shipping Address -->
                        <div class="col-md-6">
                            <div class="p-4 bg-white shadow-sm h-100 rounded-4">
                                <h5 class="mb-4">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    Shipping Address
                                </h5>
                                
                                <div class="shipping-details">
                                    <div class="pb-3 mb-3 border-bottom">
                                        <h6 class="mb-2 fw-bold">{{ $order->shipping_name }}</h6>
                                        <p class="mb-1 text-muted">
                                            <i class="fas fa-home me-2"></i>
                                            {{ $order->shipping_address }}
                                        </p>
                                        <p class="mb-1 text-muted">
                                            <i class="fas fa-city me-2"></i>
                                            {{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_zip }}
                                        </p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <p class="mb-2">
                                            <i class="fas fa-phone me-2 text-muted"></i>
                                            <a href="tel:{{ $order->shipping_phone }}" class="text-decoration-none text-dark">
                                                {{ $order->shipping_phone }}
                                            </a>
                                        </p>
                                        <p class="mb-0">
                                            <i class="fas fa-envelope me-2 text-muted"></i>
                                            <a href="mailto:{{ $order->shipping_email }}" class="text-decoration-none text-dark">
                                                {{ $order->shipping_email }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Information -->
                        <div class="col-md-6">
                            <div class="p-4 bg-white shadow-sm h-100 rounded-4">
                                <h5 class="mb-4">
                                    <i class="fas fa-credit-card text-primary me-2"></i>
                                    Payment Information
                                </h5>
                                
                                <div class="payment-details">
                                    <div class="pb-3 mb-3 border-bottom">
                                        <div class="mb-2 d-flex justify-content-between align-items-center">
                                            <span class="text-muted">Payment Method:</span>
                                            <span class="fw-bold">
                                                @if($order->payment_method == 'razorpay')
                                                    <i class="fas fa-credit-card me-1 text-primary"></i> Razorpay
                                                @else
                                                    {{ ucfirst($order->payment_method) }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">Payment Status:</span>
                                            @if($order->payment_status == 'paid')
                                                <span class="px-3 py-2 badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i> Paid
                                                </span>
                                            @elseif($order->payment_status == 'pending')
                                                <span class="px-3 py-2 badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i> Pending
                                                </span>
                                            @else
                                                <span class="px-3 py-2 badge bg-danger">
                                                    <i class="fas fa-times-circle me-1"></i> Failed
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($order->payment_id)
                                    <div class="mb-3">
                                        <p class="mb-1">
                                            <span class="text-muted">Transaction ID:</span>
                                            <br>
                                            <code class="p-2 mt-1 rounded bg-light d-inline-block">{{ $order->payment_id }}</code>
                                            <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('{{ $order->payment_id }}')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </p>
                                    </div>
                                    @endif
                                    
                                    @if($order->paid_at)
                                    <div class="mb-3">
                                        <p class="mb-0">
                                            <span class="text-muted">Paid on:</span>
                                            <br>
                                            <strong>{{ $order->paid_at->format('d M Y, h:i A') }}</strong>
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Need Help Section -->
                    <div class="mt-4">
                        <div class="p-4 bg-white shadow-sm rounded-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="p-3 rounded-circle bg-primary bg-opacity-10">
                                                <i class="fas fa-headset fa-2x text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-1">Need help with your order?</h5>
                                            <p class="mb-0 text-muted">Our customer support team is here to assist you 24/7</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="gap-2 mt-3 d-flex justify-content-md-end mt-md-0">
                                        <a href="mailto:support@example.com" class="btn btn-outline-primary">
                                            <i class="fas fa-envelope me-2"></i> Email
                                        </a>
                                        <a href="tel:18001234567" class="btn btn-primary">
                                            <i class="fas fa-phone me-2"></i> Call Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--============================
    ORDER DETAILS END
=============================-->

<!-- Support Modal -->
<div class="modal fade" id="supportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="text-white modal-header bg-primary">
                <h5 class="text-white modal-title">
                    <i class="fas fa-headset me-2"></i>
                    Customer Support
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="p-4 modal-body">
                <h6 class="mb-3">How can we help you?</h6>
                
                <div class="list-group list-group-flush">
                    <a href="#" class="p-3 list-group-item list-group-item-action d-flex align-items-center">
                        <div class="p-2 rounded-circle bg-primary bg-opacity-10 me-3">
                            <i class="fas fa-question-circle text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Order Related Query</h6>
                            <p class="mb-0 text-muted small">Questions about your order status</p>
                        </div>
                    </a>
                    
                    <a href="#" class="p-3 list-group-item list-group-item-action d-flex align-items-center">
                        <div class="p-2 rounded-circle bg-success bg-opacity-10 me-3">
                            <i class="fas fa-truck text-success"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Shipping & Delivery</h6>
                            <p class="mb-0 text-muted small">Track package, delivery issues</p>
                        </div>
                    </a>
                    
                    <a href="#" class="p-3 list-group-item list-group-item-action d-flex align-items-center">
                        <div class="p-2 rounded-circle bg-warning bg-opacity-10 me-3">
                            <i class="fas fa-undo-alt text-warning"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Returns & Refunds</h6>
                            <p class="mb-0 text-muted small">Return policy, refund status</p>
                        </div>
                    </a>
                    
                    <a href="#" class="p-3 list-group-item list-group-item-action d-flex align-items-center">
                        <div class="p-2 rounded-circle bg-danger bg-opacity-10 me-3">
                            <i class="fas fa-credit-card text-danger"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Payment Issues</h6>
                            <p class="mb-0 text-muted small">Payment failed, transaction issues</p>
                        </div>
                    </a>
                </div>
                
                <hr class="my-4">
                
                <div class="text-center">
                    <h6 class="mb-3">Contact us directly</h6>
                    <div class="gap-3 d-flex justify-content-center">
                        <a href="mailto:support@example.com" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-2"></i> support@example.com
                        </a>
                        <a href="tel:18001234567" class="btn btn-primary">
                            <i class="fas fa-phone me-2"></i> 1800-123-4567
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-footer />

 

@push('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Transaction ID copied to clipboard!');
        }, function() {
            alert('Failed to copy transaction ID');
        });
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Animate timeline items on scroll
        const observerOptions = {
            threshold: 0.2,
            rootMargin: '0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateX(0)';
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.timeline-item-vertical').forEach(item => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';
            item.style.transition = 'all 0.5s ease';
            observer.observe(item);
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    });
</script>
@endpush