<!-- main sidebar start -->
<div class="main-sidebar">
    <div class="main-menu">
        <ul class="sidebar-menu scrollable">
            <!-- 6.1 Admin Dashboard & Analytics -->
            <li class="sidebar-item {{ request()->is('admin/dashboard*') ? 'open' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-light fa-gauge-high"></i></span>
                    <span class="sidebar-txt">Dashboard & Analytics</span>
                </a>
            </li>

            <!-- 6.2 User Management (Buyers) -->
            <li class="sidebar-item {{ request()->is('admin/buyers*') ? 'open' : '' }}">
                <a role="button" class="sidebar-link-group-title has-sub">User Management</a>
                <ul class="sidebar-link-group">
                    <li class="sidebar-dropdown-item">
                        <a href="{{ route('admin.buyers.index') }}" class="sidebar-link-group-title  {{ request()->is('admin/buyers') ? 'active' : '' }}">
                            <span class="nav-icon"><i class="fa-light fa-users"></i></span>
                            <span class="sidebar-txt">All Buyers</span>
                        </a>
                    </li>
                    <li class="sidebar-dropdown-item">
                        <a href="{{ route('admin.buyers.blocked') }}" class="sidebar-link">
                            <span class="nav-icon"><i class="fa-light fa-user-slash"></i></span>
                            <span class="sidebar-txt">Blocked Users</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- 6.3 Seller Management -->
            <li class="sidebar-item {{ request()->is('admin/sellers*') ? 'open' : '' }}">
                <a role="button" class="sidebar-link-group-title has-sub">Seller Management</a>
                <ul class="sidebar-link-group">
                    <li class="sidebar-dropdown-item">
                        <a href="{{ route('admin.sellers.pending') }}" class="sidebar-link {{ request()->is('admin/sellers/pending') ? 'active' : '' }}">
                            <span class="nav-icon"><i class="fa-light fa-user-clock"></i></span>
                            <span class="sidebar-txt">Pending Approvals</span>
                            @if($pendingCount = \App\Models\User::where('role', 'seller')->where('status', 'inactive')->count())
                                <span style="position: absolute" class="badge bg-danger ms-2">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="sidebar-dropdown-item">
                        <a href="{{ route('admin.sellers.index') }}" class="sidebar-link {{ request()->is('admin/sellers') && !request()->is('admin/sellers/pending') ? 'active' : '' }}">
                            <span class="nav-icon"><i class="fa-light fa-store"></i></span>
                            <span class="sidebar-txt">All Sellers</span>
                        </a>
                    </li>
                    <li class="sidebar-dropdown-item">
                        <a href="{{ route('admin.sellers.suspended') }}" class="sidebar-link">
                            <span class="nav-icon"><i class="fa-light fa-store-slash"></i></span>
                            <span class="sidebar-txt">Suspended Sellers</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- 6.4 Product Management -->
            <li class="sidebar-item {{ request()->is('admin/products*') ? 'open' : '' }}">
                <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->is('admin/products') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-light fa-boxes-stacked"></i></span>
                    <span class="sidebar-txt">Product Management</span>
                </a>
            </li>

            <!-- 6.5 Category Management -->
            <li class="sidebar-item {{ request()->is('admin/categories*') ? 'open' : '' }}">
                <a role="button" class="sidebar-link-group-title has-sub">Category Management</a>
                <ul class="sidebar-link-group">
                    <li class="sidebar-dropdown-item">
                        <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->is('admin/categories') && !request()->is('admin/categories/create') ? 'active' : '' }}">
                            <span class="nav-icon"><i class="fa-light fa-folder-tree"></i></span>
                            <span class="sidebar-txt">All Categories</span>
                        </a>
                    </li>
                    <li class="sidebar-dropdown-item">
                        <a href="{{ route('admin.categories.create') }}" class="sidebar-link {{ request()->is('admin/categories/create') ? 'active' : '' }}">
                            <span class="nav-icon"><i class="fa-light fa-folder-plus"></i></span>
                            <span class="sidebar-txt">Add Category</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- 6.6 Order Management -->
            <li class="sidebar-item {{ request()->is('admin/orders*') ? 'open' : '' }}">
                <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->is('admin/orders') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-light fa-truck-fast"></i></span>
                    <span class="sidebar-txt">Order Management</span>
                </a>
            </li>

            <!-- 6.7 Payment Management & Subscriptions -->
            <li class="sidebar-item {{ request()->is('admin/payments*') || request()->is('admin/subscriptions*') ? 'open' : '' }}">
                <a role="button" class="sidebar-link-group-title has-sub">Payment & Subscriptions</a>
                <ul class="sidebar-link-group">
                    <li class="sidebar-dropdown-item">
                        <a href="{{ route('admin.subscriptions.index') }}" class="sidebar-link {{ request()->is('admin/subscriptions') ? 'active' : '' }}">
                            <span class="nav-icon"><i class="fa-light fa-crown"></i></span>
                            <span class="sidebar-txt">Subscription Plans</span>
                        </a>
                    </li>
                    <li class="sidebar-dropdown-item">
                        <a href="{{ route('admin.payments.transactions') }}" class="sidebar-link">
                            <span class="nav-icon"><i class="fa-light fa-credit-card"></i></span>
                            <span class="sidebar-txt">Transactions</span>
                        </a>
                    </li>
                    <li class="sidebar-dropdown-item">
                        <a href="{{ route('admin.payments.withdrawals') }}" class="sidebar-link">
                            <span class="nav-icon"><i class="fa-light fa-money-bill-transfer"></i></span>
                            <span class="sidebar-txt">Seller Withdrawals</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- 6.8 Reports & Analytics -->
            <li class="sidebar-item {{ request()->is('admin/reports*') ? 'open' : '' }}">
                <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->is('admin/reports') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-light fa-chart-mixed"></i></span>
                    <span class="sidebar-txt">Reports & Analytics</span>
                </a>
            </li>

            <!-- 6.9 System Settings -->
            <li class="sidebar-item {{ request()->is('admin/settings*') ? 'open' : '' }}">
                <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->is('admin/settings') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-light fa-gears"></i></span>
                    <span class="sidebar-txt">System Settings</span>
                </a>
            </li>

            <!-- 6.10 Content Management -->
            <li class="sidebar-item {{ request()->is('admin/content*') ? 'open' : '' }}">
                <a href="{{ route('admin.content.pages') }}" class="sidebar-link {{ request()->is('admin/content') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-light fa-file-lines"></i></span>
                    <span class="sidebar-txt">Content Management</span>
                </a>
            </li>

            <!-- Help Center (Bottom) -->
            <li class="help-center">
                <h3>Help Center</h3>
                <p>Need help? Our support team is here 24/7</p>
                <a href="#" class="btn btn-sm btn-light">Contact Support</a>
            </li>
        </ul>
    </div>
</div>
<!-- main sidebar end -->