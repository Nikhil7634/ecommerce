<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div class="logo-icon">
            <img src="{{ asset('seller-assets/assets/images/logo-icon.png') }}" class="logo-img" alt="Logo">
        </div>
        <div class="logo-name flex-grow-1">
            <h5 class="mb-0">Seller Panel</h5>
        </div>
        <div class="sidebar-close">
            <span class="material-icons-outlined">close</span>
        </div>
    </div>

    <div class="sidebar-nav">
        <ul class="metismenu" id="sidenav">

            <!-- Dashboard -->
            <li class="{{ request()->routeIs('seller.dashboard') ? 'mm-active' : '' }}">
                <a href="{{ route('seller.dashboard') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">dashboard</i></div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>

            <!-- My Store -->
            <li class="{{ request()->routeIs('seller.store') || request()->routeIs('seller.store.edit') ? 'mm-active' : '' }}">
                <a href="{{ route('seller.store') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">store</i></div>
                    <div class="menu-title">My Store</div>
                </a>
            </li>

            <!-- Products -->
            <li class="{{ request()->is('seller/products*') ? 'mm-active' : '' }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="material-icons-outlined">inventory_2</i></div>
                    <div class="menu-title">Products</div>
                </a>
                <ul class="{{ request()->is('seller/products*') ? 'mm-show' : '' }}">
                    <li>
                        <a href="{{ route('seller.products.create') }}">
                            <i class="material-icons-outlined">add_circle_outline</i> Add Product
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('seller.products.index') }}">
                            <i class="material-icons-outlined">list_alt</i> All Products
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Orders -->
            <li class="{{ request()->routeIs('seller.orders') || request()->routeIs('seller.order.show') ? 'mm-active' : '' }}">
                <a href="{{ route('seller.orders') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">shopping_cart</i></div>
                    <div class="menu-title">Orders</div>
                </a>
            </li>

            <!-- Earnings -->
            <li class="{{ request()->routeIs('seller.earnings') ? 'mm-active' : '' }}">
                <a href="{{ route('seller.earnings') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">trending_up</i></div>
                    <div class="menu-title">Earnings</div>
                </a>
            </li>

            <!-- Withdraw -->
            <li class="{{ request()->routeIs('seller.withdraw') ? 'mm-active' : '' }}">
                <a href="{{ route('seller.withdraw') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">account_balance_wallet</i></div>
                    <div class="menu-title">Withdraw</div>
                </a>
            </li>

            <!-- Subscription -->
            <li class="{{ request()->routeIs('seller.subscription') ? 'mm-active' : '' }}">
                <a href="{{ route('seller.subscription') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">card_membership</i></div>
                    <div class="menu-title">Subscription</div>
                </a>
            </li>

            <!-- Reviews -->
            <li class="{{ request()->routeIs('seller.reviews') ? 'mm-active' : '' }}">
                <a href="{{ route('seller.reviews') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">rate_review</i></div>
                    <div class="menu-title">Reviews</div>
                </a>
            </li>

            <!-- Support Tickets -->
            <li class="{{ request()->routeIs('seller.tickets*') ? 'mm-active' : '' }}">
                <a href="{{ route('seller.tickets') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">support_agent</i></div>
                    <div class="menu-title">Support Tickets</div>
                </a>
            </li>

            <!-- Account Section -->
            <li class="menu-label">Account</li>

            <!-- Profile -->
            <li class="{{ request()->routeIs('seller.profile') || request()->routeIs('seller.profile.edit') ? 'mm-active' : '' }}">
                <a href="{{ route('seller.profile') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">person</i></div>
                    <div class="menu-title">My Profile</div>
                </a>
            </li>

            <!-- Settings -->
            <li class="{{ request()->routeIs('seller.settings') ? 'mm-active' : '' }}">
                <a href="{{ route('seller.settings') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">settings</i></div>
                    <div class="menu-title">Settings</div>
                </a>
            </li>

            <!-- Logout -->
            <li>
                <a href="javascript:;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <div class="parent-icon"><i class="material-icons-outlined">logout</i></div>
                    <div class="menu-title">Logout</div>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>

        </ul>
    </div>
</aside>