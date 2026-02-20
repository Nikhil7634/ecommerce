<x-header 
    title="Vendors - {{ config('app.name', 'eCommerce') }}"
    description="Browse all our trusted vendors and their stores. Find quality products from verified sellers."
    keywords="vendors, stores, sellers, shops, {{ config('app.name', 'eCommerce') }}"
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
                        <h1>Vendors</h1>
                        <ul>
                            <li><a href="{{ route('home') }}"><i class="fal fa-home-lg"></i> Home</a></li>
                            <li><a href="#">Vendors</a></li>
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

<section class="vendor_page mt_75 mb_100">
    <div class="container">
        @if($vendors->isEmpty())
        <div class="row">
            <div class="py-5 text-center col-12">
                <h3>No vendors found</h3>
                <p>There are no active vendors at the moment.</p>
            </div>
        </div>
        @else
        <div class="row">
            @foreach($vendors as $vendor)
            <div class="col-xxl-3 col-md-6 col-lg-4 wow fadeInUp">
                <div class="single_vendor">
                    @if($vendor->banner)
                    <div class="img">
                        <img src="{{ asset('storage/' . $vendor->banner) }}" alt="{{ $vendor->name }}" class="img-fluid w-100" style="height: 200px; object-fit: cover;">
                    </div>
                    @else
                    <div class="img">
                        <img src="{{ asset('assets/images/vendor_img_1.jpg') }}" alt="{{ $vendor->name }}" class="img-fluid w-100" style="height: 200px; object-fit: cover;">
                    </div>
                    @endif
                    <div class="text">
                        <a class="vendor_logo" href="{{ route('vendor.details', $vendor->slug) }}">
                            @if($vendor->logo)
                            <img src="{{ asset('storage/' . $vendor->logo) }}" alt="{{ $vendor->name }}" class="img-fluid w-100" style="width: 80px; height: 80px; object-fit: contain;">
                            @else
                            <img src="{{ asset('assets/images/default-store.png') }}" alt="{{ $vendor->name }}" class="img-fluid w-100" style="width: 80px; height: 80px; object-fit: contain;">
                            @endif
                        </a>
                        <a class="title" href="{{ route('vendor.details', $vendor->slug) }}">{{ $vendor->name }}</a>
                        
                        <!-- Rating -->
                        <p class="rating">
                            @php
                                $fullStars = floor($vendor->rating);
                                $hasHalfStar = ($vendor->rating - $fullStars) >= 0.5;
                                $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                            @endphp
                            
                            @for($i = 0; $i < $fullStars; $i++)
                                <i class="fas fa-star" aria-hidden="true"></i>
                            @endfor
                            
                            @if($hasHalfStar)
                                <i class="fas fa-star-half-alt" aria-hidden="true"></i>
                            @endif
                            
                            @for($i = 0; $i < $emptyStars; $i++)
                                <i class="far fa-star" aria-hidden="true"></i>
                            @endfor
                            
                            <span>({{ $vendor->total_sales }} Sales)</span>
                        </p>
                        
                        <!-- Store Description (Shortened) -->
                        @if($vendor->description)
                        <p class="mb-2 store-description" style="font-size: 0.9rem; color: #666; min-height: 60px;">
                            {{ Str::limit($vendor->description, 100) }}
                        </p>
                        @endif
                        
                        <!-- Store Stats -->
                        <div class="mb-3 store-stats">
                            <small class="text-muted d-block">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $vendor->city ?? 'Location not set' }}
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-check-circle me-1 text-success"></i>
                                {{ ucfirst($vendor->status) }}
                            </small>
                        </div>
                        
                        <a class="common_btn" href="{{ route('vendor.details', $vendor->slug) }}">Visit Store</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($vendors->hasPages())
        <div class="row">
            <div class="pagination_area">
                <nav aria-label="...">
                    <ul class="pagination justify-content-center mt_50">
                        {{-- Previous Page Link --}}
                        @if($vendors->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="far fa-arrow-left" aria-hidden="true"></i>
                            </span>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $vendors->previousPageUrl() }}">
                                <i class="far fa-arrow-left" aria-hidden="true"></i>
                            </a>
                        </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach($vendors->getUrlRange(1, $vendors->lastPage()) as $page => $url)
                            @if($page == $vendors->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if($vendors->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $vendors->nextPageUrl() }}">
                                <i class="far fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        </li>
                        @else
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="far fa-arrow-right" aria-hidden="true"></i>
                            </span>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
        @endif
        @endif
    </div>
</section>

<x-footer />

@push('styles')
<style>
    .store-description {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }
    
    .vendor_logo img {
        border-radius: 10px;
        border: 2px solid #f0f0f0;
        background: white;
        padding: 5px;
    }
    
    .single_vendor {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .single_vendor:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .single_vendor .text {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .common_btn {
        margin-top: auto;
    }
</style>
@endpush