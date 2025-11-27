<x-header 
    title="Register - {{ config('app.name') }}"
    description="Create your account on {{ config('app.name') }} to access all features and manage your profile easily."
    keywords="Register, Sign up, Create Account, {{ config('app.name') }}"
    ogImage="{{ asset('assets/images/banner/register-og.jpg') }}"
>
    
</x-header>

<x-navbar />

<section class="sign_up mt_100 mb_100">
    <div class="container">
        <div class="row justify-content-center">

            <!-- Left Image (Desktop only) -->
            <div class="col-xxl-3 col-lg-4 col-xl-4 d-none d-lg-block wow fadeInLeft">
                <div class="sign_in_img">
                    <img src="{{ asset('assets/images/sign_in_img_2.jpg') }}" alt="Sign In" class="img-fluid w-100">
                </div>
            </div>

            <!-- Register Form -->
            <div class="col-xxl-5 col-lg-8 col-xl-6 col-md-10 wow fadeInRight">
                <div class="sign_in_form">

                    <h3>Sign Up to Continue</h3>

                    <!-- Success Message -->
                    @if (session('status'))
                        <div class="mt-3 text-sm alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Error Message (e.g., from social auth) -->
                    @if (session('error'))
                        <div class="mt-3 text-sm alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="mt-3 text-sm alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Register Form -->
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">

                            <!-- First Name -->
                            <div class="col-lg-6">
                                <div class="single_input">
                                    <label>first name</label>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}"
                                           placeholder="First name" class="@error('first_name') is-invalid @enderror" required>
                                    @error('first_name')
                                        <div class="mt-1 text-xs text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div class="col-lg-6">
                                <div class="single_input">
                                    <label>Last name</label>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}"
                                           placeholder="Last name" class="@error('last_name') is-invalid @enderror" required>
                                    @error('last_name')
                                        <div class="mt-1 text-xs text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-lg-12">
                                <div class="single_input">
                                    <label>email</label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                           placeholder="example@zenis.com" class="@error('email') is-invalid @enderror" required>
                                    @error('email')
                                        <div class="mt-1 text-xs text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-lg-12">
                                <div class="single_input">
                                    <label>phone</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}"
                                           placeholder="+95745841369465895" class="@error('phone') is-invalid @enderror">
                                    @error('phone')
                                        <div class="mt-1 text-xs text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="col-lg-6">
                                <div class="single_input">
                                    <label>password</label>
                                    <input type="password" name="password"
                                           placeholder="********" class="@error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <div class="mt-1 text-xs text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-lg-6">
                                <div class="single_input">
                                    <label>confirm password</label>
                                    <input type="password" name="password_confirmation"
                                           placeholder="********" required>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-lg-12">
                                <button type="submit" class="common_btn">
                                    Sign Up <i class="fas fa-long-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Already have account -->
                    <p class="dont_account">
                        Already have an account? 
                        <a href="{{ route('login') }}">Sign In</a>
                    </p>

                    <p class="or">or</p>

                    <!-- Social Login -->
                    <ul class="social-login">
                        <li>
                            <a href="{{ route('social.redirect', 'google') }}">
                                <span>
                                    <img src="{{ asset('assets/images/google_logo.png') }}" alt="google" class="img-fluid w-100">
                                </span>
                                Continue with Google
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('social.redirect', 'facebook') }}">
                                <span>
                                    <img src="{{ asset('assets/images/fb_logo.png') }}" alt="facebook" class="img-fluid w-100">
                                </span>
                                Continue with Facebook
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<x-footer />