{{-- resources/views/admin/sellers/suspended.blade.php --}}

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
    .alert { text-align: left; }
</style>

<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>Suspended Sellers</h5>
                    <div class="gap-2 btn-box d-flex">
                        <div id="tableSearch"></div>
                        <button class="btn btn-sm btn-icon btn-outline-primary" title="Refresh">
                            <i class="fa-light fa-arrows-rotate"></i>
                        </button>
                        <div class="digi-dropdown dropdown">
                            <button class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                <i class="fa-regular fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="digi-dropdown-menu dropdown-menu">
                                <li class="dropdown-title">Show Table Columns</li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showName" checked><label class="form-check-label" for="showName">Name</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showBusiness" checked><label class="form-check-label" for="showBusiness">Business Name</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showEmail" checked><label class="form-check-label" for="showEmail">Email</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showGST" checked><label class="form-check-label" for="showGST">GST No</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showPhone" checked><label class="form-check-label" for="showPhone">Phone</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showCity" checked><label class="form-check-label" for="showCity">City</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showSuspendedOn" checked><label class="form-check-label" for="showSuspendedOn">Suspended On</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showRegistered" checked><label class="form-check-label" for="showRegistered">Registered</label></div></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="mb-20 product-table-quantity d-flex justify-content-between align-items-center">
                        <ul class="mb-0">
                            <li class="text-white">
                                Suspended Sellers ({{ isset($sellers) ? ($sellers->total() ?? 0) : 0 }})
                            </li>
                        </ul>
                        <div class="gap-2 btn-box d-md-flex d-none">
                            <button class="btn btn-sm btn-icon btn-outline-primary" title="Download Excel">
                                <i class="fa-light fa-file-spreadsheet"></i>
                            </button>
                            <button class="btn btn-sm btn-icon btn-outline-primary" title="Download PDF">
                                <i class="fa-light fa-file-pdf"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3 table-filter-option">
                        <div class="row g-3 align-items-center">
                            <div class="col-xl-10 col-9">
                                <div class="row g-3">
                                    <div class="col-auto">
                                        <form class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <select class="form-control form-control-sm">
                                                    <option>Bulk action</option>
                                                    <option value="activate">Activate Selected</option>
                                                </select>
                                            </div>
                                            <div class="col-auto">
                                                <button class="btn btn-sm btn-success">Apply</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control form-control-sm" placeholder="Search suspended sellers...">
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-sm btn-primary"><i class="fa-light fa-filter"></i> Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="table table-dashed table-hover digi-dataTable table-striped" id="suspendedSellersTable">
                        <thead>
                            <tr>
                                <th class="no-sort"><div class="form-check"><input class="form-check-input" type="checkbox" id="markAll"></div></th>
                                <th>Name</th>
                                <th>Business Name</th>
                                <th>Email</th>
                                <th>GST No</th>
                                <th>Phone</th>
                                <th>City</th>
                                <th>Registered</th>
                                <th>Suspended On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sellers as $seller)
                                <tr>
                                    <td><div class="form-check"><input class="form-check-input" type="checkbox" value="{{ $seller->id }}"></div></td>
                                    <td>
                                        <a href="{{ route('admin.sellers.show', $seller) }}">
                                            {{ $seller->name ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td><strong>{{ $seller->business_name ?? 'N/A' }}</strong></td>
                                    <td>{{ $seller->email ?? 'N/A' }}</td>
                                    <td><code>{{ $seller->gst_no ?? 'N/A' }}</code></td>
                                    <td>{{ $seller->phone ?? '—' }}</td>
                                    <td>{{ $seller->city ?? '—' }}</td>
                                    <td>{{ optional($seller->created_at)->format('d M Y') ?: 'N/A' }}</td>
                                    <td>{{ optional($seller->updated_at)->format('d M Y') ?: 'N/A' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#activateModal{{ $seller->id }}">
                                            <i class="fa-light fa-unlock"></i> Activate
                                        </button>

                                        <!-- Activate Confirmation Modal -->
                                        <div class="modal fade" id="activateModal{{ $seller->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="text-white modal-header bg-success">
                                                        <h5 class="modal-title"><i class="fa-light fa-unlock"></i> Activate Seller</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to <strong>activate</strong> this suspended seller?</p>
                                                        <ul class="mb-0 list-unstyled">
                                                            <li><strong>Name:</strong> {{ $seller->name ?? '—' }}</li>
                                                            <li><strong>Business:</strong> {{ $seller->business_name ?? '—' }}</li>
                                                            <li><strong>Email:</strong> {{ $seller->email ?? '—' }}</li>
                                                            <li><strong>Suspended On:</strong> {{ optional($seller->updated_at)->format('d M Y') ?: '—' }}</li>
                                                        </ul>
                                                        <div class="mt-3 alert alert-info">
                                                            <i class="fa-light fa-info-circle"></i> The seller will regain full access to their dashboard.
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('admin.sellers.activate', $seller) }}" method="POST" style="display:inline">
                                                            @csrf
                                                            @method('PATCH')
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
                                        <i class="mb-3 fa-light fa-check-circle fa-3x text-success"></i><br>
                                        <strong>No suspended sellers</strong><br>
                                        <small>All sellers are currently active and in good standing.</small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4 table-bottom-control">
                        {{ $sellers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.layouts.footer')