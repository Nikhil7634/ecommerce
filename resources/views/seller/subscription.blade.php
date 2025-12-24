@include('seller.layouts.header')
@include('seller.layouts.topnavbar')
@include('seller.layouts.aside')

<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="mb-3 page-breadcrumb d-none d-sm-flex align-items-center">
            <div class="breadcrumb-title pe-3">Components</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="p-0 mb-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pricing Table</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Current Subscription Status -->
                        @if($currentSubscription)
                            @if($currentSubscription->isActive())
                                <div class="alert bg-success bg-opacity-10">
                                    <h5>Current Plan: <strong>{{ $currentSubscription->plan->name }}</strong></h5>
                                    <p class="mb-0">
                                        Expires on: <strong>{{ $currentSubscription->current_period_end->format('d M Y') }}</strong>
                                        <span class="badge bg-success ms-2">Active</span>
                                    </p>
                                </div>
                            @else
                                <div class="alert bg-danger bg-opacity-10">
                                    <h5>Your subscription has expired</h5>
                                    <p>Renew or upgrade to continue selling without restrictions.</p>
                                </div>
                            @endif
                        @else
                            <div class="alert bg-primary bg-opacity-10">
                                <h5>No active subscription</h5>
                                <p>Choose a plan below to unlock premium features.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse($plans as $plan)
                <div class="col-12 col-xl-4">
                    <div class="border-4 card border-top {{ $loop->index == 0 ? 'border-primary' : ($loop->index == 1 ? 'border-success' : 'border-danger') }}">
                        <div class="p-4 card-body">
                            <!-- Plan Badge -->
                            <div style="width: max-content" class="px-2 py-1 bg-opacity-10  text-center rounded fw-medium {{ $loop->index == 0 ? 'bg-primary text-primary' : ($loop->index == 1 ? 'bg-success text-success' : 'bg-danger text-danger') }} text-uppercase">
                                {{ $plan->name }}
                            </div>

                            <div class="my-4 text-center">
                                <h3 class="mb-2">{{ $plan->name }}</h3>
                                <p class="mb-0 opacity-75">Made for starters</p>
                            </div>

                            <!-- Features List -->
                            <div class="gap-3 pricing-content d-flex flex-column">
                                @php
                                    $featuresJson = $plan->features;
                                    $decoded = json_decode($featuresJson, true);
                                    $features = is_array($decoded) ? array_values($decoded) : [];
                                @endphp

                                @if(count($features) > 0)
                                    @foreach($features as $feature)
                                        <div class="d-flex align-items-center justify-content-between">
                                            <p class="mb-0 fs-6">{{ $feature }}</p>
                                            <i class="bx bx-check text-success fs-5"></i>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="mb-0 text-center opacity-75">No features listed</p>
                                @endif
                            </div>

                            <!-- Price -->
                            <div class="gap-2 my-5 price-tag d-flex align-items-center justify-content-center">
                                <h5 class="mb-0 align-self-end text-primary">₹</h5>
                                <h1 class="mb-0 lh-1 price-amount text-primary">
                                    {{ number_format($plan->price, 0) }}
                                </h1>
                                <h5 class="mb-0 align-self-end text-primary">/{{ $plan->duration }}</h5>
                            </div>

                            <!-- Button Logic -->
                            <div class="d-grid">
                                @if($currentSubscription && $currentSubscription->plan_id == $plan->id && $currentSubscription->isActive())
                                    <button class="btn btn-lg btn-secondary" disabled>Current Plan</button>
                                @else
                                    <a href="{{ route('seller.subscription.checkout', $plan->id) }}"
                                       class="btn btn-lg {{ $loop->index == 0 ? 'btn-primary' : ($loop->index == 1 ? 'btn-success' : 'btn-danger') }}">
                                        @if($currentSubscription && $currentSubscription->plan_id == $plan->id)
                                            Renew Now
                                        @elseif($currentSubscription)
                                            Upgrade Plan
                                        @else
                                            Get Started
                                        @endif
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-5 text-center col-12">
                    <p>No subscription plans available at the moment.</p>
                </div>
            @endforelse
        </div><!--end row-->
    </div>
</main>

@include('seller.layouts.footer')