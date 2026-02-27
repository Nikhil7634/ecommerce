<x-header 
    title="My Orders - {{ config('app.name', 'eCommerce') }}"
    description="View and manage your orders"
    keywords="orders, purchase history, tracking"
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
                        <h1>My Orders</h1>
                        <ul>
                            <li><a href="{{ route('home') }}"><i class="fal fa-home-lg"></i> Home</a></li>
                            <li><a href="{{ route('buyer.dashboard') }}">Dashboard</a></li>
                            <li class="active">Orders</li>
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
    ORDERS PAGE START
=============================-->
<section class="dashboard mb_100">
    <div class="container">
        <div class="row">
            <x-aside />
            <div class="col-lg-9">
                <div class="dashboard_content mt_100">
                    <div class="mb-4 dashboard_heading">
                        <h3>Order History</h3>
                        <p class="text-muted">View and manage all your orders</p>
                    </div>
                    
                    <!-- Order Filters -->
                    <div class="mb-4 order-filters">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="flex-wrap btn-group" role="group">
                                    <a href="{{ route('buyer.orders') }}?status=all" class="btn btn-outline-secondary {{ request('status') == 'all' || !request('status') ? 'active' : '' }}">
                                        All Orders ({{ $totalOrders ?? 0 }})
                                    </a>
                                    <a href="{{ route('buyer.orders') }}?status=pending" class="btn btn-outline-secondary {{ request('status') == 'pending' ? 'selected' : '' }}">
                                        Pending ({{ $pendingCount ?? 0 }})
                                    </a>
                                    <a href="{{ route('buyer.orders') }}?status=confirmed" class="btn btn-outline-secondary {{ request('status') == 'confirmed' ? 'selected' : '' }}">
                                        Confirmed ({{ $confirmedCount ?? 0 }})
                                    </a>
                                    <a href="{{ route('buyer.orders') }}?status=delivered" class="btn btn-outline-secondary {{ request('status') == 'delivered' ? 'selected' : '' }}">
                                        Delivered ({{ $deliveredCount ?? 0 }})
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <form action="{{ route('buyer.orders') }}" method="GET" class="d-flex">
                                    <input type="text" name="search" class="form-control" placeholder="Search by order ID..." value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary ms-2">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    @if($orders->count() > 0)
                    <div class="dashboard_order_table">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    @php
                                        // Check if this order has any unreviewed products
                                        $hasUnreviewed = false;
                                        $reviewableCount = 0;
                                        $firstUnreviewedProductId = null;
                                        
                                        // Only check for delivered or confirmed orders
                                        if(in_array($order->status, ['delivered', 'confirmed'])) {
                                            foreach($order->items as $item) {
                                                $existingReview = \App\Models\Review::where('user_id', Auth::id())
                                                    ->where('product_id', $item->product_id)
                                                    ->first();
                                                if (!$existingReview) {
                                                    $hasUnreviewed = true;
                                                    $reviewableCount++;
                                                    if (!$firstUnreviewedProductId) {
                                                        $firstUnreviewedProductId = $item->product_id;
                                                    }
                                                }
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>#{{ $order->order_number }}</strong>
                                        </td>
                                        <td>
                                            {{ $order->created_at->format('d M Y') }}<br>
                                            <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            {{ $order->items->count() }} item(s)
                                            @if($order->items->count() > 0)
                                                <br><small class="text-muted">{{ $order->items->first()->product_name ?? 'Product' }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>₹{{ number_format($order->total_amount, 2) }}</strong>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = '';
                                                $statusText = ucfirst($order->status);
                                                
                                                if($order->status == 'confirmed') {
                                                    $statusClass = 'complete';
                                                    $statusText = 'Confirmed';
                                                } elseif($order->status == 'pending') {
                                                    $statusClass = 'active';
                                                    $statusText = 'Pending';
                                                } elseif($order->status == 'processing') {
                                                    $statusClass = 'active';
                                                    $statusText = 'Processing';
                                                } elseif($order->status == 'shipped') {
                                                    $statusClass = 'active';
                                                    $statusText = 'Shipped';
                                                } elseif($order->status == 'delivered') {
                                                    $statusClass = 'complete';
                                                    $statusText = 'Delivered';
                                                } elseif($order->status == 'cancelled' || $order->status == 'payment_failed') {
                                                    $statusClass = 'cancel';
                                                    $statusText = 'Cancelled';
                                                }
                                            @endphp
                                            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                                            
                                            @if($order->payment_status == 'paid')
                                            <br><small class="text-success"><i class="fas fa-check-circle"></i> Paid</small>
                                            @elseif($order->payment_status == 'pending')
                                            <br><small class="text-warning"><i class="fas fa-clock"></i> Payment Pending</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('buyer.order.show', $order->id) }}" class="btn btn-sm btn-outline-primary" title="View Order">
                                                    <i class="fas fa-eye"></i> View
                                                </a>

                                                <!-- REVIEW BUTTON - Shows for delivered/confirmed orders with unreviewed products -->
                                                @if($hasUnreviewed)
                                                    @if($reviewableCount == 1)
                                                        <!-- Single product to review - go directly to review form -->
                                                        <a href="{{ route('buyer.reviews.create', ['order' => $order->id, 'product_id' => $firstUnreviewedProductId]) }}" 
                                                           class="btn btn-sm btn-warning" 
                                                           title="Write a Review">
                                                            <i class="fas fa-star"></i> Review
                                                        </a>
                                                    @else
                                                        <!-- Multiple products - go to selection page -->
                                                        <a href="{{ route('buyer.reviews.select-product', $order->id) }}" 
                                                           class="btn btn-sm btn-warning" 
                                                           title="Review Products ({{ $reviewableCount }} items)">
                                                            <i class="fas fa-star"></i> Review ({{ $reviewableCount }})
                                                        </a>
                                                    @endif
                                                @elseif(in_array($order->status, ['delivered', 'confirmed']) && $order->items->count() > 0)
                                                    <!-- All products reviewed - show disabled button with checkmark -->
                                                    <span class="btn btn-sm btn-success" style="cursor: default; opacity: 0.8;" title="All products reviewed">
                                                        <i class="fas fa-check-circle"></i> Reviewed
                                                    </span>
                                                @endif
                                                
                                                @if(in_array($order->status, ['pending', 'processing']))
                                                <form action="{{ route('buyer.order.cancel', $order->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancel Order" onclick="return confirm('Are you sure you want to cancel this order?')">
                                                        <i class="fas fa-times"></i> Cancel
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($orders->hasPages())
                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>
                        @endif
                    </div>
                    @else
                    <!-- No Orders Message -->
                    <div class="py-5 text-center">
                        <div class="empty-state">
                            <i class="mb-4 fas fa-shopping-bag fa-4x text-muted"></i>
                            <h4>No orders found</h4>
                            <p class="mb-4 text-muted">You haven't placed any orders yet.</p>
                            <a href="{{ route('shop') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i> Start Shopping
                            </a>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Order Statistics -->
                    <div class="mt-5 row">
                        <div class="col-md-3 col-6">
                            <div class="p-3 text-center border rounded stat-card">
                                <h4 class="mb-2 text-primary">{{ $totalOrders ?? 0 }}</h4>
                                <p class="mb-0 text-muted">Total Orders</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 text-center border rounded stat-card">
                                <h4 class="mb-2 text-success">{{ $totalSpent ? '₹' . number_format($totalSpent, 2) : '₹0' }}</h4>
                                <p class="mb-0 text-muted">Total Spent</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 text-center border rounded stat-card">
                                <h4 class="mb-2 text-info">{{ $avgOrderValue ? '₹' . number_format($avgOrderValue, 2) : '₹0' }}</h4>
                                <p class="mb-0 text-muted">Avg. Order Value</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 text-center border rounded stat-card">
                                <h4 class="mb-2 text-warning">{{ $lastOrderDate ?? 'N/A' }}</h4>
                                <p class="mb-0 text-muted">Last Order</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Review Summary for Delivered Orders -->
                    @php
                        $totalUnreviewedCount = 0;
                        foreach($orders as $order) {
                            if(in_array($order->status, ['delivered', 'confirmed'])) {
                                foreach($order->items as $item) {
                                    $existingReview = \App\Models\Review::where('user_id', Auth::id())
                                        ->where('product_id', $item->product_id)
                                        ->first();
                                    if (!$existingReview) {
                                        $totalUnreviewedCount++;
                                    }
                                }
                            }
                        }
                    @endphp
                    
                    @if($totalUnreviewedCount > 0)
                    <div class="mt-4 alert alert-warning">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-star fa-2x me-3 text-warning"></i>
                            <div>
                                <strong>You have {{ $totalUnreviewedCount }} product(s) waiting for your review!</strong>
                                <p class="mb-0">Share your experience and help other shoppers make informed decisions.</p>
                            </div>
                            <a href="{{ route('buyer.reviews') }}" class="btn btn-warning ms-auto">
                                Write Reviews <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<!--============================
    ORDERS PAGE END
=============================-->

<x-footer />

@push('styles')
<style>
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }
    
    .status-badge.complete {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .status-badge.active {
        background-color: #dbeafe;
        color: #1e40af;
    }
    
    .status-badge.cancel {
        background-color: #fee2e2;
        color: #991b1b;
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .btn-group .btn.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }
    
    .btn-group .btn {
        margin-bottom: 5px;
    }
    
    .empty-state {
        padding: 40px 20px;
    }
    
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .stat-card {
        transition: transform 0.3s ease;
        height: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .btn-warning {
        color: #000;
    }
    
    .btn-warning:hover {
        color: #000;
        background-color: #ffca2c;
        border-color: #ffc720;
    }
    
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
        color: white;
    }
    
    @media (max-width: 768px) {
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
        }
        
        .btn-group .btn {
            flex: 1 1 auto;
            margin-bottom: 5px;
            font-size: 12px;
            padding: 0.375rem 0.5rem;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn,
        .action-buttons form,
        .action-buttons span {
            width: 100%;
            margin-bottom: 5px;
            text-align: center;
        }
        
        .table {
            font-size: 13px;
        }
        
        .alert .d-flex {
            flex-direction: column;
            text-align: center;
        }
        
        .alert .fa-2x {
            margin-right: 0 !important;
            margin-bottom: 10px;
        }
        
        .alert .btn {
            margin-top: 10px;
            margin-left: 0 !important;
            width: 100%;
        }
    }
</style>
@endpush