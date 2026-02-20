@include('seller.layouts.header')
@include('seller.layouts.topnavbar')
@include('seller.layouts.aside')

<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="mb-3 page-breadcrumb d-none d-sm-flex align-items-center">
            <div class="breadcrumb-title pe-3">Orders</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="p-0 mb-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Manage Orders</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('seller.orders.export', request()->query()) }}" class="btn btn-success">
                        <i class="bx bx-download"></i> Export
                    </a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="mb-0 ">Total Orders</p>
                                <h4 class="mb-0">{{ $statistics['total_orders'] }}</h4>
                                <small class="text-success">
                                    <i class="bx bx-up-arrow-alt"></i> {{ $statistics['today_orders'] }} today
                                </small>
                            </div>
                            <div class="widgets-icons bg-light-primary text-primary">
                                <i class="bx bx-cart"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="mb-0 ">Total Revenue</p>
                                <h4 class="mb-0">₹{{ number_format($statistics['total_revenue'], 2) }}</h4>
                                <small class="text-success">
                                    <i class="bx bx-up-arrow-alt"></i> ₹{{ number_format($statistics['today_revenue'], 2) }} today
                                </small>
                            </div>
                            <div class="widgets-icons bg-light-success text-success">
                                <i class="bx bx-rupee"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="mb-0 ">Pending Orders</p>
                                <h4 class="mb-0">{{ $statistics['pending_orders'] }}</h4>
                                <small class="text-warning">
                                    <i class="bx bx-time"></i> Need attention
                                </small>
                            </div>
                            <div class="widgets-icons bg-light-warning text-warning">
                                <i class="bx bx-time"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="mb-0 ">Avg. Order Value</p>
                                <h4 class="mb-0">₹{{ number_format($statistics['avg_order_value'], 2) }}</h4>
                                <small class="text-info">
                                    <i class="bx bx-line-chart"></i> {{ $statistics['month_orders'] }} orders this month
                                </small>
                            </div>
                            <div class="widgets-icons bg-light-info text-info">
                                <i class="bx bx-line-chart"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-3 card radius-10">
            <div class="card-body">
                <form action="{{ route('seller.orders') }}" method="GET" id="filter-form">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>All Orders ({{ $statusCounts['all'] }})</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending ({{ $statusCounts['pending'] }})</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing ({{ $statusCounts['processing'] }})</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped ({{ $statusCounts['shipped'] }})</option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered ({{ $statusCounts['delivered'] }})</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled ({{ $statusCounts['cancelled'] }})</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">From Date</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">To Date</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Order #, Customer, Product" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-filter-alt"></i> Apply
                            </button>
                            <a href="{{ route('seller.orders') }}" class="btn btn-secondary">
                                <i class="bx bx-reset"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card radius-10">
            <div class="card-body">
                <div class="mb-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Order List</h5>
                    <div class="gap-3 d-flex align-items-center">
                        <span class="">Total {{ $orderItems->total() }} orders</span>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Bulk Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="bulkUpdate('processing')">Mark as Processing</a></li>
                                <li><a class="dropdown-item" href="#" onclick="bulkUpdate('shipped')">Mark as Shipped</a></li>
                                <li><a class="dropdown-item" href="#" onclick="bulkUpdate('delivered')">Mark as Delivered</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="bulkUpdate('cancelled')">Mark as Cancelled</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <form id="bulk-form" action="{{ route('seller.orders.bulk-update') }}" method="POST">
                        @csrf
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="40">
                                        <input type="checkbox" id="select-all" class="form-check-input">
                                    </th>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orderItems as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="order_ids[]" value="{{ $item->order_id }}" class="form-check-input order-checkbox">
                                    </td>
                                    <td>
                                        <a href="{{ route('seller.orders.show', $item->order_id) }}" class="fw-bold text-primary">
                                            #{{ $item->order->order_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <div>{{ $item->created_at->format('d M Y') }}</div>
                                        <small class="">{{ $item->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $item->order->shipping_name }}</h6>
                                                <small class="">{{ $item->order->shipping_phone }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->images->first())
                                            <div class="flex-shrink-0 me-2">
                                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                                     alt="" width="40" height="40" class="rounded" style="object-fit: cover;">
                                            </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $item->product_name ?? $item->product->name }}</h6>
                                                @if($item->variant_details)
                                                <small class="">
                                                    @php
                                                        $variants = json_decode($item->variant_details, true);
                                                        if(is_array($variants)) {
                                                            echo implode(' | ', array_map(function($v, $k) {
                                                                return ucfirst($k) . ': ' . $v;
                                                            }, $variants, array_keys($variants)));
                                                        }
                                                    @endphp
                                                </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>
                                        <strong>₹{{ number_format($item->total_price, 2) }}</strong>
                                    </td>
                                    <td>
                                        @php
                                            $status = $item->order->status;
                                            $badgeClass = [
                                                'pending' => 'bg-warning text-dark',
                                                'processing' => 'bg-info',
                                                'shipped' => 'bg-primary',
                                                'delivered' => 'bg-success',
                                                'cancelled' => 'bg-danger',
                                                'confirmed' => 'bg-success',
                                            ][$status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $paymentStatus = $item->order->payment_status;
                                            $paymentClass = [
                                                'paid' => 'bg-success',
                                                'pending' => 'bg-warning text-dark',
                                                'failed' => 'bg-danger',
                                            ][$paymentStatus] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $paymentClass }}">
                                            {{ ucfirst($paymentStatus) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="gap-2 d-flex">
                                            <a href="{{ route('seller.orders.show', $item->order_id) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="View Details">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-success" 
                                                    title="Update Status"
                                                    onclick="updateStatus('{{ $item->order_id }}', '{{ $item->order->status }}')">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="py-5 text-center">
                                        <img src="{{ asset('seller-assets/assets/images/no-orders.png') }}" alt="No orders" width="120" class="mb-3">
                                        <h5 class="">No orders found</h5>
                                        <p class="mb-0 ">Try adjusting your filters</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </form>
                </div>
                
                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <div class="">
                        Showing {{ $orderItems->firstItem() ?? 0 }} to {{ $orderItems->lastItem() ?? 0 }} of {{ $orderItems->total() }} orders
                    </div>
                    <div>
                        {{ $orderItems->appends(request()->query())->links() }}
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
                    <input type="hidden" name="order_id" id="modal_order_id">
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
    // Select all checkbox
    document.getElementById('select-all')?.addEventListener('change', function() {
        document.querySelectorAll('.order-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });

    // Bulk update function
    function bulkUpdate(status) {
        const selected = document.querySelectorAll('.order-checkbox:checked');
        if (selected.length === 0) {
            alert('Please select at least one order.');
            return;
        }
        
        if (confirm(`Are you sure you want to mark ${selected.length} order(s) as ${status}?`)) {
            const form = document.getElementById('bulk-form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'status';
            input.value = status;
            form.appendChild(input);
            form.submit();
        }
    }

    // Update status function
    function updateStatus(orderId, currentStatus) {
        document.getElementById('modal_order_id').value = orderId;
        document.getElementById('modal_status').value = currentStatus;
        
        // Show/hide tracking section based on status
        const statusSelect = document.getElementById('modal_status');
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
        
        const form = document.getElementById('statusForm');
        form.action = `/seller/orders/${orderId}/status`;
        
        new bootstrap.Modal(document.getElementById('statusModal')).show();
    }

    // Auto-submit filters on change for status dropdown
    document.querySelector('select[name="status"]')?.addEventListener('change', function() {
        document.getElementById('filter-form').submit();
    });

    // Date validation
    document.querySelector('input[name="date_from"]')?.addEventListener('change', function() {
        document.querySelector('input[name="date_to"]').min = this.value;
    });
    
    document.querySelector('input[name="date_to"]')?.addEventListener('change', function() {
        document.querySelector('input[name="date_from"]').max = this.value;
    });
</script>

 
@endpush