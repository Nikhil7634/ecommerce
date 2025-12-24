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
                        <li class="breadcrumb-item active">Add New Product</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" id="product-form">
            @csrf

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
                                <input type="text" name="name" class="form-control form-control-lg" placeholder="Enter product title" required>
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
                                        <div class="p-3" id="description-content" contenteditable="true" style="min-height: 350px;"></div>
                                        <textarea id="description" name="description" class="d-none" required></textarea>
                                    </div>
                                </div>
                                <div class="mt-2 form-text">
                                    You can format your text using the toolbar above. HTML tags will be preserved.
                                </div>
                            </div>

                            <!-- Multiple Images with Preview -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Product Images <span class="text-danger">*</span></label>
                                <div class="mb-3">
                                    <input type="file" name="images[]" id="image-upload" class="form-control" multiple accept="image/*" required>
                                    <div class="form-text">Upload up to 10 images (JPG, PNG). First image will be used as thumbnail. Maximum size: 5MB per image.</div>
                                </div>
                                
                                <!-- Image Preview Container -->
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
                                                <input type="number" name="base_price" class="form-control" step="0.01" min="0" required>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label">Sale Price (₹) <span class="text-muted">(optional)</span></label>
                                                <input type="number" name="sale_price" class="form-control" step="0.01" min="0">
                                            </div>
                                        </div>

                                        <!-- Tiered Pricing -->
                                        <div class="mt-4">
                                            <h6 class="pb-2 mb-3 border-bottom">Tiered Pricing (Bulk Discount)</h6>
                                            <div id="tiered-pricing">
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
                                                        <button type="button" class="btn btn-danger remove-tier" disabled><i class="bi bi-trash"></i></button>
                                                    </div>
                                                </div>
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
                                                <input type="number" name="stock" class="form-control" required min="0">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Low Stock Alert</label>
                                                <input type="number" name="low_stock_threshold" class="form-control" value="10" min="0">
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="allow_backorder" id="backorder" value="1">
                                                    <label class="form-check-label" for="backorder">
                                                        Allow backorders when out of stock
                                                    </label>
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
                                                <input type="number" name="weight" class="form-control" step="0.01" placeholder="e.g. 1.5" min="0">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Dimensions</label>
                                                <div class="input-group">
                                                    <input type="number" name="length" class="form-control" placeholder="L" min="0">
                                                    <span class="input-group-text">×</span>
                                                    <input type="number" name="width" class="form-control" placeholder="W" min="0">
                                                    <span class="input-group-text">×</span>
                                                    <input type="number" name="height" class="form-control" placeholder="H" min="0">
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
                            <div class="gap-2 d-grid">
                                <button type="submit" name="status" value="published" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle"></i> Publish Product
                                </button>
                                <button type="submit" name="status" value="draft" class="btn btn-outline-secondary">
                                    <i class="bi bi-save"></i> Save as Draft
                                </button>
                                <a href="{{ route('seller.products.index') }}" class="btn btn-outline-danger">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
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
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
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
    
    // Initialize with empty value
    updateDescription();
});

// Image Preview Functionality
document.getElementById('image-upload').addEventListener('change', function(e) {
    const container = document.getElementById('image-preview-container');
    const files = e.target.files;
    
    // Clear previous previews
    container.innerHTML = '';
    
    // Check file count
    if (files.length > 10) {
        alert('Maximum 10 images allowed');
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
                    <div class="preview-index">${i + 1}</div>
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

// Remove image from preview
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
let tierIndex = 0;
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
    
    // Enable remove button for first tier
    const firstRemoveBtn = document.querySelector('.remove-tier');
    if (firstRemoveBtn) {
        firstRemoveBtn.disabled = false;
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
let variantIndex = 0;
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
    
    // Enable remove button for first variant
    const firstRemoveBtn = document.querySelector('.remove-variant');
    if (firstRemoveBtn) {
        firstRemoveBtn.disabled = false;
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
    const images = document.getElementById('image-upload').files;
    const basePrice = document.querySelector('input[name="base_price"]').value;
    const categories = document.querySelector('select[name="category_ids[]"]');
    const description = document.getElementById('description-content').innerHTML.trim();
    
    // Check if images are uploaded
    if (images.length === 0) {
        e.preventDefault();
        alert('Please upload at least one product image');
        return false;
    }
    
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