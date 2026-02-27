 <!--start header-->
@include('admin.layouts.header')
<!--end top header-->

@include('admin.layouts.navbar')

<!--start sidebar-->
@include('admin.layouts.aside')

<div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
        <h2>Create New Page</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.content.pages') }}">Content Pages</a></li>
                <li class="breadcrumb-item active">Create New Page</li>
            </ol>
        </nav>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-light fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-light fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="mx-auto col-lg-10">
            <div class="panel">
                <div class="panel-header">
                    <h5>Page Information</h5>
                </div>
                <div class="panel-body">
                    <form action="{{ route('admin.content.pages.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-4">
                            <!-- Page Title -->
                            <div class="col-12">
                                <label class="form-label">Page Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Enter page title" required>
                                @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- URL Slug -->
                            <div class="col-12">
                                <label class="form-label">URL Slug</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">{{ url('/') }}/</span>
                                    <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="leave-empty-to-auto-generate">
                                </div>
                                <small class="text-muted">Leave empty to auto-generate from title. Use only letters, numbers, and hyphens.</small>
                                @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Content Editor -->
                            <div class="col-12">
                                <label class="form-label">Page Content</label>
                                <textarea name="content" id="editor" class="form-control @error('content') is-invalid @enderror" rows="20">{{ old('content') }}</textarea>
                                @error('content') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Featured Image -->
                            <div class="col-md-6">
                                <label class="form-label">Featured Image</label>
                                <input type="file" name="featured_image" id="featured_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif">
                                <small class="text-muted">Recommended size: 1200x630px (max 2MB)</small>
                                
                                <!-- Image Preview -->
                                <div id="imagePreview" class="mt-3 text-center" style="display: none;">
                                    <div class="position-relative d-inline-block">
                                        <img src="#" alt="Preview" class="img-fluid rounded-3" style="max-height: 200px;">
                                        <button type="button" class="top-0 m-2 btn btn-sm btn-danger position-absolute end-0" onclick="removeImage()">
                                            <i class="fa-light fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                @error('featured_image') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Status & Order -->
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                                <small class="text-muted">Draft pages are not visible to visitors</small>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Display Order</label>
                                <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}" min="0">
                                <small class="text-muted">Lower numbers appear first</small>
                            </div>

                            <!-- SEO Meta Title -->
                            <div class="col-12">
                                <label class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}" maxlength="255" placeholder="SEO title (50-60 chars)">
                                <small class="text-muted" id="metaTitleCount">0/255 characters</small>
                                @error('meta_title') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- SEO Meta Description -->
                            <div class="col-12">
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description" class="form-control" rows="3" maxlength="500" placeholder="SEO description (150-160 chars)">{{ old('meta_description') }}</textarea>
                                <small class="text-muted" id="metaDescCount">0/500 characters</small>
                                @error('meta_description') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- SEO Meta Keywords -->
                            <div class="col-12">
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords') }}" placeholder="keyword1, keyword2, keyword3">
                                <small class="text-muted">Comma separated keywords</small>
                                @error('meta_keywords') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-12 text-end">
                                <a href="{{ route('admin.content.pages') }}" class="btn btn-outline-secondary me-2">
                                    <i class="fa-light fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-light fa-save me-2"></i>Create Page
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.layouts.footer')

 
<style>
    /* CKEditor customization */
    .ck-editor__editable {
        min-height: 400px;
        max-height: 600px;
    }
    
    /* Form styling */
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }
    
    /* Preview image styling */
    #imagePreview {
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
 
<!-- Include CKEditor for rich text editing -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize CKEditor
        const editorElement = document.querySelector('#editor');
        if (editorElement) {
            ClassicEditor
                .create(editorElement, {
                    toolbar: {
                        items: [
                            'heading',
                            '|',
                            'bold',
                            'italic',
                            'link',
                            'bulletedList',
                            'numberedList',
                            '|',
                            'outdent',
                            'indent',
                            '|',
                            'blockQuote',
                            'insertTable',
                            'mediaEmbed',
                            'undo',
                            'redo',
                            '|',
                            'alignment'
                        ]
                    },
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                        ]
                    },
                    table: {
                        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties']
                    },
                    alignment: {
                        options: ['left', 'center', 'right']
                    },
                    placeholder: 'Start writing your page content here...'
                })
                .then(editor => {
                    console.log('CKEditor initialized successfully');
                    
                    // Auto-resize editor
                    editor.editing.view.document.on('change', () => {
                        const height = editor.editing.view.document.getRoot().clientHeight;
                        if (height > 600) {
                            document.querySelector('.ck-editor__editable').style.maxHeight = height + 'px';
                        }
                    });
                })
                .catch(error => {
                    console.error('CKEditor error:', error);
                });
        }

        // Auto-generate slug from title
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        
        if (titleInput && slugInput) {
            titleInput.addEventListener('input', function() {
                if (!slugInput.value || slugInput.dataset.autoGenerated === 'true') {
                    const slug = this.value
                        .toLowerCase()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/^-|-$/g, '');
                    slugInput.value = slug;
                    slugInput.dataset.autoGenerated = 'true';
                }
            });

            slugInput.addEventListener('input', function() {
                this.dataset.autoGenerated = 'false';
            });
        }

        // Image preview
        const imageInput = document.getElementById('featured_image');
        const imagePreview = document.getElementById('imagePreview');
        
        if (imageInput && imagePreview) {
            imageInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    
                    // Check file size (2MB max)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('File size must be less than 2MB');
                        this.value = '';
                        return;
                    }
                    
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const img = imagePreview.querySelector('img');
                        img.src = e.target.result;
                        imagePreview.style.display = 'block';
                    }
                    
                    reader.readAsDataURL(file);
                }
            });
        }

        // Meta title character counter
        const metaTitle = document.querySelector('input[name="meta_title"]');
        const metaTitleCount = document.getElementById('metaTitleCount');
        
        if (metaTitle && metaTitleCount) {
            // Initial count
            metaTitleCount.textContent = (metaTitle.value.length || 0) + '/255 characters';
            
            metaTitle.addEventListener('input', function() {
                const count = this.value.length;
                metaTitleCount.textContent = count + '/255 characters';
                
                if (count > 255) {
                    metaTitleCount.classList.add('text-danger');
                } else if (count > 60) {
                    metaTitleCount.classList.add('text-warning');
                    metaTitleCount.classList.remove('text-danger');
                } else {
                    metaTitleCount.classList.remove('text-warning', 'text-danger');
                }
            });
        }

        // Meta description character counter
        const metaDesc = document.querySelector('textarea[name="meta_description"]');
        const metaDescCount = document.getElementById('metaDescCount');
        
        if (metaDesc && metaDescCount) {
            // Initial count
            metaDescCount.textContent = (metaDesc.value.length || 0) + '/500 characters';
            
            metaDesc.addEventListener('input', function() {
                const count = this.value.length;
                metaDescCount.textContent = count + '/500 characters';
                
                if (count > 500) {
                    metaDescCount.classList.add('text-danger');
                } else if (count > 160) {
                    metaDescCount.classList.add('text-warning');
                    metaDescCount.classList.remove('text-danger');
                } else {
                    metaDescCount.classList.remove('text-warning', 'text-danger');
                }
            });
        }
    });

    // Function to remove image preview
    function removeImage() {
        const imageInput = document.getElementById('featured_image');
        const imagePreview = document.getElementById('imagePreview');
        
        if (imageInput && imagePreview) {
            imageInput.value = '';
            imagePreview.style.display = 'none';
        }
    }
</script>
 