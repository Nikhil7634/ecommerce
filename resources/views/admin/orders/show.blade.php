<!--start header-->
@include('admin.layouts.header')
<!--end top header-->

@include('admin.layouts.navbar')

<!--start sidebar-->
@include('admin.layouts.aside')

<!-- main content start -->
<div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
        <h2>Order Details</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                <li class="breadcrumb-item ">Order #{{ $order->order_number }}</li>
            </ol>
        </nav>
        <div class="gap-2 mt-2 d-flex">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fa-light fa-arrow-left me-2"></i>Back to Orders
            </a>
            <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                <i class="fa-light fa-print me-2"></i>Print
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-light fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-light fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Order Status Banner -->
    <div class="mb-4 row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="flex-wrap gap-3 d-flex align-items-center justify-content-between">
                        <div class="gap-3 d-flex align-items-center">
                            <div class="status-icon">
                                @php
                                    $statusIcons = [
                                        'pending' => 'fa-clock',
                                        'processing' => 'fa-gear',
                                        'confirmed' => 'fa-check-circle',
                                        'shipped' => 'fa-truck',
                                        'delivered' => 'fa-circle-check',
                                        'cancelled' => 'fa-circle-xmark',
                                        'payment_failed' => 'fa-exclamation-circle',
                                    ];
                                    $icon = $statusIcons[$order->status] ?? 'fa-circle-info';
                                @endphp
                                <div class="rounded-circle bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'primary') }} bg-opacity-10 p-3">
                                    <i class="fa-light {{ $icon }} fa-2x text-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'primary') }}"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="mb-1">Order #{{ $order->order_number }}</h4>
                                <p class="mb-0 text-muted">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                            </div>
                        </div>
                        <div class="gap-2 d-flex">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-warning',
                                    'processing' => 'bg-info',
                                    'confirmed' => 'bg-primary',
                                    'shipped' => 'bg-info',
                                    'delivered' => 'bg-success',
                                    'cancelled' => 'bg-danger',
                                    'payment_failed' => 'bg-danger',
                                ];
                                $color = $statusColors[$order->status] ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $color }} p-3 fs-6">
                                <i class="fa-light {{ $icon }} me-2"></i>{{ ucfirst($order->status) }}
                            </span>
                            @if($order->payment_status == 'paid')
                                <span class="p-3 badge bg-success fs-6">
                                    <i class="fa-light fa-circle-check me-2"></i>Paid
                                </span>
                            @elseif($order->payment_status == 'pending')
                                <span class="p-3 badge bg-warning fs-6">
                                    <i class="fa-light fa-clock me-2"></i>Payment Pending
                                </span>
                            @else
                                <span class="p-3 badge bg-danger fs-6">
                                    <i class="fa-light fa-circle-xmark me-2"></i>{{ ucfirst($order->payment_status) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Summary Cards -->
    <div class="mb-4 row">
        <div class="col-md-3">
            <div class="panel">
                <div class="text-center panel-body">
                    <h5 class="mb-2 text-primary">₹{{ number_format($order->total_amount, 2) }}</h5>
                    <p class="mb-0 text-muted">Total Amount</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel">
                <div class="text-center panel-body">
                    <h5 class="mb-2 text-success">{{ $order->items->count() }}</h5>
                    <p class="mb-0 text-muted">Total Items</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel">
                <div class="text-center panel-body">
                    <h5 class="mb-2 text-info">{{ $order->items->sum('quantity') }}</h5>
                    <p class="mb-0 text-muted">Total Quantity</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel">
                <div class="text-center panel-body">
                    <h5 class="mb-2 text-warning">{{ ucfirst($order->payment_method) }}</h5>
                    <p class="mb-0 text-muted">Payment Method</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Information & Order Timeline -->
    <div class="mb-4 row">
        <div class="col-md-6">
            <div class="panel h-100">
                <div class="panel-header">
                    <h5>Customer Information</h5>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tr>
                            <th style="width: 150px;">Name:</th>
                            <td>{{ $order->shipping_name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><a href="mailto:{{ $order->shipping_email }}">{{ $order->shipping_email }}</a></td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td><a href="tel:{{ $order->shipping_phone }}">{{ $order->shipping_phone }}</a></td>
                        </tr>
                        @if($order->user)
                        <tr>
                            <th>User ID:</th>
                            <td>#{{ $order->user->id }}</td>
                        </tr>
                        <tr>
                            <th>Member Since:</th>
                            <td>{{ $order->user->created_at->format('d M Y') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel h-100">
                <div class="panel-header">
                    <h5>Shipping Address</h5>
                </div>
                <div class="panel-body">
                    <address>
                        <strong>{{ $order->shipping_name }}</strong><br>
                        {{ $order->shipping_address }}<br>
                        {{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_zip }}<br>
                        <i class="fa-light fa-phone me-2"></i> {{ $order->shipping_phone }}<br>
                        <i class="fa-light fa-envelope me-2"></i> {{ $order->shipping_email }}
                    </address>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="mb-4 row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>Order Items</h5>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Seller</th>
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
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            </div>
                                            @endif
                                            <div>
                                                <strong>{{ $item->product_name ?? $item->product->name }}</strong>
                                                @if($item->variant_details)
                                                <br>
                                                <small class="text-muted">
                                                    @php
                                                        $variants = json_decode($item->variant_details, true);
                                                        if(is_array($variants)) {
                                                            foreach($variants as $key => $value) {
                                                                echo ucfirst($key) . ': ' . $value . ' ';
                                                            }
                                                        }
                                                    @endphp
                                                </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->product && $item->product->seller)
                                            {{ $item->product->seller->name }}
                                            @if($item->product->seller->business_name)
                                                <br><small>{{ $item->product->seller->business_name }}</small>
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>₹{{ number_format($item->unit_price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td><strong>₹{{ number_format($item->total_price, 2) }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Subtotal:</th>
                                    <td>₹{{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end">Shipping:</th>
                                    <td>₹{{ number_format($order->shipping_charge, 2) }}</td>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end">Tax (GST):</th>
                                    <td>₹{{ number_format($order->tax_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end">Total:</th>
                                    <td><strong>₹{{ number_format($order->total_amount, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Information & Tracking -->
    <div class="mb-4 row">
        <div class="col-md-6">
            <div class="panel">
                <div class="panel-header">
                    <h5>Payment Information</h5>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tr>
                            <th style="width: 150px;">Payment Method:</th>
                            <td>{{ ucfirst($order->payment_method) }}</td>
                        </tr>
                        <tr>
                            <th>Payment Status:</th>
                            <td>
                                @if($order->payment_status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($order->payment_status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">{{ ucfirst($order->payment_status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @if($order->payment_id)
                        <tr>
                            <th>Transaction ID:</th>
                            <td><code>{{ $order->payment_id }}</code></td>
                        </tr>
                        @endif
                        @if($order->razorpay_order_id)
                        <tr>
                            <th>Razorpay Order ID:</th>
                            <td><code>{{ $order->razorpay_order_id }}</code></td>
                        </tr>
                        @endif
                        @if($order->paid_at)
                        <tr>
                            <th>Paid At:</th>
                            <td>{{ $order->paid_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel">
                <div class="panel-header">
                    <h5>Tracking Information</h5>
                </div>
                <div class="panel-body">
                    @php
                        $trackingInfo = json_decode($order->tracking_info ?? '{}', true);
                    @endphp
                    <table class="table">
                        <tr>
                            <th style="width: 150px;">Tracking Number:</th>
                            <td>
                                @if(!empty($trackingInfo['number']))
                                    {{ $trackingInfo['number'] }}
                                @else
                                    <span class="text-muted">Not available</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tracking URL:</th>
                            <td>
                                @if(!empty($trackingInfo['url']))
                                    <a href="{{ $trackingInfo['url'] }}" target="_blank">Track Package <i class="fa-light fa-external-link ms-1"></i></a>
                                @else
                                    <span class="text-muted">Not available</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>
                                @if(!empty($trackingInfo['updated_at']))
                                    {{ Carbon\Carbon::parse($trackingInfo['updated_at'])->format('d M Y, h:i A') }}
                                @else
                                    <span class="text-muted">Not available</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Form -->
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>Update Order Status</h5>
                </div>
                <div class="panel-body">
                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tracking Number</label>
                            <input type="text" name="tracking_number" class="form-control" value="{{ $trackingInfo['number'] ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tracking URL</label>
                            <input type="url" name="tracking_url" class="form-control" value="{{ $trackingInfo['url'] ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Notes</label>
                            <input type="text" name="notes" class="form-control" placeholder="Optional notes">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-light fa-save me-2"></i>Update Order Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- main content end -->

@include('admin.layouts.footer')

 
<style>
    @media print {
        .sidebar-wrapper, .navbar, .btn, form, .modal, .dashboard-breadcrumb .btn {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
            padding: 20px !important;
        }
        .panel {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }
    }
</style>
 