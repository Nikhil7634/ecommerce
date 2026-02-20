@include('seller.layouts.header')
@include('seller.layouts.topnavbar')
@include('seller.layouts.aside')

<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="mb-3 page-breadcrumb d-none d-sm-flex align-items-center">
            <div class="breadcrumb-title pe-3">Order Details</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="p-0 mb-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('seller.orders') }}">Orders</a></li>
                        <li class="breadcrumb-item active" aria-current="page">#{{ $order->order_number }}</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="bx bx-printer"></i> Print
                </button>
            </div>
        </div>

        <!-- Order Header -->
        <div class="row">
            <div class="col-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="flex-wrap d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1">Order #{{ $order->order_number }}</h4>
                                <p class="mb-0 ">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                            </div>
                            <div class="gap-3 mt-2 d-flex align-items-center mt-md-0">
                                @php
                                    $status = $order->status;
                                    $badgeClass = [
                                        'pending' => 'bg-warning text-dark',
                                        'processing' => 'bg-info',
                                        'shipped' => 'bg-primary',
                                        'delivered' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                        'confirmed' => 'bg-success',
                                    ][$status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $badgeClass }} fs-6 px-3 py-2">{{ ucfirst($status) }}</span>
                                
                                <button type="button" class="btn btn-outline-success" 
                                        onclick="updateStatus('{{ $order->id }}', '{{ $order->status }}')">
                                    <i class="bx bx-edit"></i> Update Status
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Order Items -->
            <div class="col-xl-8">
                <div class="card radius-10">
                    <div class="card-header">
                        <h5 class="mb-0">Order Items</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle">
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
                                                         alt="{{ $item->product_name }}" 
                                                         width="60" height="60" 
                                                         class="rounded-3" 
                                                         style="object-fit: cover;">
                                                </div>
                                                @endif
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $item->product_name ?? $item->product->name }}</h6>
                                                    @if($item->variant_details)
                                                    <small class="">
                                                        @php
                                                            $variants = json_decode($item->variant_details, true);
                                                            if(is_array($variants)) {
                                                                foreach($variants as $key => $value) {
                                                                    echo '<span class="me-2">' . ucfirst($key) . ': ' . $value . '</span>';
                                                                }
                                                            }
                                                        @endphp
                                                    </small>
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

                <!-- Order Timeline -->
                <div class="mt-3 card radius-10">
                    <div class="card-header">
                        <h5 class="mb-0">Order Timeline</h5>
                    </div>
                    <div class="card-body">
                        <div class="order-timeline">
                            <div class="timeline-item {{ $order->created_at ? 'completed' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Order Placed</h6>
                                    <p class="mb-0 ">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>
                            
                            <div class="timeline-item {{ in_array($order->status, ['processing', 'shipped', 'delivered', 'confirmed']) ? 'completed' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Order Confirmed</h6>
                                    @if(in_array($order->status, ['processing', 'shipped', 'delivered', 'confirmed']))
                                    <p class="mb-0 ">{{ $order->updated_at->format('d M Y, h:i A') }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="timeline-item {{ in_array($order->status, ['shipped', 'delivered']) ? 'completed' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Order Shipped</h6>
                                    @if($order->status == 'shipped' || $order->status == 'delivered')
                                    <p class="mb-0 ">{{ $order->updated_at->format('d M Y, h:i A') }}</p>
                                    @if($order->tracking_info)
                                    @php $tracking = json_decode($order->tracking_info, true); @endphp
                                    <p class="mb-0">
                                        <small>Tracking: <a href="{{ $tracking['url'] ?? '#' }}" target="_blank">{{ $tracking['number'] ?? 'N/A' }}</a></small>
                                    </p>
                                    @endif
                                    @endif
                                </div>
                            </div>
                            
                            <div class="timeline-item {{ $order->status == 'delivered' ? 'completed' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Order Delivered</h6>
                                    @if($order->status == 'delivered')
                                    <p class="mb-0 ">{{ $order->updated_at->format('d M Y, h:i A') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary & Customer Info -->
            <div class="col-xl-4">
                <!-- Order Summary -->
                <div class="card radius-10">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="">Subtotal:</span>
                            <strong>₹{{ number_format($order->subtotal, 2) }}</strong>
                        </div>
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="">Shipping:</span>
                            <strong>₹{{ number_format($order->shipping_charge, 2) }}</strong>
                        </div>
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="">Tax:</span>
                            <strong>₹{{ number_format($order->tax_amount, 2) }}</strong>
                        </div>
                        <hr>
                        <div class="mb-3 d-flex justify-content-between">
                            <span class="fw-bold">Total:</span>
                            <h4 class="mb-0 text-primary">₹{{ number_format($order->total_amount, 2) }}</h4>
                        </div>
                        
                        <div class="mt-3 payment-info">
                            <h6 class="mb-2">Payment Information</h6>
                            <div class="mb-1 d-flex justify-content-between">
                                <span class="">Method:</span>
                                <span>{{ ucfirst($order->payment_method) }}</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span class="">Status:</span>
                                @php
                                    $paymentClass = [
                                        'paid' => 'bg-success',
                                        'pending' => 'bg-warning text-dark',
                                        'failed' => 'bg-danger',
                                    ][$order->payment_status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $paymentClass }}">{{ ucfirst($order->payment_status) }}</span>
                            </div>
                            @if($order->payment_id)
                            <div class="mb-1 d-flex justify-content-between">
                                <span class="">Transaction ID:</span>
                                <small class="text-break">{{ $order->payment_id }}</small>
                            </div>
                            @endif
                            @if($order->paid_at)
                            <div class="d-flex justify-content-between">
                                <span class="">Paid on:</span>
                                <span>{{ $order->paid_at->format('d M Y') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="mt-3 card radius-10">
                    <div class="card-header">
                        <h5 class="mb-0">Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-center">
                            <div class="flex-shrink-0">
                                @if($order->user && $order->user->avatar)
                                <img src="{{ asset('storage/' . $order->user->avatar) }}" width="50" height="50" class="rounded-circle" alt="">
                                @else
                                <div class="bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <span class="fs-4">{{ substr($order->shipping_name, 0, 1) }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $order->shipping_name }}</h6>
                                <small class="">{{ $order->shipping_email }}</small>
                            </div>
                        </div>
                        
                        <div class="mb-3 contact-info">
                            <h6 class="mb-2">Contact Details</h6>
                            <p class="mb-1"><i class="bx bx-phone me-2"></i> {{ $order->shipping_phone }}</p>
                            <p class="mb-0"><i class="bx bx-envelope me-2"></i> {{ $order->shipping_email }}</p>
                        </div>
                        
                        <div class="shipping-address">
                            <h6 class="mb-2">Shipping Address</h6>
                            <p class="mb-0">
                                {{ $order->shipping_address }}<br>
                                {{ $order->shipping_city }}, {{ $order->shipping_state }}<br>
                                {{ $order->shipping_zip }}
                            </p>
                        </div>
                        
                        @if($order->user && $order->user->orders_count > 1)
                        <div class="pt-3 mt-3 border-top">
                            <span class="badge bg-success">Returning Customer</span>
                            <small class=" ms-2">{{ $order->user->orders_count }} orders</small>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-3 card radius-10">
                    <div class="card-body">
                        <h6 class="mb-3">Actions</h6>
                        <div class="gap-2 d-grid">
                            <button type="button" class="btn btn-outline-success" onclick="updateStatus('{{ $order->id }}', '{{ $order->status }}')">
                                <i class="bx bx-edit"></i> Update Status
                            </button>
                            <a href="#" class="btn btn-outline-primary" onclick="window.print()">
                                <i class="bx bx-printer"></i> Print Invoice
                            </a>
                            <a href="{{ route('seller.orders') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back"></i> Back to Orders
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Update Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Order Status</label>
                        <select name="status" class="form-select" id="modal_status">
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3" id="tracking_section" style="display: none;">
                        <label class="form-label">Tracking Number</label>
                        <input type="text" name="tracking_number" class="form-control" placeholder="Enter tracking number">
                    </div>
                    <div class="mb-3" id="tracking_url_section" style="display: none;">
                        <label class="form-label">Tracking URL</label>
                        <input type="url" name="tracking_url" class="form-control" placeholder="https://...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Add any notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('seller.layouts.footer')

@push('scripts')
<script>
    function updateStatus(orderId, currentStatus) {
        const form = document.getElementById('statusForm');
        form.action = `/seller/orders/${orderId}/status`;
        
        const statusSelect = document.getElementById('modal_status');
        statusSelect.value = currentStatus;
        
        // Show/hide tracking section based on status
        const trackingSection = document.getElementById('tracking_section');
        const trackingUrlSection = document.getElementById('tracking_url_section');
        
        function toggleTracking() {
            if (statusSelect.value === 'shipped') {
                trackingSection.style.display = 'block';
                trackingUrlSection.style.display = 'block';
            } else {
                trackingSection.style.display = 'none';
                trackingUrlSection.style.display = 'none';
            }
        }
        
        statusSelect.addEventListener('change', toggleTracking);
        toggleTracking();
        
        new bootstrap.Modal(document.getElementById('statusModal')).show();
    }
</script>

<style>
    .order-timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .order-timeline::before {
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
        margin-bottom: 25px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 0;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #e9ecef;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #e9ecef;
    }
    
    .timeline-item.completed .timeline-marker {
        background-color: #28a745;
        box-shadow: 0 0 0 2px rgba(40, 167, 69, 0.2);
    }
    
    .timeline-content {
        padding-bottom: 10px;
    }
    
    .timeline-content h6 {
        margin-bottom: 5px;
        font-weight: 600;
    }
    
    .timeline-content p {
        margin-bottom: 0;
        font-size: 13px;
    }
    
    @media print {
        .sidebar-wrapper, .top-header, .btn, .modal, .card-header .btn {
            display: none !important;
        }
        
        .main-wrapper {
            margin-left: 0 !important;
        }
        
        .card {
            border: 1px solid #ddd !important;
        }
    }
</style>
@endpush