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
    <div class="dashboard-breadcrumb mb-25">
        <h2>Subscription Plans</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item ">Subscription Plans</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>All Subscription Plans</h5>
                    <div class="gap-2 btn-box d-flex">
                        <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-sm btn-primary">
                            <i class="fa-light fa-plus"></i> Add New Plan
                        </a>
                    </div>
                </div>

                <div class="panel-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Plan Name</th>
                                    <th>Price</th>
                                    <th>Duration</th>
                                    <th>Search Boost</th>
                                    <th>Features</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($plans as $plan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $plan->name }}</strong></td>
                                        <td>₹{{ number_format($plan->price, 2) }}</td>
                                        <td>
                                            <span class="badge bg-info text-capitalize">{{ $plan->duration }}</span>
                                        </td>
                                        <td>{{ $plan->search_boost }}x</td>
                                        <td>
                                            @if(is_array($plan->features) && count($plan->features) > 0)
                                                <ul class="mb-0 list-unstyled small">
                                                    @foreach($plan->features as $feature)
                                                        <li><i class="fa-light fa-check text-success me-1"></i> {{ $feature }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">No features</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.subscriptions.toggle', $plan) }}" method="POST" style="display:inline">
                                                @csrf @method('PATCH')
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" 
                                                           {{ $plan->is_active ? 'checked' : '' }}
                                                           onchange="this.form.submit()">
                                                    <label class="form-check-label">
                                                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                                    </label>
                                                </div>
                                            </form>
                                        </td>
                                        <td>{{ $plan->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-box">
                                                <a href="{{ route('admin.subscriptions.edit', $plan) }}" class="btn btn-sm btn-warning">
                                                    <i class="fa-light fa-pen-to-square"></i>
                                                </a>
                                                 <!-- Delete Modal Trigger -->
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $plan->id }}">
                                                    <i class="fa-light fa-trash"></i>
                                                </button>

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal{{ $plan->id }}" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="text-white modal-header bg-danger">
                                                                <h5 class="modal-title"><i class="fa-light fa-trash"></i> Delete Plan</h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to <strong>delete</strong> this plan?</p>
                                                                <p class="fw-bold">{{ $plan->name }}</p>
                                                                <div class="alert alert-danger">
                                                                    <i class="fa-light fa-exclamation-triangle"></i> This action cannot be undone.
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <form action="{{ route('admin.subscriptions.destroy', $plan) }}" method="POST" style="display:inline">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="py-5 text-center text-muted">
                                            <i class="mb-3 fa-light fa-crown fa-3x"></i><br>
                                            No subscription plans created yet. <br>
                                            <a href="{{ route('admin.subscriptions.create') }}" class="mt-3 btn btn-primary btn-sm">
                                                Create First Plan
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $plans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.layouts.footer')