@include('seller.layouts.header')
@include('seller.layouts.topnavbar')
@include('seller.layouts.aside')

<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="mb-4 page-breadcrumb d-none d-sm-flex align-items-center">
            <div class="breadcrumb-title pe-3">Products</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="p-0 mb-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('seller.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active">Edit Product</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <!-- Success/Error Messages -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill"></i> Please fix the following errors:</h5>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="product-form">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Left Column - Main Content -->
                <div class="col-12 col-lg-8">
                    <div class="mb-4 card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Product Information</h5>
                        </div>
                        <div class="card-body">
                            <!-- Product Title -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Product Title <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                       placeholder="Enter product title" value="{{ old('name', $product->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description with Bootstrap Text Editor -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Product Description <span class="text-danger">*</span></label>
                                <div class="editor-container">
                                    <div id="description-editor" class="bg-white border rounded" style="min-height: 400px;">
                                        <div class="p-2 bg-light border-bottom">
                                            <div class="btn-toolbar" role="toolbar">
                                                <div class="mb-1 btn-group btn-group-sm me-2" role="group">
                                                    <button type="button" class="btn btn-outline-secondary" data-command="bold"><i class="bi bi-type-bold"></i></button>
                                                    <button type="button" class="btn btn-outline-secondary" data-command="italic"><i class="bi bi-type-italic"></i></button>
                                                    <button type="button" class="btn btn-outline-secondary" data-command="underline"><i class="bi bi-type-underline"></i></button>
                                                </div>
                                                <div class="mb-1 btn-group btn-group-sm me-2" role="group">
                                                    <button type="button" class="btn btn-outline-secondary" data-command="insertUnorderedList"><i class="bi bi-list-ul"></i></button>
                                                    <button type="button" class="btn btn-outline-secondary" data-command="insertOrderedList"><i class="bi bi-list-ol"></i></button>
                                                </div>
                                                <div class="mb-1 btn-group btn-group-sm me-2" role="group">
                                                    <button type="button" class="btn btn-outline-secondary" data-command="justifyLeft"><i class="bi bi-text-left"></i></button>
                                                    <button type="button" class="btn btn-outline-secondary" data-command="justifyCenter"><i class="bi bi-text-center"></i></button>
                                                    <button type="button" class="btn btn-outline-secondary" data-command="justifyRight"><i class="bi bi-text-right"></i></button>
                                                    <button type="button" class="btn btn-outline-secondary" data-command="justifyFull"><i class="bi bi-justify"></i></button>
                                                </div>
                                                <div class="mb-1 btn-group btn-group-sm me-2" role="group">
                                                    <button type="button" class="btn btn-outline-secondary" data-command="createLink"><i class="bi bi-link"></i></button>
                                                    <button type="button" class="btn btn-outline-secondary" data-command="unlink"><i class="bi bi-link-45deg"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-3" id="description-content" contenteditable="true" style="min-height: 350px;">
                                            {!! old('description', $product->description) !!}
                                        </div>
                                        <textarea id="description" name="description" class="d-none" required>{{ old('description', $product->description) }}</textarea>
                                    </div>
                                </div>
                                @error('description')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                                <div class="mt-2 form-text">
                                    You can format your text using the toolbar above. HTML tags will be preserved.
                                </div>
                            </div>

                            <!-- Current Images Section -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Current Product Images</label>
                                @if($product->images->count() > 0)
                                    <div id="current-images-container" class="mt-3 row g-3">
                                        @foreach($product->images as $index => $image)
                                        <div class="col-6 col-md-4 col-lg-3" id="image-container-{{ $image->id }}">
                                            <div class="image-preview-item">
                                                <div class="preview-index">{{ $index + 1 }}</div>
                                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product Image {{ $index + 1 }}">
                                                <div class="gap-2 p-2 d-flex justify-content-center bg-light border-top">
                                                    @if(!$image->is_primary)
                                                    <button type="button" class="btn btn-sm btn-outline-primary set-primary-btn" 
                                                            data-image-id="{{ $image->id }}">
                                                        <i class="bi bi-star"></i> Set Primary
                                                    </button>
                                                    @else
                                                    <span style="display: flex;align-items: center" class="px-2 py-1 badge bg-success">
                                                        <i class="bi bi-star-fill me-1"></i> Primary
                                                    </span>
                                                    @endif
                                                    
                                                    @if($product->images->count() > 1)
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-image-btn" 
                                                            data-image-id="{{ $image->id }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="bi bi-exclamation-triangle"></i> No images uploaded for this product.
                                    </div>
                                @endif
                            </div>

                            <!-- Multiple Images Upload -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Add More Images</label>
                                <div class="mb-3">
                                    <input type="file" name="images[]" id="image-upload" class="form-control" multiple accept="image/*">
                                    <div class="form-text">Upload additional images (JPG, PNG). Maximum size: 5MB per image.</div>
                                </div>
                                
                                <!-- New Image Preview Container -->
                                <div id="image-preview-container" class="mt-3 row g-3">
                                    <!-- Preview images will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory & Pricing Card -->
                    <div class="mb-4 card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Inventory & Pricing</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <ul class="mb-4 nav nav-tabs" id="inventoryTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="pricing-tab-btn" data-bs-toggle="tab" data-bs-target="#pricing-tab" type="button">Pricing</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="stock-tab-btn" data-bs-toggle="tab" data-bs-target="#stock-tab" type="button">Stock</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="shipping-tab-btn" data-bs-toggle="tab" data-bs-target="#shipping-tab" type="button">Shipping</button>
                                    </li>
                                </ul>

                                <div class="tab-content" id="inventoryTabContent">
                                    <!-- Pricing Tab -->
                                    <div class="tab-pane fade show active" id="pricing-tab" role="tabpanel">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Base Price (₹) <span class="text-danger">*</span></label>
                                                <input type="number" name="base_price" class="form-control @error('base_price') is-invalid @enderror" 
                                                       step="0.01" min="0" value="{{ old('base_price', $product->base_price) }}" required>
                                                @error('base_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Sale Price (₹) <span class="text-muted">(optional)</span></label>
                                                <input type="number" name="sale_price" class="form-control" 
                                                       step="0.01" min="0" value="{{ old('sale_price', $product->sale_price) }}">
                                            </div>
                                        </div>

                                        <!-- Tiered Pricing -->
                                        <div class="mt-4">
                                            <h6 class="pb-2 mb-3 border-bottom">Tiered Pricing (Bulk Discount)</h6>
                                            <div id="tiered-pricing">
                                                @foreach($product->tiers as $tierIndex => $tier)
                                                <div class="mb-3 tier-row row g-3 align-items-end">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Min Quantity</label>
                                                        <input type="number" name="tiers[{{ $tierIndex }}][min_qty]" 
                                                               class="form-control" placeholder="e.g. 500" min="1" 
                                                               value="{{ old('tiers.' . $tierIndex . '.min_qty', $tier->min_qty) }}" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Max Quantity <span class="text-muted">(optional)</span></label>
                                                        <input type="number" name="tiers[{{ $tierIndex }}][max_qty]" 
                                                               class="form-control" placeholder="e.g. 1999" min="1" 
                                                               value="{{ old('tiers.' . $tierIndex . '.max_qty', $tier->max_qty) }}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Price per unit (₹)</label>
                                                        <input type="number" name="tiers[{{ $tierIndex }}][price]" 
                                                               class="form-control" step="0.01" placeholder="e.g. 1.60" 
                                                               value="{{ old('tiers.' . $tierIndex . '.price', $tier->price) }}" required>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-danger remove-tier">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @if($product->tiers->isEmpty())
                                                <div class="mb-3 tier-row row g-3 align-items-end">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Min Quantity</label>
                                                        <input type="number" name="tiers[0][min_qty]" class="form-control" placeholder="e.g. 500" min="1" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Max Quantity <span class="text-muted">(optional)</span></label>
                                                        <input type="number" name="tiers[0][max_qty]" class="form-control" placeholder="e.g. 1999" min="1">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Price per unit (₹)</label>
                                                        <input type="number" name="tiers[0][price]" class="form-control" step="0.01" placeholder="e.g. 1.60" required>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-danger remove-tier" disabled>
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <button type="button" id="add-tier" class="btn btn-outline-primary">
                                                <i class="bi bi-plus-circle"></i> Add Tier
                                            </button>
                                            <div class="mt-2 alert alert-info">
                                                <small><i class="bi bi-info-circle"></i> Example: 500-1999 kg → ₹1.60 | 2000-3999 kg → ₹1.35 | ≥4000 kg → ₹0.90</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Stock Tab -->
                                    <div class="tab-pane fade" id="stock-tab" role="tabpanel">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Current Stock <span class="text-danger">*</span></label>
                                                <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" 
                                                       value="{{ old('stock', $product->stock) }}" required min="0">
                                                @error('stock')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Low Stock Alert</label>
                                                <input type="number" name="low_stock_threshold" class="form-control" 
                                                       value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" min="0">
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="allow_backorder" 
                                                           id="backorder" {{ old('allow_backorder', $product->allow_backorder) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="backorder">Allow backorders when out of stock</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Shipping Tab -->
                                    <div class="tab-pane fade" id="shipping-tab" role="tabpanel">
                                        <div class="alert alert-warning">
                                            <i class="bi bi-truck"></i> <strong>Note:</strong> Shipping is handled by you (Seller). Customers will contact you for delivery details.
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Weight (kg)</label>
                                                <input type="number" name="weight" class="form-control" step="0.01" 
                                                       placeholder="e.g. 1.5" min="0" value="{{ old('weight', $product->weight) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Dimensions</label>
                                                <div class="input-group">
                                                    <input type="number" name="length" class="form-control" placeholder="L" 
                                                           min="0" value="{{ old('length', $product->length) }}">
                                                    <span class="input-group-text">×</span>
                                                    <input type="number" name="width" class="form-control" placeholder="W" 
                                                           min="0" value="{{ old('width', $product->width) }}">
                                                    <span class="input-group-text">×</span>
                                                    <input type="number" name="height" class="form-control" placeholder="H" 
                                                           min="0" value="{{ old('height', $product->height) }}">
                                                    <span class="input-group-text">cm</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Sidebar -->
                <div class="col-12 col-lg-4">
                    <!-- Publish Card -->
                    <div class="mb-4 card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Publish</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="published" {{ old('status', $product->status) == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>
                            <div class="gap-2 d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle"></i> Update Product
                                </button>
                                <a href="{{ route('seller.products.index') }}" class="btn btn-outline-danger">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
                            </div>
                            <div class="mt-3 text-muted small">
                                <p class="mb-1">Created: {{ $product->created_at->format('M d, Y h:i A') }}</p>
                                <p class="mb-0">Last Updated: {{ $product->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Categories Card -->
                    <div class="mb-4 card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Categories <span class="text-danger">*</span></h5>
                        </div>
                        <div class="card-body">
                            <select name="category_ids[]" class="form-select select2" multiple required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ in_array($category->id, old('category_ids', $product->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_ids')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Select one or more categories</div>
                        </div>
                    </div>

                    <!-- Product Variants Card -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Product Variants <span class="text-muted">(Optional)</span></h5>
                        </div>
                        <div class="card-body">
                            <div id="variants-container">
                                @foreach($product->variants as $variantIndex => $variant)
                                <div class="p-3 mb-3 border rounded variant-row">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Color</label>
                                            <input type="text" name="variants[{{ $variantIndex }}][color]" 
                                                   class="form-control" placeholder="e.g. Red" 
                                                   value="{{ old('variants.' . $variantIndex . '.color', $variant->color) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Size</label>
                                            <input type="text" name="variants[{{ $variantIndex }}][size]" 
                                                   class="form-control" placeholder="e.g. M" 
                                                   value="{{ old('variants.' . $variantIndex . '.size', $variant->size) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Price Adjustment (₹)</label>
                                            <input type="number" name="variants[{{ $variantIndex }}][price_adjustment]" 
                                                   class="form-control" step="0.01" placeholder="0.00" 
                                                   value="{{ old('variants.' . $variantIndex . '.price_adjustment', $variant->price_adjustment) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Stock</label>
                                            <input type="number" name="variants[{{ $variantIndex }}][stock]" 
                                                   class="form-control" placeholder="0" 
                                                   value="{{ old('variants.' . $variantIndex . '.stock', $variant->stock) }}">
                                        </div>
                                        <div class="col-12 text-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-variant">
                                                <i class="bi bi-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @if($product->variants->isEmpty())
                                <div class="p-3 mb-3 border rounded variant-row">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Color</label>
                                            <input type="text" name="variants[0][color]" class="form-control" placeholder="e.g. Red">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Size</label>
                                            <input type="text" name="variants[0][size]" class="form-control" placeholder="e.g. M">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Price Adjustment (₹)</label>
                                            <input type="number" name="variants[0][price_adjustment]" class="form-control" step="0.01" placeholder="0.00">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Stock</label>
                                            <input type="number" name="variants[0][stock]" class="form-control" placeholder="0">
                                        </div>
                                        <div class="col-12 text-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-variant" disabled>
                                                <i class="bi bi-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <button type="button" id="add-variant" class="btn btn-outline-primary w-100">
                                <i class="bi bi-plus-circle"></i> Add Variant
                            </button>
                            <div class="mt-2 form-text">
                                Add different color/size combinations with separate pricing and stock
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

@include('seller.layouts.footer')

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Before closing body -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Before closing body -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Custom Styles for Better UI -->


<!-- Custom Styles for Better UI -->
<style>
    /* Image Preview Styling */
    .image-preview-item {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
    
    .image-preview-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }
    
    .image-preview-item .preview-remove {
        position: absolute;
        top: 5px;
        right: 5px;
        background: var(--bs-danger);
        color: var(--bs-white);
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    
    .image-preview-item .preview-index {
        position: absolute;
        top: 5px;
        left: 5px;
        background: var(    --bs-primary);
        color: var(--bs-white);
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }
    
    /* Better Card Styling */
    .card {
         
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
     #description-content{
        background: var(--bs-body-bg);
     }
    
    /* Form Controls */
    .form-control-lg {
        border-radius: 8px;
        padding: 12px 15px;
    }
    
    /* Tab Styling */
    .nav-tabs .nav-link {
        border: 1px solid transparent;
        border-radius: 8px 8px 0 0;
        padding: 10px 20px;
        font-weight: 500;
    }
    
    .nav-tabs .nav-link.active {
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
        color: var(--bs-primary);
    }
    
    /* Tier and Variant Rows */
    .tier-row, .variant-row {
         
        border-radius: 8px;
        padding: 15px;
    }
    
    /* Text Editor Styling */
    #description-editor {
         
        border-radius: 8px;
        overflow: hidden;
    }
    
    #description-content {
        outline: none;
        overflow-y: auto;
    }
    
    #description-content:empty:before {
        content: 'Start typing your product description here...';
        color: #6c757d;
    }
    
    #description-content p {
        margin-bottom: 1rem;
    }
    
    #description-content ul, #description-content ol {
        padding-left: 2rem;
        margin-bottom: 1rem;
    }
    
    .btn-toolbar .btn-group .btn {
        border-radius: 4px !important;
        margin-right: 2px;
    }
    
    .btn-toolbar .btn-group .btn:hover {
        background-color: #e9ecef;
    }
    
    .btn-toolbar .btn-group .btn.active {
        background-color: #0d6efd;
        color: white;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        #description-editor {
            min-height: 300px;
        }
        
        #description-content {
            min-height: 250px;
        }
        
        .image-preview-item img {
            height: 120px;
        }
    }
    
    .form-text{
      color: var(--bs-secondary-text);
    }
    .text-muted{
      color: var(--bs-secondary-text) !important;
    }
</style>

<style>
    .ajax-alert {
        animation: slideInRight 0.3s ease-out;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .image-preview-item {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
    
    .image-preview-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }
    
    .image-preview-item .preview-index {
        position: absolute;
        top: 5px;
        left: 5px;
        background: var(--bs-primary);
        color: var(--bs-white);
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
    }
    
    .image-preview-item .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>

<script>
// Select2 Configuration
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select categories",
        allowClear: true,
        width: '100%',
        theme: 'bootstrap-5'
    });
});

// Bootstrap Text Editor Functionality
document.addEventListener('DOMContentLoaded', function() {
    const editorContent = document.getElementById('description-content');
    const descriptionInput = document.getElementById('description');
    const editorToolbar = document.getElementById('description-editor').querySelector('.btn-toolbar');
    
    // Format text using execCommand
    editorToolbar.addEventListener('click', function(e) {
        const button = e.target.closest('button[data-command]');
        if (button) {
            e.preventDefault();
            const command = button.getAttribute('data-command');
            
            // Handle link creation
            if (command === 'createLink') {
                const url = prompt('Enter URL:', 'https://');
                if (url) {
                    document.execCommand('createLink', false, url);
                }
            } else if (command === 'unlink') {
                document.execCommand('unlink', false, null);
            } else {
                document.execCommand(command, false, null);
            }
            
            // Update hidden textarea
            updateDescription();
        }
    });
    
    // Update hidden textarea on content change
    editorContent.addEventListener('input', updateDescription);
    editorContent.addEventListener('blur', updateDescription);
    
    // Also update on paste events
    editorContent.addEventListener('paste', function(e) {
        setTimeout(updateDescription, 10);
    });
    
    function updateDescription() {
        descriptionInput.value = editorContent.innerHTML;
    }
});

// Image Preview Functionality for NEW images
document.getElementById('image-upload').addEventListener('change', function(e) {
    const container = document.getElementById('image-preview-container');
    const files = e.target.files;
    
    // Clear previous previews (only for new images)
    container.innerHTML = '';
    
    // Check file count (considering existing images)
    const existingImages = document.querySelectorAll('#current-images-container .image-preview-item').length;
    if (existingImages + files.length > 10) {
        alert('Maximum 10 images total allowed. You already have ' + existingImages + ' images.');
        this.value = '';
        return;
    }
    
    // Check each file
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
        // Check file size (5MB max)
        if (file.size > 5 * 1024 * 1024) {
            alert(`File "${file.name}" exceeds 5MB limit`);
            this.value = '';
            container.innerHTML = '';
            return;
        }
        
        // Check file type
        if (!file.type.match('image.*')) {
            alert(`File "${file.name}" is not an image`);
            this.value = '';
            container.innerHTML = '';
            return;
        }
        
        // Create preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const col = document.createElement('div');
            col.className = 'col-6 col-md-4 col-lg-3';
            col.innerHTML = `
                <div class="image-preview-item">
                    <div class="preview-index">New</div>
                    <img src="${e.target.result}" alt="Preview ${i + 1}">
                    <button type="button" class="preview-remove" data-index="${i}">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            `;
            container.appendChild(col);
        };
        reader.readAsDataURL(file);
    }
});

// Remove NEW image from preview
document.getElementById('image-preview-container').addEventListener('click', function(e) {
    if (e.target.closest('.preview-remove')) {
        const button = e.target.closest('.preview-remove');
        const index = parseInt(button.dataset.index);
        const input = document.getElementById('image-upload');
        
        // Create new FileList without the removed file
        const dt = new DataTransfer();
        for (let i = 0; i < input.files.length; i++) {
            if (i !== index) {
                dt.items.add(input.files[i]);
            }
        }
        input.files = dt.files;
        
        // Trigger change event to update preview
        input.dispatchEvent(new Event('change'));
    }
});

// Tiered Pricing Management
let tierIndex = {{ $product->tiers->count() > 0 ? $product->tiers->count() - 1 : 0 }};
document.getElementById('add-tier').addEventListener('click', function() {
    tierIndex++;
    const tierRow = document.createElement('div');
    tierRow.className = 'mb-3 tier-row row g-3 align-items-end';
    tierRow.innerHTML = `
        <div class="col-md-4">
            <label class="form-label">Min Quantity</label>
            <input type="number" name="tiers[${tierIndex}][min_qty]" class="form-control" placeholder="e.g. 500" min="1" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Max Quantity <span class="text-muted">(optional)</span></label>
            <input type="number" name="tiers[${tierIndex}][max_qty]" class="form-control" placeholder="e.g. 1999" min="1">
        </div>
        <div class="col-md-3">
            <label class="form-label">Price per unit (₹)</label>
            <input type="number" name="tiers[${tierIndex}][price]" class="form-control" step="0.01" placeholder="e.g. 1.60" required>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger remove-tier"><i class="bi bi-trash"></i></button>
        </div>
    `;
    document.getElementById('tiered-pricing').appendChild(tierRow);
    
    // Enable remove button for first tier if only one exists
    const tierRows = document.querySelectorAll('.tier-row');
    if (tierRows.length === 1) {
        tierRows[0].querySelector('.remove-tier').disabled = false;
    }
});

// Remove tier
document.getElementById('tiered-pricing').addEventListener('click', function(e) {
    if (e.target.closest('.remove-tier')) {
        const tierRow = e.target.closest('.tier-row');
        tierRow.remove();
        
        // Disable remove button if only one tier left
        const tierRows = document.querySelectorAll('.tier-row');
        if (tierRows.length === 1) {
            tierRows[0].querySelector('.remove-tier').disabled = true;
        }
    }
});

// Variants Management
let variantIndex = {{ $product->variants->count() > 0 ? $product->variants->count() - 1 : 0 }};
document.getElementById('add-variant').addEventListener('click', function() {
    variantIndex++;
    const variantRow = document.createElement('div');
    variantRow.className = 'p-3 mb-3 border rounded variant-row';
    variantRow.innerHTML = `
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Color</label>
                <input type="text" name="variants[${variantIndex}][color]" class="form-control" placeholder="e.g. Red">
            </div>
            <div class="col-md-6">
                <label class="form-label">Size</label>
                <input type="text" name="variants[${variantIndex}][size]" class="form-control" placeholder="e.g. M">
            </div>
            <div class="col-md-6">
                <label class="form-label">Price Adjustment (₹)</label>
                <input type="number" name="variants[${variantIndex}][price_adjustment]" class="form-control" step="0.01" placeholder="0.00">
            </div>
            <div class="col-md-6">
                <label class="form-label">Stock</label>
                <input type="number" name="variants[${variantIndex}][stock]" class="form-control" placeholder="0">
            </div>
            <div class="col-12 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger remove-variant">
                    <i class="bi bi-trash"></i> Remove
                </button>
            </div>
        </div>
    `;
    document.getElementById('variants-container').appendChild(variantRow);
    
    // Enable remove button for first variant if only one exists
    const variantRows = document.querySelectorAll('.variant-row');
    if (variantRows.length === 1) {
        variantRows[0].querySelector('.remove-variant').disabled = false;
    }
});

// Remove variant
document.getElementById('variants-container').addEventListener('click', function(e) {
    if (e.target.closest('.remove-variant')) {
        const variantRow = e.target.closest('.variant-row');
        variantRow.remove();
        
        // Disable remove button if only one variant left
        const variantRows = document.querySelectorAll('.variant-row');
        if (variantRows.length === 1) {
            variantRows[0].querySelector('.remove-variant').disabled = true;
        }
    }
});

// Form Validation
document.getElementById('product-form').addEventListener('submit', function(e) {
    const basePrice = document.querySelector('input[name="base_price"]').value;
    const categories = document.querySelector('select[name="category_ids[]"]');
    const description = document.getElementById('description-content').innerHTML.trim();
    
    // Check base price
    if (!basePrice || parseFloat(basePrice) <= 0) {
        e.preventDefault();
        alert('Please enter a valid base price');
        return false;
    }
    
    // Check categories
    if (categories.selectedOptions.length === 0) {
        e.preventDefault();
        alert('Please select at least one category');
        return false;
    }
    
    // Check description
    if (!description || description === '') {
        e.preventDefault();
        alert('Please enter product description');
        return false;
    }
    
    // Ensure description is saved to hidden field
    document.getElementById('description').value = description;
});



 

 
</script>


<script>
$(document).ready(function() {
    // Remove image using AJAX
    $(document).on('click', '.remove-image-btn', function() {
        const button = $(this);
        const imageId = button.data('image-id');
        const productId = {{ $product->id }};
        
        if (confirm('Are you sure you want to delete this image?')) {
            // Show loading
            button.html('<i class="bi bi-hourglass"></i>');
            button.prop('disabled', true);
            
            // Send AJAX request
            $.ajax({
                url: '/seller/products/' + productId + '/images/' + imageId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Remove image container
                        $('#image-container-' + imageId).remove();
                        
                        // Show success message
                        showAlert('success', response.message || 'Image deleted successfully.');
                        
                        // Update delete buttons if only one image left
                        const remainingImages = $('.remove-image-btn').length;
                        if (remainingImages <= 1) {
                            $('.remove-image-btn').prop('disabled', true)
                                .attr('title', 'Cannot delete the only image');
                        }
                    } else {
                        showAlert('danger', response.message || 'Failed to delete image.');
                        button.html('<i class="bi bi-trash"></i>');
                        button.prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    let message = 'An error occurred while deleting the image.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    showAlert('danger', message);
                    button.html('<i class="bi bi-trash"></i>');
                    button.prop('disabled', false);
                }
            });
        }
    });
    
    // Set primary image using AJAX
    $(document).on('click', '.set-primary-btn', function() {
        const button = $(this);
        const imageId = button.data('image-id');
        const productId = {{ $product->id }};
        
        // Show loading
        button.html('<i class="bi bi-hourglass"></i>');
        button.prop('disabled', true);
        
        // Send AJAX request
        $.ajax({
            url: '/seller/products/' + productId + '/images/' + imageId + '/set-primary',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Show success message
                    showAlert('success', response.message || 'Primary image updated successfully.');
                    
                    // Reload page after 1 second to update UI
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    showAlert('danger', response.message || 'Failed to set primary image.');
                    button.html('<i class="bi bi-star"></i> Set Primary');
                    button.prop('disabled', false);
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                let message = 'An error occurred while setting primary image.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showAlert('danger', message);
                button.html('<i class="bi bi-star"></i> Set Primary');
                button.prop('disabled', false);
            }
        });
    });
    
    // Helper function to show alerts
    function showAlert(type, message) {
        // Remove existing alerts
        $('.ajax-alert').remove();
        
        // Create alert
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show ajax-alert position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('body').append(alertHtml);
        
        // Auto remove after 5 seconds
        setTimeout(function() {
            $('.ajax-alert').alert('close');
        }, 5000);
    }
});
</script>