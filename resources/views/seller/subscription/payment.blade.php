@include('seller.layouts.header')
@include('seller.layouts.topnavbar')
@include('seller.layouts.aside')

<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="mb-3 page-breadcrumb d-none d-sm-flex align-items-center">
            <div class="breadcrumb-title pe-3">Subscription</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="p-0 mb-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('seller.subscription') }}">Subscription</a></li>
                        <li class="breadcrumb-item active">Payment</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="shadow-sm card">
                    <div class="py-4 text-center text-white card-header bg-primary">
                        <h4 class="mb-0">Complete Your Subscription</h4>
                    </div>
                    <div class="p-5 card-body">
                        <!-- Plan Summary -->
                        <div class="mb-5 text-center">
                            <h3 class="mb-3">{{ $plan->name }}</h3>
                            <div class="gap-2 price-tag d-inline-flex align-items-end">
                                <h1 class="mb-0 text-primary">₹{{ number_format($plan->price, 0) }}</h1>
                                <h5 class="mb-0 text-muted">/ {{ ucfirst(str_replace('_', ' ', $plan->duration)) }}</h5>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Features -->
                        <h5 class="mb-3">Plan Includes:</h5>
                        <ul class="list-unstyled">
                            @php
                                $features = is_array($plan->features) ? $plan->features : json_decode($plan->features, true);
                                $features = is_array($features) ? array_values($features) : [];
                            @endphp
                            @forelse($features as $feature)
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="bx bx-check-circle text-success me-2 fs-4"></i>
                                    <span>{{ $feature }}</span>
                                </li>
                            @empty
                                <li class="text-muted">No features listed</li>
                            @endforelse
                        </ul>

                        <hr class="my-4">

                        <!-- Total Amount -->
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Total Amount (incl. GST)</h5>
                            <h4 class="mb-0 text-primary">₹{{ number_format($plan->price * 1.18, 2) }}</h4>
                        </div>

                        <!-- Razorpay Button -->
                        <div class="text-center">
                            <button id="rzp-button1" class="px-5 py-3 btn btn-primary btn-lg">
                                <i class="bx bx-credit-card me-2"></i> Pay Now with Razorpay
                            </button>
                        </div>

                        <div class="mt-4 text-center">
                            <small class="text-muted">
                                <i class="bx bx-lock-alt me-1"></i> Secure payment powered by Razorpay
                            </small>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('seller.subscription') }}" class="text-muted">
                        <i class="bx bx-arrow-back me-1"></i> Back to Plans
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

@include('seller.layouts.footer')

<!-- Razorpay Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {
    "key": "{{ $keyId }}",
    "subscription_id": "{{ $razorSubscription->id }}",
    "name": "{{ setting('site_name') }}",
    "description": "{{ $plan->name }} Subscription",
    "image": "{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : '' }}",
    "handler": function (response) {
        // On successful payment
        window.location.href = "{{ route('seller.subscription.success') }}?payment_id=" + response.razorpay_payment_id +
            "&subscription_id=" + response.razorpay_subscription_id +
            "&signature=" + response.razorpay_signature;
    },
    "prefill": {
        "name": "{{ Auth::user()->name }}",
        "email": "{{ Auth::user()->email }}",
        "contact": "{{ Auth::user()->phone ?? '' }}"
    },
    "theme": {
        "color": "#0d6efd"
    },
    "modal": {
        "ondismiss": function() {
            window.location.href = "{{ route('seller.subscription') }}";
        }
    }
};

var rzp1 = new Razorpay(options);
document.getElementById('rzp-button1').onclick = function(e) {
    rzp1.open();
    e.preventDefault();
};
</script>