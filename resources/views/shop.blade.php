 
 <x-header 
    title="Profile Information -  eCommerce"
    description="Shop the latest electronics, fashion, and home essentials at Valtara with fast delivery and best prices."
    keywords="Valtara, online store, eCommerce, electronics, clothing, deals"
    ogImage="{{ asset('assets/images/banner/home-og.jpg') }}"
>
    
    
</x-header>

 

<x-navbar />


   
    <section class="page_banner" style="background: url('{{ asset('assets/images/page_banner_bg.jpg') }}');">
        <div class="page_banner_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="page_banner_text wow fadeInUp">
                            <h1>Shop</h1>
                            <ul>
                                <li><a href="#"><i class="fal fa-home-lg"></i> Home</a></li>
                                <li><a href="#">Shop</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=========================
        PAGE BANNER START
    ==========================-->


     <!--============================
        SHOP PAGE START
    =============================-->
    <section class="shop_page mt_100 mb_100">
        <div class="container">
            <div class="row">
                <div class="col-xxl-2 col-lg-4 col-xl-3">
                    <div id="sticky_sidebar">
                        <div class="shop_filter_btn d-lg-none"> Filter </div>
                        <div class="shop_filter_area">
                            <div class="sidebar_range">
                                <h3>Price Range</h3>
                                <div class="range_slider"></div>
                            </div>
                            <div class="sidebar_status">
                                <h3>Product Status</h3>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        On sale
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                                    <label class="form-check-label" for="flexCheckChecked">
                                        In Stock
                                    </label>
                                </div>
                            </div>
                            <div class="sidebar_category">
                                <h3>Categories</h3>
                                <ul>
                                    <li>
                                        <a href="#">
                                            Men’s Fashion
                                            <span>20</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            western wear
                                            <span>09</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            skin care
                                            <span>04</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            sport wear
                                            <span>13</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            fashion jewellery
                                            <span>36</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            beauty Care
                                            <span>22</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            Makeoup Tools
                                            <span>16</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            Winter collention
                                            <span>27</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            Men’s Fashion
                                            <span>20</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            western wear
                                            <span>09</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            skin care
                                            <span>04</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            sport wear
                                            <span>13</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            fashion jewellery
                                            <span>36</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            beauty Care
                                            <span>22</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            Makeoup Tools
                                            <span>16</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            Winter collention
                                            <span>27</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="sidebar_rating">
                                <h3>Rating</h3>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck4">
                                    <label class="form-check-label" for="defaultCheck4">
                                        <i class="fas fa-star" aria-hidden="true"></i>
                                        5 star
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck5">
                                    <label class="form-check-label" for="defaultCheck5">
                                        <i class="fas fa-star" aria-hidden="true"></i>
                                        4 star or above
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck6">
                                    <label class="form-check-label" for="defaultCheck6">
                                        <i class="fas fa-star" aria-hidden="true"></i>
                                        3 star or above
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck7">
                                    <label class="form-check-label" for="defaultCheck7">
                                        <i class="fas fa-star" aria-hidden="true"></i>
                                        2 star or above
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck8">
                                    <label class="form-check-label" for="defaultCheck8">
                                        <i class="fas fa-star" aria-hidden="true"></i>
                                        1 star or above
                                    </label>
                                </div>
                            </div>
                            <div class="sidebar_related_product">
                                <h3>Top Rated Products</h3>
                                <ul>
                                    <li>
                                        <a href="shop_details.html" class="img">
                                            <img src="assets/images/product_18.png" alt="Product" class="img-fluid">
                                        </a>
                                        <div class="text">
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                                <i class="far fa-star"></i>
                                                <span>(29)</span>
                                            </p>
                                            <a class="title" href="shop_details.html">Kid's Western Party Dress</a>
                                            <p class="price">$59.00</p>
                                        </div>
                                    </li>
                                    <li>
                                        <a href="shop_details.html" class="img">
                                            <img src="assets/images/product_23.png" alt="Product" class="img-fluid">
                                        </a>
                                        <div class="text">
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                                <i class="far fa-star"></i>
                                                <span>(12)</span>
                                            </p>
                                            <a class="title" href="shop_details.html">Kid's dresses for summer</a>
                                            <p class="price">$54.00</p>
                                        </div>
                                    </li>
                                    <li>
                                        <a href="shop_details.html" class="img">
                                            <img src="assets/images/product_13.png" alt="Product" class="img-fluid">
                                        </a>
                                        <div class="text">
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                                <i class="far fa-star"></i>
                                                <span>(09)</span>
                                            </p>
                                            <a class="title" href="shop_details.html">Sharee Petticoat For Women</a>
                                            <p class="price">$28.00</p>
                                        </div>
                                    </li>
                                    <li>
                                        <a href="shop_details.html" class="img">
                                            <img src="assets/images/product_7.png" alt="Product" class="img-fluid">
                                        </a>
                                        <div class="text">
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                                <i class="far fa-star"></i>
                                                <span>(35)</span>
                                            </p>
                                            <a class="title" href="shop_details.html">Denim 2 Quarter Pant</a>
                                            <p class="price">$54.00</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-10 col-lg-8 col-xl-9">
                    <div class="product_page_top">
                        <div class="row">
                            <div class="col-4 col-xl-6 col-md-6">
                                <div class="product_page_top_button">
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-home" type="button" role="tab"
                                                aria-controls="nav-home" aria-selected="true">
                                                <i class="fas fa-th"></i>
                                            </button>
                                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-profile" type="button" role="tab"
                                                aria-controls="nav-profile" aria-selected="false">
                                                <i class="fas fa-list-ul"></i>
                                            </button>
                                        </div>
                                    </nav>
                                    <p>Showing 1–14 of 26 results</p>
                                </div>
                            </div>
                            <div class="col-8 col-xl-6 col-md-6">
                                <ul class="product_page_sorting">
                                    <li>
                                        <select class="select_js">
                                            <option>Default Sorting</option>
                                            <option>Low to Hight</option>
                                            <option>High to Low</option>
                                            <option>New Added</option>
                                            <option>On Sale</option>
                                        </select>
                                    </li>
                                    <li>
                                        <select class="select_js show">
                                            <option>Show: 12</option>
                                            <option>Show: 16</option>
                                            <option>Show: 20</option>
                                            <option>Show: 24</option>
                                            <option>Show: 26</option>
                                        </select>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                            aria-labelledby="nav-home-tab" tabindex="0">
                            <div class="row">
                                <div class="col-xxl-3 col-6 col-md-4 col-lg-6 col-xl-4 wow fadeInUp">
                                    <div class="product_item_2 product_item">
                                        <div class="product_img">
                                            <img src="assets/images/product_23.png" alt="Product"
                                                class="img-fluid w-100">
                                            <ul class="discount_list">
                                                <li class="new"> new</li>
                                            </ul>
                                            <ul class="btn_list">
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/compare_icon_white.svg" alt="Compare"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/love_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/cart_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="product_text">
                                            <a class="title" href="shop_details.html"> Kid's dresses for summer</a>
                                            <p class="price">$70.00</p>
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <span>(44 reviews)</span>
                                            </p>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-6 col-md-4 col-lg-6 col-xl-4 wow fadeInUp">
                                    <div class="product_item_2 product_item">
                                        <div class="product_img">
                                            <img src="assets/images/product_18.png" alt="Product"
                                                class="img-fluid w-100">
                                            <ul class="discount_list">
                                                <li class="new"> new</li>
                                            </ul>
                                            <ul class="btn_list">
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/compare_icon_white.svg" alt="Compare"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/love_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/cart_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="product_text">
                                            <a class="title" href="shop_details.html">Full Sleeve Hoodie Jacket</a>
                                            <p class="price">$88.00 </p>
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <span>(20 reviews)</span>
                                            </p>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-6 col-md-4 col-lg-6 col-xl-4 wow fadeInUp">
                                    <div class="product_item_2 product_item">
                                        <div class="product_img">
                                            <img src="assets/images/product_7.png" alt="Product"
                                                class="img-fluid w-100">
                                            <ul class="discount_list">
                                                <li class="new"> new</li>
                                            </ul>
                                            <ul class="btn_list">
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/compare_icon_white.svg" alt="Compare"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/love_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="product_text">
                                            <a class="title" href="shop_details.html">Denim 2 Quarter Pant</a>
                                            <p class="price">$40.00</p>
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                                <i class="far fa-star"></i>
                                                <span>(20 reviews)</span>
                                            </p>
                                            
                                        </div>
                                        <div class="out_of_stock">
                                            <p>out of stock</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-6 col-md-4 col-lg-6 col-xl-4 wow fadeInUp">
                                    <div class="product_item_2 product_item">
                                        <div class="product_img">
                                            <img src="assets/images/product_9.png" alt="Product"
                                                class="img-fluid w-100">
                                            <ul class="discount_list">
                                                <li class="discount"> <b>-</b> 45%</li>
                                            </ul>
                                            <ul class="btn_list">
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/compare_icon_white.svg" alt="Compare"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/love_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/cart_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="product_text">
                                            <a class="title" href="shop_details.html">Men's Denim combo set</a>
                                            <p class="price">$47.00 <del>$50.00</del></p>
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <span>(17 reviews)</span>
                                            </p>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-6 col-md-4 col-lg-6 col-xl-4 wow fadeInUp">
                                    <div class="product_item_2 product_item">
                                        <div class="product_img">
                                            <img src="assets/images/product_10.png" alt="Product"
                                                class="img-fluid w-100">
                                            <ul class="btn_list">
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/compare_icon_white.svg" alt="Compare"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/love_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/cart_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="product_text">
                                            <a class="title" href="shop_details.html">Women's Western Party Dress</a>
                                            <p class="price">$43.00</p>
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <span>(22 reviews)</span>
                                            </p>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-6 col-md-4 col-lg-6 col-xl-4 wow fadeInUp">
                                    <div class="product_item_2 product_item">
                                        <div class="product_img">
                                            <img src="assets/images/product_11.png" alt="Product"
                                                class="img-fluid w-100">
                                            <ul class="discount_list">
                                                <li class="new"> new</li>
                                                <li class="discount"> <b>-</b> 75%</li>
                                            </ul>
                                            <ul class="btn_list">
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/compare_icon_white.svg" alt="Compare"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/love_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/cart_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="product_text">
                                            <a class="title" href="shop_details.html">Kid's Western Party Dress</a>
                                            <p class="price">$75.00 <del>$69.00</del></p>
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                                <i class="far fa-star"></i>
                                                <span>(58 reviews)</span>
                                            </p>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-6 col-md-4 col-lg-6 col-xl-4 wow fadeInUp">
                                    <div class="product_item_2 product_item">
                                        <div class="product_img">
                                            <img src="assets/images/product_17.png" alt="Product"
                                                class="img-fluid w-100">
                                            <ul class="btn_list">
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/compare_icon_white.svg" alt="Compare"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/love_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/cart_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="product_text">
                                            <a class="title" href="shop_details.html">Denim Jeans Pants For Men</a>
                                            <p class="price">$71.00</p>
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <span>(20 reviews)</span>
                                            </p>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-6 col-md-4 col-lg-6 col-xl-4 wow fadeInUp">
                                    <div class="product_item_2 product_item">
                                        <div class="product_img">
                                            <img src="assets/images/product_12.png" alt="Product"
                                                class="img-fluid w-100">
                                            <ul class="btn_list">
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/compare_icon_white.svg" alt="Compare"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/love_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/cart_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="product_text">
                                            <a class="title" href="shop_details.html">Half Sleeve Tops for Women</a>
                                            <p class="price">$29.00</p>
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                                <i class="far fa-star"></i>
                                                <span>(44 reviews)</span>
                                            </p>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-6 col-md-4 col-lg-6 col-xl-4 wow fadeInUp">
                                    <div class="product_item_2 product_item">
                                        <div class="product_img">
                                            <img src="assets/images/product_13.png" alt="Product"
                                                class="img-fluid w-100">
                                            <ul class="btn_list">
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/compare_icon_white.svg" alt="Compare"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/love_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/cart_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="product_text">
                                            <a class="title" href="shop_details.html">Sharee Petticoat For Women</a>
                                            <p class="price">$56.00 </p>
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <span>(98 reviews)</span>
                                            </p>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-6 col-md-4 col-lg-6 col-xl-4 wow fadeInUp">
                                    <div class="product_item_2 product_item">
                                        <div class="product_img">
                                            <img src="assets/images/product_14.png" alt="Product"
                                                class="img-fluid w-100">
                                            <ul class="discount_list">
                                                <li class="discount"> <b>-</b> 49%</li>
                                            </ul>
                                            <ul class="btn_list">
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/compare_icon_white.svg" alt="Compare"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/love_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/cart_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="product_text">
                                            <a class="title" href="shop_details.html">Jeans Pants For Women</a>
                                            <p class="price">$49.00 <del>$39.00</del></p>
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                                <i class="far fa-star"></i>
                                                <span>(44 reviews)</span>
                                            </p>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-6 col-md-4 col-lg-6 col-xl-4 wow fadeInUp">
                                    <div class="product_item_2 product_item">
                                        <div class="product_img">
                                            <img src="assets/images/product_16.png" alt="Product"
                                                class="img-fluid w-100">
                                            <ul class="btn_list">
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/compare_icon_white.svg" alt="Compare"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/love_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/cart_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="product_text">
                                            <a class="title" href="shop_details.html">cherry fabric western tops</a>
                                            <p class="price">$33.00</p>
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                                <i class="far fa-star"></i>
                                                <span>(20 reviews)</span>
                                            </p>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-6 col-md-4 col-lg-6 col-xl-4 wow fadeInUp">
                                    <div class="product_item_2 product_item">
                                        <div class="product_img">
                                            <img src="assets/images/product_15.png" alt="Product"
                                                class="img-fluid w-100">
                                            <ul class="discount_list">
                                                <li class="new"> new</li>
                                                <li class="discount"> <b>-</b> 75%</li>
                                            </ul>
                                            <ul class="btn_list">
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/compare_icon_white.svg" alt="Compare"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/love_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/cart_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="product_text">
                                            <a class="title" href="shop_details.html">Denim Shirt For Men</a>
                                            <p class="price">$40.00 <del>$48.00</del></p>
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                                <i class="far fa-star"></i>
                                                <span>(20 reviews)</span>
                                            </p>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-6 col-md-4 col-lg-6 col-xl-4 wow fadeInUp">
                                    <div class="product_item_2 product_item">
                                        <div class="product_img">
                                            <img src="assets/images/product_18.png" alt="Product"
                                                class="img-fluid w-100">
                                            <ul class="discount_list">
                                                <li class="new"> new</li>
                                            </ul>
                                            <ul class="btn_list">
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/compare_icon_white.svg" alt="Compare"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/love_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/cart_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="product_text">
                                            <a class="title" href="shop_details.html">Full Sleeve Hoodie Jacket</a>
                                            <p class="price">$88.00 </p>
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <span>(20 reviews)</span>
                                            </p>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-6 col-md-4 col-lg-6 col-xl-4 wow fadeInUp">
                                    <div class="product_item_2 product_item">
                                        <div class="product_img">
                                            <img src="assets/images/product_19.png" alt="Product"
                                                class="img-fluid w-100">
                                            <ul class="discount_list">
                                                <li class="new"> new</li>
                                            </ul>
                                            <ul class="btn_list">
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/compare_icon_white.svg" alt="Compare"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/love_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/cart_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="product_text">
                                            <a class="title" href="shop_details.html">Men's premium formal shirt</a>
                                            <p class="price">$46.00</p>
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <span>(17 reviews)</span>
                                            </p>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-6 col-md-4 col-lg-6 col-xl-4 wow fadeInUp">
                                    <div class="product_item_2 product_item">
                                        <div class="product_img">
                                            <img src="assets/images/product_20.png" alt="Product"
                                                class="img-fluid w-100">
                                            <ul class="discount_list">
                                                <li class="new"> new</li>
                                            </ul>
                                            <ul class="btn_list">
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/compare_icon_white.svg" alt="Compare"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/love_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/cart_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="product_text">
                                            <a class="title" href="shop_details.html">cherry fabric western tops</a>
                                            <p class="price">$46.00</p>
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                                <i class="far fa-star"></i>
                                                <span>(22 reviews)</span>
                                            </p>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-6 col-md-4 col-lg-6 col-xl-4 wow fadeInUp">
                                    <div class="product_item_2 product_item">
                                        <div class="product_img">
                                            <img src="assets/images/product_4.png" alt="Product"
                                                class="img-fluid w-100">
                                            <ul class="discount_list">
                                                <li class="new"> new</li>
                                            </ul>
                                            <ul class="btn_list">
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/compare_icon_white.svg" alt="Compare"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/love_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <img src="assets/images/cart_icon_white.svg" alt="Love"
                                                            class="img-fluid">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="product_text">
                                            <a class="title" href="shop_details.html">Comfortable Sports Sneakers</a>
                                            <p class="price">$75.00</p>
                                            <p class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <span>(58 reviews)</span>
                                            </p>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="pagination_area">
                                    <nav aria-label="...">
                                        <ul class="pagination justify-content-start mt_50">
                                            <li class="page-item">
                                                <a class="page-link" href="#">
                                                    <i class="far fa-arrow-left"></i>
                                                </a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link active" href="#">01</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">02</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">03</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">
                                                    <i class="far fa-arrow-right"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"
                            tabindex="0">
                            <div class="row">
                                <div class="col-6 col-xxl-10 col-sm-12">
                                    <div class="product_list_item product_item_2 product_item">
                                        <div class=" row align-items-center">
                                            <div class="col-md-5 col-sm-6 col-xxl-4">
                                                <div class="product_img">
                                                    <img src="assets/images/product_23.png" alt="Product"
                                                        class="img-fluid w-100">
                                                    <ul class="discount_list">
                                                        <li class="new"> new</li>
                                                    </ul>
                                                    <ul class="btn_list">
                                                        <li>
                                                            <a href="#">
                                                                <img src="assets/images/compare_icon_white.svg"
                                                                    alt="Compare" class="img-fluid">
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <img src="assets/images/love_icon_white.svg" alt="Love"
                                                                    class="img-fluid">
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-7 col-sm-6 col-xxl-8">
                                                <div class="product_text">
                                                    <a class="title" href="shop_details.html">Full Sleeve Hoodie
                                                        Jacket</a>
                                                    <p class="rating">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <span>(20 reviews)</span>
                                                    </p>
                                                    <p class="price">$88.00</p>
                                                     
                                                    <p class="short_description">Lorem ipsum dolor sit amet consectetur,
                                                        adipisicing elit. Exercitationem inventore libero accusantium ex
                                                        ipsam, provident voluptas facere nemo, quas assumenda
                                                        reprehenderit nihil ratione quaerat ad.</p>
                                                    <a class="common_btn" href="shop_details.html">add to cart <i
                                                            class="fas fa-long-arrow-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-xxl-10 col-sm-12">
                                    <div class="product_list_item product_item_2 product_item">
                                        <div class=" row align-items-center">
                                            <div class="col-md-5 col-sm-6 col-xxl-4">
                                                <div class="product_img">
                                                    <img src="assets/images/product_7.png" alt="Product"
                                                        class="img-fluid w-100">
                                                    <ul class="btn_list">
                                                        <li>
                                                            <a href="#">
                                                                <img src="assets/images/compare_icon_white.svg"
                                                                    alt="Compare" class="img-fluid">
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <img src="assets/images/love_icon_white.svg" alt="Love"
                                                                    class="img-fluid">
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-7 col-sm-6 col-xxl-8">
                                                <div class="product_text">
                                                    <a class="title" href="shop_details.html">Denim 2 Quarter Pant</a>
                                                    <p class="rating">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <span>(93 reviews)</span>
                                                    </p>
                                                    <p class="price">$65.00</p>
                                                     
                                                    <p class="short_description">Lorem ipsum dolor sit amet consectetur,
                                                        adipisicing elit. Exercitationem inventore libero accusantium ex
                                                        ipsam, provident voluptas facere nemo, quas assumenda
                                                        reprehenderit nihil ratione quaerat ad.</p>
                                                    <a class="common_btn" href="shop_details.html">add to cart <i
                                                            class="fas fa-long-arrow-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-xxl-10 col-sm-12">
                                    <div class="product_list_item product_item_2 product_item">
                                        <div class=" row align-items-center">
                                            <div class="col-md-5 col-sm-6 col-xxl-4">
                                                <div class="product_img">
                                                    <img src="assets/images/product_9.png" alt="Product"
                                                        class="img-fluid w-100">
                                                    <ul class="btn_list">
                                                        <li>
                                                            <a href="#">
                                                                <img src="assets/images/compare_icon_white.svg"
                                                                    alt="Compare" class="img-fluid">
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <img src="assets/images/love_icon_white.svg" alt="Love"
                                                                    class="img-fluid">
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-7 col-sm-6 col-xxl-8">
                                                <div class="product_text">
                                                    <a class="title" href="shop_details.html">Men's Denim combo set</a>
                                                    <p class="rating">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <span>(16 reviews)</span>
                                                    </p>
                                                    <p class="price">$72.00</p>
                                                     
                                                    <p class="short_description">Lorem ipsum dolor sit amet consectetur,
                                                        adipisicing elit. Exercitationem inventore libero accusantium ex
                                                        ipsam, provident voluptas facere nemo, quas assumenda
                                                        reprehenderit nihil ratione quaerat ad.</p>
                                                    <a class="common_btn" href="shop_details.html">add to cart <i
                                                            class="fas fa-long-arrow-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-xxl-10 col-sm-12">
                                    <div class="product_list_item product_item_2 product_item">
                                        <div class=" row align-items-center">
                                            <div class="col-md-5 col-sm-6 col-xxl-4">
                                                <div class="product_img">
                                                    <img src="assets/images/product_17.png" alt="Product"
                                                        class="img-fluid w-100">
                                                    <ul class="discount_list">
                                                        <li class="new"> new</li>
                                                        <li class="discount"> <b>-</b> 75%</li>
                                                    </ul>
                                                    <ul class="btn_list">
                                                        <li>
                                                            <a href="#">
                                                                <img src="assets/images/compare_icon_white.svg"
                                                                    alt="Compare" class="img-fluid">
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <img src="assets/images/love_icon_white.svg" alt="Love"
                                                                    class="img-fluid">
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-7 col-sm-6 col-xxl-8">
                                                <div class="product_text">
                                                    <a class="title" href="shop_details.html">Denim Jeans Pants For
                                                        Men</a>
                                                    <p class="rating">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <span>(27 reviews)</span>
                                                    </p>
                                                    <p class="price">$50.00 <del>$60.00</del></p>
                                                     
                                                    <p class="short_description">Lorem ipsum dolor sit amet consectetur,
                                                        adipisicing elit. Exercitationem inventore libero accusantium ex
                                                        ipsam, provident voluptas facere nemo, quas assumenda
                                                        reprehenderit nihil ratione quaerat ad.</p>
                                                    <a class="common_btn" href="shop_details.html">add to cart <i
                                                            class="fas fa-long-arrow-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-xxl-10 col-sm-12">
                                    <div class="product_list_item product_item_2 product_item">
                                        <div class=" row align-items-center">
                                            <div class="col-md-5 col-sm-6 col-xxl-4">
                                                <div class="product_img">
                                                    <img src="assets/images/product_23.png" alt="Product"
                                                        class="img-fluid w-100">
                                                    <ul class="discount_list">
                                                        <li class="new"> new</li>
                                                    </ul>
                                                    <ul class="btn_list">
                                                        <li>
                                                            <a href="#">
                                                                <img src="assets/images/compare_icon_white.svg"
                                                                    alt="Compare" class="img-fluid">
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <img src="assets/images/love_icon_white.svg" alt="Love"
                                                                    class="img-fluid">
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-7 col-sm-6 col-xxl-8">
                                                <div class="product_text">
                                                    <a class="title" href="shop_details.html">Full Sleeve Hoodie
                                                        Jacket</a>
                                                    <p class="rating">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <span>(20 reviews)</span>
                                                    </p>
                                                    <p class="price">$88.00</p>
                                                     
                                                    <p class="short_description">Lorem ipsum dolor sit amet consectetur,
                                                        adipisicing elit. Exercitationem inventore libero accusantium ex
                                                        ipsam, provident voluptas facere nemo, quas assumenda
                                                        reprehenderit nihil ratione quaerat ad.</p>
                                                    <a class="common_btn" href="shop_details.html">add to cart <i
                                                            class="fas fa-long-arrow-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-xxl-10 col-sm-12">
                                    <div class="product_list_item product_item_2 product_item">
                                        <div class=" row align-items-center">
                                            <div class="col-md-5 col-sm-6 col-xxl-4">
                                                <div class="product_img">
                                                    <img src="assets/images/product_7.png" alt="Product"
                                                        class="img-fluid w-100">
                                                    <ul class="btn_list">
                                                        <li>
                                                            <a href="#">
                                                                <img src="assets/images/compare_icon_white.svg"
                                                                    alt="Compare" class="img-fluid">
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <img src="assets/images/love_icon_white.svg" alt="Love"
                                                                    class="img-fluid">
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-7 col-sm-6 col-xxl-8">
                                                <div class="product_text">
                                                    <a class="title" href="shop_details.html">Denim 2 Quarter Pant</a>
                                                    <p class="rating">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <span>(93 reviews)</span>
                                                    </p>
                                                    <p class="price">$65.00</p>
                                                     
                                                    <p class="short_description">Lorem ipsum dolor sit amet consectetur,
                                                        adipisicing elit. Exercitationem inventore libero accusantium ex
                                                        ipsam, provident voluptas facere nemo, quas assumenda
                                                        reprehenderit nihil ratione quaerat ad.</p>
                                                    <a class="common_btn" href="shop_details.html">add to cart <i
                                                            class="fas fa-long-arrow-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-xxl-10 col-sm-12">
                                    <div class="product_list_item product_item_2 product_item">
                                        <div class=" row align-items-center">
                                            <div class="col-md-5 col-sm-6 col-xxl-4">
                                                <div class="product_img">
                                                    <img src="assets/images/product_9.png" alt="Product"
                                                        class="img-fluid w-100">
                                                    <ul class="btn_list">
                                                        <li>
                                                            <a href="#">
                                                                <img src="assets/images/compare_icon_white.svg"
                                                                    alt="Compare" class="img-fluid">
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <img src="assets/images/love_icon_white.svg" alt="Love"
                                                                    class="img-fluid">
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-7 col-sm-6 col-xxl-8">
                                                <div class="product_text">
                                                    <a class="title" href="shop_details.html">Men's Denim combo set</a>
                                                    <p class="rating">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <span>(16 reviews)</span>
                                                    </p>
                                                    <p class="price">$72.00</p>
                                                     
                                                    <p class="short_description">Lorem ipsum dolor sit amet consectetur,
                                                        adipisicing elit. Exercitationem inventore libero accusantium ex
                                                        ipsam, provident voluptas facere nemo, quas assumenda
                                                        reprehenderit nihil ratione quaerat ad.</p>
                                                    <a class="common_btn" href="shop_details.html">add to cart <i
                                                            class="fas fa-long-arrow-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-xxl-10 col-sm-12">
                                    <div class="product_list_item product_item_2 product_item">
                                        <div class=" row align-items-center">
                                            <div class="col-md-5 col-sm-6 col-xxl-4">
                                                <div class="product_img">
                                                    <img src="assets/images/product_17.png" alt="Product"
                                                        class="img-fluid w-100">
                                                    <ul class="discount_list">
                                                        <li class="new"> new</li>
                                                        <li class="discount"> <b>-</b> 75%</li>
                                                    </ul>
                                                    <ul class="btn_list">
                                                        <li>
                                                            <a href="#">
                                                                <img src="assets/images/compare_icon_white.svg"
                                                                    alt="Compare" class="img-fluid">
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <img src="assets/images/love_icon_white.svg" alt="Love"
                                                                    class="img-fluid">
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-7 col-sm-6 col-xxl-8">
                                                <div class="product_text">
                                                    <a class="title" href="shop_details.html">Denim Jeans Pants For
                                                        Men</a>
                                                    <p class="rating">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <span>(27 reviews)</span>
                                                    </p>
                                                    <p class="price">$50.00 <del>$60.00</del></p>
                                                    
                                                    <p class="short_description">Lorem ipsum dolor sit amet consectetur,
                                                        adipisicing elit. Exercitationem inventore libero accusantium ex
                                                        ipsam, provident voluptas facere nemo, quas assumenda
                                                        reprehenderit nihil ratione quaerat ad.</p>
                                                    <a class="common_btn" href="shop_details.html">add to cart <i
                                                            class="fas fa-long-arrow-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="pagination_area">
                                    <nav aria-label="...">
                                        <ul class="pagination justify-content-start mt_50">
                                            <li class="page-item">
                                                <a class="page-link" href="#">
                                                    <i class="far fa-arrow-left"></i>
                                                </a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link active" href="#">01</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">02</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">03</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">
                                                    <i class="far fa-arrow-right"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        SHOP PAGE END
    =============================-->
 
    

<x-footer />
  