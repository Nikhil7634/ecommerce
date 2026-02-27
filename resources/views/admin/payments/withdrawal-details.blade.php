<!--start header-->
@include('admin.layouts.header')
<!--end top header-->

@include('admin.layouts.navbar')

<!--start sidebar-->
@include('admin.layouts.aside')

<!-- main content start -->
<div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
        <h2>Withdrawal Details</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.payments.withdrawals') }}">Withdrawals</a></li>
                <li class="breadcrumb-item active">#{{ $withdrawal->withdrawal_number }}</li>
            </ol>
        </nav>
        <div class="gap-2 mt-2 d-flex">
            <a href="{{ route('admin.payments.withdrawals') }}" class="btn btn-outline-secondary">
                <i class="fa-light fa-arrow-left me-2"></i>Back to Withdrawals
            </a>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="mb-4 row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="flex-wrap gap-3 d-flex align-items-center justify-content-between">
                        <div class="gap-3 d-flex align-items-center">
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'completed' => 'success',
                                    'rejected' => 'danger',
                                    'cancelled' => 'secondary',
                                ];
                                $color = $statusColors[$withdrawal->status] ?? 'secondary';
                            @endphp
                            <div class="rounded-circle bg-{{ $color }} bg-opacity-10 p-3">
                                <i class="fa-light fa-money-bill-transfer fa-2x text-{{ $color }}"></i>
                            </div>
                            <div>
                                <h4 class="mb-1">Withdrawal #{{ $withdrawal->withdrawal_number }}</h4>
                                <p class="mb-0 text-muted">Requested on {{ $withdrawal->created_at->format('F d, Y \a\t h:i A') }}</p>
                            </div>
                        </div>
                        <span class="badge bg-{{ $color }} p-3 fs-6">
                            {{ ucfirst($withdrawal->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="mb-4 row">
        <div class="col-md-4">
            <div class="panel">
                <div class="text-center panel-body">
                    <h5 class="mb-2 text-primary">₹{{ number_format($withdrawal->amount, 2) }}</h5>
                    <p class="mb-0 text-muted">Requested Amount</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel">
                <div class="text-center panel-body">
                    <h5 class="mb-2 text-danger">-₹{{ number_format($withdrawal->fee, 2) }}</h5>
                    <p class="mb-0 text-muted">Processing Fee</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel">
                <div class="text-center panel-body">
                    <h5 class="mb-2 text-success">₹{{ number_format($withdrawal->net_amount, 2) }}</h5>
                    <p class="mb-0 text-muted">Net Amount</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Seller Information -->
    <div class="mb-4 row">
        <div class="col-md-6">
            <div class="panel h-100">
                <div class="panel-header">
                    <h5>Seller Information</h5>
                </div>
                <div class="panel-body">
                    @if($withdrawal->seller)
                    <table class="table">
                        <tr>
                            <th style="width: 150px;">Name:</th>
                            <td>{{ $withdrawal->seller->name }}</td>
                        </tr>
                        @if($withdrawal->seller->business_name)
                        <tr>
                            <th>Business Name:</th>
                            <td>{{ $withdrawal->seller->business_name }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Email:</th>
                            <td><a href="mailto:{{ $withdrawal->seller->email }}">{{ $withdrawal->seller->email }}</a></td>
                        </tr>
                        @if($withdrawal->seller->phone)
                        <tr>
                            <th>Phone:</th>
                            <td><a href="tel:{{ $withdrawal->seller->phone }}">{{ $withdrawal->seller->phone }}</a></td>
                        </tr>
                        @endif
                        <tr>
                            <th>Seller Since:</th>
                            <td>{{ $withdrawal->seller->created_at->format('d M Y') }}</td>
                        </tr>
                    </table>
                    @else
                    <p class="text-muted">Seller information not available</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel h-100">
                <div class="panel-header">
                    <h5>Payment Details</h5>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tr>
                            <th style="width: 150px;">Payment Method:</th>
                            <td>{{ ucfirst($withdrawal->payment_method) }}</td>
                        </tr>
                        @if($withdrawal->payment_details)
                            @php $details = json_decode($withdrawal->payment_details, true); @endphp
                            @if($withdrawal->payment_method == 'bank')
                                <tr>
                                    <th>Account Holder:</th>
                                    <td>{{ $details['account_holder_name'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Account Number:</th>
                                    <td>{{ isset($details['account_number']) ? '****' . substr($details['account_number'], -4) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Bank Name:</th>
                                    <td>{{ $details['bank_name'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>IFSC Code:</th>
                                    <td>{{ $details['ifsc_code'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Account Type:</th>
                                    <td>{{ ucfirst($details['account_type'] ?? 'savings') }}</td>
                                </tr>
                            @elseif($withdrawal->payment_method == 'paypal')
                                <tr>
                                    <th>PayPal Email:</th>
                                    <td>{{ $details['paypal_email'] ?? 'N/A' }}</td>
                                </tr>
                            @elseif($withdrawal->payment_method == 'razorpay')
                                <tr>
                                    <th>UPI ID:</th>
                                    <td>{{ $details['upi_id'] ?? 'N/A' }}</td>
                                </tr>
                            @endif
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Timeline -->
    <div class="mb-4 row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>Withdrawal Timeline</h5>
                </div>
                <div class="panel-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6>Request Submitted</h6>
                                <p class="text-muted">{{ $withdrawal->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>

                        @if($withdrawal->processed_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6>Processed</h6>
                                <p class="text-muted">{{ \Carbon\Carbon::parse($withdrawal->processed_at)->format('d M Y, h:i A') }}</p>
                                @if($withdrawal->processor)
                                <p class="text-muted">By: {{ $withdrawal->processor->name }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($withdrawal->completed_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6>Completed</h6>
                                <p class="text-muted">{{ \Carbon\Carbon::parse($withdrawal->completed_at)->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($withdrawal->rejected_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content">
                                <h6>Rejected</h6>
                                <p class="text-muted">{{ \Carbon\Carbon::parse($withdrawal->rejected_at)->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes -->
    @if($withdrawal->notes || $withdrawal->admin_notes)
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>Notes</h5>
                </div>
                <div class="panel-body">
                    @if($withdrawal->notes)
                    <div class="mb-3">
                        <h6>Seller Notes:</h6>
                        <p>{{ $withdrawal->notes }}</p>
                    </div>
                    @endif
                    @if($withdrawal->admin_notes)
                    <div>
                        <h6>Admin Notes:</h6>
                        <p>{{ $withdrawal->admin_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Action Buttons for Pending Withdrawals -->
    @if($withdrawal->status == 'pending')
    <div class="mt-4 row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="gap-3 d-flex justify-content-end">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="fa-light fa-check me-2"></i>Approve Withdrawal
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fa-light fa-times me-2"></i>Reject Withdrawal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="text-white modal-header bg-success">
                    <h5 class="text-white modal-title">Approve Withdrawal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.payments.withdrawals.approve', $withdrawal->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p><strong>Withdrawal #{{ $withdrawal->withdrawal_number }}</strong></p>
                        <p><strong>Amount:</strong> ₹{{ number_format($withdrawal->amount, 2) }}</p>
                        <p><strong>Net Amount:</strong> ₹{{ number_format($withdrawal->net_amount, 2) }}</p>
                        <div class="mb-3">
                            <label class="form-label">Admin Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Add any notes about this approval"></textarea>
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
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="text-white modal-header bg-danger">
                    <h5 class="text-white modal-title">Reject Withdrawal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.payments.withdrawals.reject', $withdrawal->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p><strong>Withdrawal #{{ $withdrawal->withdrawal_number }}</strong></p>
                        <p><strong>Amount:</strong> ₹{{ number_format($withdrawal->amount, 2) }}</p>
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
</div>
<!-- main content end -->

@include('admin.layouts.footer')

 <style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 25px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 0;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #e9ecef;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #e9ecef;
    }
    
    .timeline-content {
        padding-bottom: 10px;
    }
    
    .timeline-content h6 {
        margin-bottom: 5px;
        font-weight: 600;
    }
</style>
 