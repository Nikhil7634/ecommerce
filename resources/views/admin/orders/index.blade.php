<!--start header-->
@include('admin.layouts.header')
<!--end top header-->

@include('admin.layouts.navbar')

<!--start sidebar-->
@include('admin.layouts.aside')

<!-- main content start -->
<div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
        <h2>Orders Management</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item ">Orders</li>
            </ol>
        </nav>
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

    <!-- Statistics Cards -->
    <div class="mb-4 row">
        <div class="col-md-3">
            <div class="panel">
                <div class="text-center panel-body">
                    <h3 class="mb-2 text-primary">{{ number_format($statistics['total_orders'] ?? 0) }}</h3>
                    <p class="mb-0 text-muted">Total Orders</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel">
                <div class="text-center panel-body">
                    <h3 class="mb-2 text-success">₹{{ number_format($statistics['total_revenue'] ?? 0, 2) }}</h3>
                    <p class="mb-0 text-muted">Total Revenue</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel">
                <div class="text-center panel-body">
                    <h3 class="mb-2 text-warning">{{ number_format($statistics['pending_orders'] ?? 0) }}</h3>
                    <p class="mb-0 text-muted">Pending Orders</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel">
                <div class="text-center panel-body">
                    <h3 class="mb-2 text-info">₹{{ number_format($statistics['avg_order_value'] ?? 0, 2) }}</h3>
                    <p class="mb-0 text-muted">Avg Order Value</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="mb-4 row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-body">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link {{ ($status ?? 'all') == 'all' ? 'active' : '' }}" 
                               href="{{ route('admin.orders.index') }}">
                                All Orders ({{ $statusCounts['all'] ?? 0 }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ ($status ?? '') == 'pending' ? 'active' : '' }}" 
                               href="{{ route('admin.orders.pending') }}">
                                Pending ({{ $statusCounts['pending'] ?? 0 }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ ($status ?? '') == 'processing' ? 'active' : '' }}" 
                               href="{{ route('admin.orders.processing') }}">
                                Processing ({{ $statusCounts['processing'] ?? 0 }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ ($status ?? '') == 'confirmed' ? 'active' : '' }}" 
                               href="{{ route('admin.orders.confirmed') }}">
                                Confirmed ({{ $statusCounts['confirmed'] ?? 0 }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ ($status ?? '') == 'shipped' ? 'active' : '' }}" 
                               href="{{ route('admin.orders.shipped') }}">
                                Shipped ({{ $statusCounts['shipped'] ?? 0 }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ ($status ?? '') == 'delivered' ? 'active' : '' }}" 
                               href="{{ route('admin.orders.delivered') }}">
                                Delivered ({{ $statusCounts['delivered'] ?? 0 }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ ($status ?? '') == 'cancelled' ? 'active' : '' }}" 
                               href="{{ route('admin.orders.cancelled') }}">
                                Cancelled ({{ $statusCounts['cancelled'] ?? 0 }})
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-4 row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>Filter Orders</h5>
                </div>
                <div class="panel-body">
                    <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate ?? now()->startOfMonth()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate ?? now()->endOfMonth()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Order #, Customer, Email, Phone" value="{{ $search ?? '' }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fa-light fa-filter me-2"></i>Apply
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                                <i class="fa-light fa-undo me-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>Orders List</h5>
                    <a href="{{ route('admin.orders.export', request()->all()) }}" class="btn btn-sm btn-success">
                        <i class="fa-light fa-download me-2"></i>Export CSV
                    </a>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders ?? [] as $order)
                                <tr>
                                    <td>
                                        <code>#{{ $order->order_number }}</code>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $order->shipping_name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $order->shipping_email }}</small>
                                            <br>
                                            <small class="text-muted">{{ $order->shipping_phone }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $order->created_at->format('d M Y') }}
                                        <br>
                                        <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $order->items_count ?? 0 }} items</span>
                                    </td>
                                    <td>
                                        <strong>₹{{ number_format($order->total_amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        @if($order->payment_status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($order->payment_status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Failed</span>
                                        @endif
                                        <br>
                                        <small>{{ ucfirst($order->payment_method) }}</small>
                                    </td>
                                    <td>
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
                                        <span class="badge {{ $color }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-box">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                                               class="btn btn-sm btn-icon btn-outline-primary" 
                                               title="View Details">
                                                <i class="fa-light fa-eye"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-icon btn-outline-success" 
                                                    title="Update Status"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#statusModal{{ $order->id }}">
                                                <i class="fa-light fa-pen"></i>
                                            </button>
                                        </div>

                                        <!-- Status Update Modal -->
                                        <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Update Order Status</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
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
                                                            <div class="mb-3">
                                                                <label class="form-label">Tracking Number</label>
                                                                <input type="text" name="tracking_number" class="form-control" value="{{ json_decode($order->tracking_info ?? '{}', true)['number'] ?? '' }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Tracking URL</label>
                                                                <input type="url" name="tracking_url" class="form-control" value="{{ json_decode($order->tracking_info ?? '{}', true)['url'] ?? '' }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Notes</label>
                                                                <textarea name="notes" class="form-control" rows="2"></textarea>
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
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="py-4 text-center">
                                        <i class="mb-3 fa-light fa-shopping-cart fa-3x text-muted"></i>
                                        <h5>No orders found</h5>
                                        <p class="text-muted">Try adjusting your filters</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($orders) && $orders->hasPages())
                    <div class="mt-4">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- main content end -->

@include('admin.layouts.footer')

<style>
    .modal-backdrop.show {
         display: none;
    }
</style>