<!--start header-->
@include('admin.layouts.header')
<!--end top header-->

@include('admin.layouts.navbar')

<!--start sidebar-->
@include('admin.layouts.aside')

<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>Blocked Customers</h5>
                    <div class="gap-2 btn-box d-flex">
                        <div id="tableSearch"></div>
                        <button class="btn btn-sm btn-icon btn-outline-primary"><i class="fa-light fa-arrows-rotate"></i></button>
                        <div class="digi-dropdown dropdown">
                            <button class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"><i class="fa-regular fa-ellipsis-vertical"></i></button>
                            <ul class="digi-dropdown-menu dropdown-menu">
                                <li class="dropdown-title">Show Table Title</li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showName" checked><label class="form-check-label" for="showName">Name</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showUsername" checked><label class="form-check-label" for="showUsername">Username</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showLastActive" checked><label class="form-check-label" for="showLastActive">Last Active</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showDateRegistered" checked><label class="form-check-label" for="showDateRegistered">Date Registered</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showEmail" checked><label class="form-check-label" for="showEmail">Email</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showOrders" checked><label class="form-check-label" for="showOrders">Orders</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showTotalSpend" checked><label class="form-check-label" for="showTotalSpend">Total Spend</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showAOV" checked><label class="form-check-label" for="showAOV">AOV</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showCountryRegion" checked><label class="form-check-label" for="showCountryRegion">Country/Region</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showCity" checked><label class="form-check-label" for="showCity">City</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showRegion" checked><label class="form-check-label" for="showRegion">Region</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showPostalCode" checked><label class="form-check-label" for="showPostalCode">Postal Code</label></div></li>
                                <li class="pb-1 dropdown-title">Showing</li>
                                <li>
                                    <div class="input-group">
                                        <input type="number" class="form-control form-control-sm w-50" value="10">
                                        <button class="btn btn-sm btn-primary w-50">Apply</button>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="mb-20 product-table-quantity d-flex justify-content-between align-items-center">
                        <ul class="mb-0">
                            <li class="text-white">Blocked ({{ $buyers->total() }})</li>
                        </ul>
                        <div class="gap-2 btn-box d-md-flex d-none">
                            <button class="btn btn-sm btn-icon btn-outline-primary" title="Download Excel"><i class="fa-light fa-file-spreadsheet"></i></button>
                            <button class="btn btn-sm btn-icon btn-outline-primary" title="Download PDF"><i class="fa-light fa-file-pdf"></i></button>
                        </div>
                    </div>

                    <div class="table-filter-option">
                        <div class="row g-3">
                            <div class="col-xl-10 col-9 col-xs-12">
                                <div class="row g-3">
                                    <div class="col">
                                        <form class="row g-2">
                                            <div class="col">
                                                <select class="form-control form-control-sm">
                                                    <option>Bulk action</option>
                                                    <option value="unban">Unblock Selected</option>
                                                    <option value="delete">Delete Permanently</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <button class="btn btn-sm btn-primary">Apply</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control form-control-sm" placeholder="Search blocked customers...">
                                    </div>
                                    <div class="col">
                                        <button class="btn btn-sm btn-primary"><i class="fa-light fa-filter"></i> Filter</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-3 col-xs-12 d-flex justify-content-end">
                                <div id="productTableLength"></div>
                            </div>
                        </div>
                    </div>

                    <table class="table table-dashed table-hover digi-dataTable all-product-table table-striped" id="allProductTable">
                        <thead>
                            <tr>
                                <th class="no-sort">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="markAllProduct">
                                    </div>
                                </th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Last Active</th>
                                <th>Date Registered</th>
                                <th>Email</th>
                                <th>Orders</th>
                                <th>Total Spend</th>
                                <th>AOV</th>
                                <th>Country / Region</th>
                                <th>City</th>
                                <th>Region</th>
                                <th>Postal Code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($buyers as $buyer)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{ $buyer->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.buyers.show', $buyer) }}">{{ $buyer->name }}</a>
                                    </td>
                                    <td>{{ $buyer->name }}</td>
                                    <td>{{ $buyer->updated_at?->diffForHumans() ?? 'Never' }}</td>
                                    <td>{{ $buyer->created_at->format('F d, Y') }}</td>
                                    <td><a href="mailto:{{ $buyer->email }}">{{ $buyer->email }}</a></td>
                                    <td>{{ $buyer->orders->count() }}</td>
                                    <td>₹{{ number_format($buyer->orders->sum('total'), 2) }}</td>
                                    <td>
                                        @if($buyer->orders->count() > 0)
                                            ₹{{ number_format($buyer->orders->avg('total'), 2) }}
                                        @else
                                            ₹0.00
                                        @endif
                                    </td>
                                    <td>{{ $buyer->country ?? 'N/A' }}</td>
                                    <td>{{ $buyer->city ?? 'N/A' }}</td>
                                    <td>{{ $buyer->state ?? 'N/A' }}</td>
                                    <td>{{ $buyer->zip ?? 'N/A' }}</td>
                                    <td>
                                        <div class="btn-box">
                                            <a href="{{ route('admin.buyers.show', $buyer) }}" class="btn btn-sm btn-icon btn-primary"><i class="fa-light fa-eye"></i></a>
                                            <form action="{{ route('admin.buyers.unban', $buyer) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-icon btn-success" title="Unblock">
                                                    <i class="fa-light fa-unlock"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="py-4 text-center">No blocked customers found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="table-bottom-control">
                        {{ $buyers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.layouts.footer')