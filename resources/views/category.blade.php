<x-header 
    title="Categories - {{ config('app.name', 'eCommerce') }}"
    description="Browse all product categories and find what you're looking for"
    keywords="categories, products, shopping, browse"
    ogImage="{{ asset('assets/images/banner/home-og.jpg') }}"
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
                        <h1>Shop by Category</h1>
                        <ul>
                            <li><a href="{{ route('home') }}"><i class="fal fa-home-lg"></i> Home</a></li>
                            <li class="active">Categories</li>
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
    CATEGORY PAGE START
=============================-->
<section class="category_page category_2 mt_75 mb_95">
    <div class="container">
        <div class="row">
            @forelse($categories as $category)
            <div class="col-xl-2 col-6 col-sm-4 col-md-3 wow fadeInUp">
                <a href="{{ route('category.show', $category->slug) }}" class="category_item">
                    <div class="img">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-fluid w-100">
                        @else
                            <img src="{{ asset('assets/images/category_img_' . ($loop->iteration % 7 + 1) . '.png') }}" alt="{{ $category->name }}" class="img-fluid w-100">
                        @endif
                    </div>
                    <h3>{{ $category->name }}</h3>
                    @if($category->products_count > 0)
                    <p style="text-align: center" class="category_count">{{ $category->products_count }} Products</p>
                    @endif
                </a>
            </div>
            @empty
            <div class="text-center col-12">
                <div class="empty_category">
                    <img src="{{ asset('assets/images/empty-category.png') }}" alt="No Categories" class="mb-3 img-fluid">
                    <h3>No Categories Found</h3>
                    <p>Categories will be added soon. Please check back later.</p>
                </div>
            </div>
            @endforelse
        </div>

        @if($categories->hasPages())
        <div class="row">
            <div class="pagination_area">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mt_50">
                        {{-- Previous Page Link --}}
                        @if ($categories->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="far fa-arrow-left" aria-hidden="true"></i>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $categories->previousPageUrl() }}" rel="prev">
                                    <i class="far fa-arrow-left" aria-hidden="true"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                            @if ($page == $categories->currentPage())
                                <li class="page-item">
                                    <a class="page-link active" href="{{ $url }}">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</a>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($categories->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $categories->nextPageUrl() }}" rel="next">
                                    <i class="far fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="far fa-arrow-right" aria-hidden="true"></i>
                                </span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
        @endif
    </div>
</section>
<!--============================
    CATEGORY PAGE END
=============================-->

<x-footer />