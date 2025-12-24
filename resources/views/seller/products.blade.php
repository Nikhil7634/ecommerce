@include('seller.layouts.header')
@include('seller.layouts.topnavbar')
@include('seller.layouts.aside')

<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="mb-3 page-breadcrumb d-none d-sm-flex align-items-center">
            <div class="breadcrumb-title pe-3">Products</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="p-0 mb-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item " aria-current="page">All Products</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>Add New Product
                    </a>
                </div>
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

        <!-- Product Count Stats -->
        <div class="flex-wrap gap-3 mb-4 product-count d-flex align-items-center gap-lg-4 fw-medium font-text1">
            <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}" 
               class="{{ request('status', 'all') == 'all' ? 'text-primary' : 'text-body' }}">
                <span class="me-1">All</span>
                <span class="text-secondary">({{ $products->total() }})</span>
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'published']) }}" 
               class="{{ request('status') == 'published' ? 'text-primary' : 'text-body' }}">
                <span class="me-1">Published</span>
                <span class="text-secondary">({{ $publishedCount }})</span>
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'draft']) }}" 
               class="{{ request('status') == 'draft' ? 'text-primary' : 'text-body' }}">
                <span class="me-1">Drafts</span>
                <span class="text-secondary">({{ $draftCount }})</span>
            </a>
        </div>

        <!-- Search and Filters -->
        <form method="GET" action="{{ route('seller.products.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="position-relative">
                        <input type="text" name="search" class="form-control ps-5" 
                               placeholder="Search products..." 
                               value="{{ request('search') }}">
                        <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="gap-2 d-flex">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <!-- Products Table -->
        <div class="card">
            <div class="card-body">
                <div class="product-table">
                    <div class="table-responsive white-space-nowrap">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <input class="form-check-input" type="checkbox" id="select-all">
                                    </th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Categories</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td>
                                        <input class="form-check-input product-checkbox" type="checkbox" value="{{ $product->id }}">
                                    </td>
                                    <td>
                                        <div class="gap-3 d-flex align-items-center">
                                            <div class="product-box">
                                                @php
                                                    // Get primary image or first image as fallback
                                                    $primaryImage = $product->images->where('is_primary', true)->first();
                                                    if (!$primaryImage && $product->images->count() > 0) {
                                                        $primaryImage = $product->images->first();
                                                    }
                                                @endphp
                                                
                                                @if($primaryImage)
                                                    <img src="{{ asset('storage/' . $primaryImage->image_path) }}" 
                                                        width="70" height="70" class="rounded-3 object-fit-cover" alt="{{ $product->name }}">
                                                @else
                                                    <img src="https://placehold.co/70x70?text=No+Image" 
                                                        width="70" height="70" class="rounded-3" alt="No Image">
                                                @endif
                                            </div>
                                            <div class="product-info">
                                                <a href="javascript:;" class="product-title">{{ Str::limit($product->name, 10) }}</a>
                                                <p class="mb-0 product-category ">{{ Str::limit($product->description, 10) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">₹{{ number_format($product->sale_price, 2) }}</span>
                                            @if($product->sale_price)
                                                <small class="text-danger text-decoration-line-through">₹{{ number_format($product->base_price, 2) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                                            {{ $product->stock }} in stock
                                        </span>
                                    </td>
                                    <td>
                                        @if($product->categories->count() > 0)
                                            @foreach($product->categories->take(2) as $category)
                                                <span class="badge bg-secondary">{{ $category->name }}</span>
                                            @endforeach
                                            @if($product->categories->count() > 2)
                                                <span class="badge bg-secondary">+{{ $product->categories->count() - 2 }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">No categories</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $product->status == 'published' ? 'success' : 'warning' }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $product->created_at->format('M d, Y') }}
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-filter dropdown-toggle dropdown-toggle-nocaret"
                                                type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('seller.products.edit', $product->id) }}">
                                                        <i class="bi bi-pencil me-2"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <form id="delete-form-{{ $product->id }}" 
                                                          action="{{ route('seller.products.destroy', $product->id) }}" 
                                                          method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a class="dropdown-item text-danger" href="#"
                                                           onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this product?')) { document.getElementById('delete-form-{{ $product->id }}').submit(); }">
                                                            <i class="bi bi-trash me-2"></i> Delete
                                                        </a>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="py-4 text-center">
                                        <div class="py-5">
                                            <i class="bi bi-box display-4 text-muted"></i>
                                            <h5 class="mt-3">No products found</h5>
                                            <p class="text-muted">Start by adding your first product.</p>
                                            <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus-lg me-2"></i>Add Product
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($products->hasPages())
                    <div class="mt-4 d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} entries
                        </div>
                        <nav aria-label="Page navigation">
                            <ul class="mb-0 pagination">
                                {{ $products->appends(request()->query())->links() }}
                            </ul>
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>

@include('seller.layouts.footer')

<style>
  .text-body {
      color: var(--bs-body-color) !important;
  }
</style>

<!-- JavaScript for Bulk Actions -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all checkbox
        const selectAll = document.getElementById('select-all');
        const productCheckboxes = document.querySelectorAll('.product-checkbox');
        
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                productCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }
        
        // Update select all when individual checkboxes change
        productCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(productCheckboxes).every(cb => cb.checked);
                selectAll.checked = allChecked;
            });
        });
    });
</script>

<!-- Custom CSS -->
 