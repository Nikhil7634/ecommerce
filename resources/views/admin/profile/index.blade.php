<!--start header-->
@include('admin.layouts.header')
<!--end top header-->

@include('admin.layouts.navbar')

<!--start sidebar-->
@include('admin.layouts.aside')

<!-- main content start -->
<div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
        <h2>My Profile</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Profile</li>
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

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Profile Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="panel">
                <div class="text-center panel-body">
                    <div class="mb-4 position-relative">
                        <div class="mx-auto avatar avatar-xxl">
                            @if($admin->avatar)
                                <img src="{{ asset('storage/' . $admin->avatar) }}" 
                                     alt="{{ $admin->name }}" 
                                     class="border border-4 rounded-circle border-primary"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="mx-auto text-white bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 150px; height: 150px; font-size: 48px;">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        
                        <button type="button" class="bottom-0 btn btn-sm btn-primary position-absolute end-0 translate-middle" 
                                data-bs-toggle="modal" data-bs-target="#avatarModal">
                            <i class="fa-light fa-camera"></i>
                        </button>
                    </div>

                    <h4 class="mb-1">{{ $admin->name }}</h4>
                    <p class="mb-3 text-muted">{{ $admin->email }}</p>
                    
                    <div class="gap-2 mb-3 d-flex justify-content-center">
                        <span class="px-3 py-2 badge bg-primary">{{ ucfirst($admin->role) }}</span>
                        <span class="px-3 py-2 badge bg-success">Active</span>
                    </div>

                    <div class="pt-3 text-start border-top">
                        <div class="mb-2">
                            <i class="fa-light fa-phone me-2 text-primary"></i>
                            <span>{{ $admin->phone ?? 'Not provided' }}</span>
                        </div>
                        <div class="mb-2">
                            <i class="fa-light fa-envelope me-2 text-primary"></i>
                            <span>{{ $admin->email }}</span>
                        </div>
                        <div class="mb-2">
                            <i class="fa-light fa-location-dot me-2 text-primary"></i>
                            <span>{{ $admin->address ?? 'Not provided' }}</span>
                        </div>
                        <div class="mb-2">
                            <i class="fa-light fa-calendar me-2 text-primary"></i>
                            <span>Joined {{ $admin->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Form -->
        <div class="col-xl-8 col-lg-7">
            <div class="panel">
                <div class="panel-header">
                    <h5>Edit Profile</h5>
                </div>
                <div class="panel-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $admin->name) }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $admin->email) }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $admin->phone) }}">
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="3">{{ old('address', $admin->address) }}</textarea>
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-light fa-save me-2"></i>Update Profile
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="mt-4 panel">
                <div class="panel-header">
                    <h5>Change Password</h5>
                </div>
                <div class="panel-body">
                    <form action="{{ route('admin.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label">Current Password <span class="text-danger">*</span></label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">New Password <span class="text-danger">*</span></label>
                                <input type="password" name="new_password" class="form-control" required>
                                <small class="text-muted">Minimum 8 characters</small>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                <input type="password" name="new_password_confirmation" class="form-control" required>
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fa-light fa-key me-2"></i>Change Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- main content end -->

<!-- Avatar Upload Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.profile.avatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-4 text-center">
                        @if($admin->avatar)
                            <img src="{{ asset('storage/' . $admin->avatar) }}" 
                                 alt="{{ $admin->name }}" 
                                 class="border border-4 rounded-circle border-primary"
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="mx-auto text-white bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 120px; height: 120px; font-size: 40px;">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Choose Image</label>
                        <input type="file" name="avatar" class="form-control" accept="image/*" required>
                        <small class="text-muted">Allowed: jpeg, png, jpg, gif (Max: 2MB)</small>
                    </div>
                    
                    <div id="imagePreview" class="text-center" style="display: none;">
                        <img src="#" alt="Preview" class="rounded img-fluid" style="max-height: 150px;">
                    </div>
                </div>
                <div class="modal-footer">
                    @if($admin->avatar)
                    <button type="button" class="btn btn-danger" onclick="removeAvatar()">
                        <i class="fa-light fa-trash me-2"></i>Remove
                    </button>
                    @endif
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-light fa-upload me-2"></i>Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Remove Avatar Form -->
@if($admin->avatar)
<form id="removeAvatarForm" action="{{ route('admin.profile.avatar.remove') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endif

@include('admin.layouts.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image preview
        document.querySelector('input[name="avatar"]')?.addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const img = preview.querySelector('img');
            
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(this.files[0]);
            } else {
                preview.style.display = 'none';
            }
        });
    });

    function removeAvatar() {
        if (confirm('Are you sure you want to remove your profile picture?')) {
            document.getElementById('removeAvatarForm').submit();
        }
    }
</script>
 