@include('seller.layouts.header')
@include('seller.layouts.topnavbar')
@include('seller.layouts.aside')

<main class="main-wrapper">
    <div class="main-content">
        <div class="mb-3 page-breadcrumb d-none d-sm-flex align-items-center">
            <div class="breadcrumb-title pe-3">Subscription</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="p-0 mb-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('seller.subscription') }}">Subscription</a></li>
                        <li class="breadcrumb-item active">Checkout</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="text-center text-white card-header bg-primary">
                        <h4>Checkout - {{ $plan->name }}</h4>
                    </div>
                    <div class="p-5 card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Plan Details</h5>
                                <table class="table table-borderless">
                                    <tr><th>Name</th><td>{{ $plan->name }}</td></tr>
                                    <tr><th>Base Price</th><td>₹{{ number_format($plan->price, 2) }}</td></tr>
                                    <tr><th>Duration</th><td>{{ ucfirst(str_replace('_', ' ', $plan->duration)) }}</td></tr>
                                </table>

                                <h5 class="mt-4">Features</h5>
                                <ul class="list-unstyled">
                                    @php
                                        $features = [];
                                        if ($plan->features) {
                                            if (is_array($plan->features)) {
                                                $features = $plan->features;
                                            } else if (is_string($plan->features)) {
                                                $decoded = json_decode($plan->features, true);
                                                $features = is_array($decoded) ? $decoded : [$plan->features];
                                            }
                                        }
                                    @endphp
                                    @forelse($features as $feature)
                                        <li><i class="bx bx-check text-success me-2"></i> {{ $feature }}</li>
                                    @empty
                                        <li class="text-muted">No features listed</li>
                                    @endforelse
                                </ul>
                            </div>

                            <div class="col-md-6">
                                <h5>Payment Summary</h5>
                                <div class="p-4 rounded bg-light">
                                    <div class="mb-2 d-flex justify-content-between">
                                        <span>Plan Price</span>
                                        <strong>₹{{ number_format($plan->price, 2) }}</strong>
                                    </div>
                                    <div class="mb-2 d-flex justify-content-between">
                                        <span>GST (18%)</span>
                                        <strong>₹{{ number_format($plan->price * 0.18, 2) }}</strong>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <h5>Total Amount</h5>
                                        <h5>₹{{ number_format($plan->price * 1.18, 2) }}</h5>
                                    </div>
                                </div>

                                <div class="mt-5 text-center">
                                    <button id="pay-button" class="px-5 btn btn-primary btn-lg" onclick="initiatePayment()">
                                        Pay ₹{{ number_format($plan->price * 1.18, 2) }} Now
                                    </button>
                                    <div id="loading" class="mt-2" style="display: none;">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2">Processing payment...</p>
                                    </div>
                                    <div id="error-message" class="mt-2 alert alert-danger" style="display: none;"></div>
                                </div>

                                <div class="mt-4">
                                    <div class="alert alert-info">
                                        <i class="bx bx-info-circle"></i>
                                        <small>You'll be redirected to Razorpay's secure payment page to complete your purchase.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('seller.layouts.footer')

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
let currentSubscriptionId = null;

async function initiatePayment() {
    const payButton = document.getElementById('pay-button');
    const loading = document.getElementById('loading');
    const errorDiv = document.getElementById('error-message');
    
    // Disable button and show loading
    payButton.disabled = true;
    loading.style.display = 'block';
    errorDiv.style.display = 'none';

    try {
        // Step 1: Create order and get subscription record
        const response = await fetch("{{ route('seller.subscription.createOrder', $plan->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const data = await response.json();

        if (!data.success) {
            throw new Error(data.message || 'Failed to create payment order');
        }

        currentSubscriptionId = data.subscription_id;

        // Step 2: Initialize Razorpay
        const options = {
            key: data.key,
            amount: data.amount,
            currency: 'INR',
            name: data.site_name,
            description: data.plan_name + ' Subscription',
            order_id: data.order_id,
            handler: async function (response) {
                // Verify payment on server
                await verifyPayment(response);
            },
            prefill: {
                name: data.seller_name,
                email: data.seller_email,
                contact: data.seller_phone
            },
            theme: {
                color: '#0d6efd'
            },
            modal: {
                ondismiss: function() {
                    // User closed the modal
                    payButton.disabled = false;
                    loading.style.display = 'none';
                    window.location.href = "{{ route('seller.subscription') }}";
                }
            }
        };

        const rzp = new Razorpay(options);
        rzp.open();

    } catch (error) {
        errorDiv.textContent = error.message;
        errorDiv.style.display = 'block';
    } finally {
        loading.style.display = 'none';
    }
}

async function verifyPayment(paymentResponse) {
    const loading = document.getElementById('loading');
    const errorDiv = document.getElementById('error-message');
    
    loading.style.display = 'block';

    try {
        const response = await fetch("{{ route('seller.subscription.verifyPayment') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                razorpay_payment_id: paymentResponse.razorpay_payment_id,
                razorpay_order_id: paymentResponse.razorpay_order_id,
                razorpay_signature: paymentResponse.razorpay_signature,
                subscription_id: currentSubscriptionId
            })
        });

        const data = await response.json();

        if (data.success) {
            // Redirect to success page
            window.location.href = data.redirect_url;
        } else {
            throw new Error(data.message || 'Payment verification failed');
        }
    } catch (error) {
        errorDiv.textContent = error.message;
        errorDiv.style.display = 'block';
        loading.style.display = 'none';
        
        // Re-enable payment button
        document.getElementById('pay-button').disabled = false;
    }
}
</script>