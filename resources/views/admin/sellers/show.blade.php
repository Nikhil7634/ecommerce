{{-- resources/views/admin/sellers/show.blade.php --}}

@include('admin.layouts.header')
@include('admin.layouts.navbar')
@include('admin.layouts.aside')

<div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
        <h2>Seller Details: {{ $seller->business_name ?? 'N/A' }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.sellers.index') }}">Sellers</a></li>
                <li class="breadcrumb-item ">{{ $seller->name ?? 'N/A' }}</li>
            </ol>
        </nav>
    </div>

    <div class="row g-4">
        <!-- Left Column: Seller Profile & Info -->
        <div class="col-md-4">
            <div class="panel">
                <div class="panel-body">
                    <div class="text-center profile-sidebar text-md-start">
                        <div class="py-4 text-center">
                            <div class="mx-auto mx-md-0 image-wrap d-inline-block">
                                <div class="overflow-hidden border border-4 border-white shadow-sm rounded-circle" style="width:120px;height:120px;">
                                    <img src="{{ $seller->avatar ? Storage::url($seller->avatar) : asset('https://cdn-icons-png.flaticon.com/512/8792/8792047.png') }}"
                                         alt="Seller Avatar" class="w-100 h-100 object-fit-cover">
                                </div>
                            </div>
                            <div class="mt-3">
                                <h4 class="admin-name">{{ $seller->name ?? 'N/A' }}</h4>
                                <span class="admin-role">Seller</span>
                                <div class="mt-2">
                                    <span class="badge bg-{{ $seller->status === 'active' ? 'success' : ($seller->status === 'inactive' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($seller->status ?? 'unknown') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 bottom">
                            <h6 class="profile-sidebar-subtitle">Business Information</h6>
                            <ul class="list-unstyled">
                                <li><span>Business Name:</span> {{ $seller->business_name ?? 'N/A' }}</li>
                                <li><span>GST No:</span> <code>{{ $seller->gst_no ?? 'N/A' }}</code></li>
                                <li><span>Email:</span> <a href="mailto:{{ $seller->email }}">{{ $seller->email ?? 'N/A' }}</a></li>
                                <li><span>Phone:</span> {{ $seller->phone ?? 'Not Provided' }}</li>
                                <li><span>Address:</span>
                                    {{ $seller->address ?? 'N/A' }}<br>
                                    {{ $seller->city ?? '' }}, {{ $seller->state ?? '' }} {{ $seller->zip ?? '' }}
                                </li>
                                <li><span>Country:</span> {{ $seller->country ?? 'India' }}</li>
                                <li><span>Registered:</span> {{ optional($seller->created_at)->format('d M Y') ?: 'N/A' }}</li>
                            </ul>

                            <h6 class="mt-4 profile-sidebar-subtitle">Uploaded Documents</h6>
                            <ul class="list-unstyled">
                                @forelse($seller->documents as $doc)
                                    <li>
                                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="text-primary">
                                            <i class="fa-light fa-file-pdf"></i> {{ $doc->original_name ?? 'Document' }}
                                        </a>
                                    </li>
                                @empty
                                    <li><span class="text-muted">No documents uploaded</span></li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Stats & Recent Products -->
        <div class="col-md-8">
            <!-- Stats Boxes -->
            <div class="row mb-25">
                <div class="col-lg-4 col-6">
                    <div class="dashboard-top-box rounded-bottom panel-bg">
                        <div class="left">
                            <h3>{{ $seller->products->count() }}</h3>
                            <p>Total Products</p>
                        </div>
                        <div class="right">
                            <div class="rounded part-icon text-primary"><i class="fa-light fa-boxes-stacked"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="dashboard-top-box rounded-bottom panel-bg">
                        <div class="left">
                            <h3>₹{{ number_format($seller->orders->sum('total') ?? 0, 2) }}</h3>
                            <p>Total Earnings</p>
                        </div>
                        <div class="right">
                            <div class="rounded part-icon text-success"><i class="fa-light fa-dollar-sign"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="dashboard-top-box rounded-bottom panel-bg">
                        <div class="left">
                            <h3>{{ $seller->orders->count() }}</h3>
                            <p>Total Orders</p>
                        </div>
                        <div class="right">
                            <div class="rounded part-icon text-info"><i class="fa-light fa-bag-shopping"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Products Table -->
            <div class="panel">
                <div class="panel-header d-flex justify-content-between align-items-center">
                    <h5>Recent Products</h5>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="panel-body">
                    @if($seller->products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($seller->products as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>₹{{ number_format($product->price, 2) }}</td>
                                            <td>{{ $product->stock }}</td>
                                            <td>
                                                <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($product->status ?? 'unknown') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="mb-0 text-muted">No products added yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.layouts.footer')