@include('seller.layouts.header')
@include('seller.layouts.topnavbar')
@include('seller.layouts.aside')

<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="mb-4 page-breadcrumb d-none d-sm-flex align-items-center">
            <div class="breadcrumb-title pe-3">Settings</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="p-0 mb-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Settings</li>
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

        @php
            // Get store from the loaded relationship
            $store = auth()->user()->store;
            
            // Calculate profile completion
            $completion = 0;
            if($store) {
                $fields = ['name', 'description', 'logo', 'address', 'phone', 'email'];
                $filled = 0;
                foreach($fields as $field) {
                    if(!empty($store->$field)) $filled++;
                }
                $completion = round(($filled / count($fields)) * 100);
            }
        @endphp

        <div class="row">
            <!-- Left Column - Settings Navigation -->
            <div class="col-12 col-xl-3">
                <div class="mb-4 card rounded-4">
                    <div class="p-3 card-body">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active" id="v-pills-general-tab" data-bs-toggle="pill" 
                                    data-bs-target="#v-pills-general" type="button" role="tab">
                                <i class="bi bi-gear me-2"></i> Store Settings
                            </button>
                            <button class="nav-link" id="v-pills-security-tab" data-bs-toggle="pill" 
                                    data-bs-target="#v-pills-security" type="button" role="tab">
                                <i class="bi bi-shield-lock me-2"></i> Security
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Store Stats -->
                <div class="card rounded-4">
                    <div class="p-3 card-body">
                        <h6 class="mb-3 fw-semibold">Store Status</h6>
                        
                        @if($store)
                        <div class="mb-2 d-flex justify-content-between">
                            <span >Profile Complete</span>
                            <span class="fw-semibold">{{ $completion }}%</span>
                        </div>
                        <div class="mb-3 progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $completion }}%"></div>
                        </div>
                        
                        <div class="mb-2 d-flex justify-content-between">
                            <span >Store Status</span>
                            <span class="fw-semibold badge bg-{{ $store->status == 'active' ? 'success' : ($store->status == 'inactive' ? 'warning' : 'danger') }}">
                                {{ ucfirst($store->status) }}
                            </span>
                        </div>
                        <div class="mb-2 d-flex justify-content-between">
                            <span >Total Sales</span>
                            <span class="fw-semibold">{{ $store->total_sales }}</span>
                        </div>
                        <div class="mb-2 d-flex justify-content-between">
                            <span >Rating</span>
                            <span class="fw-semibold">{{ number_format($store->rating, 1) }}/5</span>
                        </div>
                        @if($store->slug)
                        <div class="mb-2 d-flex justify-content-between">
                            <span >Store URL</span>
                            <a href="{{ url('/store/' . $store->slug) }}" target="_blank" class="text-primary text-decoration-underline">
                                View Store
                            </a>
                        </div>
                        @endif
                        @else
                        <div class="py-3 text-center">
                            <i class="mb-3 bi bi-shop display-6 text-muted"></i>
                            <p class="mb-0 text-muted">Store not created yet</p>
                            <small>Complete the form to create your store</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Settings Content -->
            <div class="col-12 col-xl-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <!-- Store Settings Tab -->
                    <div class="tab-pane fade show active" id="v-pills-general" role="tabpanel">
                        <div class="card rounded-4">
                            <div class="p-4 card-body">
                                <div class="mb-4 d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-0 fw-bold">{{ $store ? 'Update Store Settings' : 'Create Your Store' }}</h5>
                                        <p class="mb-0 text-muted">{{ $store ? 'Update your store information' : 'Create your store to start selling' }}</p>
                                    </div>
                                </div>

                                <form action="{{ route('seller.settings.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <!-- Hidden slug field -->
                                    <input type="hidden" name="slug" id="store_slug_hidden" 
                                           value="{{ old('slug', $store->slug ?? '') }}">

                                    <!-- Store Information -->
                                    <div class="mb-4">
                                        <h6 class="pb-2 mb-3 fw-semibold border-bottom">Store Information</h6>
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label for="store_name" class="form-label">Store Name *</label>
                                                <input type="text" name="name" id="store_name" 
                                                       class="form-control @error('name') is-invalid @enderror" 
                                                       value="{{ old('name', $store->name ?? '') }}"
                                                       placeholder="Your store name" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-12">
                                                <div class="alert alert-info">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    Store URL will be automatically generated: 
                                                    <strong id="store_url_preview">
                                                        {{ config('app.url') }}/store/<span id="slug_preview">{{ $store->slug ?? 'your-store-name' }}</span>
                                                    </strong>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12">
                                                <label for="store_description" class="form-label">Store Description</label>
                                                <textarea name="description" id="store_description" 
                                                          class="form-control @error('description') is-invalid @enderror" 
                                                          rows="4"
                                                          placeholder="Tell customers about your store">{{ old('description', $store->description ?? '') }}</textarea>
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Store Media -->
                                    <div class="mb-4">
                                        <h6 class="pb-2 mb-3 fw-semibold border-bottom">Store Media</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="store_logo" class="form-label">Store Logo</label>
                                                <input type="file" name="logo" id="store_logo" 
                                                       class="form-control @error('logo') is-invalid @enderror" 
                                                       accept="image/*">
                                                @error('logo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                @if($store && $store->logo)
                                                <div class="mt-2">
                                                    <small>Current Logo:</small><br>
                                                    <img src="{{ Storage::url($store->logo) }}" alt="Store Logo" 
                                                         style="max-width: 100px; max-height: 100px;" class="mt-1 border rounded">
                                                </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <label for="store_banner" class="form-label">Store Banner</label>
                                                <input type="file" name="banner" id="store_banner" 
                                                       class="form-control @error('banner') is-invalid @enderror" 
                                                       accept="image/*">
                                                @error('banner')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                @if($store && $store->banner)
                                                <div class="mt-2">
                                                    <small>Current Banner:</small><br>
                                                    <img src="{{ Storage::url($store->banner) }}" alt="Store Banner" 
                                                         style="max-width: 150px; max-height: 100px;" class="mt-1 border rounded">
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="mb-4">
                                        <h6 class="pb-2 mb-3 fw-semibold border-bottom">Contact Information</h6>
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label for="store_address" class="form-label">Store Address</label>
                                                <textarea name="address" id="store_address" 
                                                          class="form-control @error('address') is-invalid @enderror" 
                                                          rows="3"
                                                          placeholder="Your store address">{{ old('address', $store->address ?? '') }}</textarea>
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label for="store_phone" class="form-label">Store Phone</label>
                                                <input type="text" name="phone" id="store_phone" 
                                                       class="form-control @error('phone') is-invalid @enderror" 
                                                       value="{{ old('phone', $store->phone ?? '') }}"
                                                       placeholder="+91 9876543210">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label for="store_email" class="form-label">Store Email</label>
                                                <input type="email" name="email" id="store_email" 
                                                       class="form-control @error('email') is-invalid @enderror" 
                                                       value="{{ old('email', $store->email ?? '') }}"
                                                       placeholder="store@example.com">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="pt-3 border-top">
                                        <button type="submit" class="px-4 btn btn-primary">
                                            <i class="bi bi-save me-2"></i> {{ $store ? 'Update Store Settings' : 'Create Store' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Security Tab -->
                    <div class="tab-pane fade" id="v-pills-security" role="tabpanel">
                        <div class="card rounded-4">
                            <div class="p-4 card-body">
                                <div class="mb-4 d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-0 fw-bold">Security Settings</h5>
                                        <p class="mb-0 text-muted">Manage your account security</p>
                                    </div>
                                </div>

                                <!-- Change Password Form -->
                                <div class="mb-4">
                                    <h6 class="pb-2 mb-3 fw-semibold border-bottom">Change Password</h6>
                                    <form action="{{ route('seller.settings.password.update') }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <div class="row g-3">
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
                                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                                                       class="form-control" placeholder="Confirm new password" required>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-key me-2"></i> Update Password
                                                </button>
                                            </div>
                                        </div>
                                    </form>
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
    .nav-pills .nav-link {
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 8px;
        color: var(--bs-body-color);
        font-weight: 500;
        text-align: left;
    }
    
    .nav-pills .nav-link.active {
        background-color: #0d6efd;
        color: white;
    }
    
    .nav-pills .nav-link i {
        font-size: 18px;
        width: 24px;
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
        
        .nav-pills .nav-link {
            padding: 10px 12px;
            font-size: 0.9rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const storeNameInput = document.getElementById('store_name');
    const slugPreview = document.getElementById('slug_preview');
    const slugHiddenInput = document.getElementById('store_slug_hidden');
    
    // Get current slug
    const currentSlug = slugHiddenInput.value || '';
    
    // Function to generate slug
    function generateSlug(text) {
        let slug = text.toLowerCase()
            .replace(/[^\w\s]/gi, '')
            .replace(/\s+/g, '-')
            .replace(/--+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');

        // Check availability via AJAX
        checkSlugAvailability(slug);
    }

    // Check slug availability via AJAX
    function checkSlugAvailability(slug) {
        const url = '{{ route("seller.settings.check-slug") }}?slug=' + encodeURIComponent(slug) + 
                    '&current=' + encodeURIComponent(currentSlug);
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                slugHiddenInput.value = data.slug;
                slugPreview.textContent = data.slug;
            })
            .catch(error => {
                console.error('Error checking slug:', error);
                // Fallback - just use the generated slug
                slugHiddenInput.value = slug;
                slugPreview.textContent = slug;
            });
    }

    // Auto-generate slug when store name changes
    storeNameInput.addEventListener('input', function() {
        if (this.value.trim()) {
            generateSlug(this.value);
        } else {
            slugPreview.textContent = 'your-store-name';
            slugHiddenInput.value = '';
        }
    });

    // Initialize on page load if there's a store name
    if (storeNameInput.value.trim()) {
        // Only generate if no slug exists yet
        if (!currentSlug || currentSlug === 'your-store-name') {
            generateSlug(storeNameInput.value);
        }
    }
});
</script>