<header class="header_2">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-2">
                <div class="header_logo_area">
                    <a href="{{ route('home') }}" class="header_logo">
                        <img src="{{ asset('assets/images/logo_2.png') }}" alt="Zenis" class="img-fluid w-100">
                    </a>
                    <div class="mobile_menu_icon d-block d-lg-none" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">
                        <span class="mobile_menu_icon"><i class="far fa-stream menu_icon_bar"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6 col-xl-5 col-lg-5 d-none d-lg-block">
                <form action="{{ route('shop') }}" method="GET">
                    @csrf
                    <select name="category" class="select_2">
                        <option value="">All Categories</option>
                        <option value="fashion">Fashion</option>
                        <option value="electronics">Electronics</option>
                        <option value="beauty">Fashion & Beauty</option>
                        <option value="jewelry">Jewelry</option>
                        <option value="grocery">Grocery</option>
                    </select>
                    <div class="input">
                        <input type="text" name="search" placeholder="Search your product..." value="{{ request('search') }}">
                        <button type="submit"><i class="far fa-search"></i></button>
                    </div>
                </form>
            </div>
            <div class="col-xxl-4 col-xl-5 col-lg-5 d-none d-lg-flex">
                <div class="flex-wrap header_support_user d-flex">
                    <div class="header_support">
                        <span class="icon">
                            <i class="far fa-phone-alt"></i>
                        </span>
                        <h3>
                            Hotline:
                            <a href="tel:+40276328246">
                                <span>+(402) 763 282 46</span>
                            </a>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<nav class="main_menu_2 main_menu d-none d-lg-block">
    <div class="container">
        <div class="row">
            <div class="flex-wrap col-12 d-flex">
                <div class="main_menu_area">
                    <div class="menu_category_area">
                        <a href="{{ route('home') }}" class="menu_logo d-none">
                            <img src="{{ asset('assets/images/logo_2.png') }}" alt="Zenis" class="img-fluid w-100">
                        </a>
                        <div class="menu_category_bar">
                            <p>
                                <span>
                                    <img src="{{ asset('assets/images/bar_icon_white.svg') }}" alt="category icon">
                                </span>
                                Browse Categories
                            </p>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <ul class="menu_cat_item">
                            @php
                                $categories = [
                                    [
                                        'name' => "Men's Fashion", 
                                        'icon' => 'category_list_icon_1.png', 
                                        'sub' => [
                                            ['name' => 'Shirts', 'sub' => ['Casual Shirts', 'Formal Shirts', 'Denim Shirts']],
                                            ['name' => 'Pant', 'sub' => ['Casual Pants', 'Formal Trousers', 'Jeans & Denim']],
                                            ['name' => 'Casual Wear'],
                                            ['name' => 'Formal Attire']
                                        ]
                                    ],
                                    [
                                        'name' => "Women's Fashion", 
                                        'icon' => 'category_list_icon_2.png', 
                                        'sub' => [
                                            ['name' => 'Sharee'],
                                            ['name' => 'Shirts', 'sub' => ['Full Sleeves Printed', 'Full Sleeves Solid', 'Half Sleeves Solid']],
                                            ['name' => 'T-Shirts', 'sub' => ['Crew Neck', 'V Neck', 'Henley Neck']],
                                            ['name' => 'Nightie Set'], 
                                            ['name' => '3-Piece'], 
                                            ['name' => 'Leggings']
                                        ]
                                    ],
                                    [
                                        'name' => "Kid's Fashion", 
                                        'icon' => 'category_list_icon_3.png', 
                                        'sub' => [
                                            ["name" => "Boys' Fashion"], 
                                            ["name" => "Girls' Fashion"],
                                            ['name' => 'Newborn Essentials', 'sub' => ['Sleepwear', 'Loungewear']],
                                            ['name' => 'Party & Occasion Wear'], 
                                            ['name' => 'Winter Warmers'], 
                                            ['name' => 'Summer Coolers']
                                        ]
                                    ],
                                ];
                            @endphp

                            @foreach($categories as $cat)
                                <li>
                                    <a href="{{ route('shop') }}?category={{ strtolower(str_replace(' ', '-', $cat['name'])) }}">
                                        <span>
                                            <img src="{{ asset('assets/images/' . $cat['icon']) }}" alt="category">
                                        </span>
                                        {{ $cat['name'] }}
                                    </a>

                                    {{-- Dropdown --}}
                                    @if(isset($cat['sub']) && is_array($cat['sub']))
                                        <ul class="menu_cat_droapdown">
                                            @foreach($cat['sub'] as $sub)
                                                @if(isset($sub['sub']) && is_array($sub['sub']))
                                                    <li>
                                                        <a href="{{ route('shop') }}?sub={{ strtolower(str_replace(' ', '-', $sub['name'])) }}">
                                                            {{ $sub['name'] }} <i class="fal fa-angle-right"></i>
                                                        </a>
                                                        <ul class="sub_category">
                                                            @foreach($sub['sub'] as $item)
                                                                <li>
                                                                    <a href="{{ route('shop') }}?item={{ strtolower(str_replace(' ', '-', $item)) }}">
                                                                        {{ $item }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a href="{{ route('shop') }}?item={{ strtolower(str_replace(' ', '-', $sub['name'])) }}">
                                                            {{ $sub['name'] }}
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach

                            <li class="all_category">
                                <a href="{{ route('category') }}">View All Categories <i class="far fa-arrow-right"></i></a>
                            </li>
                        </ul>

                    </div>

                    <ul class="menu_item">
                        <li><a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                        <li><a class="{{ request()->routeIs('shop') ? 'active' : '' }}" href="{{ route('shop') }}">Shop</a></li>
                        <li><a class="{{ request()->routeIs('stores') ? 'active' : '' }}" href="{{ route('stores') }}">Stores</a></li>
                        <li><a class="{{ request()->routeIs('flash.deals') ? 'active' : '' }}" href="{{ route('flash.deals') }}">Flash Deals</a></li>
                        
                        {{-- Show "Become a Seller" only for buyers, not for sellers/admins --}}
                        @auth
                            @if(auth()->user()->role === 'buyer')
                                <li><a class="{{ request()->routeIs('become.seller') ? 'active' : '' }}" href="{{ route('become.seller') }}">Become a Seller</a></li>
                            @endif
                        @else
                            <li><a class="{{ request()->routeIs('become.seller') ? 'active' : '' }}" href="{{ route('become.seller') }}">Become a Seller</a></li>
                        @endauth
                        
                        <li><a class="{{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>
                    </ul>

                    <ul class="menu_icon">
                        {{-- Wishlist - Show only for buyers --}}
                        @auth
                            @if(auth()->user()->role === 'buyer')
                                <li>
                                    <a href="{{ route('buyer.wishlist') }}">
                                        <b><img src="{{ asset('assets/images/love_black.svg') }}" alt="Wishlist" class="img-fluid"></b>
                                        <span>{{ auth()->user()->wishlist_count ?? 5 }}</span>
                                    </a>
                                </li>
                            @endif
                        @else
                            <li>
                                <a href="{{ route('login') }}">
                                    <b><img src="{{ asset('assets/images/love_black.svg') }}" alt="Wishlist" class="img-fluid"></b>
                                    <span>5</span>
                                </a>
                            </li>
                        @endauth
                        
                        {{-- Cart - Show only for buyers --}}
                        @auth
                            @if(auth()->user()->role === 'buyer')
                                <li>
                                    <a data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                        <b><img src="{{ asset('assets/images/cart_black.svg') }}" alt="cart" class="img-fluid"></b>
                                        <span>{{ auth()->user()->cart_count ?? 3 }}</span>
                                    </a>
                                </li>
                            @endif
                        @else
                            <li>
                                <a data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                    <b><img src="{{ asset('assets/images/cart_black.svg') }}" alt="cart" class="img-fluid"></b>
                                    <span>3</span>
                                </a>
                            </li>
                        @endauth

                        @auth
                            <li>
                                <a class="user" href="{{ 
                                    auth()->user()->role === 'seller' ? route('seller.dashboard') : 
                                    (auth()->user()->role === 'admin' ? route('admin.dashboard') : route('buyer.dashboard')) 
                                }}">
                                    <b><img src="{{ asset('assets/images/user_icon_black.svg') }}" alt="user" class="img-fluid"></b>
                                    <h5>{{ auth()->user()->name }}</h5>
                                    <small class="role-badge">
                                        @if(auth()->user()->role === 'seller')
                                            @if(auth()->user()->status === 'active')
                                                <span class="badge bg-success">Seller</span>
                                            @else
                                                <span class="badge bg-warning">Pending Seller</span>
                                            @endif
                                        @elseif(auth()->user()->role === 'admin')
                                            <span class="badge bg-danger">Admin</span>
                                        @else
                                            <span class="badge bg-primary">Buyer</span>
                                        @endif
                                    </small>
                                </a>
                                <ul class="user_dropdown">
                                    {{-- Dashboard Link based on role --}}
                                    <li>
                                        <a href="{{ 
                                            auth()->user()->role === 'seller' ? route('seller.dashboard') : 
                                            (auth()->user()->role === 'admin' ? route('admin.dashboard') : route('buyer.dashboard')) 
                                        }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                                            </svg>
                                            Dashboard
                                        </a>
                                    </li>

                                    {{-- Profile Link based on role --}}
                                    <li>
                                        <a href="{{ 
                                            auth()->user()->role === 'seller' ? route('seller.profile') : 
                                            (auth()->user()->role === 'admin' ? '#' : route('buyer.profile')) 
                                        }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                            </svg>
                                            My Account
                                        </a>
                                    </li>

                                    {{-- Seller Specific Links --}}
                                    @if(auth()->user()->role === 'seller' && auth()->user()->status === 'active')
                                        <li>
                                            <a href="{{ route('seller.store') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                                                </svg>
                                                My Store
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ route('seller.products.index') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                                                </svg>
                                                Products
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ route('seller.orders') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                                </svg>
                                                Orders
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Buyer Specific Links --}}
                                    @if(auth()->user()->role === 'buyer')
                                        <li>
                                            <a href="{{ route('buyer.orders') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                                </svg>
                                                My Orders
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ route('buyer.wishlist') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                </svg>
                                                Wishlist
                                            </a>
                                        </li>
                                    @endif

                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                                                </svg>
                                                Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li style="display: flex;justify-content: center; align-items : center">
                                <a href="{{ route('login') }}" class="common_btn">Login</a>
                                <a href="{{ route('register') }}" class="common_btn2">Register</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Mini Cart Offcanvas - Show only for buyers and guests -->
@auth
    @if(auth()->user()->role === 'buyer')
        <div class="mini_cart">
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasRightLabel">My Cart <span>({{ auth()->user()->cart_count ?? 5 }})</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="far fa-times"></i></button>
                </div>
                <div class="offcanvas-body">
                    <ul>
                        @if(auth()->user()->cart_items && auth()->user()->cart_items->count() > 0)
                            @foreach(auth()->user()->cart_items as $item)
                                <li>
                                    <a href="{{ route('shop.details', $item->product->slug) }}" class="cart_img">
                                        <img src="{{ $item->product->image }}" alt="product" class="img-fluid w-100">
                                    </a>
                                    <div class="cart_text">
                                        <a class="cart_title" href="{{ route('shop.details', $item->product->slug) }}">{{ $item->product->name }}</a>
                                        <p>₹{{ $item->price }} <del>₹{{ $item->original_price }}</del></p>
                                        <span><b>Color:</b> {{ $item->variant_color }}</span>
                                        <span><b>Size:</b> {{ $item->variant_size }}</span>
                                    </div>
                                    <a class="del_icon" href="#" onclick="event.preventDefault(); document.getElementById('remove-cart-{{ $item->id }}').submit();">
                                        <i class="fal fa-times"></i>
                                    </a>
                                    <form id="remove-cart-{{ $item->id }}" action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-none">
                                        @csrf @method('DELETE')
                                    </form>
                                </li>
                            @endforeach
                        @else
                            <li class="text-center text-muted">Your cart is empty</li>
                        @endif
                    </ul>

                    <h5>Subtotal <span>₹{{ auth()->user()->cart_total ?? 429 }}</span></h5>
                    <div class="minicart_btn_area">
                        <a class="common_btn" href="{{ route('buyer.cart') }}">View Cart</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@else
    <!-- Show cart for guests -->
    <div class="mini_cart">
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasRightLabel">My Cart <span>(5)</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="far fa-times"></i></button>
            </div>
            <div class="offcanvas-body">
                <ul>
                    <li class="text-center text-muted">Please login to view your cart</li>
                </ul>
                <div class="minicart_btn_area">
                    <a class="common_btn" href="{{ route('login') }}">Login to View Cart</a>
                </div>
            </div>
        </div>
    </div>
@endauth

<!-- Mobile Menu Offcanvas -->
<div class="mobile_menu_area">
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fal fa-times"></i></button>
        <div class="offcanvas-body">
            <ul class="flex-wrap mobile_menu_header d-flex">
                @auth
                    @if(auth()->user()->role === 'buyer')
                        <li><a href="{{ route('buyer.wishlist') }}"><b><img src="{{ asset('assets/images/love_black.svg') }}" alt="Wishlist"></b><span>4</span></a></li>
                        <li><a href="{{ route('buyer.cart') }}"><b><img src="{{ asset('assets/images/cart_black.svg') }}" alt="cart"></b><span>5</span></a></li>
                    @endif
                    <li>
                        <a href="{{ 
                            auth()->user()->role === 'seller' ? route('seller.dashboard') : 
                            (auth()->user()->role === 'admin' ? route('admin.dashboard') : route('buyer.dashboard')) 
                        }}">
                            <b><img src="{{ asset('assets/images/user_icon_black.svg') }}" alt="user"></b>
                        </a>
                    </li>
                @else
                    <li><a href="{{ route('login') }}"><b><img src="{{ asset('assets/images/love_black.svg') }}" alt="Wishlist"></b><span>4</span></a></li>
                    <li><a href="{{ route('login') }}"><b><img src="{{ asset('assets/images/cart_black.svg') }}" alt="cart"></b><span>5</span></a></li>
                    <li><a href="{{ route('login') }}"><b><img src="{{ asset('assets/images/user_icon_black.svg') }}" alt="login"></b></a></li>
                @endauth
            </ul>

            <form class="mobile_menu_search" action="{{ route('shop') }}" method="GET">
                <input type="text" name="search" placeholder="Search" value="{{ request('search') }}">
                <button type="submit"><i class="far fa-search"></i></button>
            </form>

            <div class="mobile_menu_item_area">
                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home">Categories</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile">Menu</button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home">
                        <ul class="main_mobile_menu">
                            <li><a href="{{ route('shop') }}">Men's Fashion</a></li>
                            <li><a href="{{ route('shop') }} ">Women's Fashion</a></li>
                            <li><a href="{{ route('shop') }} ">Kids Fashion</a></li>
                            <li><a href="{{ route('shop') }}">Western Wear</a></li>
                            <li><a href="{{ route('shop') }}">Denim Collection</a></li>
                            <li><a href="{{ route('shop') }}">Sport Wear</a></li>
                            <li><a href="{{ route('shop') }}">Beauty Products</a></li>
                            <li><a href="{{ route('shop') }}">Fashion Jewellery</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="pills-profile">
                        <ul class="main_mobile_menu">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('shop') }}">Shop</a></li>
                            <li><a href="{{ route('stores') }}">Store</a></li>
                            <li><a href="{{ route('flash.deals') }}">Flash Deals</a></li>
                            
                            {{-- Show "Become a Seller" only for buyers in mobile menu --}}
                            @auth
                                @if(auth()->user()->role === 'buyer')
                                    <li><a href="{{ route('become.seller') }}">Become a Seller</a></li>
                                @endif
                            @else
                                <li><a href="{{ route('become.seller') }}">Become a Seller</a></li>
                            @endauth
                            
                            <li><a href="{{ route('contact') }}">Contact</a></li>
                            
                            @guest
                                <li><a href="{{ route('login') }}">Login</a></li>
                                <li><a href="{{ route('register') }}">Register</a></li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.role-badge {
    display: block;
    margin-top: 2px;
    font-size: 10px;
}
.role-badge .badge {
    font-size: 9px;
    
     height: min-content;
     width: min-content;
     top: 10px ;
     right: -30px;
}
 
.user h5 {
    margin-bottom: 0;
}
</style>