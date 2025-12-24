<!--start header-->
@include('admin.layouts.header')
<!--end top header-->

@include('admin.layouts.navbar')

<!--start sidebar-->
@include('admin.layouts.aside')

<style>
    .modal-backdrop.show { display: none; }
    .modal .modal-footer { justify-content: flex-start; }
    .modal .modal-body p, .modal .modal-body li { text-align: left; }
    .alert{
        text-align: left;
    }
</style>

<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>All Sellers</h5>
                    <div class="gap-2 btn-box d-flex">
                        <div id="tableSearch"></div>
                        <button class="btn btn-sm btn-icon btn-outline-primary"><i class="fa-light fa-arrows-rotate"></i></button>
                        <div class="digi-dropdown dropdown">
                            <button class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                <i class="fa-regular fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="digi-dropdown-menu dropdown-menu">
                                <li class="dropdown-title">Show Table Title</li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showName" checked><label class="form-check-label" for="showName">Name</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showBusiness" checked><label class="form-check-label" for="showBusiness">Business Name</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showEmail" checked><label class="form-check-label" for="showEmail">Email</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showGST" checked><label class="form-check-label" for="showGST">GST No</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showPhone" checked><label class="form-check-label" for="showPhone">Phone</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showCity" checked><label class="form-check-label" for="showCity">City</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showStatus" checked><label class="form-check-label" for="showStatus">Status</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showRegistered" checked><label class="form-check-label" for="showRegistered">Registered</label></div></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="mb-20 product-table-quantity d-flex justify-content-between align-items-center">
                        <ul class="mb-0">
                            <li class="text-white">All Sellers ({{ $sellers->total() }})</li>
                            <li>Active ({{ $sellers->where('status', 'active')->count() }})</li>
                            <li>Banned ({{ $sellers->where('status', 'banned')->count() }})</li>
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
                                                    <option value="suspend">Suspend Selected</option>
                                                    <option value="activate">Activate Selected</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <button class="btn btn-sm btn-primary">Apply</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col">
                                        <select class="form-control form-control-sm">
                                            <option>All Status</option>
                                            <option>Active</option>
                                            <option>Banned</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control form-control-sm" placeholder="Search sellers...">
                                    </div>
                                    <div class="col">
                                        <button class="btn btn-sm btn-primary"><i class="fa-light fa-filter"></i> Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="table table-dashed table-hover digi-dataTable table-striped" id="allProductTable">
                        <thead>
                            <tr>
                                <th class="no-sort"><div class="form-check"><input class="form-check-input" type="checkbox" id="markAll"></div></th>
                                <th>Name</th>
                                <th>Business Name</th>
                                <th>Email</th>
                                <th>GST No</th>
                                <th>Phone</th>
                                <th>City</th>
                                <th>Status</th>
                                <th>Registered</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sellers as $seller)
                                <tr>
                                    <td><div class="form-check"><input class="form-check-input" type="checkbox" value="{{ $seller->id }}"></div></td>
                                    <td>
                                        <a href="{{ route('admin.sellers.show', $seller) }}">{{ $seller->name }}</a>
                                    </td>
                                    <td><strong>{{ $seller->business_name }}</strong></td>
                                    <td>{{ $seller->email }}</td>
                                    <td><code>{{ $seller->gst_no }}</code></td>
                                    <td>{{ $seller->phone ?? '—' }}</td>
                                    <td>{{ $seller->city ?? '—' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $seller->status === 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($seller->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $seller->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="btn-box">
                                            @if($seller->status === 'active')
                                                <!-- Suspend Modal Trigger -->
                                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#suspendModal{{ $seller->id }}">
                                                    <i class="fa-light fa-ban"></i> Suspend
                                                </button>
                                            @else
                                                <!-- Activate Modal Trigger -->
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#activateModal{{ $seller->id }}">
                                                    <i class="fa-light fa-unlock"></i> Activate
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Suspend Modal -->
                                        <div class="modal fade" id="suspendModal{{ $seller->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning text-dark">
                                                        <h5 class="modal-title"><i class="fa-light fa-ban"></i> Suspend Seller</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to <strong>suspend</strong> this seller?</p>
                                                        <ul class="mb-0 list-unstyled">
                                                            <li><strong>Name:</strong> {{ $seller->name }}</li>
                                                            <li><strong>Business:</strong> {{ $seller->business_name }}</li>
                                                            <li><strong>Email:</strong> {{ $seller->email }}</li>
                                                        </ul>
                                                        <div class="mt-3 alert alert-warning">
                                                            <i class="fa-light fa-exclamation-triangle"></i> Seller will lose access to dashboard.
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('admin.sellers.suspend', $seller) }}" method="POST" style="display:inline">
                                                            @csrf @method('PATCH')
                                                            <button type="submit" class="btn btn-warning">Yes, Suspend</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Activate Modal -->
                                        <div class="modal fade" id="activateModal{{ $seller->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="text-white modal-header bg-success">
                                                        <h5 class="modal-title"><i class="fa-light fa-unlock"></i> Activate Seller</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to <strong>activate</strong> this seller?</p>
                                                        <ul class="mb-0 list-unstyled">
                                                            <li><strong>Name:</strong> {{ $seller->name }}</li>
                                                            <li><strong>Business:</strong> {{ $seller->business_name }}</li>
                                                            <li><strong>Email:</strong> {{ $seller->email }}</li>
                                                        </ul>
                                                        <div class="mt-3 alert alert-success">
                                                            <i class="fa-light fa-info-circle"></i> Seller will regain full access.
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('admin.sellers.activate', $seller) }}" method="POST" style="display:inline">
                                                            @csrf @method('PATCH')
                                                            <button type="submit" class="btn btn-success">Yes, Activate</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-5 text-center">
                                        <i class="mb-3 fa-light fa-store fa-3x text-muted"></i><br>
                                        <strong>No sellers found</strong>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="table-bottom-control">
                        {{ $sellers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.layouts.footer')