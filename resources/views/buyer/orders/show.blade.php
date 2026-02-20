<x-header 
    title="Order Details - {{ config('app.name', 'eCommerce') }}"
    description="View order details and tracking information"
    keywords="order details, tracking, invoice"
    ogImage="{{ asset('assets/images/banner/home-og.jpg') }}"
>
</x-header>

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
                            <li><a href="{{ route('buyer.orders') }}">Orders</a></li>
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
                    <!-- Order Header -->
                    <div class="mb-4 order-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3>Order #{{ $order->order_number }}</h3>
                                <p class="mb-0 text-muted">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                            </div>
                            <div class="text-end">
                                @php
                                    $statusClass = '';
                                    $statusText = ucfirst($order->status);
                                    
                                    if($order->status == 'confirmed') {
                                        $statusClass = 'badge bg-success';
                                        $statusText = 'Confirmed';
                                    } elseif($order->status == 'pending') {
                                        $statusClass = 'badge bg-warning';
                                        $statusText = 'Pending';
                                    } elseif($order->status == 'processing') {
                                        $statusClass = 'badge bg-info';
                                        $statusText = 'Processing';
                                    } elseif($order->status == 'shipped') {
                                        $statusClass = 'badge bg-primary';
                                        $statusText = 'Shipped';
                                    } elseif($order->status == 'delivered') {
                                        $statusClass = 'badge bg-success';
                                        $statusText = 'Delivered';
                                    } elseif($order->status == 'cancelled' || $order->status == 'payment_failed') {
                                        $statusClass = 'badge bg-danger';
                                        $statusText = 'Cancelled';
                                    }
                                @endphp
                                <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                <div class="mt-2">
                                    @if($order->payment_status == 'paid')
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Paid</span>
                                    @elseif($order->payment_status == 'pending')
                                    <span class="badge bg-warning"><i class="fas fa-clock"></i> Payment Pending</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mb-4 action-buttons">
                        <div class="btn-group" role="group">
                            <a href="{{ route('buyer.orders') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Orders
                            </a>
                            @if(Route::has('buyer.order.invoice'))
                            <a href="{{ route('buyer.order.invoice', $order->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-download"></i> Download Invoice
                            </a>
                            @endif
                            @if(in_array($order->status, ['pending', 'processing']) && Route::has('buyer.order.cancel'))
                            <form action="{{ route('buyer.order.cancel', $order->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to cancel this order?')">
                                    <i class="fas fa-times"></i> Cancel Order
                                </button>
                            </form>
                            @endif
                            @if(in_array($order->status, ['confirmed', 'delivered']) && Route::has('buyer.reviews.create'))
                            <a href="{{ route('buyer.reviews.create', ['order' => $order->id]) }}" class="btn btn-outline-success">
                                <i class="fas fa-star"></i> Write Review
                            </a>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Order Items -->
                        <div class="col-lg-8">
                            <div class="mb-4 order-items">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Order Items ({{ $order->items->count() }})</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Price</th>
                                                        <th>Quantity</th>
                                                        <th>Total</th>
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
                                                                         class="rounded" 
                                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                                </div>
                                                                @endif
                                                                <div class="flex-grow-1">
                                                                    <h6 class="mb-1">{{ $item->product_name ?? 'Product' }}</h6>
                                                                    @if($item->product)
                                                                    <p class="mb-1 text-muted">
                                                                        Seller: {{ $item->product->seller->name ?? 'Seller' }}
                                                                    </p>
                                                                    @endif
                                                                    @if($item->variant_details)
                                                                    <p class="mb-0 text-muted small">
                                                                        @php
                                                                            $variantDetails = json_decode($item->variant_details, true);
                                                                            if(is_array($variantDetails)) {
                                                                                echo implode(', ', array_map(function($key, $value) {
                                                                                    return ucfirst($key) . ': ' . $value;
                                                                                }, array_keys($variantDetails), $variantDetails));
                                                                            }
                                                                        @endphp
                                                                    </p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>₹{{ number_format($item->unit_price, 2) }}</td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td><strong>₹{{ number_format($item->total_price, 2) }}</strong></td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="col-lg-4">
                            <div class="mb-4 order-summary">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Order Summary</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="mb-2 d-flex justify-content-between">
                                                <span class="text-muted">Subtotal</span>
                                                <span>₹{{ number_format($order->subtotal, 2) }}</span>
                                            </div>
                                            <div class="mb-2 d-flex justify-content-between">
                                                <span class="text-muted">Shipping</span>
                                                <span>₹{{ number_format($order->shipping_charge, 2) }}</span>
                                            </div>
                                            <div class="mb-2 d-flex justify-content-between">
                                                <span class="text-muted">Tax (GST)</span>
                                                <span>₹{{ number_format($order->tax_amount, 2) }}</span>
                                            </div>
                                            <hr>
                                            <div class="mb-2 d-flex justify-content-between">
                                                <strong>Total</strong>
                                                <strong class="text-primary">₹{{ number_format($order->total_amount, 2) }}</strong>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3 payment-info">
                                            <h6>Payment Information</h6>
                                            <p class="mb-1"><strong>Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                                            <p class="mb-1"><strong>Status:</strong> 
                                                @if($order->payment_status == 'paid')
                                                    <span class="text-success">Paid</span>
                                                @else
                                                    <span class="text-warning">{{ ucfirst($order->payment_status) }}</span>
                                                @endif
                                            </p>
                                            @if($order->payment_id)
                                            <p class="mb-0"><strong>Transaction ID:</strong> {{ $order->payment_id }}</p>
                                            @endif
                                        </div>
                                        
                                        @if($order->paid_at)
                                        <div class="payment-date">
                                            <p class="mb-0"><strong>Paid on:</strong> {{ $order->paid_at->format('d M Y, h:i A') }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Shipping Address -->
                            <div class="mb-4 shipping-address">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Shipping Address</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-1"><strong>{{ $order->shipping_name }}</strong></p>
                                        <p class="mb-1">{{ $order->shipping_address }}</p>
                                        <p class="mb-1">{{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_zip }}</p>
                                        <p class="mb-1"><i class="fas fa-phone"></i> {{ $order->shipping_phone }}</p>
                                        <p class="mb-0"><i class="fas fa-envelope"></i> {{ $order->shipping_email }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Order Timeline -->
                            <div class="order-timeline">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Order Timeline</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="timeline">
                                            <div class="timeline-item {{ $order->created_at ? 'active' : '' }}">
                                                <div class="timeline-marker"></div>
                                                <div class="timeline-content">
                                                    <h6 class="mb-1">Order Placed</h6>
                                                    <p class="mb-0 text-muted">{{ $order->created_at->format('d M, h:i A') }}</p>
                                                </div>
                                            </div>
                                            
                                            <div class="timeline-item {{ in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']) ? 'active' : '' }}">
                                                <div class="timeline-marker"></div>
                                                <div class="timeline-content">
                                                    <h6 class="mb-1">Order Confirmed</h6>
                                                    @if(in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']))
                                                    <p class="mb-0 text-muted">Confirmed by seller</p>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="timeline-item {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'active' : '' }}">
                                                <div class="timeline-marker"></div>
                                                <div class="timeline-content">
                                                    <h6 class="mb-1">Processing</h6>
                                                    @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                                                    <p class="mb-0 text-muted">Preparing for shipment</p>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="timeline-item {{ in_array($order->status, ['shipped', 'delivered']) ? 'active' : '' }}">
                                                <div class="timeline-marker"></div>
                                                <div class="timeline-content">
                                                    <h6 class="mb-1">Shipped</h6>
                                                    @if(in_array($order->status, ['shipped', 'delivered']))
                                                    <p class="mb-0 text-muted">On the way to delivery</p>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="timeline-item {{ $order->status == 'delivered' ? 'active' : '' }}">
                                                <div class="timeline-marker"></div>
                                                <div class="timeline-content">
                                                    <h6 class="mb-1">Delivered</h6>
                                                    @if($order->status == 'delivered')
                                                    <p class="mb-0 text-muted">Delivered successfully</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Need Help Section -->
                    <div class="mt-4 need-help">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-headset fa-2x text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">Need help with your order?</h5>
                                        <p class="mb-2">Contact our customer support for assistance</p>
                                        <div class="contact-options">
                                            <a href="mailto:support@example.com" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-envelope"></i> Email Support
                                            </a>
                                            <a href="tel:18001234567" class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-phone"></i> Call 1800-123-4567
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
    </div>
</section>
<!--============================
    ORDER DETAILS END
=============================-->

<x-footer />

@push('styles')
<style>
    .badge {
        font-size: 0.875em;
        font-weight: 500;
        padding: 0.5em 1em;
    }
    
    .action-buttons .btn-group {
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .action-buttons .btn {
        border-radius: 5px;
    }
    
    .order-items .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .order-items .table td {
        vertical-align: middle;
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 0;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #e9ecef;
        border: 3px solid white;
    }
    
    .timeline-item.active .timeline-marker {
        background-color: #007bff;
    }
    
    .timeline-content h6 {
        margin-bottom: 5px;
        font-weight: 600;
    }
    
    .order-summary .card-body {
        padding: 1.5rem;
    }
    
    .order-summary hr {
        margin: 1rem 0;
    }
    
    .shipping-address .card-body {
        line-height: 1.6;
    }
    
    .contact-options {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    @media (max-width: 768px) {
        .action-buttons .btn-group {
            flex-direction: column;
            align-items: stretch;
        }
        
        .action-buttons .btn,
        .action-buttons form {
            width: 100%;
        }
        
        .timeline {
            padding-left: 20px;
        }
        
        .timeline::before {
            left: 5px;
        }
        
        .timeline-marker {
            left: -20px;
            width: 15px;
            height: 15px;
        }
    }
</style>
@endpush