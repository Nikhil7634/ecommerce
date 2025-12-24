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

        <div class="row">
            <!-- Left Column - Edit Profile Form -->
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
                                <small class="text-muted">Email cannot be changed</small>
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

            <!-- Right Column - Profile Information -->
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
        </div><!--end row-->
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
    
    .info-list-item .material-icons-outlined {
        font-size: 20px;
        min-width: 24px;
    }
    
    .border-gradient-1 {
        border-image: linear-gradient(to right, #0d6efd, #6610f2) 1;
    }
    
    .bg-grd-danger {
        background: linear-gradient(45deg, #dc3545, #fd7e14);
    }
    
    .card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    
    .card-body {
        padding: 1.5rem;
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
    }
</style>