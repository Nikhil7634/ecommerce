<!-- Mobile Menu Offcanvas -->
<div class="mobile_menu_area">
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions">
        <div class="offcanvas-header">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"><i class="fal fa-times"></i></button>
        </div>
        <div class="offcanvas-body">
            <ul class="flex-wrap mobile_menu_header d-flex">
                @auth
                    @if(auth()->user()->role === 'buyer')
                        <li><a href="{{ route('buyer.wishlist') }}"><b><img src="{{ asset('assets/images/love_black.svg') }}" alt="Wishlist"></b><span>{{ auth()->user()->wishlist->count() ?? 0 }}</span></a></li>
                        <li><a data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"><b><img src="{{ asset('assets/images/cart_black.svg') }}" alt="cart"></b><span>{{ auth()->user()->cart->count() ?? 0 }}</span></a></li>
                    @endif
                    <li>
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'seller' ? route('seller.dashboard') : route('buyer.dashboard')) }}">
                            <b><img src="{{ asset('assets/images/user_icon_black.svg') }}" alt="user"></b>
                        </a>
                    </li>
                @else
                    <li><a href="{{ route('login') }}"><b><img src="{{ asset('assets/images/love_black.svg') }}" alt="Wishlist"></b><span>0</span></a></li>
                    <li><a href="{{ route('login') }}"><b><img src="{{ asset('assets/images/cart_black.svg') }}" alt="cart"></b><span>0</span></a></li>
                    <li><a href="{{ route('login') }}"><b><img src="{{ asset('assets/images/user_icon_black.svg') }}" alt="login"></b></a></li>
                @endauth
            </ul>

            <form class="mobile_menu_search" action="{{ route('shop') }}" method="GET">
                <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}">
                <button type="submit"><i class="far fa-search"></i></button>
            </form>

            <div class="mobile_menu_item_area">
                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-categories-tab" data-bs-toggle="pill" data-bs-target="#pills-categories">Categories</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-menu-tab" data-bs-toggle="pill" data-bs-target="#pills-menu">Menu</button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <!-- Categories Tab -->
                    <div class="tab-pane fade show active" id="pills-categories">
                        <ul class="main_mobile_menu">
                            @forelse($mainCategories as $category)
                                <li>
                                    <a href="{{ route('category.show', $category->slug) }}">
                                        {{ $category->name }}
                                        @if($category->children->count() > 0)
                                            <i class="fas fa-chevron-right float-end"></i>
                                        @endif
                                    </a>
                                    @if($category->children->count() > 0)
                                        <ul class="sub_mobile_menu">
                                            @foreach($category->children as $sub)
                                                <li><a href="{{ route('category.show', $sub->slug) }}">{{ $sub->name }}</a></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @empty
                                <li class="text-muted">No categories</li>
                            @endforelse
                            <li><a href="{{ route('category') }}">View All Categories →</a></li>
                        </ul>
                    </div>

                    <!-- Menu Tab -->
                    <div class="tab-pane fade" id="pills-menu">
                        <ul class="main_mobile_menu">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('shop') }}">Shop</a></li>
                            <li><a href="{{ route('stores') }}">Stores</a></li>
                            <li><a href="{{ route('flash.deals') }}">Flash Deals</a></li>
                            
                            @auth
                                @if(auth()->user()->role === 'buyer')
                                    <li><a href="{{ route('become.seller') }}">Become a Vendor</a></li>
                                @endif
                            @else
                                <li><a href="{{ route('become.seller') }}">Become a Vendor</a></li>
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