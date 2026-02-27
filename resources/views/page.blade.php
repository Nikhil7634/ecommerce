<x-header 
    :title="$page->meta_title ?? $page->title"
    :description="$page->meta_description"
    :keywords="$page->meta_keywords"
    ogImage="{{ $page->featured_image ? asset('storage/' . $page->featured_image) : asset('assets/images/banner/home-og.jpg') }}"
/>

<x-navbar />

<!--=========================
    PAGE BANNER START
==========================-->
<section class="page_banner" style="background: url('{{ asset('assets/images/page_banner_bg.jpg') }}');">
    <div class="page_banner_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page_banner_text wow fadeInUp">
                        <h1>{{ $page->title }}</h1>
                        <ul>
                            <li><a href="{{ route('home') }}"><i class="fal fa-home-lg"></i> Home</a></li>
                            <li class="active">{{ $page->title }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--=========================
    PAGE BANNER END
==========================-->

<!--============================
    PAGE CONTENT START
=============================-->
<section class="page_content mt_100 mb_100">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="content_wrapper">
                    @if($page->featured_image)
                    <div class="mb-4 text-center featured_image">
                        <img src="{{ asset('storage/' . $page->featured_image) }}" 
                             alt="{{ $page->title }}" 
                             class="img-fluid rounded-3"
                             style="max-width: 100%; max-height: 400px; object-fit: cover;">
                    </div>
                    @endif
                    
                    <div class="page_content_text">
                        {!! $page->content !!}
                    </div>

                    <!-- Last updated info -->
                    <div class="pt-3 mt-5 border-top">
                        <p class="text-muted small">
                            <i class="fal fa-clock me-1"></i>
                            Last updated: {{ $page->updated_at->format('F j, Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--============================
    PAGE CONTENT END
=============================-->

<x-footer />

 
<style>
    .page_content_text {
        font-size: 1.1rem;
        line-height: 1;
        color: #333;
    }
    
    .page_content_text h1,
    .page_content_text h2,
    .page_content_text h3 {
        margin-top: 1rem;
        margin-bottom: 0.7rem;
        font-weight: 600;
    }
    
    .page_content_text h1 {
        font-size: 2.5rem;
    }
    
    .page_content_text h2 {
        font-size: 2rem;
    }
    
    .page_content_text h3 {
        font-size: 1.5rem;
    }
    
    .page_content_text p {
        margin-bottom: 1.5rem;
    }
    
    .page_content_text ul,
    .page_content_text ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }
    
    .page_content_text li {
        margin-bottom: 0.5rem;
    }
    
    .page_content_text table {
        width: 100%;
        margin-bottom: 1.5rem;
        border-collapse: collapse;
    }
    
    .page_content_text th,
    .page_content_text td {
        padding: 0.75rem;
        border: 1px solid #dee2e6;
    }
    
    .page_content_text th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .page_content_text blockquote {
        padding: 1rem;
        margin: 1.5rem 0;
        background-color: #f8f9fa;
        border-left: 4px solid #0d6efd;
        font-style: italic;
    }
    
    .featured_image {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    @media (max-width: 768px) {
        .page_content_text {
            font-size: 1rem;
        }
        
        .page_content_text h1 {
            font-size: 2rem;
        }
        
        .page_content_text h2 {
            font-size: 1.5rem;
        }
        
        .page_content_text h3 {
            font-size: 1.25rem;
        }
    }
</style>
 