 <!--=========================
        FOOTER 2 START
    ==========================-->
  <footer class="footer_2 pt_100" style="background: url('{{ asset('assets/images/footer_2_bg_2.jpg') }}');">
    <div class="container">
        <div class="row justify-content-between">
            <!-- Logo + About -->
            <div class="col-xl-3 col-md-6 col-lg-3 wow fadeInUp" data-wow-delay=".7s">
                <div class="footer_2_logo_area">
                    <a class="footer_logo" href="{{ route('home') }}">
                        <img src="{{ asset('assets/images/footer_logo_2.png') }}" alt="Comtodeal" class="img-fluid w-100">
                    </a>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, distinctio molestiae error
                        ullam obcaecati dolorem inventore.</p>
                    <ul>
                        <li><span>Follow :</span></li>
                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fab fa-google-plus-g"></i></a></li>
                        <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                    </ul>
                </div>
            </div>

            <!-- Company Links -->
            <div class="col-xl-2 col-sm-6 col-md-4 col-lg-2 wow fadeInUp" data-wow-delay="1s">
                <div class="footer_link">
                    <h3>Company</h3>
                    <ul>
                        <li><a href="{{ route('home') }}#about">About Us</a></li>
                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        <li><a href="#">Affiliate</a></li>
                        <li><a href="#">Career</a></li>
                        <li><a href="#">Latest News</a></li>
                    </ul>
                </div>
            </div>

            <!-- Categories -->
            <div class="col-xl-2 col-sm-6 col-md-4 col-lg-2 wow fadeInUp" data-wow-delay="1.3s">
                <div class="footer_link">
                    <h3>Category</h3>
                    <ul>
                        <li><a href="{{ route('category') }}">Men’s Fashion</a></li>
                        <li><a href="{{ route('category') }}">Denim Collection</a></li>
                        <li><a href="{{ route('category') }}">Western Wear</a></li>
                        <li><a href="{{ route('category') }}">Sport Wear</a></li>
                        <li><a href="{{ route('category') }}">Fashion Jewellery</a></li>
                    </ul>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-xl-2 col-sm-6 col-md-4 col-lg-2 wow fadeInUp" data-wow-delay="1.6s">
                <div class="footer_link">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="{{ url('/privacy-policy') }}">Privacy Policy</a></li>
                        <li><a href="{{ url('/terms-and-conditions') }}">Terms & Conditions</a></li>
                        <li><a href="{{ url('/return-policy') }}">Return Policy</a></li>
                        <li><a href="{{ url('/faq') }}">FAQ's</a></li>
                        <li><a href="{{ route('become.seller') }}">Become a Vendor</a></li>
                    </ul>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-xl-3 col-sm-6 col-md-4 col-lg-3 wow fadeInUp" data-wow-delay="1.9s">
                <div class="footer_link footer_logo_area">
                    <h3>Contact Us</h3>
                    <p>It is a long established fact that a reader will be distracted by the readable content.</p>
                    <span>
                        <b><img src="{{ asset('assets/images/location_icon_white.png') }}" alt="Map" class="img-fluid"></b>
                        37 W 24th St, New York, NY
                    </span>
                    <span>
                        <b><img src="{{ asset('assets/images/phone_icon_white.png') }}" alt="Call" class="img-fluid"></b>
                        <a href="tel:+123324587939">+123 324 5879 39</a>
                    </span>
                    <span>
                        <b><img src="{{ asset('assets/images/mail_icon_white.png') }}" alt="Mail" class="img-fluid"></b>
                        <a href="mailto:info@comtodeal.com">info@comtodeal.com</a>
                    </span>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="row">
            <div class="col-12">
                <div class="footer_copyright mt_75">
                    <p>Copyright © <b>Comtodeal</b> {{ date('Y') }}. All rights reserved.</p>
                    <ul class="payment">
                        <li>Payment by :</li>
                        <li><img src="{{ asset('assets/images/footer_payment_icon_1.jpg') }}" alt="Payment" class="img-fluid w-100"></li>
                        <li><img src="{{ asset('assets/images/footer_payment_icon_2.jpg') }}" alt="Payment" class="img-fluid w-100"></li>
                        <li><img src="{{ asset('assets/images/footer_payment_icon_3.jpg') }}" alt="Payment" class="img-fluid w-100"></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

    <!--=========================
        FOOTER 2 END
    ==========================-->


    <!--==========================
        SCROLL BUTTON START
    ===========================-->
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <!--==========================
        SCROLL BUTTON END
    ===========================-->


   <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/Font-Awesome.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.countup.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/simplyCountdown.js') }}"></script>
    <script src="{{ asset('assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('assets/js/venobox.min.js') }}"></script>
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.marquee.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.pwstabs.min.js') }}"></script>
    <script src="{{ asset('assets/js/scroll_button.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.youtube-background.min.js') }}"></script>
    <script src="{{ asset('assets/js/range_slider.js') }}"></script>
    <script src="{{ asset('assets/js/sticky_sidebar.js') }}"></script>
    <script src="{{ asset('assets/js/multiple-image-video.js') }}"></script>
    <script src="{{ asset('assets/js/animated_barfiller.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>


</body>


 </html>