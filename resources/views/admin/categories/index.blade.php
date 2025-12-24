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
        <h2>All Categories</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Categories</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>All Categories ({{ $categories->total() }})</h5>
                    <div class="gap-2 btn-box d-flex">
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-primary">
                            <i class="fa-light fa-plus"></i> Add New Category
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
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Parent</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($category->parent_id)
                                                <span class="text-muted">└─ </span>
                                            @endif
                                            <strong>{{ $category->name }}</strong>
                                        </td>
                                        <td><code>{{ $category->slug }}</code></td>
                                        <td>
                                            {{ $category->parent?->name ?? '—' }}
                                        </td>
                                        <td>
                                            @if($category->image)
                                                <img src="{{ Storage::url($category->image) }}" alt="thumb" class="img-thumbnail" style="width:50px;height:50px;object-fit:cover;">
                                            @else
                                                <span class="text-muted">No image</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-box">
                                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">
                                                    <i class="fa-light fa-pen-to-square"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $category->id }}">
                                                    <i class="fa-light fa-trash"></i>
                                                </button>
                                            </div>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="text-white modal-header bg-danger">
                                                            <h5 class="modal-title"><i class="fa-light fa-trash"></i> Delete Category</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to delete this category?</p>
                                                            <p class="fw-bold">{{ $category->name }}</p>
                                                            @if($category->children->count() > 0)
                                                                <div class="alert alert-warning">
                                                                    This category has {{ $category->children->count() }} subcategories. They will also be deleted.
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-5 text-center text-muted">
                                            <i class="mb-3 fa-light fa-folder-open fa-3x"></i><br>
                                            No categories found. <a href="{{ route('admin.categories.create') }}">Create one</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.layouts.footer')