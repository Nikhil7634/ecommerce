<x-header 
    title="Become a Seller - eCommerce"
    description="Shop the latest electronics, fashion, and home essentials at Valtara with fast delivery and best prices."
    keywords="Valtara, online store, eCommerce, electronics, clothing, deals"
    ogImage="{{ asset('assets/images/banner/home-og.jpg') }}"
></x-header>

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
                        <h1>Become a Seller</h1>
                        <ul>
                            <li><a href="{{ route('home') }}"><i class="fal fa-home-lg"></i> Home</a></li>
                            <li><a href="#">Become a Seller</a></li>
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

<!-- Add this after <form> tag -->
@if($errors->any())
<div class="alert alert-danger">
    <h5>Please fix the following errors:</h5>
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<!--============================
    BECOME A VENDOR START
=============================-->
<section class="beacome_vendor mt_100 mb_100">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 wow fadeInLeft">
                <div class="become_vendor_text">
                    <h3>Vendor Eligibility</h3>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Veniam animi recusandae nihil vero
                        saepe sed neque. Incidunt, facilis quam? Alias, quam optio animi possimus impedit autem
                        nulla fugit earum deserunt odit eveniet. Laboriosam dolorum sapiente quis accusamus
                        exercitationem iusto mollitia.</p>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Veniam animi recusandae nihil vero
                        saepe sed neque. Incidunt, facilis quam? Alias, quam optio animi possimus impedit autem
                        nulla fugit earum.</p>
                    <ul>
                        <li>Must be a registered business or individual with legal selling rights.</li>
                        <li>Must provide valid identification and business registration documents (if applicable).</li>
                        <li>Must comply with local and international laws regarding e-commerce and online selling.</li>
                    </ul>
                    <h3>2. Product Listing Guidelines</h3>
                    <ul>
                        <li>Vendors can list only approved product categories as per platform policy.</li>
                        <li>Product descriptions must be accurate, detailed, and not misleading.</li>
                        <li>Images must be high quality, without watermarks, and match the actual product.</li>
                        <li>No counterfeit, prohibited, or illegal items are allowed.</li>
                        <li>Digital products must be original and free from copyright violations.</li>
                    </ul>
                    <!-- All your original content remains 100% unchanged -->
                </div>
            </div>

            <div class="col-lg-6 wow fadeInRight">
                <div id="sticky_sidebar">
                    <div class="become_vendor_form">
                        <h3>sign up As vendor</h3>

                        <!-- ONLY CHANGE: action + @csrf + name attributes -->
                        <form action="{{ route('become.seller.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-lg-12 col-xl-6 col-md-6">
                                    <div class="single_input">
                                        <label>First name</label>
                                        <input type="text" name="first_name" placeholder="Jhon" value="{{ old('first_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-6 col-md-6">
                                    <div class="single_input">
                                        <label>last name</label>
                                        <input type="text" name="last_name" placeholder="Deo" value="{{ old('last_name') }}" required>
                                    </div>
                                </div>

                                <!-- NEW: Business Name -->
                                <div class="col-lg-12 col-xl-12 col-md-12">
                                    <div class="single_input">
                                        <label>Business Name</label>
                                        <input type="text" name="business_name" placeholder="e.g. Nikhil Store" value="{{ old('business_name') }}" required>
                                    </div>
                                </div>

                                <!-- NEW: GST No. -->
                                <div class="col-lg-12 col-xl-12 col-md-12">
                                    <div class="single_input">
                                        <label>GST No.</label>
                                        <input type="text" name="gst_no" placeholder="22AAAAA0000A1Z5" value="{{ old('gst_no') }}" required>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-xl-6 col-md-6">
                                    <div class="single_input">
                                        <label>Your phone</label>
                                        <input type="text" name="phone" placeholder="+96542145874844" value="{{ old('phone') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-6 col-md-6">
                                    <div class="single_input">
                                        <label>Your Email</label>
                                        <input type="email" name="email" placeholder="example@Zenis.com" value="{{ old('email') }}" required>
                                    </div>
                                </div>

                                <!-- Country = India Only -->
                                <div class="col-lg-12 col-xl-6 col-md-6">
                                    <div class="single_input">
                                        <label>Country</label>
                                        <select name="country" class="select_2" required>
                                            <option value="India" selected>India</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- All Indian States -->
                                <div class="col-lg-12 col-xl-6 col-md-6">
                                    <div class="single_input">
                                        <label>state</label>
                                        <select name="state" class="select_2" required>
                                            <option value="">Select State</option>
                                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                            <option value="Assam">Assam</option>
                                            <option value="Bihar">Bihar</ ;option>
                                            <option value="Chhattisgarh">Chhattisgarh</option>
                                            <option value="Goa">Goa</option>
                                            <option value="Gujarat">Gujarat</option>
                                            <option value="Haryana">Haryana</option>
                                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                                            <option value="Jharkhand">Jharkhand</option>
                                            <option value="Karnataka">Karnataka</option>
                                            <option value="Kerala">Kerala</option>
                                            <option value="Madhya Pradesh">Madhya Pradesh</option>
                                            <option value="Maharashtra">Maharashtra</option>
                                            <option value="Manipur">Manipur</option>
                                            <option value="Meghalaya">Meghalaya</option>
                                            <option value="Mizoram">Mizoram</option>
                                            <option value="Nagaland">Nagaland</option>
                                            <option value="Odisha">Odisha</option>
                                            <option value="Punjab">Punjab</option>
                                            <option value="Rajasthan">Rajasthan</option>
                                            <option value="Sikkim">Sikkim</option>
                                            <option value="Tamil Nadu">Tamil Nadu</option>
                                            <option value="Telangana">Telangana</option>
                                            <option value="Tripura">Tripura</option>
                                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                                            <option value="Uttarakhand">Uttarakhand</option>
                                            <option value="West Bengal">West Bengal</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- City (Manual) -->
                                <div class="col-lg-12 col-xl-6 col-md-6">
                                    <div class="single_input">
                                        <label>City</label>
                                        <input type="text" name="city" placeholder="Mumbai" value="{{ old('city') }}" required>
                                    </div>
                                </div>

                                <!-- Zip (Manual) -->
                                <div class="col-lg-12 col-xl-6 col-md-6">
                                    <div class="single_input">
                                        <label>zip</label>
                                        <input type="text" name="zip" placeholder="400001" value="{{ old('zip') }}" required>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-xl-12 col-md-12">
                                    <div class="single_input">
                                        <label>Address</label>
                                        <textarea name="address" rows="5" placeholder="Write your address" required>{{ old('address') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-xl-12 col-md-12">
                                    <div class="single_input">
                                        <label>Upload Document (GST Certificate / ID Proof)</label>
                                        <input type="file" name="attachment" required>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-xl-6 col-md-6">
                                    <div class="single_input">
                                        <label>Password</label>
                                        <input type="password" name="password" placeholder="******" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-6 col-md-6">
                                    <div class="single_input">
                                        <label>Confirm Password</label>
                                        <input type="password" name="password_confirmation" placeholder="******" required>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-xl-12 col-md-12">
                                    <div class="become_vendor_form_chek">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="terms" value="1" id="flexCheckDefault" required>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                I agree that I have read and accepted the <a href="#">Terms and Condition</a> and <a href="#">Privacy Policy</a>.
                                            </label>
                                        </div>
                                        <button type="submit" class="common_btn">Sign Up <i class="fas fa-long-arrow-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--============================
    BECOME A VENDOR END
=============================-->

<x-footer />