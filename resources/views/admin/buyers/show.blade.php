<!--start header-->
@include('admin.layouts.header')
<!--end top header-->

@include('admin.layouts.navbar')

<!--start sidebar-->
@include('admin.layouts.aside')

<div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
        <h2>Buyer Profile: {{ $buyer->name }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.buyers.index') }}">All Buyers</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $buyer->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="row g-4">
        <!-- Left Sidebar - User Info -->
        <div class="col-md-4">
            <div class="panel">
                <div class="panel-body">
                    <div class="profile-sidebar">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="profile-sidebar-title">Buyer Information</h5>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-icon btn-outline-primary" type="button" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-sm-end">
                                    <li>
                                        <form action="{{ route('admin.buyers.ban', $buyer) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('PATCH')
                                            @if($buyer->status === 'active')
                                                <button type="submit" class="dropdown-item text-warning">
                                                    <i class="fa-light fa-ban"></i> Ban Buyer
                                                </button>
                                            @else
                                                <button type="submit" class="dropdown-item text-success">
                                                    <i class="fa-light fa-unlock"></i> Unban Buyer
                                                </button>
                                            @endif
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.buyers.destroy', $buyer) }}" method="POST" onsubmit="return confirm('Delete this buyer permanently?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fa-light fa-trash"></i> Delete Buyer
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="top">
                            <div class="image-wrap">
                                <div class="overflow-hidden part-img rounded-circle">
                                    <img src="{{ 
                                            $buyer->avatar 
                                                ? (Str::startsWith($buyer->avatar, ['http://', 'https://']) 
                                                    ? $buyer->avatar 
                                                    : asset('storage/' . $buyer->avatar)) 
                                                : asset('https://cdn-icons-png.flaticon.com/512/8792/8792047.png') 
                                        }}" 
                                        alt="{{ $buyer->name }}" class="w-100 rounded-circle">
                                </div>
                            </div>
                            <div class="mt-3 text-center part-txt">
                                <h4 class="admin-name">{{ $buyer->name }}</h4>
                                <span class="admin-role text-capitalize">
                                    <span class="badge bg-{{ $buyer->status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($buyer->status) }} Buyer
                                    </span>
                                </span>
                                <div class="mt-3 admin-social">
                                    <a href="mailto:{{ $buyer->email }}"><i class="fa-solid fa-envelope"></i></a>
                                    @if($buyer->phone)
                                        <a href="tel:{{ $buyer->phone }}"><i class="fa-solid fa-phone"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 bottom">
                            <h6 class="profile-sidebar-subtitle">Communication Info</h6>
                            <ul>
                                <li><span>Full Name:</span> {{ $buyer->name }}</li>
                                <li><span>Mobile:</span> {{ $buyer->phone ?? 'Not provided' }}</li>
                                <li><span>Email:</span> <a href="mailto:{{ $buyer->email }}">{{ $buyer->email }}</a></li>
                                <li><span>Address:</span> 
                                    {{ $buyer->address ?? 'N/A' }}<br>
                                    {{ $buyer->city ? $buyer->city . ', ' : '' }}
                                    {{ $buyer->state ?? '' }} {{ $buyer->zip ?? '' }}<br>
                                    {{ $buyer->country ?? 'India' }}
                                </li>
                                <li><span>Joining Date:</span> {{ $buyer->created_at->format('d M Y') }}</li>
                                <li><span>Last Active:</span> {{ $buyer->updated_at->diffForHumans() }}</li>
                            </ul>

                            <h6 class="mt-4 profile-sidebar-subtitle">Order Summary</h6>
                            <ul class="list-unstyled">
                                <li><strong>Total Orders:</strong> {{ $buyer->orders->count() }}</li>
                                <li><strong>Total Spent:</strong> ₹{{ number_format($buyer->orders->sum('total'), 2) }}</li>
                                <li><strong>Average Order Value:</strong> 
                                    @if($buyer->orders->count() > 0)
                                        ₹{{ number_format($buyer->orders->avg('total'), 2) }}
                                    @else
                                        ₹0.00
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Stats & Activity -->
        <div class="col-md-8">
            <div class="row mb-25">
                <div class="col-lg-4">
                    <div class="dashboard-top-box rounded-bottom panel-bg">
                        <div class="left">
                            <h3>₹{{ number_format($buyer->orders->sum('total'), 2) }}</h3>
                            <p>Total Spend</p>
                            <a href="{{ route('admin.orders.index') }}?buyer={{ $buyer->id }}">View Orders</a>
                        </div>
                        <div class="right">
                            <span class="text-primary">Lifetime</span>
                            <div class="rounded part-icon">
                                <span><i class="fa-light fa-rupee-sign"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6 col-xs-12">
                    <div class="dashboard-top-box rounded-bottom panel-bg">
                        <div class="left">
                            <h3>{{ $buyer->orders->count() }}</h3>
                            <p>Total Orders</p>
                            <a href="{{ route('admin.orders.index') }}?status=delivered&buyer={{ $buyer->id }}">Delivered</a>
                        </div>
                        <div class="right">
                            <span class="text-success">Active</span>
                            <div class="rounded part-icon">
                                <span><i class="fa-light fa-bag-shopping"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6 col-xs-12">
                    <div class="dashboard-top-box rounded-bottom panel-bg">
                        <div class="left">
                            <h3>{{ $buyer->created_at->format('d M Y') }}</h3>
                            <p>Member Since</p>
                            <a href="#">{{ $buyer->created_at->diffForHumans() }}</a>
                        </div>
                        <div class="right">
                            <span class="text-info">Customer</span>
                            <div class="rounded part-icon">
                                <span><i class="fa-light fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="panel">
                <div class="panel-header">
                    <h5>Recent Activity</h5>
                    <a href="#" class="btn btn-sm btn-primary">View All Activity</a>
                </div>
                <div class="panel-body">
                    <div class="user-activity">
                        <ul>
                            <li>
                                <div class="left">
                                    <span class="user-activity-title">Buyer placed a new order</span>
                                    <span class="user-activity-details">Order #1001 - 3 items</span>
                                    <span class="user-activity-date">{{ now()->subHours(2)->format('d M Y') }}</span>
                                </div>
                                <div class="right">
                                    <span class="user-activity-time">2 hours ago</span>
                                </div>
                            </li>
                            <li>
                                <div class="left">
                                    <span class="user-activity-title">Buyer updated profile</span>
                                    <span class="user-activity-details">Changed phone number</span>
                                    <span class="user-activity-date">{{ now()->subDay()->format('d M Y') }}</span>
                                </div>
                                <div class="right">
                                    <span class="user-activity-time">1 day ago</span>
                                </div>
                            </li>
                            <li>
                                <div class="left">
                                    <span class="user-activity-title">Account created</span>
                                    <span class="user-activity-details">Welcome to the platform</span>
                                    <span class="user-activity-date">{{ $buyer->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="right">
                                    <span class="user-activity-time">Joined</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.layouts.footer')