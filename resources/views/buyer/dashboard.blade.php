<x-header 
    title="Buyer Dashboard - {{ config('app.name', 'eCommerce') }}"
    description="Shop the latest electronics, fashion, and home essentials at Valtara with fast delivery and best prices."
    keywords="Valtara, online store, eCommerce, electronics, clothing, deals"
    ogImage="{{ asset('assets/images/banner/home-og.jpg') }}"
>
</x-header>

<x-navbar />

<!--=========================
    PAGE BANNER START
==========================-->
<section class="page_banner" style="background: url('{{ asset('assets/images/page_banner_bg.jpg') }}');">
    <div class="page_banner_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page_banner_text wow fadeInUp">
                        <h1>My Account</h1>
                        <ul>
                            <li><a href="{{ route('home') }}"><i class="fal fa-home-lg"></i> Home</a></li>
                            <li><a href="#">Overview</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--=========================
    PAGE BANNER START
==========================-->

<!--============================
    DSHBOARD START
=============================-->
<section class="dashboard mb_100">
    <div class="container">
        <div class="row">
            <x-aside />
            <div class="col-lg-9">
                <div class="dashboard_content mt_100">
                    <div class="row">
                        <!-- Total Orders -->
                        <div class="col-xl-4 col-md-6 wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                            <div class="dashboard_overview_item">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"></path>
                                    </svg>
                                </div>
                                <h3>{{ $totalOrders ?? 0 }} <span>Total Order</span></h3>
                            </div>
                        </div>
                        
                        <!-- Completed Orders -->
                        <div class="col-xl-4 col-md-6 wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                            <div class="dashboard_overview_item blue">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z"></path>
                                    </svg>
                                </div>
                                <h3>{{ $completedOrders ?? 0 }} <span>Completed Order</span></h3>
                            </div>
                        </div>
                        
                        <!-- Pending Orders -->
                        <div class="col-xl-4 col-md-6 wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                            <div class="dashboard_overview_item orange">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"></path>
                                    </svg>
                                </div>
                                <h3>{{ $pendingOrders ?? 0 }} <span>pending order</span></h3>
                            </div>
                        </div>
                        
                        <!-- Cancelled Orders -->
                        <div class="col-xl-4 col-md-6 wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                            <div class="dashboard_overview_item red">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                                <h3>{{ $cancelledOrders ?? 0 }} <span>Canceled Order</span></h3>
                            </div>
                        </div>
                        
                        <!-- Total Wishlist -->
                        <div class="col-xl-4 col-md-6 wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                            <div class="dashboard_overview_item purple">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"></path>
                                    </svg>
                                </div>
                                <h3>{{ $totalWishlist ?? 0 }} <span>Total Wishlist</span></h3>
                            </div>
                        </div>
                        
                        <!-- Total Reviews -->
                        <div class="col-xl-4 col-md-6 wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                            <div class="dashboard_overview_item olive">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"></path>
                                    </svg>
                                </div>
                                <h3>{{ $totalReviews ?? 0 }} <span>Total Reviews</span></h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt_25">
                        <!-- Recent Orders Table -->
                        <div class="col-xl-7 wow fadeInLeft" style="visibility: visible; animation-name: fadeInLeft;">
                            <div class="dashboard_recent_order">
                                <h3>Your Recent Orders</h3>
                                <div class="dashboard_order_table">
                                    <div class="table-responsive">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($recentOrders) && $recentOrders->count() > 0)
                                                    @foreach($recentOrders as $order)
                                                    <tr>
                                                        <td>#{{ $order->order_number }}</td>
                                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                        <td>
                                                            @php
                                                                $statusClass = '';
                                                                $statusText = ucfirst($order->status);
                                                                
                                                                if($order->status == 'confirmed' || $order->status == 'delivered') {
                                                                    $statusClass = 'complete';
                                                                    $statusText = 'Completed';
                                                                } elseif($order->status == 'pending' || $order->status == 'processing') {
                                                                    $statusClass = 'active';
                                                                    $statusText = 'Active';
                                                                } elseif($order->status == 'cancelled' || $order->status == 'payment_failed') {
                                                                    $statusClass = 'cancel';
                                                                    $statusText = 'Cancelled';
                                                                } elseif($order->status == 'shipped') {
                                                                    $statusClass = 'active';
                                                                    $statusText = 'Shipped';
                                                                }
                                                            @endphp
                                                            <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                                        </td>
                                                        <td>₹{{ number_format($order->total_amount, 2) }}</td>
                                                        <td>
                                                            @if(Route::has('buyer.order.show'))
                                                            <a href="{{ route('buyer.order.show', $order->id) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                                                                </svg>
                                                                View
                                                            </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="5" class="text-center">No orders found</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Reviews -->
                        <div class="col-xl-5 wow fadeInRight" style="visibility: visible; animation-name: fadeInRight;">
                            <div class="dashboard_recent_review">
                                <h3>Your Recent Reviews</h3>
                                <div class="single_review_list_area">
                                    @if(isset($recentReviews) && $recentReviews->count() > 0)
                                        @foreach($recentReviews as $review)
                                        <div class="single_review">
                                            <div class="text">
                                                <h5>
                                                    <a class="title" href="{{ $review->product ? route('product.show', $review->product->slug) : '#' }}">
                                                        {{ $review->product ? $review->product->name : 'Product' }}
                                                    </a>
                                                    <span>
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $review->rating)
                                                                <i class="fas fa-star" aria-hidden="true"></i>
                                                            @else
                                                                <i class="far fa-star" aria-hidden="true"></i>
                                                            @endif
                                                        @endfor
                                                    </span>
                                                </h5>
                                                <p class="date">{{ $review->created_at->format('d F Y') }}</p>
                                                <p class="description">{{ Str::limit($review->comment, 100) }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="single_review">
                                            <div class="text">
                                                <h5>No reviews yet</h5>
                                                <p class="description">You haven't written any reviews yet.</p>
                                            </div>
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
</section>
<!--============================
    DSHBOARD END
=============================-->

<x-footer />