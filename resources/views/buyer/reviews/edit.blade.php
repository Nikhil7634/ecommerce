<x-header 
    title="Edit Review - {{ config('app.name', 'eCommerce') }}"
    description="Edit your product review"
/>

<x-navbar />

<section class="page_banner" style="background: url('{{ asset('assets/images/page_banner_bg.jpg') }}');">
    <div class="page_banner_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page_banner_text wow fadeInUp">
                        <h1>Edit Review</h1>
                        <ul>
                            <li><a href="{{ route('home') }}"><i class="fal fa-home-lg"></i> Home</a></li>
                            <li><a href="{{ route('buyer.dashboard') }}">Dashboard</a></li>
                            <li><a href="{{ route('buyer.reviews') }}">My Reviews</a></li>
                            <li class="active">Edit Review</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="dashboard mb_100">
    <div class="container">
        <div class="row">
            <x-aside />
            <div class="col-lg-9">
                <div class="dashboard_content mt_100">
                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <h3>Edit Your Review</h3>
                        <a href="{{ route('buyer.reviews') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Reviews
                        </a>
                    </div>
                    
                    <!-- Product Summary Card -->
                    <div class="mb-4 card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                @if($review->product && $review->product->images->first())
                                <img src="{{ asset('storage/' . $review->product->images->first()->image_path) }}" 
                                     alt="{{ $review->product->name }}" 
                                     class="rounded me-3"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                                @endif
                                <div>
                                    <h5 class="mb-1">{{ $review->product->name ?? 'Product' }}</h5>
                                    <p class="mb-0 text-muted">
                                        <i class="fas fa-calendar me-2"></i>
                                        Reviewed on {{ $review->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Edit Form -->
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('buyer.reviews.update', $review->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-4">
                                    <label class="mb-2 form-label fw-bold">Rating</label>
                                    <div class="p-3 rating-container bg-light rounded-3">
                                        @for($i = 5; $i >= 1; $i--)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rating" 
                                                   id="rating{{ $i }}" value="{{ $i }}"
                                                   {{ $review->rating == $i ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="rating{{ $i }}">
                                                @for($j = 1; $j <= 5; $j++)
                                                    @if($j <= $i)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                            </label>
                                        </div>
                                        @endfor
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="review" class="mb-2 form-label fw-bold">Your Review</label>
                                    <textarea name="review" id="review" class="form-control" rows="6" required>{{ $review->review }}</textarea>
                                    <div class="mt-2 text-end">
                                        <small class="text-muted" id="charCount">{{ strlen($review->review) }}/500</small>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    After editing, your review will be submitted for approval again.
                                </div>
                                
                                <div class="gap-3 d-flex">
                                    <button type="submit" class="px-5 btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Update Review
                                    </button>
                                    <a href="{{ route('buyer.reviews') }}" class="px-5 btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<x-footer />

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reviewText = document.getElementById('review');
        const charCount = document.getElementById('charCount');
        
        reviewText.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length + '/500';
            
            if (length > 500) {
                charCount.classList.add('text-danger');
                charCount.classList.remove('text-muted');
            } else {
                charCount.classList.remove('text-danger');
                charCount.classList.add('text-muted');
            }
        });
    });
</script>
@endpush