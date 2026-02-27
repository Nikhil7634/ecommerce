<!-- Product Stats Cards -->
<div class="mb-4 row">
    <div class="col-md-3">
        <div class="panel">
            <div class="text-center panel-body">
                <h3 class="mb-2 text-primary">{{ number_format($productStats['total_products'] ?? 0) }}</h3>
                <p class="mb-0 text-muted">Total Products</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel">
            <div class="text-center panel-body">
                <h3 class="mb-2 text-success">{{ number_format($productStats['active_products'] ?? 0) }}</h3>
                <p class="mb-0 text-muted">Active Products</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel">
            <div class="text-center panel-body">
                <h3 class="mb-2 text-danger">{{ number_format($productStats['out_of_stock'] ?? 0) }}</h3>
                <p class="mb-0 text-muted">Out of Stock</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel">
            <div class="text-center panel-body">
                <h3 class="mb-2 text-warning">{{ number_format($productStats['low_stock'] ?? 0) }}</h3>
                <p class="mb-0 text-muted">Low Stock</p>
            </div>
        </div>
    </div>
</div>

<!-- Products by Category Chart -->
<div class="mb-4 row">
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">
                <h5>Products by Category</h5>
            </div>
            <div class="panel-body">
                @if(isset($productsByCategory) && count($productsByCategory) > 0)
                    <canvas id="categoryChart" height="300"></canvas>
                @else
                    <p class="py-4 text-center text-muted">No category data available</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">
                <h5>Products by Category (Table)</h5>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Product Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productsByCategory ?? [] as $category)
                            <tr>
                                <td>{{ $category->name ?? 'N/A' }}</td>
                                <td>{{ number_format($category->product_count ?? 0) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="py-3 text-center">No categories found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Selling Products -->
<div class="mb-4 row">
    <div class="col-12">
        <div class="panel">
            <div class="panel-header">
                <h5>Top Selling Products</h5>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Seller</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Units Sold</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topSelling ?? [] as $product)
                            <tr>
                                <td>{{ $product->name ?? 'N/A' }}</td>
                                <td>{{ $product->seller_name ?? 'N/A' }}</td>
                                <td>₹{{ number_format($product->base_price ?? 0, 2) }}</td>
                                <td>
                                    @if(($product->stock ?? 0) <= 0)
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @elseif(($product->stock ?? 0) < 10)
                                        <span class="badge bg-warning">Low ({{ $product->stock }})</span>
                                    @else
                                        <span class="badge bg-success">{{ $product->stock ?? 0 }}</span>
                                    @endif
                                </td>
                                <td>{{ number_format($product->total_sold ?? 0) }}</td>
                                <td>₹{{ number_format($product->total_revenue ?? 0, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center">No product sales data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Seller Performance -->
<div class="row">
    <div class="col-12">
        <div class="panel">
            <div class="panel-header">
                <h5>Seller Performance</h5>
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
                                <th>Total Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sellerPerformance ?? [] as $seller)
                            <tr>
                                <td>{{ $seller->name ?? 'N/A' }}</td>
                                <td>{{ $seller->business_name ?? '-' }}</td>
                                <td>{{ number_format($seller->order_count ?? 0) }}</td>
                                <td>{{ number_format($seller->items_sold ?? 0) }}</td>
                                <td>₹{{ number_format($seller->total_sales ?? 0, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center">No seller performance data available</td>
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
        // Category Chart
        const categoryCtx = document.getElementById('categoryChart');
        if (categoryCtx) {
            const categories = @json($productsByCategory ?? []);
            
            if (categories && categories.length > 0) {
                new Chart(categoryCtx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: categories.map(c => c.name || 'Unknown'),
                        datasets: [{
                            data: categories.map(c => c.product_count || 0),
                            backgroundColor: [
                                '#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0',
                                '#6610f2', '#d63384', '#fd7e14', '#20c997', '#6f42c1'
                            ]
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
        }
    });
</script>
 