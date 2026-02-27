<!--start header-->
@include('admin.layouts.header')
<!--end top header-->

@include('admin.layouts.navbar')

<!--start sidebar-->
@include('admin.layouts.aside')

<!-- main content start -->
<div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
        <h2>Payment Transactions</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Payments</a></li>
                <li class="breadcrumb-item active">Transactions</li>
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
                    <h3 class="mb-2 text-primary">{{ number_format($statistics['total_transactions'] ?? 0) }}</h3>
                    <p class="mb-0 text-muted">Total Transactions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel">
                <div class="text-center panel-body">
                    <h3 class="mb-2 text-success">₹{{ number_format($statistics['total_amount'] ?? 0, 2) }}</h3>
                    <p class="mb-0 text-muted">Total Amount</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel">
                <div class="text-center panel-body">
                    <h3 class="mb-2 text-info">{{ number_format($statistics['orders']['successful'] ?? 0) }}</h3>
                    <p class="mb-0 text-muted">Successful Orders</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel">
                <div class="text-center panel-body">
                    <h3 class="mb-2 text-warning">{{ number_format($statistics['withdrawals']['pending'] ?? 0) }}</h3>
                    <p class="mb-0 text-muted">Pending Withdrawals</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-4 row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>Filter Transactions</h5>
                </div>
                <div class="panel-body">
                    <form action="{{ route('admin.payments.transactions') }}" method="GET" class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select">
                                <option value="all" {{ ($type ?? 'all') == 'all' ? 'selected' : '' }}>All Types</option>
                                <option value="orders" {{ ($type ?? '') == 'orders' ? 'selected' : '' }}>Orders Only</option>
                                <option value="withdrawals" {{ ($type ?? '') == 'withdrawals' ? 'selected' : '' }}>Withdrawals Only</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="all" {{ ($status ?? 'all') == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="paid" {{ ($status ?? '') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="pending" {{ ($status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="failed" {{ ($status ?? '') == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="completed" {{ ($status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="processing" {{ ($status ?? '') == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="rejected" {{ ($status ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate ?? now()->startOfMonth()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate ?? now()->endOfMonth()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="ID or description" value="{{ $search ?? '' }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fa-light fa-filter me-2"></i>Apply
                            </button>
                            <a href="{{ route('admin.payments.transactions') }}" class="btn btn-secondary">
                                <i class="fa-light fa-undo me-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods Distribution -->
    <div class="mb-4 row">
        <div class="col-md-6">
            <div class="panel">
                <div class="panel-header">
                    <h5>Payment Methods Distribution</h5>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Payment Method</th>
                                    <th>Transactions</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentMethods ?? [] as $method)
                                <tr>
                                    <td>
                                        @switch($method->payment_method)
                                            @case('razorpay')
                                                <i class="fa-brands fa-razorpay text-primary me-2"></i>Razorpay
                                                @break
                                            @case('paypal')
                                                <i class="fa-brands fa-paypal text-info me-2"></i>PayPal
                                                @break
                                            @default
                                                <i class="fa-light fa-credit-card me-2"></i>{{ ucfirst($method->payment_method ?? 'Cash') }}
                                        @endswitch
                                    </td>
                                    <td>{{ number_format($method->count ?? 0) }}</td>
                                    <td>₹{{ number_format($method->total ?? 0, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="py-3 text-center">No payment data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel">
                <div class="panel-header">
                    <h5>Quick Summary</h5>
                    <a href="{{ route('admin.payments.transactions.export', request()->all()) }}" class="btn btn-sm btn-success">
                        <i class="fa-light fa-download me-2"></i>Export CSV
                    </a>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tr>
                            <td><strong>Orders Summary</strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Total Orders:</td>
                            <td class="text-end">{{ number_format($statistics['orders']['total'] ?? 0) }}</td>
                        </tr>
                        <tr>
                            <td>Order Amount:</td>
                            <td class="text-end">₹{{ number_format($statistics['orders']['amount'] ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Successful Orders:</td>
                            <td class="text-end">{{ number_format($statistics['orders']['successful'] ?? 0) }} (₹{{ number_format($statistics['orders']['successful_amount'] ?? 0, 2) }})</td>
                        </tr>
                        <tr>
                            <td>Pending Orders:</td>
                            <td class="text-end">{{ number_format($statistics['orders']['pending'] ?? 0) }} (₹{{ number_format($statistics['orders']['pending_amount'] ?? 0, 2) }})</td>
                        </tr>
                        <tr>
                            <td>Failed Orders:</td>
                            <td class="text-end">{{ number_format($statistics['orders']['failed'] ?? 0) }} (₹{{ number_format($statistics['orders']['failed_amount'] ?? 0, 2) }})</td>
                        </tr>
                        
                        <tr><td colspan="2"><hr></td></tr>
                        
                        <tr>
                            <td><strong>Withdrawals Summary</strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Total Withdrawals:</td>
                            <td class="text-end">{{ number_format($statistics['withdrawals']['total'] ?? 0) }}</td>
                        </tr>
                        <tr>
                            <td>Withdrawal Amount:</td>
                            <td class="text-end">₹{{ number_format($statistics['withdrawals']['amount'] ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Completed:</td>
                            <td class="text-end">{{ number_format($statistics['withdrawals']['completed'] ?? 0) }} (₹{{ number_format($statistics['withdrawals']['completed_amount'] ?? 0, 2) }})</td>
                        </tr>
                        <tr>
                            <td>Pending:</td>
                            <td class="text-end">{{ number_format($statistics['withdrawals']['pending'] ?? 0) }} (₹{{ number_format($statistics['withdrawals']['pending_amount'] ?? 0, 2) }})</td>
                        </tr>
                        <tr>
                            <td>Rejected:</td>
                            <td class="text-end">{{ number_format($statistics['withdrawals']['rejected'] ?? 0) }} (₹{{ number_format($statistics['withdrawals']['rejected_amount'] ?? 0, 2) }})</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>Transaction History</h5>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions ?? [] as $transaction)
                                <tr>
                                    <td>
                                        <code>{{ $transaction->transaction_id }}</code>
                                    </td>
                                    <td>
                                        @if($transaction->transaction_type == 'order')
                                            <span class="badge bg-primary">Order</span>
                                        @else
                                            <span class="badge bg-warning">Withdrawal</span>
                                        @endif
                                    </td>
                                    <td>{{ $transaction->description }}</td>
                                    <td>
                                        @if($transaction->transaction_type == 'order' && $transaction->user)
                                            {{ $transaction->user->name }}
                                        @elseif($transaction->transaction_type == 'withdrawal' && $transaction->seller)
                                            {{ $transaction->seller->name }} (Seller)
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <strong>₹{{ number_format($transaction->amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        @switch($transaction->payment_method)
                                            @case('razorpay')
                                                <i class="fa-brands fa-razorpay text-primary"></i> Razorpay
                                                @break
                                            @case('paypal')
                                                <i class="fa-brands fa-paypal text-info"></i> PayPal
                                                @break
                                            @default
                                                <i class="fa-light fa-credit-card"></i> {{ ucfirst($transaction->payment_method ?? 'Cash') }}
                                        @endswitch
                                    </td>
                                    <td>
                                        @php
                                            $statusColor = [
                                                'paid' => 'success',
                                                'completed' => 'success',
                                                'pending' => 'warning',
                                                'processing' => 'info',
                                                'failed' => 'danger',
                                                'rejected' => 'danger',
                                                'cancelled' => 'secondary',
                                            ][$transaction->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }}">{{ ucfirst($transaction->status) }}</span>
                                    </td>
                                    <td>{{ $transaction->created_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <div class="btn-box">
                                            @if($transaction->transaction_type == 'order')
                                                <a href="{{ route('admin.orders.show', $transaction->id) }}" class="btn btn-sm btn-icon btn-outline-primary" title="View Order">
                                                    <i class="fa-light fa-eye"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('admin.payments.withdrawals.show', $transaction->id) }}" class="btn btn-sm btn-icon btn-outline-info" title="View Withdrawal">
                                                    <i class="fa-light fa-eye"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="py-4 text-center">
                                        <i class="mb-3 fa-light fa-credit-card fa-3x text-muted"></i>
                                        <h5>No transactions found</h5>
                                        <p class="text-muted">Try adjusting your filters</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($transactions) && $transactions->hasPages())
                    <div class="mt-4">
                        {{ $transactions->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- main content end -->

@include('admin.layouts.footer')