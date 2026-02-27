<x-header 
    title="Write a Review - {{ config('app.name', 'eCommerce') }}"
    description="Share your experience with this product"
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
                        <h1>Write a Review</h1>
                        <ul>
                            <li><a href="{{ route('home') }}"><i class="fal fa-home-lg"></i> Home</a></li>
                            <li><a href="{{ route('buyer.dashboard') }}">Dashboard</a></li>
                            <li><a href="{{ route('buyer.orders') }}">Orders</a></li>
                            <li><a href="{{ route('buyer.order.show', $orderId) }}">Order #{{ $orderId }}</a></li>
                            <li class="active">Write Review</li>
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
    WRITE REVIEW START
=============================-->
<section class="dashboard mb_100">
    <div class="container">
        <div class="row">
            <x-aside />
            <div class="col-lg-9">
                <div class="dashboard_content mt_100">
                    
                    <!-- Header with Actions -->
                    <div class="flex-wrap mb-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h3>Write a Review</h3>
                            <p class="mb-0 text-muted">Share your experience and help other shoppers</p>
                        </div>
                        <a href="{{ route('buyer.order.show', $orderId) }}" class="mt-2 mt-sm-0 btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Order
                        </a>
                    </div>

                    <!-- Product Summary Card -->
                    <div class="mb-4 overflow-hidden border-0 shadow-sm card">
                        <div class="p-0 row g-0">
                            <div class="col-md-3">
                                @if($product->images->first())
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                         alt="{{ $product->name }}" 
                                         class="img-fluid h-100 w-100"
                                         style="object-fit: cover; min-height: 180px;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center h-100 w-100" 
                                         style="min-height: 180px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <div class="p-4 card-body">
                                    <h4 class="mb-2">{{ $product->name }}</h4>
                                    
                                    <div class="flex-wrap gap-3 mb-3 d-flex align-items-center">
                                        @if($product->categories->count() > 0)
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-tag me-1"></i>
                                            @foreach($product->categories as $category)
                                                {{ $category->name }}@if(!$loop->last), @endif
                                            @endforeach
                                        </span>
                                        @endif
                                        
                                        @if($product->seller)
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-store me-1"></i>
                                            {{ $product->seller->name }}
                                        </span>
                                        @endif
                                    </div>
                                    
                                    <div class="d-flex align-items-center">
                                        <div class="me-3 text-warning">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <span class="text-muted">5.0 average rating</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Form Card -->
                    <div class="border-0 shadow-sm card">
                        <div class="pt-4 pb-0 bg-white border-0 card-header">
                            <h5 class="mb-0">Your Review</h5>
                        </div>
                        
                        <div class="card-body">
                            <form action="{{ route('buyer.reviews.store') }}" method="POST" enctype="multipart/form-data" id="reviewForm">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $orderId }}">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <!-- Rating Selection -->
                                <div class="mb-4">
                                    <label class="mb-3 form-label fw-bold">
                                        How would you rate this product? <span class="text-danger">*</span>
                                    </label>
                                    
                                    <div class="p-4 rating-container bg-light rounded-3">
                                        <div class="rating-stars">
                                            @for($i = 5; $i >= 1; $i--)
                                            <div class="mb-2 rating-option">
                                                <input class="rating-input" 
                                                       type="radio" 
                                                       name="rating" 
                                                       id="star{{ $i }}" 
                                                       value="{{ $i }}"
                                                       {{ old('rating') == $i ? 'checked' : '' }}>
                                                <label class="rating-label" for="star{{ $i }}">
                                                    @for($j = 1; $j <= 5; $j++)
                                                        @if($j <= $i)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-warning"></i>
                                                        @endif
                                                    @endfor
                                                    <span class="ms-2 rating-text">
                                                        @switch($i)
                                                            @case(5) Excellent @break
                                                            @case(4) Good @break
                                                            @case(3) Average @break
                                                            @case(2) Poor @break
                                                            @case(1) Terrible @break
                                                        @endswitch
                                                    </span>
                                                </label>
                                            </div>
                                            @endfor
                                        </div>
                                        
                                        <div class="mt-3 selected-rating" id="selectedRating">
                                            @if(old('rating'))
                                                @php
                                                    $ratingMessages = [
                                                        5 => 'You love it! Share why you think it\'s excellent.',
                                                        4 => 'You like it! Tell others what you enjoyed.',
                                                        3 => 'It\'s okay. Share what could be improved.',
                                                        2 => 'You\'re not satisfied. Help others know what went wrong.',
                                                        1 => 'You\'re very disappointed. Share your experience.'
                                                    ];
                                                @endphp
                                                <div class="p-3 rounded-3 bg-info bg-opacity-10">
                                                    <i class="fas fa-info-circle text-info me-2"></i>
                                                    <span class="text-info">{{ $ratingMessages[old('rating')] }}</span>
                                                </div>
                                            @else
                                                <div class="p-3 rounded-3 bg-light">
                                                    <i class="fas fa-star me-2 text-warning"></i>
                                                    <span class="text-muted">Select a rating to begin</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        @error('rating')
                                            <div class="mt-2 text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Review Text -->
                                <div class="mb-4">
                                    <label for="review" class="mb-2 form-label fw-bold">
                                        Write your review <span class="text-danger">*</span>
                                    </label>
                                    <div class="review-textarea-wrapper">
                                        <textarea name="review" 
                                                  id="review" 
                                                  class="form-control @error('review') is-invalid @enderror" 
                                                  rows="6" 
                                                  placeholder="What did you like or dislike? How was the quality? Would you recommend it to others?"
                                                  required>{{ old('review') }}</textarea>
                                        
                                        <div class="mt-2 d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i>
                                                Minimum 10 characters
                                            </small>
                                            <div class="char-counter" id="charCounter">
                                                <span id="currentChars">0</span>/500
                                            </div>
                                        </div>
                                        
                                        <div class="mt-2 progress" style="height: 4px;">
                                            <div class="progress-bar" id="charProgress" role="progressbar" style="width: 0%"></div>
                                        </div>
                                    </div>
                                    
                                    @error('review')
                                        <div class="mt-2 text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Photo Upload -->
                                <div class="mb-4">
                                    <label class="mb-2 form-label fw-bold">
                                        Add Photos <span class="text-muted fw-normal">(Optional)</span>
                                    </label>
                                    
                                    <div class="upload-area-wrapper">
                                        <div class="upload-area" id="uploadArea">
                                            <input type="file" 
                                                   name="images[]" 
                                                   id="images" 
                                                   class="d-none" 
                                                   multiple 
                                                   accept="image/jpeg,image/png,image/jpg,image/gif">
                                            
                                            <div class="p-5 text-center">
                                                <div class="mb-3 upload-icon">
                                                    <i class="opacity-50 fas fa-cloud-upload-alt fa-4x text-primary"></i>
                                                </div>
                                                <h6 class="mb-2">Drag & drop photos here</h6>
                                                <p class="mb-3 text-muted small">or</p>
                                                <button type="button" class="px-4 btn btn-outline-primary" id="browseBtn">
                                                    <i class="fas fa-folder-open me-2"></i>Browse Files
                                                </button>
                                                <p class="mt-3 mb-0 text-muted small">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Max 5 images, 2MB each (JPEG, PNG, JPG, GIF)
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <!-- Image Preview Grid -->
                                        <div class="image-preview-grid" id="imagePreview"></div>
                                    </div>
                                    
                                    @error('images.*')
                                        <div class="mt-2 text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Review Guidelines -->
                                <div class="p-4 mb-4 bg-light rounded-3">
                                    <h6 class="mb-3">
                                        <i class="fas fa-clipboard-list text-primary me-2"></i>
                                        Review Guidelines
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <ul class="mb-0 list-unstyled">
                                                <li class="mb-2">
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                    <span>Be honest and specific</span>
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                    <span>Focus on product quality</span>
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                    <span>Share your experience</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="mb-0 list-unstyled">
                                                <li class="mb-2">
                                                    <i class="fas fa-times-circle text-danger me-2"></i>
                                                    <span>No personal information</span>
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-times-circle text-danger me-2"></i>
                                                    <span>No promotional content</span>
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-times-circle text-danger me-2"></i>
                                                    <span>No inappropriate language</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="gap-3 d-flex">
                                    <button type="submit" class="px-5 btn btn-primary" id="submitBtn">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Submit Review
                                    </button>
                                    <a href="{{ route('buyer.order.show', $orderId) }}" class="px-5 btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i>
                                        Cancel
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
<!--============================
    WRITE REVIEW END
=============================-->

<x-footer />
 