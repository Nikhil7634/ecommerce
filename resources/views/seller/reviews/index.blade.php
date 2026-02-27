@include('seller.layouts.header')
@include('seller.layouts.topnavbar')
@include('seller.layouts.aside')

<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="mb-3 page-breadcrumb d-none d-sm-flex align-items-center">
            <div class="breadcrumb-title pe-3">Reviews</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="p-0 mb-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Product Reviews</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="bx bx-printer"></i> Print
                    </button>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="mb-0 ">Total Reviews</p>
                                <h4 class="mb-0">{{ $totalReviews ?? 0 }}</h4>
                                <small class="text-success">All time reviews</small>
                            </div>
                            <div class="widgets-icons bg-light-primary text-primary">
                                <i class="bx bx-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="mb-0 ">Average Rating</p>
                                <h4 class="mb-0">{{ number_format($averageRating ?? 0, 1) }} <small class="fs-6">/5</small></h4>
                                <small class="text-success">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($averageRating ?? 0))
                                            <i class="bx bxs-star text-warning"></i>
                                        @else
                                            <i class="bx bx-star "></i>
                                        @endif
                                    @endfor
                                </small>
                            </div>
                            <div class="widgets-icons bg-light-warning text-warning">
                                <i class="bx bx-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="mb-0 ">Products Reviewed</p>
                                <h4 class="mb-0">{{ $productsWithReviews ?? 0 }}</h4>
                                <small class="text-success">With customer feedback</small>
                            </div>
                            <div class="widgets-icons bg-light-success text-success">
                                <i class="bx bx-package"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="mb-0 ">Pending Moderation</p>
                                <h4 class="mb-0">{{ $pendingReviews ?? 0 }}</h4>
                                <small class="text-warning">Awaiting approval</small>
                            </div>
                            <div class="widgets-icons bg-light-danger text-danger">
                                <i class="bx bx-time"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-3 card radius-10">
            <div class="card-body">
                <form action="{{ route('seller.reviews') }}" method="GET" id="filter-form">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Rating</label>
                            <select name="rating" class="form-select">
                                <option value="">All Ratings</option>
                                <option value="5" {{ request('rating') == 5 ? 'selected' : '' }}>5 Star</option>
                                <option value="4" {{ request('rating') == 4 ? 'selected' : '' }}>4 Star & Up</option>
                                <option value="3" {{ request('rating') == 3 ? 'selected' : '' }}>3 Star & Up</option>
                                <option value="2" {{ request('rating') == 2 ? 'selected' : '' }}>2 Star & Up</option>
                                <option value="1" {{ request('rating') == 1 ? 'selected' : '' }}>1 Star & Up</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search by customer or product" value="{{ request('search') }}">
                        </div>
                        
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-filter-alt"></i> Apply
                            </button>
                            <a href="{{ route('seller.reviews') }}" class="btn btn-secondary">
                                <i class="bx bx-reset"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reviews Table -->
        <div class="card radius-10">
            <div class="card-body">
                <div class="mb-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Product Reviews</h5>
                    <span class="">Total {{ $reviews->total() }} reviews</span>
                </div>
                
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Rating</th>
                                <th>Review</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $review)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($review->product && $review->product->images->first())
                                        <div class="flex-shrink-0 me-2">
                                            <img src="{{ asset('storage/' . $review->product->images->first()->image_path) }}" 
                                                 alt="" width="40" height="40" class="rounded" style="object-fit: cover;">
                                        </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{ $review->product ? $review->product->name : 'Product' }}</h6>
                                            <small class="">#{{ $review->product_id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <h6 class="mb-0">{{ $review->user ? $review->user->name : 'Customer' }}</h6>
                                        <small class="">{{ $review->user ? $review->user->email : '' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="bx bxs-star"></i>
                                            @else
                                                <i class="bx bx-star"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-1 text-dark">({{ $review->rating }}/5)</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="review-text">
                                        <p class="mb-0">{{ Str::limit($review->comment, 60) }}</p>
                                        @if($review->comment && strlen($review->comment) > 60)
                                        <a href="#" class="small text-primary" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $review->id }}">Read More</a>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $review->created_at->format('d M Y') }}
                                        <br>
                                        <small class="">{{ $review->created_at->format('h:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'pending' => 'bg-warning text-dark',
                                            'approved' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                        ][$review->status] ?? 'bg-secondary';
                                        
                                        $statusIcons = [
                                            'pending' => 'bx-time',
                                            'approved' => 'bx-check-circle',
                                            'rejected' => 'bx-x-circle',
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusClass }}">
                                        <i class="bx {{ $statusIcons[$review->status] ?? 'bx-time' }} me-1"></i>
                                        {{ ucfirst($review->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="gap-2 d-flex">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary" 
                                                title="View Details"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#reviewModal{{ $review->id }}">
                                            <i class="bx bx-show"></i>
                                        </button>
                                        
                                        @if($review->status == 'pending')
                                        <form action="{{ route('seller.reviews.approve', $review->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Approve">
                                                <i class="bx bx-check"></i>
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('seller.reviews.reject', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this review?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Reject">
                                                <i class="bx bx-x"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Review Modal -->
                            <div class="modal fade" id="reviewModal{{ $review->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Review Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <h6>Product</h6>
                                                <p>{{ $review->product ? $review->product->name : 'Product' }}</p>
                                            </div>
                                            <div class="mb-3">
                                                <h6>Customer</h6>
                                                <p>{{ $review->user ? $review->user->name : 'Customer' }} ({{ $review->user ? $review->user->email : '' }})</p>
                                            </div>
                                            <div class="mb-3">
                                                <h6>Rating</h6>
                                                <div class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="bx bxs-star fs-4"></i>
                                                        @else
                                                            <i class="bx bx-star fs-4"></i>
                                                        @endif
                                                    @endfor
                                                    <span class="ms-2">{{ $review->rating }}/5</span>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <h6>Review</h6>
                                                <p class="mb-0">{{ $review->comment }}</p>
                                            </div>
                                            <div class="mb-3">
                                                <h6>Date</h6>
                                                <p>{{ $review->created_at->format('d M Y, h:i A') }}</p>
                                            </div>
                                            <div class="mb-3">
                                                <h6>Status</h6>
                                                <span class="badge {{ $statusClass }}">{{ ucfirst($review->status) }}</span>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            @if($review->status == 'pending')
                                            <form action="{{ route('seller.reviews.approve', $review->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success">
                                                    <i class="bx bx-check"></i> Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('seller.reviews.reject', $review->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="bx bx-x"></i> Reject
                                                </button>
                                            </form>
                                            @endif
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="7" class="py-5 text-center">
                                    <img src="{{ asset('seller-assets/assets/images/no-reviews.png') }}" alt="No reviews" width="120" class="mb-3">
                                    <h5 class="">No reviews found</h5>
                                    <p class="mb-0 ">Your customers haven't left any reviews yet.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($reviews->hasPages())
                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <div class="">
                        Showing {{ $reviews->firstItem() ?? 0 }} to {{ $reviews->lastItem() ?? 0 }} of {{ $reviews->total() }} reviews
                    </div>
                    <div>
                        {{ $reviews->appends(request()->query())->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>

@include('seller.layouts.footer')

@push('styles')
<style>
    .widgets-icons {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 24px;
    }
    
    .bg-light-primary {
        background-color: rgba(13, 110, 253, 0.1);
    }
    
    .bg-light-success {
        background-color: rgba(25, 135, 84, 0.1);
    }
    
    .bg-light-warning {
        background-color: rgba(255, 193, 7, 0.1);
    }
    
    .bg-light-danger {
        background-color: rgba(220, 53, 69, 0.1);
    }
    
    .table > :not(caption) > * > * {
        vertical-align: middle;
    }
    
    .badge {
        padding: 5px 10px;
        font-weight: 500;
    }
    
    .review-text {
        max-width: 250px;
    }
    
    .modal-body h6 {
        color: #0d6efd;
        margin-bottom: 0.5rem;
    }
    
    .bx {
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-submit filters on change for rating and status dropdowns
    document.querySelector('select[name="rating"]')?.addEventListener('change', function() {
        document.getElementById('filter-form').submit();
    });
    
    document.querySelector('select[name="status"]')?.addEventListener('change', function() {
        document.getElementById('filter-form').submit();
    });
    
    // Handle bulk actions
    function bulkAction(action) {
        const selected = document.querySelectorAll('input[name="review_ids[]"]:checked');
        if (selected.length === 0) {
            alert('Please select at least one review.');
            return;
        }
        
        const form = document.getElementById('bulk-form');
        form.action = '{{ route("seller.reviews.bulk") }}';
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        form.appendChild(actionInput);
        
        form.submit();
    }
</script>
@endpush