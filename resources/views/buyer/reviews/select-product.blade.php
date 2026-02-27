<x-header 
    title="Select Product to Review - {{ config('app.name', 'eCommerce') }}"
    description="Choose which product you'd like to review"
/>

<x-navbar />

<section class="dashboard mb_100">
    <div class="container">
        <div class="row">
            <x-aside />
            <div class="col-lg-9">
                <div class="dashboard_content mt_100">
                    <div class="mb-4 dashboard_heading">
                        <h3>Select a Product to Review</h3>
                        <p class="text-muted">Choose which product from order #{{ $order->order_number }} you'd like to review</p>
                    </div>

                    <div class="row">
                        @foreach($reviewableProducts as $product)
                        <div class="mb-4 col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        @if($product->images->first())
                                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                             alt="{{ $product->name }}" 
                                             class="rounded me-3"
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                        @else
                                        <div class="rounded bg-light me-3 d-flex align-items-center justify-content-center" 
                                             style="width: 80px; height: 80px;">
                                            <i class="fas fa-image fa-2x text-muted"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <h5 class="mb-1">{{ $product->name }}</h5>
                                            <p class="mb-2 text-muted small">{{ Str::limit($product->description, 100) }}</p>
                                            <a href="{{ route('buyer.reviews.create', ['order' => $order->id, 'product_id' => $product->id]) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-star me-2"></i> Write Review
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<x-footer />