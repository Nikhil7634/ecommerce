 
 <x-header 
    title="Profile Edit -  eCommerce"
    description="Shop the latest electronics, fashion, and home essentials at Valtara with fast delivery and best prices."
    keywords="Valtara, online store, eCommerce, electronics, clothing, deals"
    ogImage="{{ asset('assets/images/banner/home-og.jpg') }}"
>
    
    
</x-header>

 

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
                            <h1>My Account</h1>
                            <ul>
                                <li><a href="#"><i class="fal fa-home-lg"></i> Home</a></li>
                                <li><a href="#">Profile Edit</a></li>
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
        DSHBOARD START
    =============================-->
    <section class="dashboard mb_100">
        <div class="container">
            <div class="row">
                <x-aside />
                <div class="col-lg-9 wow fadeInRight" data-select2-id="select2-data-18-e8jw" style="visibility: visible; animation-name: fadeInRight;">
                    <div class="dashboard_content mt_100" data-select2-id="select2-data-17-9cu8">
                        <h3 class="dashboard_title">Edit Information <a class="common_btn cancel_edit" href="dashboard_profile.html">cancel</a></h3>
                        <div class="dashboard_profile_info_edit">
                            <form class="info_edit_form" data-select2-id="select2-data-16-hg01">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="single_input">
                                            <label>Name</label>
                                            <input type="text" placeholder="Jhon Deo">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="single_input">
                                            <label>email</label>
                                            <input type="email" placeholder="example@Zenis.com">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="single_input">
                                            <label>Phone</label>
                                            <input type="text" placeholder="+964574621675658">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="single_input">
                                            <label>Country</label>
                                            <select class="select_2 select2-hidden-accessible" data-select2-id="select2-data-4-bfao" tabindex="-1" aria-hidden="true">
                                                <option value="#" data-select2-id="select2-data-6-p6fj">Singapore</option>
                                                <option value="#">Japan</option>
                                                <option value="#">Korea</option>
                                                <option value="#">Thailand</option>
                                                <option value="#">Kanada</option>
                                            </select>
                                         </div>
                                    </div>
                                    <div class="col-md-6" data-select2-id="select2-data-15-ll3u">
                                        <div class="single_input" data-select2-id="select2-data-14-26gj">
                                            <label>City</label>
                                            <select class="select_2 select2-hidden-accessible" data-select2-id="select2-data-7-ubd3" tabindex="-1" aria-hidden="true">
                                                <option value="#" data-select2-id="select2-data-9-w3rw">Tokyo</option>
                                                <option value="#" data-select2-id="select2-data-24-qcui">Japan</option>
                                                <option value="#" data-select2-id="select2-data-25-i66p">Korea</option>
                                                <option value="#" data-select2-id="select2-data-26-hve9">Thailand</option>
                                                <option value="#" data-select2-id="select2-data-27-cjb6">Kanada</option>
                                            </select> 
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="single_input">
                                            <label>state</label>
                                            <select class="select_2 select2-hidden-accessible" data-select2-id="select2-data-10-rxzs" tabindex="-1" aria-hidden="true">
                                                <option value="#" data-select2-id="select2-data-12-j5d0">Korea</option>
                                                <option value="#">Singapore</option>
                                                <option value="#">Japan</option>
                                                <option value="#">Thailand</option>
                                                <option value="#">Kanada</option>
                                            </select>
                                         </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="single_input">
                                            <label>Address</label>
                                            <textarea rows="6" placeholder="441, 4th street, Washington DC, USA"></textarea>
                                        </div>
                                        <button type="submit" class="common_btn">Update Profile <i class="fas fa-long-arrow-right" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>
    

<x-footer />
  