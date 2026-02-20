<!-- main sidebar start -->
<div class="main-sidebar">
    <div class="main-menu">
        <ul class="sidebar-menu scrollable">
            <!-- Existing menu items remain unchanged -->
            <!-- 6.1 Admin Dashboard & Analytics -->
            <li class="sidebar-item {{ request()->is('admin/dashboard*') ? 'open' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->is('admin.dashboard') ? 'active' : '' }}">
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
                <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->is('admin.orders') ? 'active' : '' }}">
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

            <!-- NEW: Chat System Menu Item -->
            <li class="sidebar-item {{ request()->is('admin/support*') || request()->is('admin/chats*') ? 'open' : '' }}">
                <a role="button" class="sidebar-link-group-title has-sub">Support & Chat</a>
                <ul class="sidebar-link-group">
                    <li class="sidebar-dropdown-item">
                        <a href="{{ route('admin.support.chat') }}" class="sidebar-link {{ request()->is('admin/support/chat') ? 'active' : '' }}">
                            <span class="nav-icon"><i class="fa-light fa-comments"></i></span>
                            <span class="sidebar-txt">Live Chat</span>
                            @php
                                $unreadCount = \App\Models\Chat\ChatMessage::where('receiver_id', auth()->id())
                                    ->where('is_read', false)
                                    ->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="badge bg-danger ms-2">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="sidebar-dropdown-item">
                        <a href="{{ route('admin.support.tickets') }}" class="sidebar-link {{ request()->is('admin/support/tickets') ? 'active' : '' }}">
                            <span class="nav-icon"><i class="fa-light fa-ticket"></i></span>
                            <span class="sidebar-txt">Support Tickets</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Help Center (Bottom) -->
            <li class="help-center">
                <h3>Help Center</h3>
                <p>Need help? Our support team is here 24/7</p>
                <a href="{{ route('admin.support.chat') }}" class="btn btn-sm btn-light">Live Chat Support</a>
            </li>
        </ul>
    </div>
    
    <!-- Mini Chat Widget -->
    <div class="chat-widget">
        <div class="chat-widget-header">
            <h6><i class="fa-light fa-comments me-2"></i> Quick Chat</h6>
            <div class="chat-actions">
                <button class="chat-minimize btn btn-sm">
                    <i class="fa-light fa-minus"></i>
                </button>
                <button class="chat-close btn btn-sm">
                    <i class="fa-light fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="chat-widget-body">
            <!-- Online Users List -->
            <div class="online-users">
                <h6 class="mb-3"><i class="fa-light fa-user-check me-2"></i> Recent Users</h6>
                <div class="user-list">
                    @php
                        $recentUsers = \App\Models\Chat\ChatMessage::select('sender_id', 'receiver_id')
                            ->where(function($query) {
                                $query->where('sender_id', auth()->id())
                                    ->orWhere('receiver_id', auth()->id());
                            })
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get()
                            ->map(function($msg) {
                                return $msg->sender_id == auth()->id() ? $msg->receiver_id : $msg->sender_id;
                            })
                            ->unique();
                    @endphp
                    
                    @foreach($recentUsers as $userId)
                        @php
                            $user = \App\Models\User::find($userId);
                        @endphp
                        @if($user)
                        <div class="user-item" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                            <div class="user-avatar">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}">
                                @else
                                    <div class="avatar-placeholder">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                                @endif
                                <span class="online-status"></span>
                            </div>
                            <div class="user-info">
                                <h6>{{ $user->name }}</h6>
                                <small>{{ ucfirst($user->role) }}</small>
                            </div>
                            @php
                                $unread = \App\Models\Chat\ChatMessage::where('sender_id', $user->id)
                                    ->where('receiver_id', auth()->id())
                                    ->where('is_read', false)
                                    ->count();
                            @endphp
                            @if($unread > 0)
                                <span class="unread-count">{{ $unread }}</span>
                            @endif
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            
            <!-- Active Chats -->
            <div class="active-chats">
                <h6 class="mb-3"><i class="fa-light fa-message-lines me-2"></i> Recent Chats</h6>
                @php
                    $recentChats = \App\Models\Chat\ChatMessage::where('receiver_id', auth()->id())
                        ->orWhere('sender_id', auth()->id())
                        ->orderBy('created_at', 'desc')
                        ->take(3)
                        ->get()
                        ->unique(function($msg) {
                            $otherId = $msg->sender_id == auth()->id() ? $msg->receiver_id : $msg->sender_id;
                            return $msg->chat_room_id . '-' . $otherId;
                        });
                @endphp
                
                @foreach($recentChats as $recentChat)
                    @php
                        $otherUserId = $recentChat->sender_id == auth()->id() ? $recentChat->receiver_id : $recentChat->sender_id;
                        $otherUser = \App\Models\User::find($otherUserId);
                    @endphp
                    @if($otherUser)
                    <div class="chat-item" data-user-id="{{ $otherUser->id }}">
                        <div class="chat-avatar">
                            @if($otherUser->profile_photo)
                                <img src="{{ asset('storage/' . $otherUser->profile_photo) }}" alt="{{ $otherUser->name }}">
                            @else
                                <div class="avatar-placeholder">{{ strtoupper(substr($otherUser->name, 0, 2)) }}</div>
                            @endif
                        </div>
                        <div class="chat-info">
                            <h6>{{ $otherUser->name }}</h6>
                            <p class="last-message">{{ Str::limit($recentChat->message, 20) }}</p>
                            <small class="text-muted">{{ $recentChat->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        
        <div class="chat-widget-footer">
            <a href="{{ route('admin.support.chat') }}" class="btn btn-primary btn-sm w-100">
                <i class="fa-light fa-plus me-2"></i> Start New Chat
            </a>
        </div>
    </div>
</div>
<!-- main sidebar end -->