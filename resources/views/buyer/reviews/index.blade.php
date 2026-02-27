<x-header 
    title="My Reviews - {{ config('app.name', 'eCommerce') }}"
    description="View and manage all your product reviews"
    keywords="reviews, feedback, ratings"
    ogImage="{{ asset('assets/images/banner/home-og.jpg') }}"
/>

<x-navbar />

<!--=========================
    PAGE BANNER START
==========================-->
<section class="page_banner" style="background: url('{{ asset('assets/images/page_banner_bg.jpg') }}');">
    <div class="page_banner_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page_banner_text wow fadeInUp">
                        <h1>My Reviews</h1>
                        <ul>
                            <li><a href="{{ route('home') }}"><i class="fal fa-home-lg"></i> Home</a></li>
                            <li><a href="{{ route('buyer.dashboard') }}">Dashboard</a></li>
                            <li class="active">My Reviews</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--=========================
    PAGE BANNER END
==========================-->

<!--============================
    REVIEWS PAGE START
=============================-->
<section class="dashboard mb_100">
    <div class="container">
        <div class="row">
            <x-aside />
            <div class="col-lg-9">
                <div class="dashboard_content mt_100">
                    <div class="mb-4 dashboard_heading">
                        <div class="flex-wrap gap-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h3>My Reviews</h3>
                                <p class="text-muted">Manage all your product reviews and ratings</p>
                            </div>
                            <div>
                                <a href="{{ route('shop') }}" class="btn btn-primary">
                                    <i class="fas fa-shopping-bag me-2"></i> Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="mb-4 row g-3">
                        <div class="col-md-3 col-6">
                            <div class="border-0 shadow-sm card stat-card">
                                <div class="text-center card-body">
                                    <h3 class="mb-2 text-primary">{{ $totalReviews ?? 0 }}</h3>
                                    <p class="mb-0 text-muted">Total Reviews</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="border-0 shadow-sm card stat-card">
                                <div class="text-center card-body">
                                    <h3 class="mb-2 text-success">{{ number_format($averageRating ?? 0, 1) }}</h3>
                                    <p class="mb-0 text-muted">Average Rating</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="border-0 shadow-sm card stat-card">
                                <div class="text-center card-body">
                                    <h3 class="mb-2 text-warning">{{ $pendingReviews ?? 0 }}</h3>
                                    <p class="mb-0 text-muted">Pending Approval</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="border-0 shadow-sm card stat-card">
                                <div class="text-center card-body">
                                    <h3 class="mb-2 text-info">{{ $productsReviewed ?? 0 }}</h3>
                                    <p class="mb-0 text-muted">Products Reviewed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============ PENDING REVIEWS SECTION - NEW ============ -->
                    @if(isset($pendingReviewItems) && $pendingReviewItems->count() > 0)
                    <div class="mb-5">
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-clock text-warning me-2"></i>
                                Products Awaiting Your Review
                            </h4>
                            <span class="p-2 badge bg-warning text-dark rounded-pill">
                                {{ $pendingReviewItems->count() }} item(s)
                            </span>
                        </div>
                        
                        <div class="row g-3">
                            @foreach($pendingReviewItems as $item)
                            <div class="col-md-6">
                                <div class="border-0 shadow-sm card pending-review-card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <!-- Product Image -->
                                            <div class="flex-shrink-0 me-3">
                                                @if($item->product && $item->product->images->first())
                                                    <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="rounded-3"
                                                         style="width: 80px; height: 80px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" 
                                                         style="width: 80px; height: 80px;">
                                                        <i class="fas fa-image fa-2x text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Product Details -->
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $item->product->name ?? 'Product' }}</h6>
                                                <p class="mb-2 text-muted small">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    Purchased on {{ $item->created_at->format('M d, Y') }}
                                                </p>
                                                
                                                <div class="gap-2 d-flex">
                                                    <!-- Write Review Button -->
                                                    <a href="{{ route('buyer.reviews.create', ['order' => $item->order_id, 'product_id' => $item->product_id]) }}" 
                                                       class="btn btn-sm btn-warning">
                                                        <i class="fas fa-star me-1"></i>
                                                        Write Review
                                                    </a>
                                                    
                                                    <!-- View Order Button -->
                                                    <a href="{{ route('buyer.order.show', $item->order_id) }}" 
                                                       class="btn btn-sm btn-outline-secondary"
                                                       title="View Order">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- View All Orders Link -->
                        <div class="mt-3 text-center">
                            <a href="{{ route('buyer.orders') }}?status=delivered" class="btn btn-link text-decoration-none">
                                View all delivered orders <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    @endif

                    <!-- Filters -->
                    <div class="mb-4 card">
                        <div class="card-body">
                            <form action="{{ route('buyer.reviews') }}" method="GET" id="filter-form">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select" onchange="this.form.submit()">
                                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Reviews</option>
                                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Rating</label>
                                        <select name="rating" class="form-select" onchange="this.form.submit()">
                                            <option value="">All Ratings</option>
                                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Star</option>
                                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Star & Up</option>
                                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Star & Up</option>
                                            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Star & Up</option>
                                            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star & Up</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Search</label>
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @if(request()->has('status') || request()->has('rating') || request()->has('search'))
                                <div class="mt-3">
                                    <a href="{{ route('buyer.reviews') }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i> Clear Filters
                                    </a>
                                </div>
                                @endif
                            </form>
                        </div>
                    </div>

                    <!-- Reviews List -->
                    @if($reviews->count() > 0)
                        @foreach($reviews as $review)
                        <div class="mb-3 card review-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 col-4">
                                        @if($review->product && $review->product->images->first())
                                            <img src="{{ asset('storage/' . $review->product->images->first()->image_path) }}" 
                                                 alt="{{ $review->product->name }}" 
                                                 class="rounded img-fluid"
                                                 style="max-width: 100px; max-height: 100px; object-fit: cover;">
                                        @else
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 100px; height: 100px;">
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-10 col-8">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5 class="mb-1">
                                                    <a href="{{ route('product.show', $review->product->slug ?? '#') }}" class="text-decoration-none">
                                                        {{ $review->product->name ?? 'Product' }}
                                                    </a>
                                                </h5>
                                                <div class="mb-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-warning"></i>
                                                        @endif
                                                    @endfor
                                                    <span class="ms-2 text-muted">{{ $review->rating }}/5</span>
                                                </div>
                                                <p class="review-text">{{ $review->review }}</p>
                                                
                                                @if($review->images && count($review->images) > 0)
                                                <div class="mt-2 review-images">
                                                    <small class="mb-1 text-muted d-block">Review Images:</small>
                                                    <div class="gap-2 d-flex">
                                                        @foreach($review->images as $image)
                                                        <a href="{{ asset('storage/' . $image) }}" target="_blank">
                                                            <img src="{{ asset('storage/' . $image) }}" 
                                                                 alt="Review Image" 
                                                                 class="rounded" 
                                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                                        </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                                
                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        <i class="far fa-calendar-alt me-1"></i> {{ $review->created_at->format('M d, Y') }}
                                                        @if($review->updated_at && $review->updated_at != $review->created_at)
                                                            <span class="ms-2 text-info">(Edited)</span>
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                @php
                                                    $statusClass = [
                                                        'approved' => 'success',
                                                        'pending' => 'warning',
                                                        'rejected' => 'danger',
                                                    ][$review->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }} mb-2">
                                                    {{ ucfirst($review->status) }}
                                                </span>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editReviewModal{{ $review->id }}">
                                                                <i class="fas fa-edit me-2"></i> Edit Review
                                                            </a>
                                                        </li>
                                                        @if($review->status == 'pending')
                                                        <li>
                                                            <a class="dropdown-item text-warning" href="#" onclick="alert('Your review is pending approval. You cannot delete it while pending.')">
                                                                <i class="fas fa-clock me-2"></i> Pending Approval
                                                            </a>
                                                        </li>
                                                        @else
                                                        <li>
                                                            <form action="{{ route('buyer.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fas fa-trash me-2"></i> Delete Review
                                                                </button>
                                                            </form>
                                                        </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if($review->seller_reply)
                                        <div class="p-3 mt-3 rounded bg-light seller-reply">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-store text-primary fa-2x"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">Seller Response</h6>
                                                    <p class="mb-1">{{ $review->seller_reply }}</p>
                                                    <small class="text-muted">
                                                        <i class="far fa-clock me-1"></i> {{ $review->replied_at ? $review->replied_at->format('M d, Y') : '' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Review Modal -->
                        <div class="modal fade" id="editReviewModal{{ $review->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="text-white modal-header bg-primary">
                                        <h5 class="text-white modal-title">
                                            <i class="fas fa-edit me-2"></i>Edit Your Review
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    
                                    <form action="{{ route('buyer.reviews.update', $review->id) }}" method="POST" class="edit-review-form">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="modal-body">
                                            <!-- Rating Selection -->
                                            <div class="mb-4">
                                                <label class="mb-2 form-label fw-bold">Your Rating</label>
                                                <div class="p-3 rating-edit-container bg-light rounded-3">
                                                    <div class="flex-wrap gap-3 d-flex">
                                                        @for($i = 5; $i >= 1; $i--)
                                                        <div class="rating-edit-option">
                                                            <input type="radio" 
                                                                name="rating" 
                                                                id="edit_rating_{{ $review->id }}_{{ $i }}" 
                                                                value="{{ $i }}"
                                                                class="btn-check rating-edit-input"
                                                                {{ $review->rating == $i ? 'checked' : '' }}
                                                                required>
                                                            <label for="edit_rating_{{ $review->id }}_{{ $i }}" 
                                                                class="btn btn-outline-warning rating-edit-label">
                                                                @for($j = 1; $j <= 5; $j++)
                                                                    @if($j <= $i)
                                                                        <i class="fas fa-star"></i>
                                                                    @else
                                                                        <i class="far fa-star"></i>
                                                                    @endif
                                                                @endfor
                                                            </label>
                                                        </div>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Review Text -->
                                            <div class="mb-3">
                                                <label for="edit_review_{{ $review->id }}" class="mb-2 form-label fw-bold">
                                                    Your Review
                                                </label>
                                                <textarea name="review" 
                                                        id="edit_review_{{ $review->id }}" 
                                                        class="form-control" 
                                                        rows="5" 
                                                        required>{{ $review->review }}</textarea>
                                                <div class="mt-2 text-end">
                                                    <small class="text-muted edit-char-counter" id="editCharCounter{{ $review->id }}">
                                                        {{ strlen($review->review) }}/500
                                                    </small>
                                                </div>
                                            </div>
                                            
                                            <!-- Note about re-approval -->
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <strong>Note:</strong> After editing, your review will be submitted for approval again.
                                            </div>
                                        </div>
                                        
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-2"></i>Cancel
                                            </button>
                                            <button type="submit" class="btn btn-primary" id="submitEdit{{ $review->id }}">
                                                <i class="fas fa-save me-2"></i>Update Review
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!-- Pagination -->
                        @if($reviews->hasPages())
                        <div class="mt-4 d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Showing {{ $reviews->firstItem() ?? 0 }} to {{ $reviews->lastItem() ?? 0 }} of {{ $reviews->total() }} reviews
                            </div>
                            <div>
                                {{ $reviews->appends(request()->query())->links() }}
                            </div>
                        </div>
                        @endif
                    @else
                        <!-- No Reviews -->
                        <div class="py-5 text-center">
                            <div class="mb-4">
                                <i class="fas fa-star fa-5x text-muted"></i>
                            </div>
                            <h3 class="text-muted">No Reviews Found</h3>
                            <p class="mb-4 text-muted">
                                @if(request()->has('search') || request()->has('status') || request()->has('rating'))
                                    No reviews match your filters. Try adjusting your search criteria.
                                @else
                                    You haven't written any reviews yet. Reviews appear after you purchase products.
                                @endif
                            </p>
                            @if(request()->has('search') || request()->has('status') || request()->has('rating'))
                                <a href="{{ route('buyer.reviews') }}" class="btn btn-primary">
                                    <i class="fas fa-times me-2"></i> Clear Filters
                                </a>
                            @else
                                <a href="{{ route('shop') }}" class="btn btn-primary">
                                    <i class="fas fa-shopping-bag me-2"></i> Start Shopping
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<!--============================
    REVIEWS PAGE END
=============================-->

<x-footer />

@push('styles')
<style>
    .stat-card {
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    
    .pending-review-card {
        transition: all 0.3s ease;
        border-left: 4px solid #ffc107 !important;
        border-radius: 12px !important;
        overflow: hidden;
    }
    
    .pending-review-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    .pending-review-card .btn-warning {
        transition: all 0.3s ease;
    }
    
    .pending-review-card .btn-warning:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
    }
    
    .review-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
        border-radius: 12px !important;
        overflow: hidden;
    }
    
    .review-card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        border-left-color: #0d6efd;
    }
    
    .review-text {
        font-size: 0.95rem;
        line-height: 1.6;
        color: #495057;
        margin-bottom: 10px;
    }
    
    .seller-reply {
        border-left: 3px solid #0d6efd;
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    
    /* Edit Rating Styles */
    .rating-edit-option {
        display: inline-block;
    }
    
    .rating-edit-label {
        padding: 8px 15px;
        border-radius: 30px;
        transition: all 0.3s ease;
        font-size: 14px;
    }
    
    .rating-edit-label i {
        margin-right: 2px;
        font-size: 14px;
    }
    
    .rating-edit-input:checked + .rating-edit-label {
        background-color: #ffc107;
        color: #000;
        border-color: #ffc107;
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(255, 193, 7, 0.3);
    }
    
    .rating-edit-input:checked + .rating-edit-label i {
        color: #000;
    }
    
    .rating-edit-label:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .btn-check:focus + .rating-edit-label {
        box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
    }
    
    .edit-char-counter {
        font-size: 0.875rem;
        padding: 4px 12px;
        background-color: #f8f9fa;
        border-radius: 20px;
        display: inline-block;
    }
    
    .edit-char-counter.text-danger {
        background-color: #f8d7da;
        color: #721c24 !important;
    }
    
    .badge {
        padding: 6px 12px;
        font-weight: 500;
    }
    
    @media (max-width: 768px) {
        .dashboard_heading .d-flex {
            flex-direction: column;
            align-items: start !important;
            gap: 15px;
        }
        
        .btn-primary {
            width: 100%;
        }
        
        .pending-review-card .card-body .d-flex {
            flex-direction: column;
            text-align: center;
        }
        
        .pending-review-card .flex-shrink-0 {
            margin-right: 0 !important;
            margin-bottom: 15px;
        }
        
        .review-card .row {
            flex-direction: column;
        }
        
        .review-card .col-md-2,
        .review-card .col-md-10 {
            width: 100%;
            text-align: center;
        }
        
        .review-card .col-md-2 img,
        .review-card .col-md-2 div {
            margin: 0 auto 15px;
        }
        
        .review-card .d-flex {
            flex-direction: column;
            align-items: center !important;
            text-align: center;
        }
        
        .review-card .dropdown {
            margin-top: 10px;
        }
        
        .seller-reply .d-flex {
            flex-direction: row;
            text-align: left;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle rating stars preview in edit modal
        const ratingInputs = document.querySelectorAll('.rating-edit-input');
        
        ratingInputs.forEach(input => {
            input.addEventListener('change', function() {
                const container = this.closest('.rating-edit-container');
                const labels = container.querySelectorAll('.rating-edit-label');
                
                labels.forEach(label => {
                    const stars = label.querySelectorAll('i');
                    const inputId = label.getAttribute('for');
                    const radio = document.getElementById(inputId);
                    
                    if (radio && radio.checked) {
                        // This is the selected rating
                        stars.forEach(star => {
                            star.classList.remove('far');
                            star.classList.add('fas');
                        });
                        label.classList.add('active');
                    } else {
                        // Reset other ratings
                        stars.forEach(star => {
                            star.classList.remove('fas');
                            star.classList.add('far');
                        });
                        label.classList.remove('active');
                    }
                });
            });
        });
        
        // Character count for edit review textareas
        const editTextareas = document.querySelectorAll('textarea[id^="edit_review_"]');
        
        editTextareas.forEach(textarea => {
            const reviewId = textarea.id.split('_').pop();
            const counter = document.getElementById('editCharCounter' + reviewId);
            
            if (counter) {
                textarea.addEventListener('input', function() {
                    const length = this.value.length;
                    counter.textContent = length + '/500';
                    
                    if (length > 500) {
                        counter.classList.add('text-danger');
                        counter.classList.remove('text-muted');
                    } else if (length > 450) {
                        counter.classList.add('text-warning');
                        counter.classList.remove('text-danger', 'text-muted');
                    } else {
                        counter.classList.remove('text-danger', 'text-warning');
                        counter.classList.add('text-muted');
                    }
                });
            }
        });
        
        // Initialize rating displays on modal open
        const modals = document.querySelectorAll('.modal[id^="editReviewModal"]');
        
        modals.forEach(modal => {
            modal.addEventListener('shown.bs.modal', function() {
                const checkedRadio = this.querySelector('.rating-edit-input:checked');
                if (checkedRadio) {
                    checkedRadio.dispatchEvent(new Event('change'));
                }
            });
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    });
</script>
@endpush