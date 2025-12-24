<!--start header-->
@include('admin.layouts.header')
<!--end top header-->

@include('admin.layouts.navbar')

<!--start sidebar-->
@include('admin.layouts.aside')

<div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
        <h2>System Settings</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item ">Settings</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>General Settings</h5>
                </div>
                <div class="panel-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="row g-4">
                            <!-- Site Name -->
                            <div class="col-md-6">
                                <label class="form-label">Site Name <span class="text-danger">*</span></label>
                                <input type="text" name="site_name" class="form-control" value="{{ old('site_name', setting('site_name')) }}" required>
                            </div>

                            <!-- Admin Email -->
                            <div class="col-md-6">
                                <label class="form-label">Admin Email <span class="text-danger">*</span></label>
                                <input type="email" name="admin_email" class="form-control" value="{{ old('admin_email', setting('admin_email')) }}" required>
                            </div>

                            <!-- Contact Number — FIXED -->
                            <div class="col-md-6">
                                <label class="form-label">Contact Number</label>
                                <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', setting('contact_number')) }}" placeholder="+91 98765 43210">
                            </div>

                            <!-- Commission Rate -->
                            <div class="col-md-6">
                                <label class="form-label">Commission Rate (%) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" max="100" name="commission_rate" class="form-control" value="{{ old('commission_rate', setting('commission_rate')) }}" required>
                                <small class="text-muted">Percentage taken from seller earnings</small>
                            </div>

                            <!-- Copyright Text -->
                            <div class="col-12">
                                <label class="form-label">Copyright Text</label>
                                <input type="text" name="copyright_text" class="form-control" value="{{ old('copyright_text', setting('copyright_text')) }}">
                            </div>

                            <!-- Social Media Links -->
                            <div class="col-md-6">
                                <label class="form-label">Facebook URL</label>
                                <input type="url" name="social_facebook" class="form-control" value="{{ old('social_facebook', setting('social_facebook')) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Instagram URL</label>
                                <input type="url" name="social_instagram" class="form-control" value="{{ old('social_instagram', setting('social_instagram')) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Twitter URL</label>
                                <input type="url" name="social_twitter" class="form-control" value="{{ old('social_twitter', setting('social_twitter')) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">LinkedIn URL</label>
                                <input type="url" name="social_linkedin" class="form-control" value="{{ old('social_linkedin', setting('social_linkedin')) }}">
                            </div>

                            

                            <!-- Logo Upload -->
                            <div class="col-md-6">
                                <label class="form-label">Site Logo</label>
                                <input type="file" name="site_logo" class="form-control" accept="image/*">
                                <small class="text-muted">Recommended: 200x60px transparent PNG</small>

                                @if(setting('site_logo'))
                                    <div class="mt-3">
                                        <p>Current Logo:</p>
                                        <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="Current Logo" class="img-thumbnail" style="max-height:80px;">
                                    </div>
                                @endif
                            </div>
                           

                            <!-- Favicon -->
                            <div class="col-md-6">
                                <label class="form-label">Favicon</label>
                                <input type="file" name="favicon" class="form-control" accept="image/*,.ico">
                                <small class="text-muted">16x16 or 32x32px (ICO or PNG)</small>

                                @if(setting('favicon'))
                                    <div class="mt-3">
                                        <p>Current Favicon:</p>
                                        <img src="{{ asset('storage/' . setting('favicon')) }}" alt="Favicon" style="width:32px;height:32px;">
                                    </div>
                                @endif
                            </div>


                            <div class="col-12">
                                <hr>
                                <h5>Razorpay Payment Gateway Settings</h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Razorpay Key ID</label>
                                <input type="text" name="razorpay_key" class="form-control" value="{{ setting('razorpay_key') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Razorpay Secret Key</label>
                                <input type="password" name="razorpay_secret" class="form-control" value="{{ setting('razorpay_secret') }}">
                            </div>

                            <!-- Submit -->
                            <div class="mt-5 col-12 text-end">
                                <button type="submit" class="px-5 btn btn-primary">
                                    <i class="fa-light fa-save me-2"></i> Save All Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.layouts.footer')