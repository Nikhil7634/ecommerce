 
 <x-header 
    title="Profile Information -  eCommerce"
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
                                <li><a href="#">Profile Information</a></li>
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
                <div class="col-xl-9 wow fadeInRight" style="visibility: visible; animation-name: fadeInRight;">
                    <div class="dashboard_content mt_100">
                        <h3 class="dashboard_title">
                            Profile Information 
                            <a class="common_btn" href="{{ route('buyer.profile.edit') }}">Edit</a>
                        </h3>

                        <div class="dashboard_profile_info_list">
                            <ul>
                                <li><span>Name:</span> {{ $user->name ?? 'Not added yet' }}</li>
                                <li><span>Email:</span> {{ $user->email ?? 'Not added yet' }}</li>
                                <li><span>Phone:</span> {{ $user->phone ?: 'Not added yet' }}</li>
                                <li><span>Country:</span> {{ $user->country ?: 'Not added yet' }}</li>
                                <li><span>City:</span> {{ $user->city ?: 'Not added yet' }}</li>
                                <li><span>Zip:</span> {{ $user->zip ?: 'Not added yet' }}</li>
                                <li><span>Address:</span> {{ $user->address ?: 'Not added yet' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>
    

<x-footer />
  