<!--start header-->
@include('admin.layouts.header')
<!--end top header-->

@include('admin.layouts.navbar')

<!--start sidebar-->
@include('admin.layouts.aside')

<div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
        <h2>Add New Subscription Plan</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.subscriptions.index') }}">Subscription Plans</a></li>
                <li class="breadcrumb-item active">Add New</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="mx-auto col-lg-8">
            <div class="panel">
                <div class="panel-header">
                    <h5>Create Subscription Plan</h5>
                </div>
                <div class="panel-body">
                    <form action="{{ route('admin.subscriptions.store') }}" method="POST">
                        @csrf

                        <div class="row g-4">
                            <!-- Plan Name -->
                            <div class="col-12">
                                <label class="form-label">Plan Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Price & Duration -->
                            <div class="col-md-6">
                                <label class="form-label">Price (₹) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', '0.00') }}" required>
                                @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Duration <span class="text-danger">*</span></label>
                                <select name="duration" class="form-select" required>
                                    <option value="1_month" {{ old('duration') == '1_month' ? 'selected' : '' }}>1 Month</option>
                                    <option value="3_months" {{ old('duration') == '3_months' ? 'selected' : '' }}>3 Months</option>
                                    <option value="6_months" {{ old('duration') == '6_months' ? 'selected' : '' }}>6 Months</option>
                                    <option value="1_year" {{ old('duration') == '1_year' ? 'selected' : '' }}>1 Year</option>
                                    <option value="2_years" {{ old('duration') == '2_years' ? 'selected' : '' }}>2 Years</option>
                                    <option value="3_years" {{ old('duration') == '3_years' ? 'selected' : '' }}>3 Years</option>
                                    <option value="4_years" {{ old('duration') == '4_years' ? 'selected' : '' }}>4 Years</option>
                                </select>
                            </div>

                            <!-- Search Boost -->
                            <div class="col-12">
                                <label class="form-label">Search Boost Multiplier <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="search_boost" class="form-control" value="{{ old('search_boost', '1.00') }}" required>
                                <small class="text-muted">e.g., 1.00 = normal, 2.00 = 2x higher in search results</small>
                                @error('search_boost') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Features (One per line) -->
                            <div class="col-12">
                                <label class="form-label">Features (one per line) <span class="text-danger">*</span></label>
                                <textarea name="features" rows="8" class="form-control" placeholder="Unlimited products&#10;Featured store listing&#10;Priority support&#10;No commission fee" required>{{ old('features') }}</textarea>
                                <small class="text-muted">Enter each feature on a new line. These will be displayed as a bullet list on the frontend.</small>
                                @error('features') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">Plan is Active</label>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="col-12 text-end">
                                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create Plan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.layouts.footer')