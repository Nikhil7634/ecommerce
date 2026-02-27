@include('seller.layouts.header')
@include('seller.layouts.topnavbar')
@include('seller.layouts.aside')

<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="mb-3 page-breadcrumb d-none d-sm-flex align-items-center">
            <div class="breadcrumb-title pe-3">Withdraw Funds</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="p-0 mb-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('seller.earnings') }}">Earnings</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Withdraw Funds</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('seller.withdrawals.history') }}" class="btn btn-outline-primary">
                    <i class="bx bx-history"></i> Withdrawal History
                </a>
            </div>
        </div>
        <!--end breadcrumb-->

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Balance Summary Cards -->
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="overflow-hidden text-white card radius-10 bg-success bg-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="mb-0 text-white opacity-75">Available Balance</p>
                                <h3 class="mb-0 text-white">₹{{ number_format($availableBalance ?? 0, 2) }}</h3>
                                <small class="text-white opacity-75">Ready to withdraw</small>
                            </div>
                            <div class="bg-white widgets-icons text-success rounded-circle">
                                <i class="bx bx-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4 col-md-6">
                <div class="overflow-hidden text-white card radius-10 bg-warning bg-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="mb-0 text-white opacity-75">Pending Withdrawals</p>
                                <h3 class="mb-0 text-white">₹{{ number_format($pendingWithdrawals ?? 0, 2) }}</h3>
                                <small class="text-white opacity-75">Being processed</small>
                            </div>
                            <div class="bg-white widgets-icons text-warning rounded-circle">
                                <i class="bx bx-time"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4 col-md-6">
                <div class="overflow-hidden text-white card radius-10 bg-info bg-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="mb-0 text-white opacity-75">Min. Withdrawal</p>
                                <h3 class="mb-0 text-white">₹{{ number_format($minWithdrawal ?? 100, 2) }}</h3>
                                <small class="text-white opacity-75">Minimum amount required</small>
                            </div>
                            <div class="bg-white widgets-icons text-info rounded-circle">
                                <i class="bx bx-info-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Withdrawal Form -->
        <div class="row">
            <div class="mx-auto col-xl-8">
                <div class="card radius-10">
                    <div class="bg-transparent card-header">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="mb-1">Request Withdrawal</h5>
                                <p class="mb-0 ">Withdraw your earnings to your preferred payment method</p>
                            </div>
                            <div class="ms-auto">
                                <span class="p-2 badge bg-light-info text-info">
                                    <i class="bx bx-info-circle"></i> 
                                    Processed within 2-3 business days
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <form action="{{ route('seller.withdrawals.request') }}" method="POST" id="withdrawalForm">
                            @csrf
                            
                            <!-- Amount Section -->
                            <div class="mb-4">
                                <label class="mb-2 form-label fw-bold">
                                    Withdrawal Amount <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="text-white input-group-text bg-primary">₹</span>
                                    <input type="number" 
                                           name="amount" 
                                           id="amount" 
                                           class="form-control form-control-lg @error('amount') is-invalid @enderror" 
                                           placeholder="Enter amount"
                                           min="{{ $minWithdrawal ?? 100 }}" 
                                           max="{{ $availableBalance ?? 0 }}"
                                           step="0.01"
                                           value="{{ old('amount') }}"
                                           required>
                                </div>
                                @error('amount')
                                    <div class="mt-1 text-danger">{{ $message }}</div>
                                @enderror
                                
                                <!-- Quick Amount Buttons -->
                                <div class="mt-3">
                                    <label class="small form-label ">Quick Select</label>
                                    <div class="flex-wrap gap-2 d-flex">
                                        <button type="button" class="btn btn-outline-primary btn-sm quick-amount" data-amount="1000">₹1,000</button>
                                        <button type="button" class="btn btn-outline-primary btn-sm quick-amount" data-amount="5000">₹5,000</button>
                                        <button type="button" class="btn btn-outline-primary btn-sm quick-amount" data-amount="10000">₹10,000</button>
                                        <button type="button" class="btn btn-outline-primary btn-sm quick-amount" data-amount="25000">₹25,000</button>
                                        <button type="button" class="btn btn-outline-primary btn-sm quick-amount" data-amount="50000">₹50,000</button>
                                        <button type="button" class="btn btn-outline-success btn-sm" id="max-amount">
                                            <i class="bx bx-star"></i> Max
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Amount Help Text -->
                                <div class="mt-2 ">
                                    <i class="bx bx-info-circle"></i> 
                                    Minimum: ₹{{ number_format($minWithdrawal ?? 100, 2) }} | 
                                    Maximum: ₹{{ number_format($availableBalance ?? 0, 2) }}
                                </div>
                            </div>

                            <!-- Fee Calculation Preview -->
                            <div class="p-3 mb-4 bg-light rounded-3" id="feePreview" style="display: none;">
                                <h6 class="mb-3">Fee Calculation Preview</h6>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="mb-2">Withdrawal Amount:</p>
                                        <p class="mb-2">Processing Fee:</p>
                                        <p class="mb-0 fw-bold">You'll Receive:</p>
                                    </div>
                                    <div class="text-end col-sm-6">
                                        <p class="mb-2 fw-bold" id="preview-amount">₹0.00</p>
                                        <p class="mb-2 text-danger" id="preview-fee">₹0.00</p>
                                        <p class="mb-0 fw-bold text-success" id="preview-net">₹0.00</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Method Selection -->
                            <div class="mb-4">
                                <label class="mb-2 form-label fw-bold">
                                    Payment Method <span class="text-danger">*</span>
                                </label>
                                
                                @if(empty($paymentMethods))
                                    <div class="alert alert-warning">
                                        <i class="bx bx-info-circle"></i> 
                                        No payment methods configured. Please add payment details in your 
                                        <a href="{{ route('seller.profile') }}" class="alert-link">profile settings</a>.
                                    </div>
                                @else
                                    <div class="row">
                                        @foreach($paymentMethods as $key => $method)
                                        <div class="mb-3 col-md-6">
                                            <div class="form-check payment-method-card">
                                                <input class="form-check-input" 
                                                       type="radio" 
                                                       name="payment_method" 
                                                       id="method_{{ $key }}" 
                                                       value="{{ $key }}"
                                                       {{ old('payment_method') == $key ? 'checked' : '' }}
                                                       required>
                                                <label class="w-100 form-check-label" for="method_{{ $key }}">
                                                    <div class="d-flex align-items-center">
                                                        <div class="payment-method-icon me-3">
                                                            @if($key == 'bank')
                                                                <i class="bx bx-building-house fs-2 text-primary"></i>
                                                            @elseif($key == 'paypal')
                                                                <i class="bx bxl-paypal fs-2 text-info"></i>
                                                            @elseif($key == 'razorpay')
                                                                <i class="bx bx-mobile-alt fs-2 text-success"></i>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $method['name'] }}</h6>
                                                            <small class="">{{ $method['details'] }}</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @endif
                                @error('payment_method')
                                    <div class="mt-1 text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Dynamic Payment Details Forms -->
                            <div id="paymentDetailsForms">
                                <!-- Bank Details Form -->
                                <div id="bankDetailsForm" class="mb-4 payment-details-form" style="display: none;">
                                    <div class="border-primary card">
                                        <div class="text-white card-header bg-primary bg-gradient">
                                            <h6 class="mb-0 text-white"><i class="bx bx-building-house"></i> Bank Account Details</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                                                    <input type="text" 
                                                           name="account_holder_name" 
                                                           class="form-control @error('account_holder_name') is-invalid @enderror" 
                                                           value="{{ old('account_holder_name', $seller->account_holder_name ?? '') }}"
                                                           placeholder="Enter account holder name">
                                                </div>
                                                
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                                    <input type="text" 
                                                           name="account_number" 
                                                           class="form-control @error('account_number') is-invalid @enderror" 
                                                           value="{{ old('account_number', $seller->account_number ?? '') }}"
                                                           placeholder="Enter account number">
                                                </div>
                                                
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                                                    <input type="text" 
                                                           name="bank_name" 
                                                           class="form-control @error('bank_name') is-invalid @enderror" 
                                                           value="{{ old('bank_name', $seller->bank_name ?? '') }}"
                                                           placeholder="Enter bank name">
                                                </div>
                                                
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">IFSC Code <span class="text-danger">*</span></label>
                                                    <input type="text" 
                                                           name="ifsc_code" 
                                                           class="form-control @error('ifsc_code') is-invalid @enderror" 
                                                           value="{{ old('ifsc_code', $seller->ifsc_code ?? '') }}"
                                                           placeholder="Enter IFSC code">
                                                </div>
                                                
                                                <div class="col-md-12">
                                                    <label class="form-label">Account Type</label>
                                                    <select name="account_type" class="form-select">
                                                        <option value="savings">Savings Account</option>
                                                        <option value="current">Current Account</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PayPal Details Form -->
                                <div id="paypalDetailsForm" class="mb-4 payment-details-form" style="display: none;">
                                    <div class="border-info card">
                                        <div class="text-white card-header bg-info bg-gradient">
                                            <h6 class="mb-0 text-white"><i class="bx bxl-paypal"></i> PayPal Details</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">PayPal Email <span class="text-danger">*</span></label>
                                                <input type="email" 
                                                       name="paypal_email" 
                                                       class="form-control @error('paypal_email') is-invalid @enderror" 
                                                       value="{{ old('paypal_email', $seller->paypal_email ?? '') }}"
                                                       placeholder="Enter PayPal email">
                                                <div class="">We'll send the payment to this PayPal email address.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Razorpay/UPI Details Form -->
                                <div id="razorpayDetailsForm" class="mb-4 payment-details-form" style="display: none;">
                                    <div class="border-success card">
                                        <div class="text-white card-header bg-success bg-gradient">
                                            <h6 class="mb-0 text-white"><i class="bx bx-mobile-alt"></i> UPI / Razorpay Details</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">UPI ID <span class="text-danger">*</span></label>
                                                <input type="text" 
                                                       name="upi_id" 
                                                       class="form-control @error('upi_id') is-invalid @enderror" 
                                                       value="{{ old('upi_id', $seller->upi_id ?? '') }}"
                                                       placeholder="Enter UPI ID (e.g., name@okhdfcbank)">
                                                <div class="">Example: yourname@okhdfcbank, yourname@paytm, yourname@ybl</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Notes -->
                            <div class="mb-4">
                                <label class="form-label">Additional Notes (Optional)</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Any additional information about this withdrawal request...">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I confirm that the payment details provided are correct and I agree to the 
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">withdrawal terms & conditions</a>.
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="gap-3 d-grid">
                                <button type="submit" class="btn btn-success btn-lg" id="submitBtn" {{ empty($paymentMethods) ? 'disabled' : '' }}>
                                    <i class="bx bx-money"></i> Request Withdrawal
                                </button>
                                <a href="{{ route('seller.earnings') }}" class="btn btn-outline-secondary">
                                    <i class="bx bx-arrow-back"></i> Back to Earnings
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Withdrawal Requests -->
        @if(isset($recentWithdrawals) && $recentWithdrawals->count() > 0)
        <div class="mt-4 row">
            <div class="mx-auto col-xl-10">
                <div class="card radius-10">
                    <div class="bg-transparent card-header">
                        <div class="d-flex align-items-center">
                            <div>
                                <h5 class="mb-1">Recent Withdrawal Requests</h5>
                                <p class="mb-0 ">Your last 5 withdrawal requests</p>
                            </div>
                            <div class="ms-auto">
                                <a href="{{ route('seller.withdrawals.history') }}" class="btn btn-sm btn-primary">
                                    View All <i class="bx bx-right-arrow-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Request #</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentWithdrawals as $withdrawal)
                                    <tr>
                                        <td>
                                            <a href="{{ route('seller.withdrawals.details', $withdrawal->id) }}" class="fw-bold text-primary">
                                                {{ $withdrawal->withdrawal_number }}
                                            </a>
                                        </td>
                                        <td>{{ $withdrawal->created_at->format('d M Y, h:i A') }}</td>
                                        <td>
                                            <strong>₹{{ number_format($withdrawal->amount, 2) }}</strong>
                                        </td>
                                        <td>
                                            @php
                                                $methodIcons = [
                                                    'bank' => 'bx-building-house',
                                                    'paypal' => 'bxl-paypal',
                                                    'razorpay' => 'bx-mobile-alt'
                                                ];
                                            @endphp
                                            <i class="bx {{ $methodIcons[$withdrawal->payment_method] ?? 'bx-credit-card' }}"></i>
                                            {{ ucfirst($withdrawal->payment_method) }}
                                        </td>
                                        <td>
                                            @php
                                                $statusClasses = [
                                                    'pending' => 'badge bg-warning text-dark',
                                                    'processing' => 'badge bg-info',
                                                    'completed' => 'badge bg-success',
                                                    'rejected' => 'badge bg-danger',
                                                    'cancelled' => 'badge bg-secondary'
                                                ];
                                                $statusIcons = [
                                                    'pending' => 'bx-time',
                                                    'processing' => 'bx-loader',
                                                    'completed' => 'bx-check-circle',
                                                    'rejected' => 'bx-x-circle',
                                                    'cancelled' => 'bx-block'
                                                ];
                                            @endphp
                                            <span class="{{ $statusClasses[$withdrawal->status] ?? 'badge bg-secondary' }}">
                                                <i class="bx {{ $statusIcons[$withdrawal->status] ?? 'bx-time' }}"></i>
                                                {{ ucfirst($withdrawal->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('seller.withdrawals.details', $withdrawal->id) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="View Details">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            @if($withdrawal->status == 'pending')
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Cancel Request"
                                                    onclick="cancelWithdrawal('{{ $withdrawal->id }}')">
                                                <i class="bx bx-x"></i>
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Withdrawal Information -->
        <div class="mt-4 row">
            <div class="mx-auto col-xl-10">
                <div class="bg-light card radius-10">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="text-white widgets-icons bg-info me-3">
                                        <i class="bx bx-time"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Processing Time</h6>
                                        <p class="mb-0 ">2-3 business days</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="text-white widgets-icons bg-success me-3">
                                        <i class="bx bx-money"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Minimum Amount</h6>
                                        <p class="mb-0 ">₹{{ number_format($minWithdrawal ?? 100, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="text-white widgets-icons bg-warning me-3">
                                        <i class="bx bx-support"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Need Help?</h6>
                                        <p class="mb-0 ">
                                            <a href="mailto:support@example.com">support@example.com</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Terms & Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="text-white modal-header bg-primary">
                <h5 class="text-white modal-title">Withdrawal Terms & Conditions</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="terms-content">
                    <h6 class="mb-2">1. Processing Time</h6>
                    <p class="mb-3 ">Withdrawal requests are processed within 2-3 business days. Processing time may vary based on your payment method and bank processing times.</p>
                    
                    <h6 class="mb-2">2. Minimum Withdrawal</h6>
                    <p class="mb-3 ">The minimum withdrawal amount is ₹{{ number_format($minWithdrawal ?? 100, 2) }}. Requests below this amount will not be processed.</p>
                    
                    <h6 class="mb-2">3. Processing Fees</h6>
                    <p class="mb-3 ">
                        - Bank Transfer: Free<br>
                        - PayPal: 2.9% fee<br>
                        - Razorpay/UPI: 2% fee
                    </p>
                    
                    <h6 class="mb-2">4. Cancellation Policy</h6>
                    <p class="mb-3 ">You can cancel pending withdrawal requests from your withdrawal history page. Once processed, cancellations are not possible.</p>
                    
                    <h6 class="mb-2">5. Tax Compliance</h6>
                    <p class="mb-3 ">Please ensure your tax information is up to date. TDS will be deducted as per applicable tax laws.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand & Accept</button>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Withdrawal Form -->
<form id="cancelWithdrawalForm" method="POST" style="display: none;">
    @csrf
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DOM Elements
        const amountInput = document.getElementById('amount');
        const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
        const bankDetails = document.getElementById('bankDetailsForm');
        const paypalDetails = document.getElementById('paypalDetailsForm');
        const razorpayDetails = document.getElementById('razorpayDetailsForm');
        const feePreview = document.getElementById('feePreview');
        const previewAmount = document.getElementById('preview-amount');
        const previewFee = document.getElementById('preview-fee');
        const previewNet = document.getElementById('preview-net');
        const submitBtn = document.getElementById('submitBtn');
        
        // Fee rates
        const feeRates = {
            'bank': 0,
            'paypal': 2.9,
            'razorpay': 2.0
        };
        
        // Quick amount buttons
        document.querySelectorAll('.quick-amount').forEach(btn => {
            btn.addEventListener('click', function() {
                amountInput.value = this.dataset.amount;
                calculateFee();
                amountInput.focus();
            });
        });
        
        // Max amount button
        document.getElementById('max-amount').addEventListener('click', function() {
            amountInput.value = {{ $availableBalance ?? 0 }};
            calculateFee();
            amountInput.focus();
        });
        
        // Amount input change
        amountInput.addEventListener('input', calculateFee);
        
        // Payment method change
        paymentMethodRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                // Hide all payment details forms
                bankDetails.style.display = 'none';
                paypalDetails.style.display = 'none';
                razorpayDetails.style.display = 'none';
                
                // Show selected payment details form
                if (this.value === 'bank') {
                    bankDetails.style.display = 'block';
                } else if (this.value === 'paypal') {
                    paypalDetails.style.display = 'block';
                } else if (this.value === 'razorpay') {
                    razorpayDetails.style.display = 'block';
                }
                
                calculateFee();
            });
        });
        
        // Calculate fee function
        function calculateFee() {
            const amount = parseFloat(amountInput.value) || 0;
            const method = document.querySelector('input[name="payment_method"]:checked')?.value;
            
            if (amount > 0 && method) {
                const feeRate = feeRates[method] || 0;
                const fee = (amount * feeRate) / 100;
                const net = amount - fee;
                
                previewAmount.textContent = '₹' + amount.toFixed(2);
                previewFee.textContent = '₹' + fee.toFixed(2);
                previewNet.textContent = '₹' + net.toFixed(2);
                
                feePreview.style.display = 'block';
            } else {
                feePreview.style.display = 'none';
            }
        }
        
        // Form validation
        document.getElementById('withdrawalForm').addEventListener('submit', function(e) {
            const amount = parseFloat(amountInput.value);
            const maxAmount = {{ $availableBalance ?? 0 }};
            const minAmount = {{ $minWithdrawal ?? 100 }};
            
            if (amount < minAmount) {
                e.preventDefault();
                alert('Minimum withdrawal amount is ₹' + minAmount.toFixed(2));
                return;
            }
            
            if (amount > maxAmount) {
                e.preventDefault();
                alert('Insufficient balance. Your available balance is ₹' + maxAmount.toFixed(2));
                return;
            }
            
            if (!confirm('Are you sure you want to request withdrawal of ₹' + amount.toFixed(2) + '?')) {
                e.preventDefault();
            }
        });
        
        // Initialize if old values exist
        if (document.querySelector('input[name="payment_method"]:checked')) {
            document.querySelector('input[name="payment_method"]:checked').dispatchEvent(new Event('change'));
        }
        
        if (amountInput.value) {
            calculateFee();
        }
    });
    
     // Cancel withdrawal function - Alternative FIX
    function cancelWithdrawal(withdrawalId) {
        if (confirm('Are you sure you want to cancel this withdrawal request?')) {
            // Build URL manually
            const baseUrl = '{{ url("/seller/withdrawals") }}';
            const form = document.getElementById('cancelWithdrawalForm');
            form.action = baseUrl + '/' + withdrawalId + '/cancel';
            form.submit();
        }
    }
</script>

<style>
    .payment-method-card {
        padding: 15px;
        border: 2px solid #dee2e6;
        border-radius: 10px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .payment-method-card:hover {
        border-color: #0d6efd;
        background-color: #f8f9fa;
    }
    
    .payment-method-card .form-check-input:checked ~ .form-check-label .payment-method-card {
        border-color: #0d6efd;
        background-color: #e7f1ff;
    }
    
    .payment-method-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 10px;
    }
    
    .widgets-icons {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 24px;
    }
    
    .quick-amount, #max-amount {
        min-width: 80px;
        transition: all 0.3s;
    }
    
    .quick-amount:hover, #max-amount:hover {
        transform: translateY(-2px);
    }
    
    .payment-details-form {
        animation: slideDown 0.3s ease;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .terms-content {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 10px;
    }
    
    .terms-content::-webkit-scrollbar {
        width: 5px;
    }
    
    .terms-content::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    .terms-content::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 5px;
    }
    
    .bg-gradient {
        background: linear-gradient(45deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
    }
    
    .bg-success.bg-gradient {
        background: linear-gradient(45deg, #28a745 0%, #20c997 100%);
    }
    
    .bg-warning.bg-gradient {
        background: linear-gradient(45deg, #ffc107 0%, #fd7e14 100%);
    }
    
    .bg-info.bg-gradient {
        background: linear-gradient(45deg, #17a2b8 0%, #0dcaf0 100%);
    }
</style>

@include('seller.layouts.footer')
 

 