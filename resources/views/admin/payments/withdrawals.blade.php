<!--start header-->
@include('admin.layouts.header')
<!--end top header-->

@include('admin.layouts.navbar')

<!--start sidebar-->
@include('admin.layouts.aside')

<!-- main content start -->
<div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
        <h2>Withdrawal Requests</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Payments</a></li>
                <li class="breadcrumb-item active">Withdrawals</li>
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
                    <h3 class="mb-2 text-primary">{{ number_format($statistics['total_withdrawals'] ?? 0) }}</h3>
                    <p class="mb-0 text-muted">Total Withdrawals</p>
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
                    <h3 class="mb-2 text-warning">{{ number_format($statistics['pending_count'] ?? 0) }}</h3>
                    <p class="mb-0 text-muted">Pending Requests</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel">
                <div class="text-center panel-body">
                    <h3 class="mb-2 text-info">{{ number_format($statistics['pending_amount'] ?? 0, 2) }}</h3>
                    <p class="mb-0 text-muted">Pending Amount</p>
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
                               href="{{ route('admin.payments.withdrawals') }}">
                                All ({{ $statusCounts['all'] ?? 0 }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ ($status ?? '') == 'pending' ? 'active' : '' }}" 
                               href="{{ route('admin.payments.withdrawals', ['status' => 'pending']) }}">
                                Pending ({{ $statusCounts['pending'] ?? 0 }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ ($status ?? '') == 'processing' ? 'active' : '' }}" 
                               href="{{ route('admin.payments.withdrawals', ['status' => 'processing']) }}">
                                Processing ({{ $statusCounts['processing'] ?? 0 }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ ($status ?? '') == 'completed' ? 'active' : '' }}" 
                               href="{{ route('admin.payments.withdrawals', ['status' => 'completed']) }}">
                                Completed ({{ $statusCounts['completed'] ?? 0 }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ ($status ?? '') == 'rejected' ? 'active' : '' }}" 
                               href="{{ route('admin.payments.withdrawals', ['status' => 'rejected']) }}">
                                Rejected ({{ $statusCounts['rejected'] ?? 0 }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ ($status ?? '') == 'cancelled' ? 'active' : '' }}" 
                               href="{{ route('admin.payments.withdrawals', ['status' => 'cancelled']) }}">
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
                    <h5>Filter Withdrawals</h5>
                </div>
                <div class="panel-body">
                    <form action="{{ route('admin.payments.withdrawals') }}" method="GET" class="row g-3">
                        <input type="hidden" name="status" value="{{ $status ?? 'all' }}">
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
                            <input type="text" name="search" class="form-control" placeholder="Withdrawal #, Seller name" value="{{ $search ?? '' }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fa-light fa-filter me-2"></i>Apply
                            </button>
                            <a href="{{ route('admin.payments.withdrawals') }}" class="btn btn-secondary">
                                <i class="fa-light fa-undo me-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>Withdrawal Requests</h5>
                    <a href="{{ route('admin.payments.withdrawals.export', request()->all()) }}" class="btn btn-sm btn-success">
                        <i class="fa-light fa-download me-2"></i>Export CSV
                    </a>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Withdrawal #</th>
                                    <th>Seller</th>
                                    <th>Amount</th>
                                    <th>Fee</th>
                                    <th>Net Amount</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Requested</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdrawals ?? [] as $withdrawal)
                                <tr>
                                    <td>
                                        <code>{{ $withdrawal->withdrawal_number }}</code>
                                    </td>
                                    <td>
                                        @if($withdrawal->seller)
                                            <strong>{{ $withdrawal->seller->name }}</strong>
                                            @if($withdrawal->seller->business_name)
                                                <br><small class="text-muted">{{ $withdrawal->seller->business_name }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">Deleted Seller</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>₹{{ number_format($withdrawal->amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="text-danger">-₹{{ number_format($withdrawal->fee, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-success">₹{{ number_format($withdrawal->net_amount, 2) }}</span>
                                    </td>
                                    <td>
                                        @switch($withdrawal->payment_method)
                                            @case('bank')
                                                <span class="badge bg-primary">Bank Transfer</span>
                                                @break
                                            @case('paypal')
                                                <span class="badge bg-info">PayPal</span>
                                                @break
                                            @case('razorpay')
                                                <span class="badge bg-success">Razorpay/UPI</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($withdrawal->payment_method) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-warning',
                                                'processing' => 'bg-info',
                                                'completed' => 'bg-success',
                                                'rejected' => 'bg-danger',
                                                'cancelled' => 'bg-secondary',
                                            ];
                                            $color = $statusColors[$withdrawal->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $color }}">{{ ucfirst($withdrawal->status) }}</span>
                                    </td>
                                    <td>
                                        {{ $withdrawal->created_at->format('d M Y') }}
                                        <br>
                                        <small class="text-muted">{{ $withdrawal->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-box">
                                            <a href="{{ route('admin.payments.withdrawals.show', $withdrawal->id) }}" 
                                               class="btn btn-sm btn-icon btn-outline-primary" 
                                               title="View Details">
                                                <i class="fa-light fa-eye"></i>
                                            </a>
                                            @if($withdrawal->status == 'pending')
                                                <button type="button" 
                                                        class="btn btn-sm btn-icon btn-outline-success" 
                                                        title="Approve"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#approveModal{{ $withdrawal->id }}">
                                                    <i class="fa-light fa-check"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-sm btn-icon btn-outline-danger" 
                                                        title="Reject"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectModal{{ $withdrawal->id }}">
                                                    <i class="fa-light fa-times"></i>
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Approve Modal -->
                                        @if($withdrawal->status == 'pending')
                                        <div class="modal fade" id="approveModal{{ $withdrawal->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="text-white modal-header bg-success">
                                                        <h5 class="text-white modal-title">Approve Withdrawal</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('admin.payments.withdrawals.approve', $withdrawal->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Withdrawal #{{ $withdrawal->withdrawal_number }}</label>
                                                                <p><strong>Seller:</strong> {{ $withdrawal->seller->name ?? 'N/A' }}</p>
                                                                <p><strong>Amount:</strong> ₹{{ number_format($withdrawal->amount, 2) }}</p>
                                                                <p><strong>Fee:</strong> ₹{{ number_format($withdrawal->fee, 2) }}</p>
                                                                <p><strong>Net Amount:</strong> ₹{{ number_format($withdrawal->net_amount, 2) }}</p>
                                                                <p><strong>Payment Method:</strong> {{ ucfirst($withdrawal->payment_method) }}</p>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Admin Notes (Optional)</label>
                                                                <textarea name="notes" class="form-control" rows="2" placeholder="Add any notes about this approval"></textarea>
                                                            </div>
                                                            <div class="alert alert-info">
                                                                <i class="fa-light fa-info-circle me-2"></i>
                                                                Approving this withdrawal will mark it as completed and the amount will be deducted from seller's available balance.
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-success">Approve Withdrawal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $withdrawal->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="text-white modal-header bg-danger">
                                                        <h5 class="text-white modal-title">Reject Withdrawal</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('admin.payments.withdrawals.reject', $withdrawal->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Withdrawal #{{ $withdrawal->withdrawal_number }}</label>
                                                                <p><strong>Seller:</strong> {{ $withdrawal->seller->name ?? 'N/A' }}</p>
                                                                <p><strong>Amount:</strong> ₹{{ number_format($withdrawal->amount, 2) }}</p>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                                                <textarea name="reason" class="form-control" rows="3" required placeholder="Please provide a reason for rejection"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Reject Withdrawal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="py-4 text-center">
                                        <i class="mb-3 fa-light fa-money-bill-transfer fa-3x text-muted"></i>
                                        <h5>No withdrawal requests found</h5>
                                        <p class="text-muted">Try adjusting your filters</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($withdrawals) && $withdrawals->hasPages())
                    <div class="mt-4">
                        {{ $withdrawals->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- main content end -->

@include('admin.layouts.footer')