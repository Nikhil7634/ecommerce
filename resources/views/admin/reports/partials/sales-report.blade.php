<!-- Summary Cards -->
<div class="mb-4 row">
    <div class="col-md-3">
        <div class="panel">
            <div class="text-center panel-body">
                <h3 class="mb-2 text-primary">{{ number_format($summary['total_orders'] ?? 0) }}</h3>
                <p class="mb-0 text-muted">Total Orders</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel">
            <div class="text-center panel-body">
                <h3 class="mb-2 text-success">₹{{ number_format($summary['total_revenue'] ?? 0, 2) }}</h3>
                <p class="mb-0 text-muted">Total Revenue</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel">
            <div class="text-center panel-body">
                <h3 class="mb-2 text-info">₹{{ number_format($summary['avg_order_value'] ?? 0, 2) }}</h3>
                <p class="mb-0 text-muted">Avg Order Value</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel">
            <div class="text-center panel-body">
                <h3 class="mb-2 text-warning">{{ number_format($summary['total_items_sold'] ?? 0) }}</h3>
                <p class="mb-0 text-muted">Items Sold</p>
            </div>
        </div>
    </div>
</div>

<!-- Orders by Status -->
<div class="mb-4 row">
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">
                <h5>Orders by Status</h5>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Count</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ordersByStatus ?? [] as $status)
                            <tr>
                                <td>
                                    @php
                                        $badgeClass = [
                                            'pending' => 'bg-warning',
                                            'confirmed' => 'bg-primary',
                                            'processing' => 'bg-info',
                                            'shipped' => 'bg-info',
                                            'delivered' => 'bg-success',
                                            'cancelled' => 'bg-danger',
                                            'payment_failed' => 'bg-danger',
                                        ][$status->status ?? ''] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($status->status ?? 'Unknown') }}</span>
                                </td>
                                <td>{{ number_format($status->count ?? 0) }}</td>
                                <td>₹{{ number_format($status->amount ?? 0, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-3 text-center">No order status data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">
                <h5>Payment Methods</h5>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Method</th>
                                <th>Count</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paymentMethods ?? [] as $method)
                            <tr>
                                <td>{{ ucfirst($method->payment_method ?? 'Unknown') }}</td>
                                <td>{{ number_format($method->count ?? 0) }}</td>
                                <td>₹{{ number_format($method->amount ?? 0, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-3 text-center">No payment method data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sales Trends Chart -->
<div class="mb-4 row">
    <div class="col-12">
        <div class="panel">
            <div class="panel-header">
                <h5>Sales Trends</h5>
            </div>
            <div class="panel-body">
                @if(isset($trends) && count($trends) > 0)
                    <canvas id="salesTrendsChart" height="100"></canvas>
                @else
                    <p class="py-4 text-center text-muted">No sales trend data available</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Top Products -->
<div class="row">
    <div class="col-12">
        <div class="panel">
            <div class="panel-header">
                <h5>Top 10 Products</h5>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity Sold</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts ?? [] as $product)
                            <tr>
                                <td>{{ $product->name ?? 'N/A' }}</td>
                                <td>{{ number_format($product->total_quantity ?? 0) }}</td>
                                <td>₹{{ number_format($product->total_revenue ?? 0, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center">No product sales data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesTrendsChart');
        if (ctx) {
            const trends = @json($trends ?? []);
            
            if (trends && trends.length > 0) {
                const labels = trends.map(item => {
                    if (item.date) return item.date;
                    if (item.month) return item.month;
                    return 'Week ' + (item.week || '');
                });
                const orderData = trends.map(item => item.order_count || 0);
                const revenueData = trends.map(item => item.revenue || 0);

                new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Orders',
                            data: orderData,
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            yAxisID: 'y',
                        }, {
                            label: 'Revenue (₹)',
                            data: revenueData,
                            borderColor: '#198754',
                            backgroundColor: 'rgba(25, 135, 84, 0.1)',
                            yAxisID: 'y1',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        },
                        scales: {
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Number of Orders'
                                }
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                title: {
                                    display: true,
                                    text: 'Revenue (₹)'
                                },
                                grid: {
                                    drawOnChartArea: false,
                                },
                            },
                        },
                    }
                });
            }
        }
    });
</script>
 