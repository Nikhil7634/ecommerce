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
                    <h5>Pending Seller Approvals</h5>
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
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showRegistered" checked><label class="form-check-label" for="showRegistered">Registered</label></div></li>
                                <li><div class="form-check"><input class="form-check-input" type="checkbox" id="showDocument" checked><label class="form-check-label" for="showDocument">Document</label></div></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="mb-20 product-table-quantity d-flex justify-content-between align-items-center">
                        <ul class="mb-0">
                            <li class="text-white">Pending Sellers ({{ $sellers->total() }})</li>
                        </ul>
                        <div class="gap-2 btn-box d-md-flex d-none">
                            <button class="btn btn-sm btn-icon btn-outline-primary" title="Download Excel"><i class="fa-light fa-file-spreadsheet"></i></button>
                            <button class="btn btn-sm btn-icon btn-outline-primary" title="Download PDF"><i class="fa-light fa-file-pdf"></i></button>
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
                                <th>Registered</th>
                                <th>Document</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sellers as $seller)
                                <tr>
                                    <td><div class="form-check"><input class="form-check-input" type="checkbox" value="{{ $seller->id }}"></div></td>
                                    <td><a href="{{ route('admin.sellers.show', $seller) }}">{{ $seller->name }}</a></td>
                                    <td><strong>{{ $seller->business_name }}</strong></td>
                                    <td>{{ $seller->email }}</td>
                                    </td>
                                    <td><code>{{ $seller->gst_no }}</code></td>
                                    <td>{{ $seller->phone ?? '—' }}</td>
                                    <td>{{ $seller->city ?? '—' }}</td>
                                    <td>{{ $seller->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if($seller->documents->first())
                                            <a href="{{ Storage::url($seller->documents->first()->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fa-light fa-eye"></i> View
                                            </a>
                                        @else
                                            <span class="text-muted">No file</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-box">
                                            <!-- Approve Modal Trigger -->
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $seller->id }}">
                                                <i class="fa-light fa-check"></i> Approve
                                            </button>

                                            <!-- Reject Modal Trigger -->
                                            <button type="button" class="btn btn-sm btn-danger ms-1" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $seller->id }}">
                                                <i class="fa-light fa-times"></i> Reject
                                            </button>
                                        </div>

                                        <!-- Approve Modal -->
                                        <div class="modal fade" id="approveModal{{ $seller->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="text-white modal-header bg-success">
                                                        <h5 class="modal-title"><i class="fa-light fa-check-circle"></i> Approve Seller</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to <strong>approve</strong> this seller?</p>
                                                        <ul class="mb-0 list-unstyled">
                                                            <li><strong>Name:</strong> {{ $seller->name }}</li>
                                                            <li><strong>Business:</strong> {{ $seller->business_name }}</li>
                                                            <li><strong>GST:</strong> {{ $seller->gst_no }}</li>
                                                            <li><strong>Email:</strong> {{ $seller->email }}</li>
                                                        </ul>
                                                        <div class="mt-3 alert alert-success">
                                                            <i class="fa-light fa-info-circle"></i> Seller will be able to sell immediately.
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('admin.sellers.approve', $seller) }}" method="POST" style="display:inline">
                                                            @csrf @method('PATCH')
                                                            <button type="submit" class="btn btn-success">Yes, Approve</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $seller->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="text-white modal-header bg-danger">
                                                        <h5 class="modal-title"><i class="fa-light fa-times-circle"></i> Reject Application</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to <strong>reject</strong> this seller application?</p>
                                                        <ul class="mb-0 list-unstyled">
                                                            <li><strong>Name:</strong> {{ $seller->name }}</li>
                                                            <li><strong>Business:</strong> {{ $seller->business_name }}</li>
                                                            <li><strong>GST:</strong> {{ $seller->gst_no }}</li>
                                                        </ul>
                                                        <div class="mt-3 alert alert-warning">
                                                            <i class="fa-light fa-exclamation-triangle"></i> The seller will be notified and can reapply.
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('admin.sellers.reject', $seller) }}" method="POST" style="display:inline">
                                                            @csrf @method('PATCH')
                                                            <button type="submit" class="btn btn-danger">Yes, Reject</button>
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
                                        <strong>No pending sellers</strong><br>
                                        <small>All applications have been processed.</small>
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