<!-- Earnings Summary Cards -->
<div class="mb-4 row">
    <div class="col-md-3">
        <div class="panel">
            <div class="text-center panel-body">
                <h3 class="mb-2 text-primary">₹{{ number_format($earningsSummary['total_earnings'] ?? 0, 2) }}</h3>
                <p class="mb-0 text-muted">Total Net Earnings</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel">
            <div class="text-center panel-body">
                <h3 class="mb-2 text-danger">₹{{ number_format($earningsSummary['total_commission'] ?? 0, 2) }}</h3>
                <p class="mb-0 text-muted">Total Commission</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel">
            <div class="text-center panel-body">
                <h3 class="mb-2 text-success">₹{{ number_format($earningsSummary['available_earnings'] ?? 0, 2) }}</h3>
                <p class="mb-0 text-muted">Available</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel">
            <div class="text-center panel-body">
                <h3 class="mb-2 text-warning">₹{{ number_format($earningsSummary['pending_earnings'] ?? 0, 2) }}</h3>
                <p class="mb-0 text-muted">Pending</p>
            </div>
        </div>
    </div>
</div>

<!-- Withdrawals Summary -->
<div class="mb-4 row">
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">
                <h5>Withdrawals Summary</h5>
            </div>
            <div class="panel-body">
                <table class="table">
                    <tr>
                        <td>Total Withdrawn</td>
                        <td class="text-end">₹{{ number_format($withdrawalsSummary['total_withdrawn'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Pending Withdrawals</td>
                        <td class="text-end">₹{{ number_format($withdrawalsSummary['pending_withdrawals'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Fees</td>
                        <td class="text-end">₹{{ number_format($withdrawalsSummary['total_fees'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Number of Withdrawals</td>
                        <td class="text-end">{{ number_format($withdrawalsSummary['withdrawal_count'] ?? 0) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">
                <h5>Earnings Status</h5>
            </div>
            <div class="panel-body">
                @if(($earningsSummary['available_earnings'] ?? 0) > 0 || ($earningsSummary['pending_earnings'] ?? 0) > 0 || ($earningsSummary['withdrawn_earnings'] ?? 0) > 0)
                    <canvas id="earningsStatusChart" height="200"></canvas>
                @else
                    <p class="py-4 text-center text-muted">No earnings data available</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Earnings Trend Chart -->
<div class="mb-4 row">
    <div class="col-12">
        <div class="panel">
            <div class="panel-header">
                <h5>Earnings Trend</h5>
            </div>
            <div class="panel-body">
                @if(isset($earningsTrend) && count($earningsTrend) > 0)
                    <canvas id="earningsTrendChart" height="100"></canvas>
                @else
                    <p class="py-4 text-center text-muted">No earnings trend data available</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Earnings by Seller -->
<div class="row">
    <div class="col-12">
        <div class="panel">
            <div class="panel-header">
                <h5>Earnings by Seller</h5>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Seller</th>
                                <th>Business Name</th>
                                <th>Transactions</th>
                                <th>Gross Earnings</th>
                                <th>Commission</th>
                                <th>Net Earnings</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($earningsBySeller ?? [] as $seller)
                            <tr>
                                <td>{{ $seller->name ?? 'N/A' }}</td>
                                <td>{{ $seller->business_name ?? '-' }}</td>
                                <td>{{ number_format($seller->transaction_count ?? 0) }}</td>
                                <td>₹{{ number_format($seller->gross_earnings ?? 0, 2) }}</td>
                                <td class="text-danger">-₹{{ number_format($seller->total_commission ?? 0, 2) }}</td>
                                <td class="text-success">₹{{ number_format($seller->net_earnings ?? 0, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center">No earnings data available</td>
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
        // Earnings Status Chart
        const statusCtx = document.getElementById('earningsStatusChart');
        if (statusCtx) {
            new Chart(statusCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Available', 'Pending', 'Withdrawn'],
                    datasets: [{
                        data: [
                            {{ $earningsSummary['available_earnings'] ?? 0 }},
                            {{ $earningsSummary['pending_earnings'] ?? 0 }},
                            {{ $earningsSummary['withdrawn_earnings'] ?? 0 }}
                        ],
                        backgroundColor: ['#198754', '#ffc107', '#6c757d']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        }

        // Earnings Trend Chart
        const trendCtx = document.getElementById('earningsTrendChart');
        if (trendCtx) {
            const trends = @json($earningsTrend ?? []);
            
            if (trends && trends.length > 0) {
                new Chart(trendCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: trends.map(t => t.month || ''),
                        datasets: [{
                            label: 'Gross Earnings',
                            data: trends.map(t => t.gross || 0),
                            backgroundColor: '#0d6efd'
                        }, {
                            label: 'Net Earnings',
                            data: trends.map(t => t.net || 0),
                            backgroundColor: '#198754'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        }
                    }
                });
            }
        }
    });
</script>
 