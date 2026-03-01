<!--start header-->
@include('admin.layouts.header')
<!--end top header-->

@include('admin.layouts.navbar')

<!--start sidebar-->
@include('admin.layouts.aside')

<!-- main content start -->
<div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
        <h2>Search Results for "{{ $query }}"</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Search</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Orders Results -->
        <div class="mb-4 col-md-6">
            <div class="panel">
                <div class="panel-header">
                    <h5>Orders ({{ $results['orders']->count() }})</h5>
                </div>
                <div class="panel-body">
                    @if($results['orders']->count() > 0)
                        <div class="list-group">
                            @foreach($results['orders'] as $order)
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between">
                                    <strong>#{{ $order->order_number }}</strong>
                                    <small>{{ $order->created_at->format('d M Y') }}</small>
                                </div>
                                <div>{{ $order->shipping_name }}</div>
                                <small class="text-muted">₹{{ number_format($order->total_amount, 2) }}</small>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <p class="py-3 text-center text-muted">No orders found</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Users Results -->
        <div class="mb-4 col-md-6">
            <div class="panel">
                <div class="panel-header">
                    <h5>Users ({{ $results['users']->count() }})</h5>
                </div>
                <div class="panel-body">
                    @if($results['users']->count() > 0)
                        <div class="list-group">
                            @foreach($results['users'] as $user)
                            <a href="{{ $user->role == 'seller' ? route('admin.sellers.show', $user->id) : route('admin.buyers.show', $user->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->avatar_url }}" alt="" class="rounded-circle me-2" width="30">
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $user->email }} | {{ ucfirst($user->role) }}</small>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <p class="py-3 text-center text-muted">No users found</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Products Results -->
        <div class="mb-4 col-md-6">
            <div class="panel">
                <div class="panel-header">
                    <h5>Products ({{ $results['products']->count() }})</h5>
                </div>
                <div class="panel-body">
                    @if($results['products']->count() > 0)
                        <div class="list-group">
                            @foreach($results['products'] as $product)
                            <a href="{{ route('admin.products.show', $product->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $product->name }}</strong>
                                    <small>₹{{ number_format($product->base_price, 2) }}</small>
                                </div>
                                <small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }} | Stock: {{ $product->stock }}</small>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <p class="py-3 text-center text-muted">No products found</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Withdrawals Results -->
        <div class="mb-4 col-md-6">
            <div class="panel">
                <div class="panel-header">
                    <h5>Withdrawals ({{ $results['withdrawals']->count() }})</h5>
                </div>
                <div class="panel-body">
                    @if($results['withdrawals']->count() > 0)
                        <div class="list-group">
                            @foreach($results['withdrawals'] as $withdrawal)
                            <a href="{{ route('admin.payments.withdrawals.show', $withdrawal->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $withdrawal->withdrawal_number }}</strong>
                                    <small>₹{{ number_format($withdrawal->amount, 2) }}</small>
                                </div>
                                <small class="text-muted">{{ ucfirst($withdrawal->status) }} | {{ $withdrawal->created_at->format('d M Y') }}</small>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <p class="py-3 text-center text-muted">No withdrawals found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($results['orders']->count() == 0 && $results['users']->count() == 0 && $results['products']->count() == 0 && $results['withdrawals']->count() == 0)
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="py-5 text-center panel-body">
                    <i class="mb-3 fa-light fa-magnifying-glass fa-4x text-muted"></i>
                    <h4>No results found for "{{ $query }}"</h4>
                    <p class="text-muted">Try different keywords or check your spelling</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<!-- main content end -->

@include('admin.layouts.footer')