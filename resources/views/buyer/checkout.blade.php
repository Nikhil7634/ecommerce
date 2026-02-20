<x-header
    title="Checkout - {{ config('app.name', 'eCommerce') }}"
    description="Complete your purchase with secure checkout"
    keywords="checkout, payment, shipping, order"
    ogImage="{{ asset('assets/images/banner/home-og.jpg') }}"
/>

<x-navbar />

<!--=========================
    PAGE BANNER START
==========================-->
<section class="page_banner" style="background: url({{ asset('assets/images/page_banner_bg.jpg') }});">
    <div class="page_banner_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page_banner_text wow fadeInUp">
                        <h1>Checkout</h1>
                        <ul>
                            <li><a href="{{ route('home') }}"><i class="fal fa-home-lg"></i> Home</a></li>
                            <li><a href="{{ route('buyer.cart') }}">Cart</a></li>
                            <li class="active">Checkout</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--=========================
    PAGE BANNER END
==========================-->

<!--============================
    CHECKOUT PAGE START
=============================-->
<section class="checkout_page mt_100 mb_100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-10 col-xl-11">
                <!-- Checkout Steps -->
                <div class="mb-4 wow fadeInUp">
                    <div class="checkout-steps">
                        <div class="step active">
                            <span class="step-number">1</span>
                            <span class="step-title">Delivery</span>
                        </div>
                        <div class="step">
                            <span class="step-number">2</span>
                            <span class="step-title">Payment</span>
                        </div>
                        <div class="step">
                            <span class="step-number">3</span>
                            <span class="step-title">Confirmation</span>
                        </div>
                    </div>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show wow fadeInUp" id="session-error">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show wow fadeInUp" id="session-success">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($isBuyNow)
                    <div class="alert alert-info alert-dismissible fade show wow fadeInUp">
                        <i class="fas fa-bolt me-2"></i>
                        <strong>Buy Now Order:</strong> You're purchasing directly.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('buyer.checkout.process') }}" id="checkout-form">
                    @csrf
                    <div class="row">

                        
                        <!-- Left Column - Shipping & Billing -->
                        <div class="col-lg-8 wow fadeInLeft">
                            <!-- Delivery Address -->
                            <div class="mb-4 checkout_card">
                                <div class="checkout_card_header">
                                    <h3><i class="fas fa-map-marker-alt me-2"></i> Delivery Address</h3>
                                    @if($user->address || $user->city || $user->state || $user->zip)
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="use-profile-address">
                                        <i class="fas fa-user me-1"></i> Use Profile Address
                                    </button>
                                    @endif
                                </div>
                                <div class="checkout_card_body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 form-floating">
                                                <input type="text" name="shipping_name" id="shipping_name" class="form-control" 
                                                       value="{{ old('shipping_name', $user->name) }}" 
                                                       placeholder="Full Name" required>
                                                <label for="shipping_name">Full Name <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 form-floating">
                                                <input type="email" name="shipping_email" id="shipping_email" class="form-control" 
                                                       value="{{ old('shipping_email', $user->email) }}" 
                                                       placeholder="Email Address" required>
                                                <label for="shipping_email">Email <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 form-floating">
                                                <input type="tel" name="shipping_phone" id="shipping_phone" class="form-control" 
                                                       value="{{ old('shipping_phone', $user->phone) }}" 
                                                       placeholder="Phone Number" required>
                                                <label for="shipping_phone">Phone <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 form-floating">
                                                <input type="text" name="shipping_address" id="shipping_address" class="form-control" 
                                                       value="{{ old('shipping_address', $user->address) }}" 
                                                       placeholder="Complete Address" required>
                                                <label for="shipping_address">Address <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3 form-floating">
                                                <input type="text" name="shipping_city" id="shipping_city" class="form-control" 
                                                       value="{{ old('shipping_city', $user->city) }}" 
                                                       placeholder="City" required>
                                                <label for="shipping_city">City <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3 form-floating">
                                                <input type="text" name="shipping_state" id="shipping_state" class="form-control" 
                                                       value="{{ old('shipping_state', $user->state) }}" 
                                                       placeholder="State" required>
                                                <label for="shipping_state">State <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3 form-floating">
                                                <input type="text" name="shipping_zip" id="shipping_zip" class="form-control" 
                                                       value="{{ old('shipping_zip', $user->zip) }}" 
                                                       placeholder="ZIP Code" required>
                                                <label for="shipping_zip">ZIP Code <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Order Summary -->
                        <div class="col-lg-4 wow fadeInRight">
                            <div class="sticky-top" style="top: 20px;">
                                <!-- Order Summary -->
                                <div class="mb-4 checkout_card">
                                    <div class="checkout_card_header">
                                        <h3><i class="fas fa-shopping-bag me-2"></i> Order Summary</h3>
                                    </div>
                                    <div class="checkout_card_body">
                                        <!-- Cart Items -->
                                        <div class="order-items">
                                            @foreach($cartItems as $item)
                                            <div class="mb-3 order-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        @if($item->product->images->first())
                                                        <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                                             alt="{{ $item->product->name }}" 
                                                             class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                        @else
                                                        <div class="rounded bg-light d-flex align-items-center justify-content-center" 
                                                             style="width: 60px; height: 60px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                        @if($item->variant_color || $item->variant_size)
                                                        <p class="mb-1 small text-muted">
                                                            @if($item->variant_color)
                                                                Color: {{ $item->variant_color }}
                                                            @endif
                                                            @if($item->variant_size)
                                                                @if($item->variant_color), @endif
                                                                Size: {{ $item->variant_size }}
                                                            @endif
                                                        </p>
                                                        @endif
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="text-muted">Qty: {{ $item->quantity }}</span>
                                                            @php
                                                                $price = $item->price ?: ($item->product->sale_price ?? $item->product->base_price);
                                                            @endphp
                                                            <span class="fw-bold">₹{{ number_format($price * $item->quantity, 2) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>

                                        <!-- Price Breakdown -->
                                        <div class="order-summary">
                                            <div class="mb-2 d-flex justify-content-between">
                                                <span class="text-muted">Subtotal</span>
                                                <span class="fw-bold" id="display-subtotal">₹{{ number_format($subtotal, 2) }}</span>
                                            </div>
                                            <div class="mb-2 d-flex justify-content-between">
                                                <span class="text-muted">Shipping</span>
                                                <span id="shipping-cost">₹{{ number_format($shipping, 2) }}</span>
                                            </div>
                                            <div class="mb-2 d-flex justify-content-between">
                                                <span class="text-muted">Tax ({{ config('cart.tax_rate', 18) }}% GST)</span>
                                                <span id="display-tax">₹{{ number_format($tax, 2) }}</span>
                                            </div>
                                            <hr>
                                            <div class="mb-3 d-flex justify-content-between">
                                                <span class="mb-0 h5">Total</span>
                                                <span class="mb-0 h5 text-primary" id="total-price">
                                                    ₹{{ number_format($total, 2) }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Hidden fields for order data -->
                                        <input type="hidden" name="subtotal" value="{{ $subtotal }}">
                                        <input type="hidden" name="shipping_charge" value="{{ $shipping }}">
                                        <input type="hidden" name="tax_amount" value="{{ $tax }}">
                                        <input type="hidden" name="total_amount" value="{{ $total }}">

                                        <!-- Terms and Conditions -->
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="terms" name="terms" required>
                                                <label class="form-check-label" for="terms">
                                                    I agree to the <a href="#" class="text-decoration-underline" data-bs-toggle="modal" 
                                                    data-bs-target="#termsModal">Terms & Conditions</a>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Place Order Button -->
                                        <button type="submit" class="py-3 common_btn w-100" id="place-order-btn">
                                            <i class="fas fa-lock me-2"></i>
                                            Pay ₹{{ number_format($total, 2) }}
                                        </button>

                                        <!-- Secure Payment Info -->
                                        <div class="mt-3 text-center">
                                            <small class="text-muted">
                                                <i class="fas fa-shield-alt me-1"></i>
                                                100% Secure Payment | SSL Encrypted
                                            </small>
                                            <div class="mt-2">
                                                <img src="{{ asset('assets/images/payment-methods.png') }}" alt="Payment Methods" class="img-fluid" style="max-height: 30px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Help Info -->
                                <div class="checkout_card">
                                    <div class="checkout_card_body">
                                        <div class="help-info">
                                            <div class="mb-3 d-flex align-items-center">
                                                <div class="help-icon">
                                                    <i class="fas fa-headset fa-lg text-primary"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="mb-1">Need Help?</h6>
                                                    <p class="mb-0 text-muted">Call us: 1800-123-4567</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="help-icon">
                                                    <i class="fas fa-undo fa-lg text-success"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="mb-1">Easy Returns</h6>
                                                    <p class="mb-0 text-muted">30-Day Return Policy</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!--============================
    CHECKOUT PAGE END
=============================-->

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terms & Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>By placing an order, you agree to our terms and conditions:</p>
                <ul>
                    <li>All prices are inclusive of GST</li>
                    <li>Delivery times are estimates and may vary</li>
                    <li>Returns must be initiated within 30 days</li>
                    <li>Products must be in original condition for returns</li>
                    <li>Digital products are non-refundable</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div>

<x-footer />

@push('styles')
<style>
    /* ... your existing styles ... */
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Use profile address if available
        const useProfileBtn = document.getElementById('use-profile-address');
        if (useProfileBtn) {
            useProfileBtn.addEventListener('click', function() {
                document.getElementById('shipping_name').value = "{{ $user->name }}";
                document.getElementById('shipping_email').value = "{{ $user->email }}";
                document.getElementById('shipping_phone').value = "{{ $user->phone ?? '' }}";
                document.getElementById('shipping_address').value = "{{ $user->address ?? '' }}";
                document.getElementById('shipping_city').value = "{{ $user->city ?? '' }}";
                document.getElementById('shipping_state').value = "{{ $user->state ?? '' }}";
                document.getElementById('shipping_zip').value = "{{ $user->zip ?? '' }}";
            });
        }

        // Form validation before submission
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            const termsCheckbox = document.getElementById('terms');
            if (!termsCheckbox.checked) {
                e.preventDefault();
                alert('Please agree to the Terms & Conditions');
                termsCheckbox.focus();
            }
        });
    });
</script>
@endpush