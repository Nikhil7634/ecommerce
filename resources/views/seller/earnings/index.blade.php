@include('seller.layouts.header')
@include('seller.layouts.topnavbar')
@include('seller.layouts.aside')

<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="mb-3 page-breadcrumb d-none d-sm-flex align-items-center">
            <div class="breadcrumb-title pe-3">Earnings</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="p-0 mb-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Earnings Dashboard</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('seller.earnings.withdrawal') }}" class="btn btn-success">
                    <i class="bx bx-money"></i> Request Withdrawal
                </a>
            </div>
        </div>
        <!--end breadcrumb-->

        <!-- Balance Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="overflow-hidden card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="mb-0 ">Available Balance</p>
                                <h3 class="mb-0 text-success">₹{{ number_format($availableBalance, 2) }}</h3>
                                <small class="">Ready to withdraw</small>
                            </div>
                            <div class="widgets-icons bg-light-success text-success">
                                <i class="bx bx-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="overflow-hidden card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="mb-0 ">Pending Clearance</p>
                                <h3 class="mb-0 text-warning">₹{{ number_format($statistics['pending_earnings'], 2) }}</h3>
                                <small class="">Will be available soon</small>
                            </div>
                            <div class="widgets-icons bg-light-warning text-warning">
                                <i class="bx bx-time"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="overflow-hidden card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="mb-0 ">Lifetime Earnings</p>
                                <h3 class="mb-0 text-primary">₹{{ number_format($lifetimeEarnings, 2) }}</h3>
                                <small class="">Total all time</small>
                            </div>
                            <div class="widgets-icons bg-light-primary text-primary">
                                <i class="bx bx-trending-up"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="overflow-hidden card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="mb-0 ">Total Withdrawn</p>
                                <h3 class="mb-0 text-info">₹{{ number_format($totalWithdrawn, 2) }}</h3>
                                <small class="">Successfully withdrawn</small>
                            </div>
                            <div class="widgets-icons bg-light-info text-info">
                                <i class="bx bx-history"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Row -->
        <div class="row">
            <div class="col-12 col-xl-8">
                <div class="card radius-10">
                    <div class="bg-transparent card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Earnings Overview</h5>
                            <div class="gap-2 d-flex">
                                <select class="form-select form-select-sm" style="width: auto;" id="year-select">
                                    @for($y = now()->year; $y >= now()->year - 2; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="earningsChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-xl-4">
                <div class="card radius-10">
                    <div class="bg-transparent card-header">
                        <h5 class="mb-0">Today's Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="px-0 list-group-item d-flex justify-content-between align-items-center">
                                <span>Sales Today</span>
                                <strong class="text-primary">₹{{ number_format($statistics['today_sales'], 2) }}</strong>
                            </div>
                            <div class="px-0 list-group-item d-flex justify-content-between align-items-center">
                                <span>Net Earnings Today</span>
                                <strong class="text-success">₹{{ number_format($statistics['today_net'], 2) }}</strong>
                            </div>
                            <div class="px-0 list-group-item d-flex justify-content-between align-items-center">
                                <span>Orders Today</span>
                                <strong>{{ $statistics['today_orders'] }}</strong>
                            </div>
                            <div class="px-0 list-group-item d-flex justify-content-between align-items-center">
                                <span>vs Yesterday</span>
                                @php
                                    $change = $statistics['yesterday_sales'] > 0 
                                        ? round((($statistics['today_sales'] - $statistics['yesterday_sales']) / $statistics['yesterday_sales']) * 100, 1)
                                        : 100;
                                @endphp
                                <strong class="{{ $change >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $change >= 0 ? '+' : '' }}{{ $change }}%
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3 card radius-10">
                    <div class="bg-transparent card-header">
                        <h5 class="mb-0">This Month</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="px-0 list-group-item d-flex justify-content-between align-items-center">
                                <span>Sales</span>
                                <strong class="text-primary">₹{{ number_format($statistics['month_sales'], 2) }}</strong>
                            </div>
                            <div class="px-0 list-group-item d-flex justify-content-between align-items-center">
                                <span>Net Earnings</span>
                                <strong class="text-success">₹{{ number_format($statistics['month_net'], 2) }}</strong>
                            </div>
                            <div class="px-0 list-group-item d-flex justify-content-between align-items-center">
                                <span>Orders</span>
                                <strong>{{ $statistics['month_orders'] }}</strong>
                            </div>
                            <div class="px-0 list-group-item d-flex justify-content-between align-items-center">
                                <span>Commission Rate</span>
                                <strong>{{ $commissionRate }}%</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Earnings and Top Products -->
        <div class="row">
            <div class="col-xl-8">
                <div class="card radius-10">
                    <div class="bg-transparent card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Recent Earnings</h5>
                            <a href="{{ route('seller.earnings.report') }}" class="btn btn-sm btn-primary">View Full Report</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Order #</th>
                                        <th>Product</th>
                                        <th>Amount</th>
                                        <th>Commission</th>
                                        <th>Net</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentEarnings as $earning)
                                    <tr>
                                        <td>{{ $earning->created_at->format('d M Y') }}</td>
                                        <td>
                                            @if($earning->order)
                                            <a href="{{ route('seller.orders.show', $earning->order_id) }}" class="text-primary">
                                                #{{ $earning->order->order_number }}
                                            </a>
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>
                                            @if($earning->orderItem && $earning->orderItem->product)
                                            {{ $earning->orderItem->product->name }}
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>₹{{ number_format($earning->amount, 2) }}</td>
                                        <td class="text-danger">-₹{{ number_format($earning->commission, 2) }}</td>
                                        <td class="text-success">₹{{ number_format($earning->net_amount, 2) }}</td>
                                        <td>
                                            @php
                                                $badgeClass = [
                                                    'pending' => 'bg-warning text-dark',
                                                    'available' => 'bg-success',
                                                    'withdrawn' => 'bg-secondary',
                                                ][$earning->status] ?? 'bg-secondary';
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ ucfirst($earning->status) }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="py-4 text-center">
                                            <i class="mb-2 bx bx-data fs-3 d-block"></i>
                                            <p class="mb-0 ">No earnings yet</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4">
                <div class="card radius-10">
                    <div class="bg-transparent card-header">
                        <h5 class="mb-0">Top Products</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @forelse($topProducts as $product)
                            <div class="px-0 list-group-item d-flex align-items-center">
                                @if($product->product && $product->product->images->first())
                                <img src="{{ asset('storage/' . $product->product->images->first()->image_path) }}" 
                                     alt="" width="40" height="40" class="rounded me-3" style="object-fit: cover;">
                                @else
                                <div class="rounded bg-light me-3" style="width: 40px; height: 40px;"></div>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $product->product->name ?? 'Product' }}</h6>
                                    <small class="">{{ $product->total_quantity }} units sold</small>
                                </div>
                                <div class="text-end">
                                    <strong class="text-primary">₹{{ number_format($product->total_revenue, 2) }}</strong>
                                </div>
                            </div>
                            @empty
                            <div class="py-4 text-center">
                                <p class="mb-0 ">No products sold yet</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <div class="mt-3 card radius-10">
                    <div class="bg-transparent card-header">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="gap-2 d-grid">
                            <a href="{{ route('seller.earnings.withdrawal') }}" class="btn btn-success">
                                <i class="bx bx-money"></i> Request Withdrawal
                            </a>
                            <a href="{{ route('seller.earnings.withdrawals') }}" class="btn btn-outline-primary">
                                <i class="bx bx-history"></i> View Withdrawal History
                            </a>
                            <a href="{{ route('seller.earnings.report') }}" class="btn btn-outline-info">
                                <i class="bx bx-file"></i> Generate Report
                            </a>
                            <a href="{{ route('seller.earnings.export') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-download"></i> Export Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('seller.layouts.footer')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Earnings Chart
        const ctx = document.getElementById('earningsChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($monthlyEarnings, 'month')) !!},
                datasets: [{
                    label: 'Sales (₹)',
                    data: {!! json_encode(array_column($monthlyEarnings, 'sales')) !!},
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Net Earnings (₹)',
                    data: {!! json_encode(array_column($monthlyEarnings, 'net')) !!},
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += '₹' + context.raw.toFixed(2);
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + value;
                            }
                        }
                    }
                }
            }
        });
        
        // Year selector change handler
        document.getElementById('year-select').addEventListener('change', function() {
            window.location.href = '{{ route("seller.earnings") }}?year=' + this.value;
        });
    });
</script>

<style>
    .widgets-icons {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 24px;
    }
    
    .bg-light-primary {
        background-color: rgba(13, 110, 253, 0.1);
    }
    
    .bg-light-success {
        background-color: rgba(25, 135, 84, 0.1);
    }
    
    .bg-light-warning {
        background-color: rgba(255, 193, 7, 0.1);
    }
    
    .bg-light-info {
        background-color: rgba(13, 202, 240, 0.1);
    }
    
    .list-group-item {
        border: none;
        padding: 12px 0;
    }
    
    .table > :not(caption) > * > * {
        vertical-align: middle;
    }
    
    .badge {
        padding: 5px 10px;
        font-weight: 500;
    }
</style>
@endpush