<!--start header-->
@include('admin.layouts.header')
<!--end top header-->

@include('admin.layouts.navbar')

<!--start sidebar-->
@include('admin.layouts.aside')

<!-- main content start -->
<div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
        <h2>Admin Dashboard</h2>
        <div class="input-group dashboard-filter">
            <input type="text" class="form-control" name="basic" id="dashboardFilter" readonly>
            <label for="dashboardFilter" class="input-group-text"><i class="fa-light fa-calendar-days"></i></label>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-25">
        <div class="col-lg-3 col-6 col-xs-12">
            <div class="dashboard-top-box rounded-bottom panel-bg">
                <div class="left">
                    <h3>₹{{ number_format($totalRevenue ?? 0, 2) }}</h3>
                    <p>Total Revenue</p>
                    <a href="{{ route('admin.orders.index') }}">View all orders</a>
                </div>
                <div class="right">
                    <span class="text-{{ $revenueGrowth >= 0 ? 'success' : 'danger' }}">
                        {{ $revenueGrowth >= 0 ? '+' : '' }}{{ number_format($revenueGrowth, 2) }}%
                    </span>
                    <div class="rounded part-icon">
                        <span><i class="fa-light fa-dollar-sign"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 col-xs-12">
            <div class="dashboard-top-box rounded-bottom panel-bg">
                <div class="left">
                    <h3>{{ number_format($totalOrders ?? 0) }}</h3>
                    <p>Total Orders</p>
                    <a href="{{ route('admin.orders.index') }}">View all orders</a>
                </div>
                <div class="right">
                    <span class="text-{{ $orderGrowth >= 0 ? 'success' : 'danger' }}">
                        {{ $orderGrowth >= 0 ? '+' : '' }}{{ number_format($orderGrowth, 2) }}%
                    </span>
                    <div class="rounded part-icon">
                        <span><i class="fa-light fa-bag-shopping"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 col-xs-12">
            <div class="dashboard-top-box rounded-bottom panel-bg">
                <div class="left">
                    <h3>{{ number_format($totalCustomers ?? 0) }}</h3>
                    <p>Total Customers</p>
                    <a href="{{ route('admin.buyers.index') }}">View all customers</a>
                </div>
                <div class="right">
                    <span class="text-{{ $customerGrowth >= 0 ? 'success' : 'danger' }}">
                        {{ $customerGrowth >= 0 ? '+' : '' }}{{ number_format($customerGrowth, 2) }}%
                    </span>
                    <div class="rounded part-icon">
                        <span><i class="fa-light fa-user"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 col-xs-12">
            <div class="dashboard-top-box rounded-bottom panel-bg">
                <div class="left">
                    <h3>{{ number_format($totalSellers ?? 0) }}</h3>
                    <p>Active Sellers</p>
                    <a href="{{ route('admin.sellers.index') }}">Manage sellers</a>
                </div>
                <div class="right">
                    <span class="text-{{ $sellerGrowth >= 0 ? 'success' : 'danger' }}">
                        {{ $sellerGrowth >= 0 ? '+' : '' }}{{ number_format($sellerGrowth, 2) }}%
                    </span>
                    <div class="rounded part-icon">
                        <span><i class="fa-light fa-store"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sales Analytics Chart -->
        <div class="col-xxl-8">
            <div class="panel chart-panel-1">
                <div class="panel-header">
                    <h5>Sales Analytics ({{ $currentYear }})</h5>
                    <div class="btn-box">
                        <button class="btn btn-sm btn-outline-primary" onclick="changeChartPeriod('week')">Week</button>
                        <button class="btn btn-sm btn-outline-primary" onclick="changeChartPeriod('month')">Month</button>
                        <button class="btn btn-sm btn-outline-primary active" onclick="changeChartPeriod('year')">Year</button>
                    </div>
                </div>
                <div class="panel-body">
                    <div id="saleAnalytics" class="chart-dark"></div>
                </div>
            </div>
        </div>

        <!-- Subscription Plans -->
        <div class="col-xxl-4 col-md-6">
            <div class="panel">
                <div class="panel-header">
                    <h5>Subscription Plans</h5>
                    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-sm btn-outline-primary">Manage Plans</a>
                </div>
                <div class="panel-body">
                    <table class="table table-borderless subscription-table">
                        <thead>
                            <tr>
                                <th>Plan Name</th>
                                <th>Price</th>
                                <th>Duration</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscriptionPlans ?? [] as $plan)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $plan->name }}</span>
                                    <br>
                                    <small class="text-muted">Boost: {{ $plan->search_boost }}x</small>
                                </td>
                                <td>₹{{ number_format($plan->price, 2) }}</td>
                                <td>
                                    @php
                                        $durationText = [
                                            '1_month' => '1 Month',
                                            '3_months' => '3 Months',
                                            '6_months' => '6 Months',
                                            '1_year' => '1 Year',
                                            '2_years' => '2 Years',
                                            '3_years' => '3 Years',
                                            '4_years' => '4 Years',
                                        ];
                                    @endphp
                                    {{ $durationText[$plan->duration] ?? $plan->duration }}
                                </td>
                                <td>
                                    @if($plan->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Sellers -->
        <div class="col-xxl-4 col-md-6">
            <div class="panel">
                <div class="panel-header">
                    <h5>Recent Sellers</h5>
                    <a href="{{ route('admin.sellers.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="panel-body">
                    <table class="table table-borderless new-customer-table">
                        <tbody>
                            @forelse($recentSellers ?? [] as $seller)
                            <tr>
                                <td>
                                    <div class="new-customer">
                                        <div class="part-img">
                                            @if($seller->avatar)
                                                <img src="{{ asset('storage/' . $seller->avatar) }}" alt="{{ $seller->name }}">
                                            @else
                                                <img src="{{ asset('admin-assets/assets/images/avatar.png') }}" alt="Avatar">
                                            @endif
                                        </div>
                                        <div class="part-txt">
                                            <p class="customer-name">{{ $seller->name }}</p>
                                            <span>{{ $seller->business_name ?? 'No business name' }}</span>
                                            <br>
                                            <small class="text-muted">Joined: {{ $seller->created_at->format('d M Y') }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($seller->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($seller->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($seller->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center">No sellers found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Buyers -->
        <div class="col-xxl-4 col-md-6">
            <div class="panel">
                <div class="panel-header">
                    <h5>Recent Buyers</h5>
                    <a href="{{ route('admin.buyers.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="panel-body">
                    <table class="table table-borderless new-customer-table">
                        <tbody>
                            @forelse($recentBuyers ?? [] as $buyer)
                            <tr>
                                <td>
                                    <div class="new-customer">
                                        <div class="part-img">
                                            @if($buyer->avatar)
                                                <img src="{{ asset('storage/' . $buyer->avatar) }}" alt="{{ $buyer->name }}">
                                            @else
                                                <img src="{{ asset('admin-assets/assets/images/avatar.png') }}" alt="Avatar">
                                            @endif
                                        </div>
                                        <div class="part-txt">
                                            <p class="customer-name">{{ $buyer->name }}</p>
                                            <span>{{ $buyer->email }}</span>
                                            <br>
                                            <small class="text-muted">Joined: {{ $buyer->created_at->format('d M Y') }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $orderCount = $buyer->orders_count ?? 0;
                                    @endphp
                                    <span class="badge bg-info">{{ $orderCount }} Orders</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center">No buyers found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-xxl-8">
            <div class="panel">
                <div class="panel-header">
                    <h5>Recent Orders</h5>
                    <div id="tableSearch"></div>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary ms-2">View All</a>
                </div>
                <div class="panel-body">
                    <table class="table table-dashed recent-order-table" id="myTable">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Order Date</th>
                                <th>Payment Method</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders ?? [] as $order)
                            <tr>
                                <td>#{{ $order->order_number }}</td>
                                <td>{{ $order->shipping_name }}</td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>{{ ucfirst($order->payment_method) }}</td>
                                <td>₹{{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'pending' => 'bg-info',
                                            'confirmed' => 'bg-primary',
                                            'processing' => 'bg-primary',
                                            'shipped' => 'bg-info',
                                            'delivered' => 'bg-success',
                                            'cancelled' => 'bg-danger',
                                            'payment_failed' => 'bg-danger',
                                        ][$order->status] ?? 'bg-secondary';
                                        
                                        $statusText = [
                                            'pending' => 'Pending',
                                            'confirmed' => 'Confirmed',
                                            'processing' => 'Processing',
                                            'shipped' => 'Shipped',
                                            'delivered' => 'Delivered',
                                            'cancelled' => 'Cancelled',
                                            'payment_failed' => 'Payment Failed',
                                        ][$order->status] ?? ucfirst($order->status);
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td>
                                    <div class="btn-box">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-icon btn-outline-primary">
                                            <i class="fa-light fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No orders found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Status Summary -->
        <div class="col-xxl-4">
            <div class="panel">
                <div class="panel-header">
                    <h5>Order Status Summary</h5>
                </div>
                <div class="panel-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td><span class="badge bg-info">Pending</span></td>
                                <td>{{ number_format($orderStats['pending'] ?? 0) }} orders</td>
                                <td>₹{{ number_format($orderStats['pending_amount'] ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-primary">Confirmed/Processing</span></td>
                                <td>{{ number_format($orderStats['processing'] ?? 0) }} orders</td>
                                <td>₹{{ number_format($orderStats['processing_amount'] ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-info">Shipped</span></td>
                                <td>{{ number_format($orderStats['shipped'] ?? 0) }} orders</td>
                                <td>₹{{ number_format($orderStats['shipped_amount'] ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-success">Delivered</span></td>
                                <td>{{ number_format($orderStats['delivered'] ?? 0) }} orders</td>
                                <td>₹{{ number_format($orderStats['delivered_amount'] ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-danger">Cancelled</span></td>
                                <td>{{ number_format($orderStats['cancelled'] ?? 0) }} orders</td>
                                <td>₹{{ number_format($orderStats['cancelled_amount'] ?? 0, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xxl-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="panel-body">
                    <div class="row g-3">
                         
                        <div class="col-md-4">
                            <a href="{{ route('admin.sellers.pending') }}" class="py-3 btn btn-outline-warning w-100">
                                <i class="mb-2 fa-light fa-user-clock fa-2x d-block"></i>
                                Pending Sellers
                                @if(isset($pendingSellersCount) && $pendingSellersCount > 0)
                                    <span class="badge bg-danger">{{ $pendingSellersCount }}</span>
                                @endif
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('admin.subscriptions.create') }}" class="py-3 btn btn-outline-success w-100">
                                <i class="mb-2 fa-light fa-crown fa-2x d-block"></i>
                                New Subscription Plan
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('admin.reports.index') }}" class="py-3 btn btn-outline-info w-100">
                                <i class="mb-2 fa-light fa-chart-line fa-2x d-block"></i>
                                Generate Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- main content end -->

@include('admin.layouts.footer')

 

 