<header class="header_2">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-2">
                <div class="header_logo_area">
                    <a href="{{ route('home') }}" class="header_logo">
                        <img src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('assets/images/logo_2.png') }}" 
                             alt="{{ setting('site_name', 'Zenis') }}" class="img-fluid w-100">
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
                        @foreach($mainCategories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
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
                            <a href="tel:{{ setting('contact_number') }}">
                                <span>{{ setting('contact_number', '+(402) 763 282 46') }}</span>
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
                            @foreach($mainCategories as $category)
                                <li>
                                    <a href="{{ route('category.show', $category->slug) }}">
                                        <span>
                                            @if($category->image)
                                                <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}">
                                            @else
                                                <img src="https://via.placeholder.com/30x30?text={{ substr($category->name, 0, 2) }}" alt="{{ $category->name }}">
                                            @endif
                                        </span>
                                        {{ $category->name }}
                                    </a>

                                    @if($category->children->count() > 0)
                                        <ul class="menu_cat_droapdown">
                                            @foreach($category->children as $subcategory)
                                                <li>
                                                    <a href="{{ route('category.show', $subcategory->slug) }}">
                                                        {{ $subcategory->name }}
                                                        @if($subcategory->children->count() > 0)
                                                            <i class="fal fa-angle-right"></i>
                                                        @endif
                                                    </a>

                                                    @if($subcategory->children->count() > 0)
                                                        <ul class="sub_category">
                                                            @foreach($subcategory->children as $child)
                                                                <li>
                                                                    <a href="{{ route('category.show', $child->slug) }}">
                                                                        {{ $child->name }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
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
                        <li><a class="{{ request()->routeIs('shop') ? 'active' : '' }}" href="{{ route('shop') }}">Products</a></li>
                        <li><a class="{{ request()->routeIs('stores') ? 'active' : '' }}" href="{{ route('stores') }}">Manufacturers</a></li>
                        <li><a class="{{ request()->routeIs('flash.deals') ? 'active' : '' }}" href="{{ route('flash.deals') }}">Flash Deals</a></li>
                        
                        @auth
                            @if(auth()->user()->role === 'buyer')
                                <li><a class="{{ request()->routeIs('become.seller') ? 'active' : '' }}" href="{{ route('become.seller') }}">Become a Vendor</a></li>
                            @endif
                        @else
                            <li><a href="{{ route('become.seller') }}">Become a Vendor</a></li>
                        @endauth
                        
                        <li><a class="{{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>
                    </ul>

                    <ul class="menu_icon">
                        @auth
                            @if(auth()->user()->role === 'buyer')
                                <li>
                                    <a href="{{ route('buyer.wishlist') }}">
                                        <b><img src="{{ asset('assets/images/love_black.svg') }}" alt="Wishlist"></b>
                                        <span>{{ auth()->user()->wishlist()->count() }}</span>
                                    </a>
                                </li>

                                <li>
                                    <a data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight">
                                        <b><img src="{{ asset('assets/images/cart_black.svg') }}" alt="cart"></b>
                                        <span>{{ auth()->user()->cart()->count() }}</span>
                                    </a>
                                </li>
                            @endif

                            <li class="position-relative">
                                <a class="user">
                                    <b><img src="{{ asset('assets/images/user_icon_black.svg') }}" alt="user"></b>
                                    <h5>{{ Str::limit(auth()->user()->name, 12) }}</h5>
                                    <small class="role-badge">
                                        @switch(auth()->user()->role)
                                            @case('admin')
                                                <span class="badge bg-danger">Admin</span>
                                                @break
                                            @case('seller')
                                                <span class="badge bg-success">{{ auth()->user()->status === 'active' ? 'Seller' : 'Pending' }}</span>
                                                @break
                                            @default
                                                <span class="badge bg-primary">Buyer</span>
                                        @endswitch
                                    </small>
                                </a>
                                <ul class="user_dropdown">
                                    <li><a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'seller' ? route('seller.dashboard') : route('buyer.dashboard')) }}">Dashboard</a></li>
                                    <li><a href="{{ auth()->user()->role === 'seller' ? route('seller.profile') : route('buyer.profile') }}">My Account</a></li>

                                    @if(auth()->user()->role === 'seller' && auth()->user()->status === 'active')
                                        <li><a href="{{ route('seller.store') }}">My Store</a></li>
                                        <li><a href="{{ route('seller.products.index') }}">Products</a></li>
                                        <li><a href="{{ route('seller.orders') }}">Orders</a></li>
                                    @endif

                                    @if(auth()->user()->role === 'buyer')
                                        <li><a href="{{ route('buyer.orders') }}">My Orders</a></li>
                                        <li><a href="{{ route('buyer.wishlist') }}">Wishlist</a></li>
                                    @endif

                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li style="display: flex; gap: 10px;">
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

<!-- Mobile Menu & Cart Offcanvas -->
@include('partials.mobile-menu') <!-- Create this partial for mobile menu -->

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