<!--start header-->
@include('admin.layouts.header')
<!--end top header-->

@include('admin.layouts.navbar')

<!--start sidebar-->
@include('admin.layouts.aside')

<div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
        <h2>Add New Category</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                <li class="breadcrumb-item ">Add New</li>
            </ol>
        </nav>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>Add New Category</h5>
                </div>
                <div class="panel-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-sm" id="categoryTitle" value="{{ old('name') }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                <p class="mt-2 perma-txt">
                                    Permalink: 
                                    <span class="text-primary" id="categoryPermalink">
                                        {{ url('category') }}/<span id="slug-preview">{{ old('slug') ?? 'category-name' }}</span>
                                    </span>
                                    <input type="hidden" name="slug" id="categorySlug" value="{{ old('slug') }}">
                                    <button type="button" class="btn-flush bg-primary" id="editPermaBtn">Edit</button>
                                    <button type="button" class="btn-flush bg-success" id="createPerma" hidden>OK</button>
                                    <button type="button" class="btn-flush bg-danger" id="cancelPerma" hidden>Cancel</button>
                                </p>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Parent Category</label>
                                <select name="parent_id" class="form-control form-control-sm">
                                    <option value="">None (Main Category)</option>
                                    @foreach($parentCategories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('parent_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Select a parent to create a subcategory</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Category Thumbnail</label>
                                <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                                <small class="text-muted">Recommended size: 300x300px (JPG, PNG)</small>
                                @error('image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mt-4 col-12 d-flex justify-content-end">
                                <div class="btn-box">
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-sm btn-primary">Save Category</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.layouts.footer')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const titleInput = document.getElementById('categoryTitle');
    const slugPreview = document.getElementById('slug-preview');
    const slugInput = document.getElementById('categorySlug');
    const editBtn = document.getElementById('editPermaBtn');
    const okBtn = document.getElementById('createPerma');
    const cancelBtn = document.getElementById('cancelPerma');

    function generateSlug(str) {
        return str.toLowerCase()
                  .replace(/[^a-z0-9]+/g, '-')
                  .replace(/^-|-$/g, '')
                  .replace(/--+/g, '-');
    }

    function updateSlug() {
        const slug = generateSlug(titleInput.value);
        slugPreview.textContent = slug || 'category-name';
        slugInput.value = slug;
    }

    titleInput.addEventListener('input', updateSlug);

    editBtn.addEventListener('click', function () {
        editBtn.hidden = true;
        okBtn.hidden = false;
        cancelBtn.hidden = false;
        slugPreview.contentEditable = true;
        slugPreview.focus();
    });

    okBtn.addEventListener('click', function () {
        let customSlug = slugPreview.textContent.trim();
        customSlug = generateSlug(customSlug);
        slugPreview.textContent = customSlug || 'category-name';
        slugInput.value = customSlug;
        editBtn.hidden = false;
        okBtn.hidden = true;
        cancelBtn.hidden = true;
        slugPreview.contentEditable = false;
    });

    cancelBtn.addEventListener('click', function () {
        updateSlug();
        editBtn.hidden = false;
        okBtn.hidden = true;
        cancelBtn.hidden = true;
        slugPreview.contentEditable = false;
    });

    // Initial update
    updateSlug();
});
</script>
@endpush