@include('seller.layouts.header')

@include('seller.layouts.topnavbar')

@include('seller.layouts.aside')

<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="mb-3 page-breadcrumb d-none d-sm-flex align-items-center">
            <div class="breadcrumb-title pe-3">Dashboard</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="p-0 mb-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">eCommerce</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary">Settings</button>
                    <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                        data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> 
                        <a class="dropdown-item" href="javascript:;">Action</a>
                        <a class="dropdown-item" href="javascript:;">Another action</a>
                        <a class="dropdown-item" href="javascript:;">Something else here</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="javascript:;">Separated link</a>
                    </div>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <!-- Congratulations Card -->
            <div class="col-12 col-lg-4 col-xxl-4 d-flex">
                <div class="card rounded-4 w-100">
                    <div class="card-body">
                        <div class="">
                            <div class="gap-2 mb-2 d-flex align-items-center">
                                <h5 class="mb-0">Congratulations <span class="fw-600">{{ explode(' ', $seller->name)[0] }}</span></h5>
                                <img src="{{asset('seller-assets/assets/images/apps/party-popper.png')}}" width="24" height="24" alt="">
                            </div>
                            <p class="mb-4">You are the best seller of this month</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="">
                                    <h3 class="mb-0 text-indigo">₹{{ number_format($currentMonthSales, 1) }}K</h3>
                                    <p class="mb-3">{{ $salesPercentage }}% of sales target</p>
                                    <button class="px-4 border-0 btn btn-grd btn-grd-primary rounded-5">View Details</button>
                                </div>
                                <img src="{{asset('seller-assets/assets/images/apps/gift-box-3.png')}}" width="100" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Total Orders Card -->
            <div class="col-12 col-lg-4 col-xxl-2 d-flex">
                <div class="card rounded-4 w-100">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <div class="wh-42 d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary">
                                <span class="material-icons-outlined fs-5">shopping_cart</span>
                            </div>
                            <div>
                                <span class="text-success d-flex align-items-center">
                                    @php
                                        $orderChange = 24; // Calculate from previous month
                                    @endphp
                                    +{{ $orderChange }}%<i class="material-icons-outlined">expand_less</i>
                                </span>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ number_format($totalOrders) }}</h4>
                            <p class="mb-3">Total Orders</p>
                            <div id="chart1"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Total Sales Card -->
            <div class="col-12 col-lg-4 col-xxl-2 d-flex">
                <div class="card rounded-4 w-100">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <div class="wh-42 d-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 text-success">
                                <span class="material-icons-outlined fs-5">attach_money</span>
                            </div>
                            <div>
                                <span class="text-success d-flex align-items-center">
                                    @php
                                        $salesChange = 14; // Calculate from previous month
                                    @endphp
                                    +{{ $salesChange }}%<i class="material-icons-outlined">expand_less</i>
                                </span>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-0">₹{{ number_format($totalSales / 1000, 1) }}k</h4>
                            <p class="mb-3">Total Sales</p>
                            <div id="chart2"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Total Visits Card -->
            <div class="col-12 col-lg-6 col-xxl-2 d-flex">
                <div class="card rounded-4 w-100">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <div class="wh-42 d-flex align-items-center justify-content-center rounded-circle bg-info bg-opacity-10 text-info">
                                <span class="material-icons-outlined fs-5">visibility</span>
                            </div>
                            <div>
                                <span class="text-danger d-flex align-items-center">
                                    @php
                                        $visitsChange = 35; // Calculate from previous month
                                    @endphp
                                    -{{ $visitsChange }}%<i class="material-icons-outlined">expand_less</i>
                                </span>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ number_format($totalVisits / 1000, 1) }}K</h4>
                            <p class="mb-3">Total Visits</p>
                            <div id="chart3"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bounce Rate Card -->
            <div class="col-12 col-lg-6 col-xxl-2 d-flex">
                <div class="card rounded-4 w-100">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <div class="wh-42 d-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10 text-warning">
                                <span class="material-icons-outlined fs-5">leaderboard</span>
                            </div>
                            <div>
                                <span class="text-success d-flex align-items-center">
                                    +18%<i class="material-icons-outlined">expand_less</i>
                                </span>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $bounceRate }}%</h4>
                            <p class="mb-3">Bounce Rate</p>
                            <div id="chart4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end row-->

        <div class="row">
            <!-- Order Status Pie Chart -->
            <div class="col-12 col-xl-4">
                <div class="card w-100 rounded-4">
                    <div class="card-body">
                        <div class="gap-3 d-flex flex-column">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="">
                                    <h5 class="mb-0">Order Status</h5>
                                </div>
                                <div class="dropdown">
                                    <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                        data-bs-toggle="dropdown">
                                        <span class="material-icons-outlined fs-5">more_vert</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                        <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                        <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="position-relative">
                                <div class="piechart-legend">
                                    <h2 class="mb-1">{{ $salesPercentageTotal }}%</h2>
                                    <h6 class="mb-0">Total Sales</h6>
                                </div>
                                <div id="chart6"></div>
                            </div>
                            <div class="gap-3 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="gap-2 mb-0 d-flex align-items-center w-25">
                                        <span class="material-icons-outlined fs-6 text-primary">fiber_manual_record</span>Sales
                                    </p>
                                    <div class="">
                                        <p class="mb-0">{{ $salesPercentageTotal }}%</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="gap-2 mb-0 d-flex align-items-center w-25">
                                        <span class="material-icons-outlined fs-6 text-danger">fiber_manual_record</span>Products
                                    </p>
                                    <div class="">
                                        <p class="mb-0">{{ $totalProducts }}</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="gap-2 mb-0 d-flex align-items-center w-25">
                                        <span class="material-icons-outlined fs-6 text-success">fiber_manual_record</span>Income
                                    </p>
                                    <div class="">
                                        <p class="mb-0">₹{{ number_format($totalProfit / 1000, 1) }}K</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sales & Views Chart -->
            <div class="col-12 col-xl-8">
                <div class="card w-100 rounded-4">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-start justify-content-between">
                            <div class="">
                                <h5 class="mb-0">Sales & Views</h5>
                            </div>
                            <div class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    <span class="material-icons-outlined fs-5">more_vert</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                                </ul>
                            </div>
                        </div>
                        <div id="chart5"></div>
                        <div class="gap-3 p-3 mt-3 border d-flex flex-column flex-lg-row align-items-start justify-content-around rounded-4">
                            <div class="gap-4 d-flex align-items-center">
                                <div class="">
                                    <p class="mb-0 data-attributes">
                                        <span data-peity='{ "fill": ["#2196f3", "rgb(255 255 255 / 12%)"], "innerRadius": 32, "radius": 40 }'>5/7</span>
                                    </p>
                                </div>
                                <div class="">
                                    <p class="mb-1 fs-6 fw-bold">Weekly</p>
                                    <h2 class="mb-0">{{ number_format(array_sum($salesData)) }}</h2>
                                    <p class="mb-0"><span class="text-success me-2 fw-medium">16.5%</span><span>₹{{ number_format(array_sum($salesData) / 7, 2) }}</span></p>
                                </div>
                            </div>
                            <div class="vr"></div>
                            <div class="gap-4 d-flex align-items-center">
                                <div class="">
                                    <p class="mb-0 data-attributes">
                                        <span data-peity='{ "fill": ["#ffd200", "rgb(255 255 255 / 12%)"], "innerRadius": 32, "radius": 40 }'>5/7</span>
                                    </p>
                                </div>
                                <div class="">
                                    <p class="mb-1 fs-6 fw-bold">Monthly</p>
                                    <h2 class="mb-0">₹{{ number_format($currentMonthSales) }}</h2>
                                    <p class="mb-0"><span class="text-success me-2 fw-medium">{{ $salesPercentage }}%</span><span>of target</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end row-->

        <div class="row">
            <!-- Social Revenue -->
            <div class="col-12 col-lg-6 col-xxl-4 d-flex">
                <div class="card w-100 rounded-4">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-start justify-content-between">
                            <div class="">
                                <h5 class="mb-0">Social Revenue</h5>
                            </div>
                            <div class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    <span class="material-icons-outlined fs-5">more_vert</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="gap-3 d-flex align-items-center">
                                <h3 class="mb-0">{{ number_format($socialRevenue['facebook']['amount'] + $socialRevenue['twitter']['amount'] + $socialRevenue['instagram']['amount']) }}</h3>
                                <p class="gap-3 mb-0 text-success">27%<span class="material-icons-outlined fs-6">arrow_upward</span></p>
                            </div>
                            <p class="mb-0 font-13">Last 1 Year Income</p>
                        </div>
                        <div class="table-responsive">
                            <div class="gap-4 d-flex flex-column">
                                <div class="gap-3 d-flex align-items-center">
                                    <div class="gap-3 social-icon d-flex align-items-center flex-grow-1">
                                        <img src="{{asset('seller-assets/assets/images/apps/17.png')}}" width="40" alt="">
                                        <div>
                                            <h6 class="mb-0">Facebook</h6>
                                            <p class="mb-0">Social Media</p>
                                        </div>
                                    </div>
                                    <h5 class="mb-0">₹{{ number_format($socialRevenue['facebook']['amount']) }}</h5>
                                    <div class="card-lable bg-{{ $socialRevenue['facebook']['trend'] == 'up' ? 'success' : 'danger' }} text-{{ $socialRevenue['facebook']['trend'] == 'up' ? 'success' : 'danger' }} bg-opacity-10">
                                        <p class="mb-0 text-{{ $socialRevenue['facebook']['trend'] == 'up' ? 'success' : 'danger' }}">
                                            {{ $socialRevenue['facebook']['trend'] == 'up' ? '+' : '-' }}{{ $socialRevenue['facebook']['change'] }}%
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="gap-3 d-flex align-items-center">
                                    <div class="gap-3 social-icon d-flex align-items-center flex-grow-1">
                                        <img src="{{asset('seller-assets/assets/images/apps/twitter-circle.png')}}" width="40" alt="">
                                        <div>
                                            <h6 class="mb-0">Twitter</h6>
                                            <p class="mb-0">Social Media</p>
                                        </div>
                                    </div>
                                    <h5 class="mb-0">₹{{ number_format($socialRevenue['twitter']['amount']) }}</h5>
                                    <div class="card-lable bg-{{ $socialRevenue['twitter']['trend'] == 'up' ? 'success' : 'danger' }} text-{{ $socialRevenue['twitter']['trend'] == 'up' ? 'success' : 'danger' }} bg-opacity-10">
                                        <p class="mb-0 text-{{ $socialRevenue['twitter']['trend'] == 'up' ? 'success' : 'danger' }}">
                                            {{ $socialRevenue['twitter']['trend'] == 'up' ? '+' : '-' }}{{ $socialRevenue['twitter']['change'] }}%
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="gap-3 d-flex align-items-center">
                                    <div class="gap-3 social-icon d-flex align-items-center flex-grow-1">
                                        <img src="{{asset('seller-assets/assets/images/apps/03.png')}}" width="40" alt="">
                                        <div>
                                            <h6 class="mb-0">Tik Tok</h6>
                                            <p class="mb-0">Entertainment</p>
                                        </div>
                                    </div>
                                    <h5 class="mb-0">₹{{ number_format($socialRevenue['tiktok']['amount']) }}</h5>
                                    <div class="card-lable bg-{{ $socialRevenue['tiktok']['trend'] == 'up' ? 'success' : 'danger' }} text-{{ $socialRevenue['tiktok']['trend'] == 'up' ? 'success' : 'danger' }} bg-opacity-10">
                                        <p class="mb-0 text-{{ $socialRevenue['tiktok']['trend'] == 'up' ? 'success' : 'danger' }}">
                                            {{ $socialRevenue['tiktok']['trend'] == 'up' ? '+' : '-' }}{{ $socialRevenue['tiktok']['change'] }}%
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="gap-3 d-flex align-items-center">
                                    <div class="gap-3 social-icon d-flex align-items-center flex-grow-1">
                                        <img src="{{asset('seller-assets/assets/images/apps/19.png')}}" width="40" alt="">
                                        <div>
                                            <h6 class="mb-0">Instagram</h6>
                                            <p class="mb-0">Social Media</p>
                                        </div>
                                    </div>
                                    <h5 class="mb-0">₹{{ number_format($socialRevenue['instagram']['amount']) }}</h5>
                                    <div class="card-lable bg-{{ $socialRevenue['instagram']['trend'] == 'up' ? 'success' : 'danger' }} text-{{ $socialRevenue['instagram']['trend'] == 'up' ? 'success' : 'danger' }} bg-opacity-10">
                                        <p class="mb-0 text-{{ $socialRevenue['instagram']['trend'] == 'up' ? 'success' : 'danger' }}">
                                            {{ $socialRevenue['instagram']['trend'] == 'up' ? '+' : '-' }}{{ $socialRevenue['instagram']['change'] }}%
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="gap-3 d-flex align-items-center">
                                    <div class="gap-3 social-icon d-flex align-items-center flex-grow-1">
                                        <img src="{{asset('seller-assets/assets/images/apps/20.png')}}" width="40" alt="">
                                        <div>
                                            <h6 class="mb-0">Snapchat</h6>
                                            <p class="mb-0">Conversation</p>
                                        </div>
                                    </div>
                                    <h5 class="mb-0">₹{{ number_format($socialRevenue['snapchat']['amount']) }}</h5>
                                    <div class="card-lable bg-{{ $socialRevenue['snapchat']['trend'] == 'up' ? 'success' : 'danger' }} text-{{ $socialRevenue['snapchat']['trend'] == 'up' ? 'success' : 'danger' }} bg-opacity-10">
                                        <p class="mb-0 text-{{ $socialRevenue['snapchat']['trend'] == 'up' ? 'success' : 'danger' }}">
                                            {{ $socialRevenue['snapchat']['trend'] == 'up' ? '+' : '-' }}{{ $socialRevenue['snapchat']['change'] }}%
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Popular Products -->
            <div class="col-12 col-lg-6 col-xxl-4 d-flex">
                <div class="card w-100 rounded-4">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-start justify-content-between">
                            <div class="">
                                <h5 class="mb-0">Popular Products</h5>
                            </div>
                            <div class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    <span class="material-icons-outlined fs-5">more_vert</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="gap-4 d-flex flex-column">
                            @forelse($popularProducts as $product)
                            <div class="gap-3 d-flex align-items-center">
                                @if($product->images->first())
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" width="55" class="rounded-circle" alt="{{ $product->name }}">
                                @else
                                <img src="{{asset('seller-assets/assets/images/top-products/01.png')}}" width="55" class="rounded-circle" alt="">
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $product->name }}</h6>
                                    <p class="mb-0">Sale: {{ $product->total_sales ?? 0 }}</p>
                                </div>
                                <div class="text-center">
                                    <h6 class="mb-1">₹{{ number_format($product->sale_price ?? $product->base_price, 0) }}</h6>
                                    @php
                                        $priceChange = rand(-25, 30); // Placeholder - calculate actual change
                                    @endphp
                                    <p class="mb-0 text-{{ $priceChange > 0 ? 'success' : 'danger' }} font-13">
                                        {{ $priceChange > 0 ? '+' : '' }}{{ $priceChange }}%
                                    </p>
                                </div>
                            </div>
                            @empty
                            <div class="py-4 text-center">
                                <p class="mb-0 text-muted">No products found</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Top Vendors -->
            <div class="col-12 col-lg-12 col-xxl-4 d-flex">
                <div class="card w-100 rounded-4">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-start justify-content-between">
                            <div class="">
                                <h5 class="mb-0">Top Vendors</h5>
                            </div>
                            <div class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    <span class="material-icons-outlined fs-5">more_vert</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="gap-4 d-flex flex-column">
                            @forelse($topVendors as $vendor)
                            <div class="gap-3 d-flex align-items-center">
                                @if($vendor->avatar)
                                <img src="{{ asset('storage/' . $vendor->avatar) }}" width="55" class="rounded-circle" alt="{{ $vendor->name }}">
                                @else
                                <img src="{{asset('seller-assets/assets/images/avatars/0'. ($loop->iteration + 1) .'.png')}}" width="55" class="rounded-circle" alt="">
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ explode(' ', $vendor->name)[0] }}</h6>
                                    <p class="mb-0">Sale: {{ $vendor->total_sales ?? 879 }}</p>
                                </div>
                                <div class="ratings">
                                    @php
                                        $rating = rand(3, 5); // Calculate actual vendor rating
                                    @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating)
                                            <span class="material-icons-outlined text-warning fs-5">star</span>
                                        @else
                                            <span class="material-icons-outlined fs-5">star</span>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            @empty
                            <div class="py-4 text-center">
                                <p class="mb-0 text-muted">No other vendors found</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end row-->

        <div class="row">
            <!-- Recent Transactions -->
            <div class="col-12 col-xxl-6 d-flex">
                <div class="card rounded-4 w-100">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-start justify-content-between">
                            <div class="">
                                <h5 class="mb-0">Recent Transactions</h5>
                            </div>
                            <div class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    <span class="material-icons-outlined fs-5">more_vert</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentTransactions as $transaction)
                                    <tr>
                                        <td>
                                            <div class="">
                                                <h6 class="mb-0">{{ $transaction->created_at->format('d M, Y') }}</h6>
                                                <p class="mb-0">{{ $transaction->created_at->format('h:i A') }}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex-row gap-3 d-flex align-items-center">
                                                <div class="">
                                                    @if($transaction->product && $transaction->product->images->first())
                                                    <img src="{{ asset('storage/' . $transaction->product->images->first()->image_path) }}" width="35" alt="">
                                                    @else
                                                    <img src="{{asset('seller-assets/assets/images/apps/paypal.png')}}" width="35" alt="">
                                                    @endif
                                                </div>
                                                <div class="">
                                                    <h6 class="mb-0">{{ $transaction->product_name ?? 'Product' }}</h6>
                                                    <p class="mb-0">Qty: {{ $transaction->quantity }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $status = $transaction->order->status ?? 'pending';
                                                $statusClass = $status == 'delivered' || $status == 'confirmed' ? 'success' : 
                                                              ($status == 'pending' || $status == 'processing' ? 'warning' : 'danger');
                                                $statusText = ucfirst($status);
                                            @endphp
                                            <div class="card-lable bg-{{ $statusClass }} text-{{ $statusClass }} bg-opacity-10">
                                                <p class="mb-0 text-{{ $statusClass }}">{{ $statusText }}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <h5 class="mb-0">₹{{ number_format($transaction->total_price, 2) }}</h5>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="py-4 text-center">No transactions found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Messages Card -->
            <div class="col-12 col-lg-6 col-xxl-3 d-flex flex-column">
                <div class="card rounded-4 w-100">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-1">Messages</p>
                                <h3 class="mb-0">{{ number_format($messagesCount) }}</h3>
                            </div>
                            <div class="wh-42 d-flex align-items-center justify-content-center rounded-circle bg-grd-danger">
                                <span class="text-white material-icons-outlined fs-5">chat</span>
                            </div>
                        </div>
                        <div class="mb-0 progress" style="height:6px;">
                            <div class="progress-bar bg-grd-danger" role="progressbar" style="width: {{ min($messagesCount / 10, 100) }}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="gap-2 mt-3 d-flex align-items-center">
                            <div class="card-lable bg-success bg-opacity-10">
                                <p class="mb-0 text-success">+{{ $messagesChange }}%</p>
                            </div>
                            <p class="mb-0 font-13">from last month</p>
                        </div>
                    </div>
                </div>

                <!-- Total Profit Card -->
                <div class="card rounded-4 w-100">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-start justify-content-between">
                            <div class="">
                                <h5 class="mb-0">₹{{ number_format($totalProfit / 1000, 1) }}K</h5>
                                <p class="mb-0">Total Profit</p>
                            </div>
                            <div class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    <span class="material-icons-outlined fs-5">more_vert</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="">
                            <div id="chart9"></div>
                        </div>
                        <div class="mt-3 text-center">
                            <p class="mb-0"><span class="text-success me-1">+{{ $profitChange }}%</span> from last month</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Monthly Budget Card -->
            <div class="col-12 col-lg-6 col-xxl-3 d-flex">
                <div class="card rounded-4 w-100">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-start justify-content-between">
                            <div class="">
                                <h5 class="mb-0">Monthly Budget</h5>
                            </div>
                            <div class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    <span class="material-icons-outlined fs-5">more_vert</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="mb-2 chart-container">
                            <div id="chart8"></div>
                        </div>
                        <div class="text-center">
                            <h3>₹{{ number_format($monthlyBudget) }}</h3>
                            <p class="mb-3">Monthly budget allocated for marketing & operations.</p>
                            <button class="px-4 btn btn-grd btn-grd-info rounded-5">Increase Budget</button>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end row-->

    </div>
</main>

@include('seller.layouts.footer')