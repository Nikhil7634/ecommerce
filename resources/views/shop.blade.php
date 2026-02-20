<x-header 
    title="Products - {{ setting('site_name', 'eCommerce') }}"
    description="Shop the latest electronics, fashion, and home essentials at Valtara with fast delivery and best prices."
    keywords="Valtara, online store, eCommerce, electronics, clothing, deals"
    ogImage="{{ asset('assets/images/banner/home-og.jpg') }}"
></x-header>

<x-navbar />

<!-- Livewire Notification Manager Component -->
@livewire('notification-manager')

 

<section class="shop_page mt_100 mb_100">
    <div class="container">
        <div class="row">
            <div class="col-xxl-2 col-lg-4 col-xl-3">
                <div id="sticky_sidebar">
                    <div class="shop_filter_btn d-lg-none"> Filter </div>
                    <form method="GET" action="{{ route('shop') }}" id="filter-form">
                        @csrf
                        <div class="shop_filter_area">
                            <!-- Price Range Filter -->
                            <div class="sidebar_range">
                                <h3>Price Range</h3>
                                <div class="range_slider_wrapper">
                                    <div id="price-range"></div>
                                    <div class="mt-3 price-inputs d-flex justify-content-between">
                                        <input type="number" name="min_price" id="min-price" 
                                               value="{{ request('min_price', 0) }}" min="0" class="form-control form-control-sm" placeholder="Min">
                                        <span class="mx-2">-</span>
                                        <input type="number" name="max_price" id="max-price" 
                                               value="{{ request('max_price', 10000) }}" min="0" class="form-control form-control-sm" placeholder="Max">
                                    </div>
                                </div>
                            </div>

                            <!-- Product Status Filter -->
                            <div class="sidebar_status">
                                <h3>Product Status</h3>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="on_sale" value="1" 
                                           id="flexCheckDefault" {{ request('on_sale') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexCheckDefault">
                                        On sale
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="in_stock" value="1" 
                                           id="flexCheckChecked" {{ request('in_stock') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexCheckChecked">
                                        In Stock
                                    </label>
                                </div>
                            </div>

                            <!-- Categories Filter -->
                            <div class="sidebar_category">
                                <h3>Categories</h3>
                                <ul>
                                    @foreach($categories as $category)
                                        <li>
                                            <a href="{{ route('shop', ['category' => $category->id]) }}" 
                                            class="{{ request('category') == $category->id ? 'active' : '' }}">
                                                {{ $category->name }}
                                                <span>{{ $category->products_count ?? 0 }}</span>
                                            </a>
                                        </li>
                                        @if($category->children->count() > 0)
                                            @foreach($category->children as $child)
                                                <li style="padding-left: 20px;">
                                                    <a href="{{ route('shop', ['category' => $child->id]) }}" 
                                                    class="{{ request('category') == $child->id ? 'active' : '' }}">
                                                        {{ $child->name }}
                                                        <span>{{ $child->products_count ?? 0 }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            
                            <!-- Rating Filter -->
                            <div class="sidebar_rating">
                                <h3>Rating</h3>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rating" value="5" 
                                           id="rating5" {{ request('rating') == '5' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating5">
                                        <i class="fas fa-star" aria-hidden="true"></i>
                                        5 star
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rating" value="4" 
                                           id="rating4" {{ request('rating') == '4' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating4">
                                        <i class="fas fa-star" aria-hidden="true"></i>
                                        4 star or above
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rating" value="3" 
                                           id="rating3" {{ request('rating') == '3' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating3">
                                        <i class="fas fa-star" aria-hidden="true"></i>
                                        3 star or above
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rating" value="2" 
                                           id="rating2" {{ request('rating') == '2' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating2">
                                        <i class="fas fa-star" aria-hidden="true"></i>
                                        2 star or above
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rating" value="1" 
                                           id="rating1" {{ request('rating') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating1">
                                        <i class="fas fa-star" aria-hidden="true"></i>
                                        1 star or above
                                    </label>
                                </div>
                            </div>

                            <!-- Top Rated Products -->
                            <div class="sidebar_related_product">
                                <h3>Top Rated Products</h3>
                                <ul>
                                    @foreach($topRatedProducts as $product)
                                        @php
                                            $primaryImage = $product->images->where('is_primary', true)->first();
                                            if (!$primaryImage && $product->images->count() > 0) {
                                                $primaryImage = $product->images->first();
                                            }
                                            $avgRating = $product->reviews_avg_rating ?? 0;
                                            $reviewCount = $product->reviews_count ?? 0;
                                        @endphp
                                        <li>
                                            <a href="{{ route('product.show', $product->slug) }}" class="img">
                                                @if($primaryImage)
                                                    <img src="{{ asset('storage/' . $primaryImage->image_path) }}" 
                                                        alt="{{ $product->name }}" class="img-fluid">
                                                @else
                                                    <img src="https://placehold.co/100x100?text=No+Image" 
                                                        alt="No Image" class="img-fluid">
                                                @endif
                                            </a>
                                            <div class="text">
                                                <p class="rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= floor($avgRating))
                                                            <i class="fas fa-star"></i>
                                                        @elseif($i == ceil($avgRating) && fmod($avgRating, 1) > 0)
                                                            <i class="fas fa-star-half-alt"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                    <span>({{ $reviewCount }})</span>
                                                </p>
                                                <a class="title" href="{{ route('product.show', $product->slug) }}">
                                                    {{ Str::limit($product->name, 30) }}
                                                </a>
                                                <p class="price">
                                                    @if($product->sale_price)
                                                        <span class="text-danger">₹{{ number_format($product->sale_price, 2) }}</span>
                                                        <span class="text-decoration-line-through">₹{{ number_format($product->base_price, 2) }}</span>
                                                    @else
                                                        ₹{{ number_format($product->base_price, 2) }}
                                                    @endif
                                                </p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Filter Buttons -->
                            <div class="mt-4 filter-buttons">
                                <button type="submit" class="mb-2 btn btn-primary w-100">
                                    <i class="fas fa-filter me-2"></i> Apply Filters
                                </button>
                                <a href="{{ route('shop') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-times me-2"></i> Clear Filters
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-xxl-10 col-lg-8 col-xl-9">
                <!-- Search and Sorting -->
                <div class="product_page_top">
                    <div class="row">
                        <div class="col-4 col-xl-6 col-md-6">
                            <div class="product_page_top_button">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link active" id="nav-grid-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-grid" type="button" role="tab"
                                            aria-controls="nav-grid" aria-selected="true">
                                            <i class="fas fa-th"></i>
                                        </button>
                                        <button class="nav-link" id="nav-list-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-list" type="button" role="tab"
                                            aria-controls="nav-list" aria-selected="false">
                                            <i class="fas fa-list-ul"></i>
                                        </button>
                                    </div>
                                </nav>
                                <p>Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() ?? 0 }} results</p>
                            </div>
                        </div>
                        <div class="col-8 col-xl-6 col-md-6">
                            <ul class="product_page_sorting">
                                <li>
                                    <form method="GET" action="{{ route('shop') }}" class="d-inline">
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                        <input type="hidden" name="category" value="{{ request('category') }}">
                                        <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                                        <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                                        <input type="hidden" name="on_sale" value="{{ request('on_sale') }}">
                                        <input type="hidden" name="in_stock" value="{{ request('in_stock') }}">
                                        <input type="hidden" name="rating" value="{{ request('rating') }}">
                                        @if(request('categories'))
                                            @foreach(request('categories') as $category)
                                                <input type="hidden" name="categories[]" value="{{ $category }}">
                                            @endforeach
                                        @endif
                                        
                                        <select class="select_js" name="sort" onchange="this.form.submit()">
                                            <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                                        </select>
                                    </form>
                                </li>
                                <li>
                                    <select class="select_js show" name="per_page" onchange="updatePerPage(this.value)">
                                        <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>Show: 12</option>
                                        <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>Show: 24</option>
                                        <option value="36" {{ request('per_page') == 36 ? 'selected' : '' }}>Show: 36</option>
                                        <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>Show: 48</option>
                                    </select>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Products Grid View -->
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-grid" role="tabpanel"
                        aria-labelledby="nav-grid-tab" tabindex="0">
                        @if($products->count() > 0)
                            <div class="row">
                                @foreach($products as $product)
                                    <div class="col-xxl-3 col-6 col-md-4 col-lg-6 col-xl-4 wow fadeInUp">
                                        <div class="product_item_2 product_item">
                                            <div class="product_img">
                                                @php
                                                    $primaryImage = $product->images->where('is_primary', true)->first();
                                                    if (!$primaryImage && $product->images->count() > 0) {
                                                        $primaryImage = $product->images->first();
                                                    }
                                                @endphp
                                                
                                                @if($primaryImage)
                                                    <img src="{{ asset('storage/' . $primaryImage->image_path) }}" 
                                                         alt="{{ $product->name }}" class="img-fluid w-100">
                                                @else
                                                    <img src="https://placehold.co/300x300?text=No+Image" 
                                                         alt="No Image" class="img-fluid w-100">
                                                @endif
                                                
                                                <ul class="discount_list">
                                                    @if($product->sale_price)
                                                        @php
                                                            $discount = round((($product->base_price - $product->sale_price) / $product->base_price) * 100);
                                                        @endphp
                                                        <li class="sale">{{ $discount }}% OFF</li>
                                                    @endif
                                                    @if($product->created_at->gt(now()->subDays(7)))
                                                        <li class="new">New</li>
                                                    @endif
                                                </ul>
                                                <ul class="btn_list">
                                                    <li>
                                                        <!-- Wishlist Livewire Component -->
                                                        @livewire('wishlist-toggle', [
                                                            'productId' => $product->id,
                                                            'buttonType' => 'icon'
                                                        ], key('wishlist-' . $product->id))
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('product.show', $product->slug) }}">
                                                            <img style="filter: brightness(0) invert(1)" src="{{ asset('https://icon-library.com/images/white-eye-icon/white-eye-icon-4.jpg') }}" alt="View"
                                                                class="img-fluid">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <!-- Cart Livewire Component -->
                                                        @livewire('add-to-cart', [
                                                            'productId' => $product->id,
                                                            'buttonType' => 'icon'
                                                        ], key('cart-' . $product->id))
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="product_text">
                                                <a class="title" href="{{ route('product.show', $product->slug) }}">
                                                    {{ Str::limit($product->name, 40) }}
                                                </a>
                                                <p class="price">
                                                    @if($product->sale_price)
                                                        <span class="text-danger">₹{{ number_format($product->final_sale_price ?? $product->sale_price, 2) }}</span>
                                                        <span class="text-decoration-line-through">₹{{ number_format($product->final_base_price ?? $product->base_price, 2) }}</span>
                                                    @else
                                                        ₹{{ number_format($product->final_base_price ?? $product->base_price, 2) }}
                                                    @endif
                                                </p>
                                                <p class="rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= floor($product->reviews_avg_rating ?? 0))
                                                            <i class="fas fa-star text-warning"></i>
                                                        @elseif($i == ceil($product->reviews_avg_rating ?? 0) && fmod($product->reviews_avg_rating ?? 0, 1) > 0)
                                                            <i class="fas fa-star-half-alt text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-warning"></i>
                                                        @endif
                                                    @endfor
                                                    <span>({{ $product->reviews_count ?? 0 }})</span>
                                                </p>
                                                @if($product->stock <= 0)
                                                    <span class="badge bg-danger">Out of Stock</span>
                                                @elseif($product->stock < 10)
                                                    <span class="badge bg-warning">Only {{ $product->stock }} left</span>
                                                @else
                                                    <span class="badge bg-success">In Stock</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-5 text-center">
                                <i class="fas fa-box-open display-1 text-muted"></i>
                                <h3 class="mt-3">No products found</h3>
                                <p class="text-muted">Try adjusting your search or filter to find what you're looking for.</p>
                                <a href="{{ route('shop') }}" class="mt-2 btn btn-primary">
                                    <i class="fas fa-times me-2"></i> Clear Filters
                                </a>
                            </div>
                        @endif

                        <!-- Pagination -->
                        @if($products->hasPages())
                            <div class="row">
                                <div class="pagination_area">
                                    <nav aria-label="...">
                                        <ul class="pagination justify-content-start mt_50">
                                            {{-- Previous Page Link --}}
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

                                            {{-- Pagination Elements --}}
                                            @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                                @if($page == $products->currentPage())
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

                    <!-- Products List View -->
                    <div class="tab-pane fade" id="nav-list" role="tabpanel" aria-labelledby="nav-list-tab"
                        tabindex="0">
                        @if($products->count() > 0)
                            <div class="row">
                                @foreach($products as $product)
                                    <div class="col-12">
                                        <div class="mb-4 product_list_item product_item_2 product_item">
                                            <div class="row align-items-center">
                                                <div class="col-md-5 col-sm-6 col-xxl-4">
                                                    <div class="product_img">
                                                        @php
                                                            $primaryImage = $product->images->where('is_primary', true)->first();
                                                            if (!$primaryImage && $product->images->count() > 0) {
                                                                $primaryImage = $product->images->first();
                                                            }
                                                        @endphp
                                                        
                                                        @if($primaryImage)
                                                            <img src="{{ asset('storage/' . $primaryImage->image_path) }}" 
                                                                 alt="{{ $product->name }}" class="img-fluid w-100">
                                                        @else
                                                            <img src="https://placehold.co/300x300?text=No+Image" 
                                                                 alt="No Image" class="img-fluid w-100">
                                                        @endif
                                                        
                                                        <ul class="discount_list">
                                                            @if($product->sale_price)
                                                                @php
                                                                    $discount = round((($product->base_price - $product->sale_price) / $product->base_price) * 100);
                                                                @endphp
                                                                <li class="sale">{{ $discount }}% OFF</li>
                                                            @endif
                                                            @if($product->created_at->gt(now()->subDays(7)))
                                                                <li class="new">New</li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="col-md-7 col-sm-6 col-xxl-8">
                                                    <div class="product_text">
                                                        <a class="title" href="{{ route('product.show', $product->slug) }}">
                                                            {{ $product->name }}
                                                        </a>
                                                        <p class="rating">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= floor($product->reviews_avg_rating ?? 0))
                                                                    <i class="fas fa-star text-warning"></i>
                                                                @elseif($i == ceil($product->reviews_avg_rating ?? 0) && fmod($product->reviews_avg_rating ?? 0, 1) > 0)
                                                                    <i class="fas fa-star-half-alt text-warning"></i>
                                                                @else
                                                                    <i class="far fa-star text-warning"></i>
                                                                @endif
                                                            @endfor
                                                            <span>({{ $product->reviews_count ?? 0 }} reviews)</span>
                                                        </p>
                                                        <p class="price">
                                                            @if($product->sale_price)
                                                                <span class="text-danger">₹{{ number_format($product->sale_price, 2) }}</span>
                                                                <span class="text-decoration-line-through">₹{{ number_format($product->base_price, 2) }}</span>
                                                            @else
                                                                ₹{{ number_format($product->base_price, 2) }}
                                                            @endif
                                                        </p>
                                                        @if($product->stock <= 0)
                                                            <span class="badge bg-danger">Out of Stock</span>
                                                        @elseif($product->stock < 10)
                                                            <span class="badge bg-warning">Only {{ $product->stock }} left</span>
                                                        @else
                                                            <span class="badge bg-success">In Stock</span>
                                                        @endif
                                                        
                                                        <p class="mt-2 short_description">
                                                            {{ Str::limit($product->description, 200) }}
                                                        </p>
                                                        <div class="flex-wrap gap-2 mt-3 d-flex">
                                                            <!-- Cart Livewire Component -->
                                                            @livewire('add-to-cart', [
                                                                'productId' => $product->id,
                                                                'buttonType' => 'button',
                                                                'showQuantity' => true
                                                            ], key('cart-list-' . $product->id))
                                                            
                                                            <!-- Wishlist Livewire Component -->
                                                            @livewire('wishlist-toggle', [
                                                                'productId' => $product->id,
                                                                'buttonType' => 'button'
                                                            ], key('wishlist-list-' . $product->id))
                                                            
                                                            <a style="max-height: min-content" href="{{ route('product.show', $product->slug) }}" class="common_btn buy_now">
                                                                <i style="transform: rotate(0)" class="fas fa-eye me-2"></i> View Details
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<x-footer />

@push('styles')
<style>
    /* Livewire Component Styles */
    .add-to-cart, .add-to-wishlist {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }
    
    .cart-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #28a745;
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .quantity-controls button {
        width: 30px;
        height: 30px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
    }
    
    .quantity-controls span {
        min-width: 30px;
        text-align: center;
        font-weight: bold;
    }
    
    /* Loading States */
    [wire\:loading] {
        opacity: 0.7;
        pointer-events: none;
    }
    
    [wire\:loading] .spinner-border {
        display: inline-block;
    }
    
    .btn_list li {
        position: relative;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.6.0/nouislider.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.6.0/nouislider.min.css">

<script>
    $(document).ready(function() {
        // Initialize price range slider
        var priceSlider = document.getElementById('price-range');
        if (priceSlider) {
            noUiSlider.create(priceSlider, {
                start: [{{ request('min_price', 0) }}, {{ request('max_price', 10000) }}],
                connect: true,
                range: {
                    'min': 0,
                    'max': 10000
                },
                step: 100
            });

            priceSlider.noUiSlider.on('update', function(values) {
                $('#min-price').val(Math.round(values[0]));
                $('#max-price').val(Math.round(values[1]));
            });

            $('#min-price, #max-price').on('change', function() {
                priceSlider.noUiSlider.set([$('#min-price').val(), $('#max-price').val()]);
            });
        }

        // Update per page
        function updatePerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            window.location.href = url.toString();
        }

        // Auto submit form when filter changes
        $('.category-checkbox, [name="rating"], [name="on_sale"], [name="in_stock"]').on('change', function() {
            $('#filter-form').submit();
        });

        // Mobile filter toggle
        $('.shop_filter_btn').on('click', function() {
            $('.shop_filter_area').toggleClass('active');
        });

        // Test function - call this from browser console
        window.testNotifications = function() {
            console.log('Testing notifications...');
            
            // Test success notification
            Livewire.dispatch('showNotification', {
                type: 'success',
                message: 'Test success notification!'
            });
            
            setTimeout(() => {
                // Test error notification
                Livewire.dispatch('showNotification', {
                    type: 'error',
                    message: 'Test error notification!'
                });
            }, 1000);
            
            setTimeout(() => {
                // Test warning notification
                Livewire.dispatch('showNotification', {
                    type: 'warning',
                    message: 'Test warning notification!'
                });
            }, 2000);
            
            setTimeout(() => {
                // Test info notification
                Livewire.dispatch('showNotification', {
                    type: 'info',
                    message: 'Test info notification!'
                });
            }, 3000);
            
            setTimeout(() => {
                // Test login alert
                Livewire.dispatch('showLoginAlert');
            }, 4000);
        };

        // Debug: Check if Livewire is working
        console.log('Notification system ready');
        console.log('Livewire available:', typeof Livewire !== 'undefined');
        console.log('Test function available: testNotifications()');
    });
</script>
@endpush