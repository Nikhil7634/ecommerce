<!-- User Stats Cards -->
<div class="mb-4 row">
    <div class="col-md-3">
        <div class="panel">
            <div class="text-center panel-body">
                <h3 class="mb-2 text-primary">{{ number_format($userStats['total_users'] ?? 0) }}</h3>
                <p class="mb-0 text-muted">Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel">
            <div class="text-center panel-body">
                <h3 class="mb-2 text-success">{{ number_format($userStats['total_buyers'] ?? 0) }}</h3>
                <p class="mb-0 text-muted">Total Buyers</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel">
            <div class="text-center panel-body">
                <h3 class="mb-2 text-info">{{ number_format($userStats['total_sellers'] ?? 0) }}</h3>
                <p class="mb-0 text-muted">Total Sellers</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel">
            <div class="text-center panel-body">
                <h3 class="mb-2 text-warning">{{ number_format($userStats['new_users'] ?? 0) }}</h3>
                <p class="mb-0 text-muted">New Users (Period)</p>
            </div>
        </div>
    </div>
</div>

<!-- Registration Trends Chart -->
<div class="mb-4 row">
    <div class="col-12">
        <div class="panel">
            <div class="panel-header">
                <h5>Registration Trends</h5>
            </div>
            <div class="panel-body">
                <canvas id="registrationChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Seller Stats -->
<div class="mb-4 row">
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">
                <h5>Seller Status</h5>
            </div>
            <div class="panel-body">
                <table class="table">
                    <tr>
                        <td>Active Sellers</td>
                        <td><span class="badge bg-success">{{ number_format($userStats['active_sellers'] ?? 0) }}</span></td>
                    </tr>
                    <tr>
                        <td>Pending Sellers</td>
                        <td><span class="badge bg-warning">{{ number_format($userStats['pending_sellers'] ?? 0) }}</span></td>
                    </tr>
                    <tr>
                        <td>New Sellers (Period)</td>
                        <td><span class="badge bg-info">{{ number_format($userStats['new_sellers'] ?? 0) }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">
                <h5>Buyer Stats</h5>
            </div>
            <div class="panel-body">
                <table class="table">
                    <tr>
                        <td>Total Buyers</td>
                        <td>{{ number_format($userStats['total_buyers'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td>New Buyers (Period)</td>
                        <td>{{ number_format($userStats['new_buyers'] ?? 0) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Top Buyers -->
<div class="mb-4 row">
    <div class="col-12">
        <div class="panel">
            <div class="panel-header">
                <h5>Top Buyers by Spending</h5>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Buyer</th>
                                <th>Email</th>
                                <th>Orders</th>
                                <th>Total Spent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topBuyers ?? [] as $buyer)
                            <tr>
                                <td>{{ $buyer->name }}</td>
                                <td>{{ $buyer->email }}</td>
                                <td>{{ number_format($buyer->order_count ?? 0) }}</td>
                                <td>₹{{ number_format($buyer->total_spent ?? 0, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Sellers -->
<div class="row">
    <div class="col-12">
        <div class="panel">
            <div class="panel-header">
                <h5>Top Sellers by Revenue</h5>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Seller</th>
                                <th>Business Name</th>
                                <th>Orders</th>
                                <th>Items Sold</th>
                                <th>Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topSellers ?? [] as $seller)
                            <tr>
                                <td>{{ $seller->name }}</td>
                                <td>{{ $seller->business_name ?? '-' }}</td>
                                <td>{{ number_format($seller->order_count ?? 0) }}</td>
                                <td>{{ number_format($seller->items_sold ?? 0) }}</td>
                                <td>₹{{ number_format($seller->total_revenue ?? 0, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No data available</td>
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
        // Registration Chart
        const regCtx = document.getElementById('registrationChart').getContext('2d');
        const trends = @json($registrationTrends ?? []);
        
        if (trends.length > 0) {
            new Chart(regCtx, {
                type: 'line',
                data: {
                    labels: trends.map(t => t.date),
                    datasets: [{
                        label: 'Total Registrations',
                        data: trends.map(t => t.total || 0),
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        fill: true
                    }, {
                        label: 'Buyers',
                        data: trends.map(t => t.buyers || 0),
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        fill: true
                    }, {
                        label: 'Sellers',
                        data: trends.map(t => t.sellers || 0),
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        } else {
            regCtx.canvas.parentNode.innerHTML = '<p class="text-center text-muted">No registration data available for this period</p>';
        }
    });
</script>
 