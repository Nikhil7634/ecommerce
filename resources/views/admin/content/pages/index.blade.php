@include('admin.layouts.header')
@include('admin.layouts.navbar')
@include('admin.layouts.aside')

<!-- main content start -->
<div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
        <h2>Content Pages</h2>
        <div class="gap-2 d-flex">
            <a href="{{ route('admin.content.pages.create') }}" class="btn btn-primary">
                <i class="fa-light fa-plus-circle me-2"></i>Create New Page
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-4 row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-body">
                    <form action="{{ route('admin.content.pages') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search by title or content..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fa-light fa-filter me-2"></i>Filter
                            </button>
                            <a href="{{ route('admin.content.pages') }}" class="btn btn-secondary">
                                <i class="fa-light fa-undo me-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Pages Table -->
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>All Pages ({{ $pages->total() }})</h5>
                </div>
                <div class="panel-body">
                    <table class="table table-dashed">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Author</th>
                                <th>Last Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pages as $page)
                            <tr>
                                <td>#{{ $page->id }}</td>
                                <td>
                                    <strong>{{ $page->title }}</strong>
                                    @if($page->featured_image)
                                        <br><small class="text-muted"><i class="fa-light fa-image"></i> Has image</small>
                                    @endif
                                </td>
                                <td>
                                    <code>/{{ $page->slug }}</code>
                                </td>
                                <td>
                                    @if($page->status == 'published')
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-secondary">Draft</span>
                                    @endif
                                </td>
                                <td>{{ $page->author->name ?? 'Unknown' }}</td>
                                <td>
                                    {{ $page->updated_at->format('d M Y') }}
                                    <br>
                                    <small class="text-muted">{{ $page->updated_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="btn-box">
                                        <a href="{{ route('admin.content.pages.edit', $page->id) }}" class="btn btn-sm btn-icon btn-outline-primary" title="Edit">
                                            <i class="fa-light fa-pen"></i>
                                        </a>
                                        <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="btn btn-sm btn-icon btn-outline-info" title="View">
                                            <i class="fa-light fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.content.pages.destroy', $page->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this page?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-outline-danger" title="Delete">
                                                <i class="fa-light fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center">
                                    <i class="mb-3 fa-light fa-file-lines fa-3x text-muted"></i>
                                    <h5>No pages found</h5>
                                    <p class="text-muted">Get started by creating your first page.</p>
                                    <a href="{{ route('admin.content.pages.create') }}" class="btn btn-primary">
                                        <i class="fa-light fa-plus-circle me-2"></i>Create New Page
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    @if($pages->hasPages())
                    <div class="mt-4">
                        {{ $pages->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- main content end -->

@include('admin.layouts.footer')