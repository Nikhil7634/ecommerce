<x-header 
    title="Login - {{ config('app.name') }}"
    description="Login to your {{ config('app.name') }} account to access your dashboard and manage your profile easily."
    keywords="Login, Sign In, User Account, {{ config('app.name') }}"
    ogImage="{{ asset('assets/images/banner/login-og.jpg') }}"
>
    
</x-header>

<x-navbar />

<section class="sign_in mt_100 mb_100">
    <div class="container">
        <div class="row justify-content-center">
            <!-- Left Side Image -->
            <div class="col-xxl-3 col-lg-4 col-xl-4 d-none d-lg-block wow fadeInLeft" style="visibility: visible; animation-name: fadeInLeft;">
                <div class="sign_in_img">
                    <img src="{{ asset('assets/images/sign_in_img.jpg') }}" alt="Sign In" class="img-fluid w-100">
                </div>
            </div>

            <!-- Login Form -->
            <div class="col-xxl-4 col-lg-7 col-xl-6 col-md-10 wow fadeInRight" style="visibility: visible; animation-name: fadeInRight;">
                <div class="sign_in_form">
                    <h3>Sign In to Continue 👋</h3>

                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="single_input">
                                    <label>Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="example@Zenis.com">
                                    @error('email')
                                        <p class="mt-1 text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <div class="single_input">
                                    <label>Password</label>
                                    <input type="password" name="password" required placeholder="********">
                                    @error('password')
                                        <p class="mt-1 text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="forgot">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                        <label class="form-check-label" for="remember">
                                            Remember Me
                                        </label>
                                    </div>
                                    <a href="{{ route('password.request') }}">Forgot Password?</a>
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <button type="submit" class="common_btn">
                                    Sign In <i class="fas fa-long-arrow-right" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <p class="dont_account">Don't have an account? 
                        <a href="{{ route('register') }}">Sign Up</a>
                    </p>

                    <p class="or">or</p>

                    <!-- ✅ Social Logins -->
                    <ul>
                        <li>
                            <a href="{{ url('/auth/google/redirect') }}">
                                <span>
                                    <img src="{{ asset('assets/images/google_logo.png') }}" alt="Google" class="img-fluid w-100">
                                </span>
                                Google
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/auth/facebook/redirect') }}">
                                <span>
                                    <img src="{{ asset('assets/images/fb_logo.png') }}" alt="Facebook" class="img-fluid w-100">
                                </span>
                                Facebook
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<x-footer />
