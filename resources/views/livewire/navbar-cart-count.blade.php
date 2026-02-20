<div style="display: flex; gap: 10px;">
    @if(auth()->check() && auth()->user()->role === 'buyer')
        <li>
            <a href="{{ route('buyer.wishlist') }}">
                <b><img src="{{ asset('assets/images/love_black.svg') }}" alt="Wishlist"></b>
                <span>{{ $wishlistCount }}</span>
            </a>
        </li>

        <li>
            <a data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight">
                <b><img src="{{ asset('assets/images/cart_black.svg') }}" alt="cart"></b>
                <span>{{ $cartCount }}</span>
            </a>
        </li>
    @endif
</div>

 