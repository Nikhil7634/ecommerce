@include('seller.layouts.header')
@include('seller.layouts.topnavbar')
@include('seller.layouts.aside')

<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="mb-4 page-breadcrumb d-none d-sm-flex align-items-center">
            <div class="breadcrumb-title pe-3">Profile</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="p-0 mb-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Profile Header Card -->
        <div class="card rounded-4">
            <div class="p-4 card-body">
                <div class="mb-5 position-relative">
                    <!-- Profile Cover Image (Optional) -->
                    <div class="position-relative">
                        <div class="bg-primary bg-gradient rounded-4" style="height: 200px;"></div>
                        <!-- Profile Avatar with Upload -->
                        <div class="profile-avatar position-absolute top-100 start-50 translate-middle">
                            <div class="position-relative">
                                @if($seller->avatar)
                                    <img src="{{ asset('storage/' . $seller->avatar) }}" 
                                         class="p-2 bg-white shadow img-fluid rounded-circle" 
                                         width="170" height="170" alt="{{ $seller->name }}">
                                @else
                                    <div class="border border-4 border-white shadow bg-primary d-flex align-items-center justify-content-center rounded-circle" 
                                         style="width: 170px; height: 170px;">
                                        <span class="text-white display-4 fw-bold">
                                            {{ strtoupper(substr($seller->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                
                                <!-- Camera Icon for Upload -->
                                <button type="button" class="bottom-0 border border-white btn btn-primary btn-sm rounded-circle position-absolute end-0 border-3"
                                        onclick="document.getElementById('avatarInput').click()">
                                    <i class="bi bi-camera"></i>
                                </button>
                                
                                <!-- Hidden File Input -->
                                <form id="avatarForm" action="{{ route('seller.profile.avatar.update') }}" method="POST" enctype="multipart/form-data" class="d-none">
                                    @csrf
                                    <input type="file" id="avatarInput" name="avatar" accept="image/*" 
                                           onchange="document.getElementById('avatarForm').submit()">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="pt-5 profile-info d-flex align-items-center justify-content-between">
                    <div class="">
                        <h3>{{ $seller->name }}</h3>
                        <p class="mb-0">
                            @if($seller->business_name)
                                {{ $seller->business_name }}<br>
                            @endif
                            @if($seller->city || $seller->country)
                                {{ $seller->city ? $seller->city . ', ' : '' }}{{ $seller->country ?? '' }}
                            @endif
                        </p>
                    </div>
                    <div class="">
                        <span class="badge bg-{{ $seller->status == 'active' ? 'success' : ($seller->status == 'inactive' ? 'warning' : 'danger') }} rounded-pill px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i> {{ ucfirst($seller->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Tabs -->
        <div class="mt-4">
            <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
                        <i class="bi bi-person me-2"></i>Personal Information
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab">
                        <i class="bi bi-credit-card me-2"></i>Payment Methods
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                        <i class="bi bi-shield-lock me-2"></i>Security
                    </button>
                </li>
            </ul>
            
            <div class="mt-4 tab-content" id="profileTabsContent">
                <!-- Personal Information Tab -->
                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                    <div class="row">
                        <div class="col-12 col-xl-8">
                            <div class="border-4 card rounded-4 border-top border-primary border-gradient-1">
                                <div class="p-4 card-body">
                                    <div class="mb-3 d-flex align-items-start justify-content-between">
                                        <div class="">
                                            <h5 class="mb-0 fw-bold">Edit Profile</h5>
                                        </div>
                                    </div>
                                    
                                    <form action="{{ route('seller.profile.update') }}" method="POST" class="row g-4">
                                        @csrf
                                        @method('PATCH')

                                        <div class="col-md-6">
                                            <label for="input1" class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="input1" class="form-control @error('name') is-invalid @enderror" 
                                                   placeholder="Full Name" value="{{ old('name', $seller->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" value="{{ $seller->email }}" disabled>
                                            <small class="">Email cannot be changed</small>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="input3" class="form-label">Phone</label>
                                            <input type="text" name="phone" id="input3" class="form-control @error('phone') is-invalid @enderror" 
                                                   placeholder="Phone" value="{{ old('phone', $seller->phone) }}">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="business_name" class="form-label">Business Name</label>
                                            <input type="text" name="business_name" id="business_name" class="form-control @error('business_name') is-invalid @enderror" 
                                                   placeholder="Business Name" value="{{ old('business_name', $seller->business_name) }}">
                                            @error('business_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="gst_no" class="form-label">GST Number</label>
                                            <input type="text" name="gst_no" id="gst_no" class="form-control @error('gst_no') is-invalid @enderror" 
                                                   placeholder="GST Number" value="{{ old('gst_no', $seller->gst_no) }}">
                                            @error('gst_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-12">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                                      id="address" placeholder="Address ..." rows="3">{{ old('address', $seller->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="city" class="form-label">City</label>
                                            <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" 
                                                   placeholder="City" value="{{ old('city', $seller->city) }}">
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="state" class="form-label">State</label>
                                            <input type="text" name="state" id="state" class="form-control @error('state') is-invalid @enderror" 
                                                   placeholder="State" value="{{ old('state', $seller->state) }}">
                                            @error('state')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="zip" class="form-label">ZIP Code</label>
                                            <input type="text" name="zip" id="zip" class="form-control @error('zip') is-invalid @enderror" 
                                                   placeholder="ZIP Code" value="{{ old('zip', $seller->zip) }}">
                                            @error('zip')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="country" class="form-label">Country</label>
                                            <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror" 
                                                   placeholder="Country" value="{{ old('country', $seller->country) }}">
                                            @error('country')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-12">
                                            <div class="gap-3 d-md-flex d-grid align-items-center">
                                                <button type="submit" class="px-4 btn btn-primary">
                                                    <i class="bi bi-check-circle me-2"></i> Update Profile
                                                </button>
                                                <a href="{{ route('seller.profile') }}" class="px-4 btn btn-light">
                                                    <i class="bi bi-x-circle me-2"></i> Cancel
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>  

                        <div class="col-12 col-xl-4">
                            <!-- About Card -->
                            <div class="card rounded-4">
                                <div class="card-body">
                                    <div class="mb-3 d-flex align-items-start justify-content-between">
                                        <div class="">
                                            <h5 class="mb-0 fw-bold">About</h5>
                                        </div>
                                    </div>
                                    
                                    <div class="full-info">
                                        <div class="gap-3 info-list d-flex flex-column">
                                            <div class="gap-3 info-list-item d-flex align-items-center">
                                                <span class="material-icons-outlined text-primary">account_circle</span>
                                                <p class="mb-0">Full Name: {{ $seller->name }}</p>
                                            </div>
                                            <div class="gap-3 info-list-item d-flex align-items-center">
                                                <span class="material-icons-outlined text-primary">done</span>
                                                <p class="mb-0">Status: <span class="text-capitalize">{{ $seller->status }}</span></p>
                                            </div>
                                            <div class="gap-3 info-list-item d-flex align-items-center">
                                                <span class="material-icons-outlined text-primary">code</span>
                                                <p class="mb-0">Role: <span class="text-capitalize">{{ $seller->role }}</span></p>
                                            </div>
                                            @if($seller->country)
                                            <div class="gap-3 info-list-item d-flex align-items-center">
                                                <span class="material-icons-outlined text-primary">flag</span>
                                                <p class="mb-0">Country: {{ $seller->country }}</p>
                                            </div>
                                            @endif
                                            <div class="gap-3 info-list-item d-flex align-items-center">
                                                <span class="material-icons-outlined text-primary">send</span>
                                                <p class="mb-0">Email: {{ $seller->email }}</p>
                                            </div>
                                            @if($seller->phone)
                                            <div class="gap-3 info-list-item d-flex align-items-center">
                                                <span class="material-icons-outlined text-primary">call</span>
                                                <p class="mb-0">Phone: {{ $seller->phone }}</p>
                                            </div>
                                            @endif
                                            @if($seller->business_name)
                                            <div class="gap-3 info-list-item d-flex align-items-center">
                                                <span class="material-icons-outlined text-primary">business</span>
                                                <p class="mb-0">Business: {{ $seller->business_name }}</p>
                                            </div>
                                            @endif
                                            @if($seller->gst_no)
                                            <div class="gap-3 info-list-item d-flex align-items-center">
                                                <span class="material-icons-outlined text-primary">receipt</span>
                                                <p class="mb-0">GST: {{ $seller->gst_no }}</p>
                                            </div>
                                            @endif
                                            @if($seller->seller_verified_at)
                                            <div class="gap-3 info-list-item d-flex align-items-center">
                                                <span class="material-icons-outlined text-success">verified</span>
                                                <p class="mb-0">Verified: {{ $seller->seller_verified_at->format('M d, Y') }}</p>
                                            </div>
                                            @endif
                                            <div class="gap-3 info-list-item d-flex align-items-center">
                                                <span class="material-icons-outlined text-primary">calendar_today</span>
                                                <p class="mb-0">Joined: {{ $seller->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Methods Tab -->
                <div class="tab-pane fade" id="payment" role="tabpanel">
                    <div class="row">
                        <div class="col-12 col-xl-8">
                            <div class="border-4 card rounded-4 border-top border-success border-gradient-1">
                                <div class="p-4 card-body">
                                    <div class="mb-3 d-flex align-items-start justify-content-between">
                                        <div class="">
                                            <h5 class="mb-0 fw-bold">Payment Methods</h5>
                                            <p class="">Add your payment details to receive withdrawals</p>
                                        </div>
                                    </div>
                                    
                                    <form action="{{ route('seller.profile.payment.update') }}" method="POST" class="row g-4">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <!-- Bank Transfer Section -->
                                        <div class="col-md-12">
                                            <div class="mb-3 d-flex align-items-center">
                                                <div class="p-2 bg-primary bg-opacity-10 rounded-circle me-2">
                                                    <i class="bi bi-bank text-primary"></i>
                                                </div>
                                                <h6 class="mb-0">Bank Transfer Details</h6>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="account_holder_name" class="form-label">Account Holder Name</label>
                                            <input type="text" name="account_holder_name" id="account_holder_name" 
                                                   class="form-control @error('account_holder_name') is-invalid @enderror" 
                                                   placeholder="Enter account holder name" 
                                                   value="{{ old('account_holder_name', $seller->account_holder_name ?? '') }}">
                                            @error('account_holder_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="account_number" class="form-label">Account Number</label>
                                            <input type="text" name="account_number" id="account_number" 
                                                   class="form-control @error('account_number') is-invalid @enderror" 
                                                   placeholder="Enter account number" 
                                                   value="{{ old('account_number', $seller->account_number ?? '') }}">
                                            @error('account_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="bank_name" class="form-label">Bank Name</label>
                                            <input type="text" name="bank_name" id="bank_name" 
                                                   class="form-control @error('bank_name') is-invalid @enderror" 
                                                   placeholder="Enter bank name" 
                                                   value="{{ old('bank_name', $seller->bank_name ?? '') }}">
                                            @error('bank_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="ifsc_code" class="form-label">IFSC Code</label>
                                            <input type="text" name="ifsc_code" id="ifsc_code" 
                                                   class="form-control @error('ifsc_code') is-invalid @enderror" 
                                                   placeholder="Enter IFSC code" 
                                                   value="{{ old('ifsc_code', $seller->ifsc_code ?? '') }}">
                                            @error('ifsc_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="">Format: SBIN0123456</small>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="account_type" class="form-label">Account Type</label>
                                            <select name="account_type" id="account_type" class="form-select">
                                                <option value="savings" {{ (old('account_type', $seller->account_type ?? '') == 'savings') ? 'selected' : '' }}>Savings Account</option>
                                                <option value="current" {{ (old('account_type', $seller->account_type ?? '') == 'current') ? 'selected' : '' }}>Current Account</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <hr class="my-3">
                                        </div>
                                        
                                        <!-- UPI Section -->
                                        <div class="col-md-12">
                                            <div class="mb-3 d-flex align-items-center">
                                                <div class="p-2 bg-success bg-opacity-10 rounded-circle me-2">
                                                    <i class="bi bi-phone text-success"></i>
                                                </div>
                                                <h6 class="mb-0">UPI / Razorpay Details</h6>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-8">
                                            <label for="upi_id" class="form-label">UPI ID</label>
                                            <input type="text" name="upi_id" id="upi_id" 
                                                   class="form-control @error('upi_id') is-invalid @enderror" 
                                                   placeholder="Enter UPI ID (e.g., name@okhdfcbank)" 
                                                   value="{{ old('upi_id', $seller->upi_id ?? '') }}">
                                            @error('upi_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="">Examples: yourname@okhdfcbank, yourname@paytm, yourname@ybl</small>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="upi_qr" class="form-label">UPI QR Code (Optional)</label>
                                            <input type="file" name="upi_qr" id="upi_qr" class="form-control" accept="image/*">
                                            <small class="">Upload QR code for UPI payments</small>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <hr class="my-3">
                                        </div>
                                        
                                        <!-- Payment Status Info -->
                                        @if($seller->account_number || $seller->upi_id)
                                        <div class="col-md-12">
                                            <div class="alert alert-success">
                                                <i class="bi bi-check-circle-fill me-2"></i>
                                                Your payment methods are configured. You can now request withdrawals.
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-md-12">
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle-fill me-2"></i>
                                                Add at least one payment method to receive withdrawals.
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <div class="col-md-12">
                                            <div class="gap-3 d-md-flex d-grid align-items-center">
                                                <button type="submit" class="px-4 btn btn-success">
                                                    <i class="bi bi-check-circle me-2"></i> Update Payment Methods
                                                </button>
                                                <a href="{{ route('seller.profile') }}" class="px-4 btn btn-light">
                                                    <i class="bi bi-x-circle me-2"></i> Cancel
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 col-xl-4">
                            <!-- Payment Info Card -->
                            <div class="card rounded-4">
                                <div class="card-body">
                                    <div class="mb-3 d-flex align-items-start justify-content-between">
                                        <div class="">
                                            <h5 class="mb-0 fw-bold">Withdrawal Information</h5>
                                        </div>
                                    </div>
                                    
                                    <div class="full-info">
                                        <div class="gap-3 info-list d-flex flex-column">
                                            <div class="info-list-item">
                                                <h6 class="mb-2 text-primary">Bank Transfer</h6>
                                                @if($seller->bank_name && $seller->account_number)
                                                    <p class="mb-1"><strong>Bank:</strong> {{ $seller->bank_name }}</p>
                                                    <p class="mb-1"><strong>Account:</strong> ****{{ substr($seller->account_number, -4) }}</p>
                                                    <p class="mb-0"><strong>IFSC:</strong> {{ $seller->ifsc_code }}</p>
                                                    <p class="mt-2 text-success"><i class="bi bi-check-circle"></i> Configured</p>
                                                @else
                                                    <p class="text-warning"><i class="bi bi-exclamation-triangle"></i> Not configured</p>
                                                @endif
                                            </div>
                                            
                                            <div class="info-list-item">
                                                <h6 class="mb-2 text-success">UPI / Razorpay</h6>
                                                @if($seller->upi_id)
                                                    <p class="mb-1"><strong>UPI ID:</strong> {{ $seller->upi_id }}</p>
                                                    <p class="mt-2 text-success"><i class="bi bi-check-circle"></i> Configured</p>
                                                @else
                                                    <p class="text-warning"><i class="bi bi-exclamation-triangle"></i> Not configured</p>
                                                @endif
                                            </div>
                                            
                                            <div class="info-list-item">
                                                <h6 class="mb-2 text-info">Important Notes</h6>
                                                <ul class="mb-0 ps-3">
                                                    <li>Withdrawals take 2-3 business days</li>
                                                    <li>Minimum withdrawal: ₹100</li>
                                                    <li>Bank transfers are free</li>
                                                    <li>UPI/Razorpay fee: 2%</li>
                                                    <li>Verify your bank details before withdrawal</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Security Tab -->
                <div class="tab-pane fade" id="security" role="tabpanel">
                    <div class="row">
                        <div class="col-12 col-xl-8">
                            <div class="border-4 card rounded-4 border-top border-warning border-gradient-1">
                                <div class="p-4 card-body">
                                    <div class="mb-3 d-flex align-items-start justify-content-between">
                                        <div class="">
                                            <h5 class="mb-0 fw-bold">Change Password</h5>
                                            <p class="">Update your password to keep your account secure</p>
                                        </div>
                                    </div>
                                    
                                    <form action="{{ route('seller.profile.password.update') }}" method="POST" class="row g-4">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <div class="col-md-12">
                                            <label for="current_password" class="form-label">Current Password</label>
                                            <input type="password" name="current_password" id="current_password" 
                                                   class="form-control @error('current_password') is-invalid @enderror" 
                                                   placeholder="Enter current password" required>
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="new_password" class="form-label">New Password</label>
                                            <input type="password" name="new_password" id="new_password" 
                                                   class="form-control @error('new_password') is-invalid @enderror" 
                                                   placeholder="Enter new password" required>
                                            @error('new_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                                            <input type="password" name="new_password_confirmation" id="confirm_password" 
                                                   class="form-control" placeholder="Confirm new password" required>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="gap-3 d-md-flex d-grid align-items-center">
                                                <button type="submit" class="px-4 btn btn-warning">
                                                    <i class="bi bi-shield-lock me-2"></i> Update Password
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 col-xl-4">
                            <!-- Security Info Card -->
                            <div class="card rounded-4">
                                <div class="card-body">
                                    <div class="mb-3 d-flex align-items-start justify-content-between">
                                        <div class="">
                                            <h5 class="mb-0 fw-bold">Security Tips</h5>
                                        </div>
                                    </div>
                                    
                                    <div class="full-info">
                                        <div class="gap-3 info-list d-flex flex-column">
                                            <div class="info-list-item">
                                                <i class="bi bi-key text-primary me-2"></i>
                                                <span>Use a strong, unique password</span>
                                            </div>
                                            <div class="info-list-item">
                                                <i class="bi bi-shield-check text-success me-2"></i>
                                                <span>Enable two-factor authentication</span>
                                            </div>
                                            <div class="info-list-item">
                                                <i class="bi bi-clock-history text-info me-2"></i>
                                                <span>Change password every 3 months</span>
                                            </div>
                                            <div class="info-list-item">
                                                <i class="bi bi-envelope text-warning me-2"></i>
                                                <span>Keep your email secure</span>
                                            </div>
                                        </div>
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

@include('seller.layouts.footer')

<style>
    .profile-avatar {
        transform: translate(-50%, -50%);
    }
    
    .profile-avatar .btn-primary {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .profile-avatar .btn-primary i {
        font-size: 16px;
    }
    
    .profile-info h3 {
        font-weight: 600;
    }
    
    .info-list-item {
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .info-list-item:last-child {
        border-bottom: none;
    }
    
    .info-list-item .material-icons-outlined,
    .info-list-item i {
        font-size: 20px;
        min-width: 24px;
    }
    
    .border-gradient-1 {
        border-image: linear-gradient(to right, #0d6efd, #6610f2) 1;
    }
    
    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 12px 20px;
        border-radius: 8px;
    }
    
    .nav-tabs .nav-link:hover {
        background-color: #f8f9fa;
    }
    
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        background-color: #e7f1ff;
        border: none;
    }
    
    .nav-tabs .nav-link i {
        font-size: 18px;
    }
    
    .card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .bg-opacity-10 {
        --bs-bg-opacity: 0.1;
    }
    
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }
        
        .profile-avatar {
            width: 120px;
            height: 120px;
        }
        
        .profile-avatar img,
        .profile-avatar div {
            width: 120px !important;
            height: 120px !important;
        }
        
        .nav-tabs .nav-link {
            padding: 8px 12px;
            font-size: 14px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle tab from URL hash
        if (window.location.hash) {
            const tab = document.querySelector(`[data-bs-target="${window.location.hash}"]`);
            if (tab) {
                new bootstrap.Tab(tab).show();
            }
        }
        
        // Update URL hash when tab changes
        const tabs = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                history.pushState(null, null, e.target.dataset.bsTarget);
            });
        });
        
        // Preview UPI QR code upload
        document.getElementById('upi_qr')?.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // You can show a preview here if needed
                    console.log('QR code selected');
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>