@php
    use Carbon\Carbon;
@endphp

<x-header 
    title="{{ $store->name }} - {{ config('app.name', 'eCommerce') }}"
    description="{{ $store->description ? Str::limit($store->description, 160) : 'Shop products from ' . $store->name }}"
    keywords="{{ $store->name }}, {{ config('app.name', 'eCommerce') }}, store, vendor"
    ogImage="{{ $store->banner ? asset('storage/' . $store->banner) : asset('assets/images/vendor_details_banner.jpg') }}"
/>

<x-navbar />

<section class="page_banner" style="background: url({{ asset('assets/images/page_banner_bg.jpg') }});">
    <div class="page_banner_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page_banner_text wow fadeInUp">
                        <h1>{{ $store->name }}</h1>
                        <ul>
                            <li><a href="{{ route('home') }}"><i class="fal fa-home-lg"></i> Home</a></li>
                            <li><a href="{{ route('stores') }}">Stores</a></li>
                            <li class="active">{{ $store->name }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="vendor_details shop_page mt_100 mb_100">
    <div class="container">
        <!-- Store Banner & Info Section -->
        <div class="vendor_details_banner">
            <div class="row align-items-center justify-content-between">
                <div class="col-xxl-5 col-xl-5 col-lg-6 wow fadeInLeft">
                    <div class="vendor_details_img">
                        @if($store->banner)
                            <img src="{{ asset('storage/' . $store->banner) }}" alt="{{ $store->name }}" class="img-fluid w-100" style="height: 400px; object-fit: cover;">
                        @else
                            <img src="{{ asset('assets/images/vendor_details_banner.jpg') }}" alt="{{ $store->name }}" class="img-fluid w-100">
                        @endif
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-4 col-lg-6 wow fadeInUp">
                    <div class="vendor_details_info">
                        <div class="vendor_details_logo">
                            @if($store->logo)
                                <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" 
                                     class="img-fluid w-100" style="width: 120px; height: 120px; object-fit: contain;">
                            @else
                                <img src="{{ asset('assets/images/brand2.png') }}" alt="{{ $store->name }}" class="img-fluid w-100">
                            @endif
                        </div>
                        <span class="since">Since {{ Carbon::parse($store->created_at)->format('Y') }}</span>
                        <h4>{{ $store->name }}</h4>
                        
                        <!-- Store Rating -->
                        <p class="rating">
                            @php
                                $rating = $store->rating;
                                $fullStars = floor($rating);
                                $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                            @endphp
                            
                            @for($i = 0; $i < $fullStars; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                            
                            @if($hasHalfStar)
                                <i class="fas fa-star-half-alt"></i>
                            @endif
                            
                            @for($i = 0; $i < $emptyStars; $i++)
                                <i class="far fa-star"></i>
                            @endfor
                            
                            <span>({{ $store->total_sales }} sales)</span>
                        </p>
                    </div>
                    
                    <!-- Store Contact Information -->
                    <div class="vendor_details_contact">
                        <ul class="address">
                            @if($store->address)
                            <li>
                                <p>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                    {{ $store->address }}
                                </p>
                            </li>
                            @endif
                            
                            @if($store->phone)
                            <li>
                                <a href="tel:{{ $store->phone }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 3.75v4.5m0-4.5h-4.5m4.5 0-6 6m3 12c-8.284 0-15-6.716-15-15V4.5A2.25 2.25 0 0 1 4.5 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44-.054.902-.417 1.173l-1.293.97a1.062 1.062 0 0 0-.38 1.21 12.035 12.035 0 0 0 7.143 7.143c.441.162.928-.004 1.21-.38l.97-1.293a1.125 1.125 0 0 1 1.173-.417l4.423 1.106c.5.125.852.575.852 1.091V19.5a2.25 2.25 0 0 1-2.25 2.25h-2.25Z" />
                                    </svg>
                                    {{ $store->phone }}
                                </a>
                            </li>
                            @endif
                            
                            @if($store->email)
                            <li>
                                <a href="mailto:{{ $store->email }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 0 1-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 0 0 1.183 1.981l6.478 3.488m8.839 2.51-4.66-2.51m0 0-1.023-.55a2.25 2.25 0 0 0-2.134 0l-1.022.55m0 0-4.661 2.51m16.5 1.615a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V8.844a2.25 2.25 0 0 1 1.183-1.981l7.5-4.039a2.25 2.25 0 0 1 2.134 0l7.5 4.039a2.25 2.25 0 0 1 1.183 1.98V19.5Z" />
                                    </svg>
                                    {{ $store->email }}
                                </a>
                            </li>
                            @endif
                        </ul>
                        
                        <!-- Store Description -->
                        @if($store->description)
                        <div class="mt-3 store-description">
                            <p>{{ $store->description }}</p>
                        </div>
                        @endif
                        
                        <!-- Social Media Links -->
                        <ul class="social_icon">
                            <li><span>Follow Us:</span></li>
                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fab fa-whatsapp"></i></a></li>
                            <li><a href="#"><i class="fab fa-pinterest-p"></i></a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div class="col-xxl-3 col-xl-3 wow fadeInRight">
                    <div class="vendor_details_message">
                        <h4>Contact Vendor</h4>
                        <form action=" " method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-lg-6 col-xl-12">
                                    <input type="text" name="name" placeholder="Your name" required>
                                </div>
                                <div class="col-12 col-lg-6 col-xl-12">
                                    <input type="email" name="email" placeholder="Your email" required>
                                </div>
                                <div class="col-12">
                                    <textarea name="message" rows="3" placeholder="Type your message" required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="common_btn">
                                        Send Message <i class="fas fa-long-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Products Section -->
        <div class="row mt_100">
            <!-- Sidebar Filters -->
            <div class="col-xxl-2 col-lg-4 col-xl-3">
                <div id="sticky_sidebar">
                    <div class="shop_filter_btn d-lg-none">Filter</div>
                    <div class="shop_filter_area">
                        <!-- Top Rated Products -->
                        <div class="sidebar_related_product">
                            <h3>Top Products</h3>
                            <ul>
                                @foreach($topProducts as $product)
                                <li>
                                    <a href="{{ route('product.show', $product->slug) }}" class="img">
                                        @if($product->images->first())
                                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                                 alt="{{ $product->name }}" class="img-fluid" style="width: 80px; height: 80px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('assets/images/product_18.png') }}" alt="{{ $product->name }}" class="img-fluid">
                                        @endif
                                    </a>
                                    <div class="text">
                                        <p class="rating">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                            <i class="far fa-star"></i>
                                            <span>({{ $product->views }} views)</span>
                                        </p>
                                        <a class="title" href="{{ route('product.show', $product->slug) }}">
                                            {{ Str::limit($product->name, 30) }}
                                        </a>
                                        <p class="price">
                                            ₹{{ number_format($product->sale_price ?? $product->base_price, 2) }}
                                            @if($product->sale_price)
                                                <del>₹{{ number_format($product->base_price, 2) }}</del>
                                            @endif
                                        </p>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        
                        <!-- Product Status Filter -->
                        <div class="sidebar_status">
                            <h3>Product Status</h3>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="sale" id="on_sale">
                                <label class="form-check-label" for="on_sale">
                                    On sale
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="stock" id="in_stock" checked>
                                <label class="form-check-label" for="in_stock">
                                    In Stock
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="col-xxl-10 col-lg-8 col-xl-9">
                <!-- Products Header -->
                <div class="product_page_top">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="product_page_top_button">
                                <p>We found <b>{{ $products->total() }}</b> items from this store!</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <ul class="product_page_sorting">
                                <li>
                                    <select class="select_js" id="sort_by">
                                        <option value="latest">Latest Products</option>
                                        <option value="price_low">Price: Low to High</option>
                                        <option value="price_high">Price: High to Low</option>
                                        <option value="sale">On Sale</option>
                                    </select>
                                </li>
                                <li>
                                    <select class="select_js show" id="per_page">
                                        <option value="12">Show: 12</option>
                                        <option value="24">Show: 24</option>
                                        <option value="36">Show: 36</option>
                                    </select>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Products Grid -->
                <div class="row" id="products-grid">
                    @foreach($products as $product)
                    <div class="col-xxl-3 col-6 col-md-4 col-lg-6 col-xl-4 wow fadeInUp">
                        <div class="product_item_2 product_item">
                            <div class="product_img">
                                @if($product->images->first())
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                         alt="{{ $product->name }}" class="img-fluid w-100" style="height: 250px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('assets/images/product_23.png') }}" alt="{{ $product->name }}" class="img-fluid w-100">
                                @endif
                                
                                <!-- Product Badges -->
                                <ul class="discount_list">
                                    @if($product->sale_price)
                                    <li class="discount">
                                        <b>-</b> {{ round((($product->base_price - $product->sale_price) / $product->base_price) * 100) }}%
                                    </li>
                                    @endif
                                    @if($product->created_at > now()->subDays(7))
                                    <li class="new">New</li>
                                    @endif
                                </ul>
                                
                                <!-- Product Actions -->
                                <ul class="btn_list">
                                    <li>
                                        <a href="{{ route('product.show', $product->slug) }}">
                                            <img src="{{ asset('assets/images/cart_icon_white.svg') }}" alt="View" class="img-fluid">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="add-to-wishlist" data-product-id="{{ $product->id }}">
                                            <img src="{{ asset('assets/images/love_icon_white.svg') }}" alt="Wishlist" class="img-fluid">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="product_text">
                                <a class="title" href="{{ route('product.show', $product->slug) }}">
                                    {{ Str::limit($product->name, 40) }}
                                </a>
                                <p class="price">
                                    ₹{{ number_format($product->sale_price ?? $product->base_price, 2) }}
                                    @if($product->sale_price)
                                        <del>₹{{ number_format($product->base_price, 2) }}</del>
                                    @endif
                                </p>
                                <p class="rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <span>({{ $product->views }} views)</span>
                                </p>
                                
                                <!-- Stock Status -->
                                @if($product->stock <= 0)
                                <div class="out_of_stock">
                                    <p>Out of stock</p>
                                </div>
                                @elseif($product->stock <= 10)
                                <div class="low_stock">
                                    <p>Only {{ $product->stock }} left!</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($products->hasPages())
                <div class="row">
                    <div class="pagination_area">
                        <nav aria-label="...">
                            <ul class="pagination justify-content-start mt_50">
                                {{-- Previous Page --}}
                                @if($products->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="far fa-arrow-left"></i>
                                    </span>
                                </li>
                                @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $products->previousPageUrl() }}">
                                        <i class="far fa-arrow-left"></i>
                                    </a>
                                </li>
                                @endif

                                {{-- Page Numbers --}}
                                @foreach(range(1, $products->lastPage()) as $page)
                                    @if($page == $products->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                    @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $products->url($page) }}">{{ $page }}</a>
                                    </li>
                                    @endif
                                @endforeach

                                {{-- Next Page --}}
                                @if($products->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $products->nextPageUrl() }}">
                                        <i class="far fa-arrow-right"></i>
                                    </a>
                                </li>
                                @else
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="far fa-arrow-right"></i>
                                    </span>
                                </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<x-footer />

@push('styles')
<style>
    .store-description {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
    }
    
    .out_of_stock {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #dc3545;
        color: white;
        padding: 3px 10px;
        border-radius: 4px;
        font-size: 12px;
    }
    
    .low_stock {
        color: #ffc107;
        font-size: 12px;
        font-weight: bold;
        margin-top: 5px;
    }
    
    .product_img {
        position: relative;
        overflow: hidden;
    }
    
    .btn_list {
        position: absolute;
        bottom: -50px;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        gap: 10px;
        background: rgba(0,0,0,0.7);
        padding: 10px;
        transition: bottom 0.3s ease;
    }
    
    .product_item:hover .btn_list {
        bottom: 0;
    }
    
    .discount_list {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 2;
    }
    
    .discount_list li {
        display: inline-block;
        padding: 3px 8px;
        color: white;
        font-size: 12px;
        border-radius: 3px;
        margin-right: 5px;
    }
    
    .discount_list .discount {
        background: #28a745;
    }
    
    .discount_list .new {
        background: #007bff;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sorting and filtering
        const sortSelect = document.getElementById('sort_by');
        const perPageSelect = document.getElementById('per_page');
        
        function updateProducts() {
            const params = new URLSearchParams(window.location.search);
            params.set('sort', sortSelect.value);
            params.set('per_page', perPageSelect.value);
            
            window.location.href = window.location.pathname + '?' + params.toString();
        }
        
        sortSelect.addEventListener('change', updateProducts);
        perPageSelect.addEventListener('change', updateProducts);
        
        // Add to wishlist
        document.querySelectorAll('.add-to-wishlist').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.dataset.productId;
                
                fetch('/wishlist/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert('Product added to wishlist!');
                    } else {
                        alert(data.message || 'Error adding to wishlist');
                    }
                });
            });
        });
    });
</script>
@endpush