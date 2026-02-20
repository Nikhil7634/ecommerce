@extends('layouts.app')

@section('title', 'Order Confirmed - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="border-0 shadow-lg card">
                <div class="p-5 text-center card-body">
                    <div class="mb-4">
                        <div class="p-4 mb-3 bg-success rounded-circle d-inline-flex align-items-center justify-content-center">
                            <i class="text-white fas fa-check fa-3x"></i>
                        </div>
                        <h1 class="display-5 fw-bold text-success">Order Confirmed!</h1>
                        <p class="mb-4 lead">Thank you for your purchase. Your order has been received.</p>
                    </div>

                    <div class="mb-4 alert alert-info">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h5 class="mb-1">Order Number: <strong>{{ $order->order_number }}</strong></h5>
                                <p class="mb-0">We've sent a confirmation email to {{ $order->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 row">
                        <div class="mb-3 col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-muted">Order Date</h6>
                                    <p class="card-text h5">{{ $order->created_at->format('F d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-muted">Total Amount</h6>
                                    <p class="card-text h5 text-success">₹{{ number_format($order->total, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="gap-2 d-grid d-md-flex justify-content-md-center">
                        <a href="{{ route('buyer.orders') }}" class="px-5 btn btn-primary btn-lg">
                            <i class="fas fa-list me-2"></i> View Orders
                        </a>
                        <a href="{{ route('shop') }}" class="px-5 btn btn-outline-primary btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i> Continue Shopping
                        </a>
                    </div>

                    <div class="mt-5">
                        <h5 class="mb-3">Need Help?</h5>
                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <div class="border-0 card h-100">
                                    <div class="card-body">
                                        <i class="mb-3 fas fa-headset fa-2x text-primary"></i>
                                        <h6>Customer Support</h6>
                                        <p class="text-muted small">24/7 available for your queries</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 col-md-4">
                                <div class="border-0 card h-100">
                                    <div class="card-body">
                                        <i class="mb-3 fas fa-shipping-fast fa-2x text-primary"></i>
                                        <h6>Track Order</h6>
                                        <p class="text-muted small">Real-time tracking available soon</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 col-md-4">
                                <div class="border-0 card h-100">
                                    <div class="card-body">
                                        <i class="mb-3 fas fa-file-invoice fa-2x text-primary"></i>
                                        <h6>Invoice</h6>
                                        <p class="text-muted small">Download invoice from order details</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection