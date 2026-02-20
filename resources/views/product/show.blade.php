<x-header 
    title="{{ $product->name }} - {{ setting('site_name', 'eCommerce') }}"
    description="{{ Str::limit(strip_tags($product->description), 160) }}"
    keywords="{{ $product->name }}, {{ $product->categories->pluck('name')->implode(', ') }}"
    ogImage="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('assets/images/banner/home-og.jpg') }}"
></x-header>

<x-navbar />

<!-- Livewire Notification Manager -->
@livewire('notification-manager')

<!-- Livewire Chat Component -->
@livewire('chat.chat-modal', ['productId' => $product->id], key('chat-modal-' . $product->id))

<section class="page_banner" style="background: url('{{ asset('assets/images/page_banner_bg.jpg') }}');">
    <div class="page_banner_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page_banner_text wow fadeInUp">
                        <h1>{{ $product->name }}</h1>
                        <ul>
                            <li><a href="{{ url('/') }}"><i class="fal fa-home-lg"></i> Home</a></li>
                            <li><a href="{{ route('shop') }}">Shop</a></li>
                            <li><a href="#">{{ Str::limit($product->name, 30) }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="shop_details mt_100">
    <div class="container">
        <div class="row">
            <div class="col-xxl-10">
                <div class="row">
                    <div class="col-lg-6 col-md-10 wow fadeInLeft">
                        <div class="shop_details_slider_area">
                            <div class="row">
                                @if($product->images->count() > 1)
                                <div class="order-2 col-xl-2 col-lg-3 col-md-3 order-md-1">
                                    <div class="row details_slider_nav">
                                        @foreach($product->images as $image)
                                        <div class="col-12">
                                            <div class="details_slider_nav_item">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="img-fluid w-100">
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                <div class="{{ $product->images->count() > 1 ? 'col-xl-10 col-lg-9 col-md-9' : 'col-12' }} order-md-1">
                                    <div class="row details_slider_thumb">
                                        @foreach($product->images as $image)
                                        <div class="col-12">
                                            <div class="details_slider_thumb_item">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="img-fluid w-100">
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 wow fadeInUp">
                        <div class="shop_details_text">
                            <p class="category">
                                @foreach($product->categories as $category)
                                    {{ $category->name }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </p>
                            <h2 class="details_title">{{ $product->name }}</h2>
                            <div class="flex-wrap d-flex align-items-center">
                                <p class="{{ $product->stock > 0 ? 'stock' : 'out_stock stock' }}">
                                    {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                </p>
                                @php
                                    $avgRating = $product->reviews->avg('rating') ?? 0;
                                    $reviewCount = $product->reviews->count();
                                @endphp
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
                                    <span>({{ $reviewCount }} reviews)</span>
                                </p>
                            </div>
                            <h3 class="price">
                                @if($product->sale_price)
                                    ₹{{ number_format($product->final_sale_price ?? $product->sale_price, 2) }} 
                                    <del>₹{{ number_format($product->final_base_price ?? $product->base_price, 2) }}</del>
                                @else
                                    ₹{{ number_format($product->final_base_price ?? $product->base_price, 2) }}
                                @endif
                            </h3>
                            <p class="short_description">{{ Str::limit(strip_tags($product->description), 200) }}</p>
                            
                            <!-- Product Variants -->
                            @if($product->variants->count() > 0)
                            <div class="mb-3 details_single_variant">
                                <p class="variant_title">Color:</p>
                                <ul class="details_variant_color">
                                    @foreach($product->variants->unique('color')->whereNotNull('color') as $index => $variant)
                                    <li class="{{ $index === 0 ? 'active' : '' }}" 
                                        style="background: {{ $variant->color }};"
                                        data-variant-id="{{ $variant->id }}"
                                        title="{{ $variant->color }}">
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="mb-3 details_single_variant">
                                <p class="variant_title">Size:</p>
                                <ul class="details_variant_size">
                                    @foreach($product->variants->unique('size')->whereNotNull('size') as $index => $variant)
                                    <li class="{{ $index === 0 ? 'active' : '' }}"
                                        data-variant-id="{{ $variant->id }}">
                                        {{ strtoupper($variant->size) }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <!-- Tiered Pricing -->
                            @if($product->tiers->count() > 0)
                            <div class="mb-4 details_single_variant">
                                <p style="width: 100%" class="mb-3 variant_title">Bulk Pricing Discounts</p>
                                <div style="width: 100%" class="row g-3">
                                    @php
                                        $basePrice = $product->sale_price ?? $product->base_price;
                                    @endphp
                                    @foreach($product->tiers->sortBy('min_qty') as $tier)
                                    @php
                                        $savings = $basePrice - $tier->price;
                                        $savingsPercent = $basePrice > 0 ? round(($savings / $basePrice) * 100) : 0;
                                        $maxQty = $tier->max_qty ? $tier->max_qty : '9999+';
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded" style="border: 2px solid #e9ecef; transition: all 0.3s ease;">
                                            <div class="mb-2 d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1 fw-bold text-dark">
                                                        {{ $tier->min_qty }} - {{ $maxQty }} pieces
                                                    </h6>
                                                    <small class="text-muted">Minimum order quantity</small>
                                                </div>
                                                @if($savings > 0)
                                                <span class="badge bg-danger">Save {{ $savingsPercent }}%</span>
                                                @endif
                                            </div>
                                            <div class="mt-2">
                                                <p class="mb-1 h5 text-success">
                                                    ₹{{ number_format($tier->price, 2) }}
                                                </p>
                                                @if($savings > 0)
                                                <small class="text-decoration-line-through text-muted">
                                                    ₹{{ number_format($basePrice, 2) }}
                                                </small>
                                                <p class="mt-1 mb-0 small text-danger">
                                                    <i class="fas fa-check-circle"></i> Save ₹{{ number_format($savings, 2) }}
                                                </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Add to Cart Section -->
                            <div class="flex-wrap d-flex align-items-center">
                                @if($product->stock > 0)
                                <form method="POST" action="{{ route('cart.buyNow') }}" class="w-100">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    
                                    <!-- Hidden quantity input (you can keep the visible one for display) -->
                                    <input type="hidden" name="quantity" id="hiddenQuantity" value="1">
                                    
                                    @if($product->variants->count() > 0)
                                        <!-- Hidden variant inputs -->
                                        <input type="hidden" name="variant_id" id="selectedVariantId" 
                                            value="{{ $product->variants->first()->id ?? '' }}">
                                    @endif
                                    
                                    <div style="zoom: 0.8" class="d-flex align-items-center">
                                        <!-- Visible quantity controls -->
                                        <div class="details_qty_input">
                                            <button type="button" class="minus" onclick="updateQuantity('decrease')">
                                                <i class="fal fa-minus" aria-hidden="true"></i>
                                            </button>
                                            <input type="number" class="quantity-input" id="productQuantity" 
                                                placeholder="01" value="1" min="1" max="{{ $product->stock }}"
                                                onchange="updateHiddenQuantity(this.value)">
                                            <button type="button" class="plus" onclick="updateQuantity('increase')">
                                                <i class="fal fa-plus" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="details_btn_area">
                                            <button type="submit" class="common_btn buy_now">
                                                Buy Now <i class="fas fa-long-arrow-right" aria-hidden="true"></i>
                                            </button>
                                            
                                            <!-- Add to Cart button (separate from Buy Now) -->
                                            @livewire('add-to-cart', [
                                                'productId' => $product->id,
                                                'buttonType' => 'button'
                                            ], key('product-cart-' . $product->id))
                                        </div>
                                    </div>
                                </form>
                                @else
                                <div class="alert alert-warning w-100">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    This product is currently out of stock.
                                </div>
                                @endif
                            </div>
                            
                            <!-- Action Buttons -->
                            <ul class="mt-3 details_list_btn">
                                <li>
                                    @livewire('wishlist-toggle', [
                                        'productId' => $product->id,
                                        'buttonType' => 'link'
                                    ], key('product-wishlist-' . $product->id))
                                </li>
                                 
                                <li>
                                    @auth
                                        <a href="#" onclick="event.preventDefault(); Livewire.dispatch('openChatModal', { productId: {{ $product->id }} })">
                                            <i class="fas fa-comments"></i> Chat Now
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}">
                                            <i class="fas fa-comments"></i> Chat Now
                                        </a>
                                    
                                        
                                    @endauth
                                    
                                </li>
                            </ul>

                            <!-- Product Details -->
                            <ul class="mt-3 details_tags_sku">
                                <li>
                                    <span>Category:</span> 
                                    @foreach($product->categories as $category)
                                        {{ $category->name }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </li>
                                <li><span>Stock:</span> {{ $product->stock }} units</li>
                                @if($product->variants->count() > 0)
                                <li><span>Variants:</span> {{ $product->variants->count() }} options</li>
                                @endif
                                <li><span>Brand:</span> {{ $product->brand ?? 'N/A' }}</li>
                            </ul>

                            <!-- Social Sharing -->
                            <ul class="mt-3 shop_details_shate">
                                <li>Share:</li>
                                <li>
                                    <a href="javascript:void(0);" onclick="shareOnFacebook(event); return false;">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="shareOnTwitter(event); return false;">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="shareOnWhatsApp(event); return false;">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="copyLink(event); return false;">
                                        <i class="fas fa-link"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Product Tabs -->
                <div class="row mt_90 wow fadeInUp">
                    <div class="col-12">
                        <div class="shop_details_des_area">
                            <ul class="nav nav-pills" id="pills-tab2" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="description-tab" data-bs-toggle="pill"
                                        data-bs-target="#description" type="button" role="tab"
                                        aria-controls="description" aria-selected="false">Description</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="specifications-tab" data-bs-toggle="pill"
                                        data-bs-target="#specifications" type="button" role="tab"
                                        aria-controls="specifications" aria-selected="false">Specifications</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="reviews-tab" data-bs-toggle="pill"
                                        data-bs-target="#reviews" type="button" role="tab"
                                        aria-controls="reviews" aria-selected="false">
                                        Reviews ({{ $reviewCount }})
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="pills-tabContent2">
                                <!-- Description Tab -->
                                <div class="tab-pane fade show active" id="description" role="tabpanel"
                                    aria-labelledby="description-tab" tabindex="0">
                                    <div class="shop_details_description">
                                        <h3>Description</h3>
                                        {!! $product->description !!}
                                        
                                        <!-- Additional Specifications if available -->
                                        @if($product->specifications)
                                        <div class="mt-4">
                                            <h4>Additional Details</h4>
                                            <div class="row">
                                                @foreach(json_decode($product->specifications, true) as $key => $value)
                                                <div class="mb-2 col-md-6">
                                                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> 
                                                    {{ $value }}
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Specifications Tab -->
                                <div class="tab-pane fade" id="specifications" role="tabpanel"
                                    aria-labelledby="specifications-tab" tabindex="0">
                                    <div  class="shop_details_specifications">
                                        <h3 style="margin-block: 20px">Product Specifications</h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <td><strong>Product Name</strong></td>
                                                            <td>{{ $product->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Brand</strong></td>
                                                            <td>{{ $product->brand ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Categories</strong></td>
                                                            <td>
                                                                @foreach($product->categories as $category)
                                                                    {{ $category->name }}{{ !$loop->last ? ', ' : '' }}
                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Weight</strong></td>
                                                            <td>{{ $product->weight ?? 'N/A' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <td><strong>Base Price</strong></td>
                                                            <td>₹{{ number_format($product->base_price, 2) }}</td>
                                                        </tr>
                                                        @if($product->sale_price)
                                                        <tr>
                                                            <td><strong>Sale Price</strong></td>
                                                            <td class="text-danger">₹{{ number_format($product->sale_price, 2) }}</td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <td><strong>Stock</strong></td>
                                                            <td>{{ $product->stock }} units</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Product Code</strong></td>
                                                            <td>{{ $product->id }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                                        <!-- Variants Table -->
                                        @if($product->variants->count() > 0)
                                        <div class="mt-4">
                                            <h4>Available Variants</h4>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            @if($product->variants->first()->color)<th>Color</th>@endif
                                                            @if($product->variants->first()->size)<th>Size</th>@endif
                                                            <th>Price Adjustment</th>
                                                            <th>Final Price</th>
                                                            <th>Stock</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($product->variants as $variant)
                                                        <tr>
                                                            @if($variant->color)
                                                            <td>
                                                                <span class="color-dot" style="background-color: {{ $variant->color }};"></span>
                                                                {{ $variant->color }}
                                                            </td>
                                                            @endif
                                                            @if($variant->size)<td>{{ $variant->size }}</td>@endif
                                                            <td>
                                                                @if($variant->price_adjustment > 0)
                                                                <span class="text-success">+₹{{ number_format($variant->price_adjustment, 2) }}</span>
                                                                @elseif($variant->price_adjustment < 0)
                                                                <span class="text-danger">-₹{{ number_format(abs($variant->price_adjustment), 2) }}</span>
                                                                @else
                                                                <span>No change</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                ₹{{ number_format($product->base_price + $variant->price_adjustment, 2) }}
                                                            </td>
                                                            <td>{{ $variant->stock }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Reviews Tab -->
                                <div class="tab-pane fade" id="reviews" role="tabpanel"
                                    aria-labelledby="reviews-tab" tabindex="0">
                                    <div class="shop_details_review">
                                        <div class="single_review_list_area">
                                             
                                            
                                            <!-- Reviews List -->
                                            @if($reviewCount > 0)
                                                @foreach($product->reviews as $review)
                                                <div class="single_review">
                                                    <div class="img">
                                                        @if($review->user->avatar)
                                                            <img src="{{ $review->user->avatar
                                                                ? (Str::startsWith($review->user->avatar, 'http')
                                                                    ? $review->user->avatar
                                                                    : asset('storage/' . $review->user->avatar))
                                                                : 'https://cdn-icons-png.flaticon.com/512/8792/8792047.png' }}" 
                                                                 alt="{{ $review->user->name }}" 
                                                                 class="img-fluid w-100">
                                                        @else
                                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                                                 style="width: 60px; height: 60px;">
                                                                <span class="text-white fw-bold">
                                                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="text">
                                                        <h5>
                                                            {{ $review->user->name }}
                                                            <span>
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    @if($i <= $review->rating)
                                                                        <i class="fas fa-star text-warning"></i>
                                                                    @else
                                                                        <i class="far fa-star text-warning"></i>
                                                                    @endif
                                                                @endfor
                                                            </span>
                                                        </h5>
                                                        <p class="date">{{ $review->created_at->format('d F Y') }}</p>
                                                        <p class="description">{{ $review->review }}</p>
                                                    </div>
                                                </div>
                                                @endforeach
                                            @else
                                                <div class="py-4 text-center">
                                                    <i class="fas fa-comments display-1 text-muted"></i>
                                                    <h5 class="mt-3">No reviews yet</h5>
                                                    <p class="text-muted">Be the first to review this product</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-xxl-2 wow fadeInRight">
                <div id="sticky_sidebar_2">
                    <div class="shop_details_sidebar">
                        <div class="row">
                            <!-- Shipping Info -->
                            <div class="col-xxl-12 col-md">
                                <div class="shop_details_sidebar_info">
                                    <ul>
                                        <li>
                                            <span>
                                                <i class="fas fa-shipping-fast"></i>
                                            </span>
                                            Shipping worldwide
                                        </li>
                                        <li>
                                            <span>
                                                <i class="fas fa-check-circle"></i>
                                            </span>
                                            Always Authentic
                                        </li>
                                        <li>
                                            <span>
                                                <i class="fas fa-money-bill-wave"></i>
                                            </span>
                                            Cash on Delivery Available
                                        </li>
                                    </ul>
                                    <h5>Return & Warranty</h5>
                                    <ul>
                                        <li>
                                            <span>
                                                <i class="fas fa-exchange-alt"></i>
                                            </span>
                                            14 days easy return
                                        </li>
                                        <li>
                                            <span>
                                                <i class="fas fa-shield-alt"></i>
                                            </span>
                                            1 Year Warranty
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Seller Info (Changed to Admin Contact) -->
                            <div class="col-xxl-12 col-md">
                                <div class="shop_details_sidebar_store">
                                    <p class="sold_by">Customer Support</p>
                                    <h4 class="store_name">Administrator</h4>
                                    <ul>
                                        <li>
                                            <p>Response Time</p>
                                            <span>Within 1 hour</span>
                                        </li>
                                        <li>
                                            <p>Support Hours</p>
                                            <span>24/7 Available</span>
                                        </li>
                                        <li>
                                            <p>Help Topics</p>
                                            <span>Product Queries, Orders, Returns</span>
                                        </li>
                                    </ul>
                                    <div class="gap-2 mt-3 d-grid">
                                        @auth
                                            <button type="button" 
                                                    onclick="event.preventDefault(); Livewire.dispatch('openChatModal')" 
                                                    class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-comments me-1"></i> Chat Now
                                            </button>
                                        @else
                                            <a href="{{ route('login') }}" 
                                            class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-comments me-1"></i> Chat Now
                                            </a>
                                        @endauth
                                        <a href="tel:+911234567890" class="btn btn-primary btn-sm">
                                            <i class="fas fa-phone me-1"></i> Call Support
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Estimated Delivery -->
                            <div class="mt-3 col-xxl-12 col-md">
                                <div class="border-0 shadow-sm card">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-truck me-2"></i>Delivery Info
                                        </h6>
                                        <ul class="mb-0 list-unstyled">
                                            <li class="mb-2">
                                                <small class="text-muted">Delivery Time:</small>
                                                <p class="mb-0">3-7 business days</p>
                                            </li>
                                            <li class="mb-2">
                                                <small class="text-muted">Shipping Cost:</small>
                                                <p class="mb-0">Free shipping on orders above ₹999</p>
                                            </li>
                                            <li>
                                                <small class="text-muted">Estimated Delivery:</small>
                                                <p class="mb-0">{{ now()->addDays(5)->format('d M Y') }}</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
@if($relatedProducts->count() > 0)
<section class="related_products mt_90 mb_70 wow fadeInUp">
    <div class="container">
        <div class="row">
            <div class="col-xl-6">
                <div class="section_heading_2 section_heading">
                    <h3><span>Related</span> Products</h3>
                </div>
            </div>
        </div>
        <div class="row mt_25">
            @foreach($relatedProducts as $relatedProduct)
            <div class="mb-4 col-xl-3 col-lg-4 col-md-6">
                <div class="product_item_2 product_item h-100">
                    <div class="product_img position-relative">
                        @php
                            $primaryImage = $relatedProduct->images->where('is_primary', true)->first();
                            if (!$primaryImage && $relatedProduct->images->count() > 0) {
                                $primaryImage = $relatedProduct->images->first();
                            }
                        @endphp
                        
                        <a href="{{ route('product.show', $relatedProduct->slug) }}">
                            @if($primaryImage)
                                <img src="{{ asset('storage/' . $primaryImage->image_path) }}" 
                                     alt="{{ $relatedProduct->name }}" 
                                     class="img-fluid w-100" style="height: 200px; object-fit: cover;">
                            @else
                                <img src="https://placehold.co/300x200?text=No+Image" 
                                     alt="No Image" 
                                     class="img-fluid w-100" style="height: 200px; object-fit: cover;">
                            @endif
                        </a>
                        
                        <!-- Badges -->
                        <div class="top-0 p-2 position-absolute start-0">
                            @if($relatedProduct->sale_price)
                                @php
                                    $discount = round((($relatedProduct->base_price - $relatedProduct->sale_price) / $relatedProduct->base_price) * 100);
                                @endphp
                                <span class="badge bg-danger">{{ $discount }}% OFF</span>
                            @endif
                            @if($relatedProduct->created_at->gt(now()->subDays(7)))
                                <span class="badge bg-success">New</span>
                            @endif
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="top-0 p-2 position-absolute end-0">
                            @livewire('wishlist-toggle', [
                                'productId' => $relatedProduct->id,
                                'buttonType' => 'icon'
                            ], key('related-wishlist-' . $relatedProduct->id))
                        </div>
                    </div>
                    <div class="p-3 product_text">
                        <a class="mb-2 title d-block" href="{{ route('product.show', $relatedProduct->slug) }}">
                            {{ Str::limit($relatedProduct->name, 40) }}
                        </a>
                        
                        <!-- Rating -->
                        @php
                            $relatedAvgRating = $relatedProduct->reviews_avg_rating ?? 0;
                            $relatedReviewCount = $relatedProduct->reviews_count ?? 0;
                        @endphp
                        <div class="mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($relatedAvgRating))
                                    <i class="fas fa-star text-warning" style="font-size: 0.8rem;"></i>
                                @elseif($i == ceil($relatedAvgRating) && fmod($relatedAvgRating, 1) > 0)
                                    <i class="fas fa-star-half-alt text-warning" style="font-size: 0.8rem;"></i>
                                @else
                                    <i class="far fa-star text-warning" style="font-size: 0.8rem;"></i>
                                @endif
                            @endfor
                            <small class="text-muted">({{ $relatedReviewCount }})</small>
                        </div>
                        
                        <!-- Price -->
                        <div class="mb-3">
                            @if($relatedProduct->sale_price)
                                <span class="h5 text-danger">₹{{ number_format($relatedProduct->sale_price, 2) }}</span>
                                <span class="text-decoration-line-through text-muted">₹{{ number_format($relatedProduct->base_price, 2) }}</span>
                            @else
                                <span class="h5 text-dark">₹{{ number_format($relatedProduct->base_price, 2) }}</span>
                            @endif
                        </div>
                        
                        <!-- Add to Cart Button -->
                        @livewire('add-to-cart', [
                            'productId' => $relatedProduct->id,
                            'buttonType' => 'button'
                        ], key('related-cart-' . $relatedProduct->id))
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<x-footer />

@push('styles')
<style>
    /* Variant Styling */
    .details_variant_color li, 
    .details_variant_size li {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .details_variant_color li:hover, 
    .details_variant_size li:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .details_variant_color li.active, 
    .details_variant_size li.active {
        border-color: #007bff !important;
    }
    
    .details_variant_color li {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 10px;
        border: 2px solid #dee2e6;
    }
    
    .color-dot {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
        vertical-align: middle;
        border: 1px solid #dee2e6;
    }
    
    /* Quantity Controls */
    .details_qty_input {
        display: flex;
        align-items: center;
        margin-right: 15px;
    }
    
    .details_qty_input .minus,
    .details_qty_input .plus {
        width: 40px;
        height: 40px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .details_qty_input .minus:hover,
    .details_qty_input .plus:hover {
        background: #e9ecef;
    }
    
    .details_qty_input .quantity-input {
        width: 60px;
        height: 40px;
        text-align: center;
        border: 1px solid #dee2e6;
        border-left: none;
        border-right: none;
        -moz-appearance: textfield;
    }
    
    .details_qty_input .quantity-input::-webkit-outer-spin-button,
    .details_qty_input .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    /* Chat Button */
    .details_list_btn a:hover {
        color: #007bff;
        transform: translateY(-2px);
    }
</style>
@endpush

@push('scripts')
<script>
    
</script>
@endpush