<!-- resources/views/buyer/checkout-cancel.blade.php -->
<x-header
    title="Payment Cancelled - {{ config('app.name', 'eCommerce') }}"
    description="Your payment was cancelled"
/>

<x-navbar />

<section class="page_banner" style="background: url({{ asset('assets/images/page_banner_bg.jpg') }});">
    <div class="page_banner_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page_banner_text wow fadeInUp">
                        <h1>Payment Cancelled</h1>
                        <ul>
                            <li><a href="{{ route('home') }}"><i class="fal fa-home-lg"></i> Home</a></li>
                            <li><a href="{{ route('buyer.cart') }}">Cart</a></li>
                            <li class="active">Payment Cancelled</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="checkout_page mt_100 mb_100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center checkout_card">
                    <div class="checkout_card_body">
                        <div class="mb-4">
                            <i class="mb-3 fas fa-times-circle fa-4x text-danger"></i>
                            <h3 class="mb-3">Payment Cancelled</h3>
                            <p class="text-muted">
                                Your payment was cancelled. No amount has been deducted from your account.
                            </p>
                        </div>
                        
                        @if(session('error'))
                        <div class="mb-4 alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                        </div>
                        @endif
                        
                        <div class="gap-3 d-grid">
                            <a href="{{ route('buyer.checkout') }}" class="py-3 btn btn-primary">
                                <i class="fas fa-credit-card me-2"></i> Try Payment Again
                            </a>
                            <a href="{{ route('buyer.cart') }}" class="py-3 btn btn-outline-secondary">
                                <i class="fas fa-shopping-cart me-2"></i> Back to Cart
                            </a>
                            <a href="{{ route('home') }}" class="py-3 btn btn-outline-primary">
                                <i class="fas fa-home me-2"></i> Continue Shopping
                            </a>
                        </div>
                        
                        <div class="pt-4 mt-4 border-top">
                            <small class="text-muted">
                                Need help? <a href="{{ route('contact') }}">Contact Support</a> or call 1800-123-4567
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<x-footer />