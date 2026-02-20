<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />

    <!-- ✅ Dynamic Title -->
    
    <title>{{ $title ?? config('app.name', 'Valtara - Multipurpose eCommerce Website') }}</title>

    <!-- ✅ Dynamic SEO Meta Tags -->
    
    <meta name="description" content="{{ $description ?? 'Shop top-quality products online at Valtara. Discover electronics, fashion, home appliances, and more with secure checkout & fast delivery.' }}">
    <meta name="keywords" content="{{ $keywords ?? 'eCommerce, online shopping, buy online, Valtara store, electronics, fashion' }}">
    <meta name="author" content="Valtara">

    <!-- ✅ Open Graph Tags (for social media) -->
    
    <meta property="og:title" content="{{ $title ?? 'Valtara - Multipurpose eCommerce Website' }}">
    <meta property="og:description" content="{{ $description ?? 'Shop the latest products at Valtara. Discover unbeatable prices and fast delivery.' }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('assets/images/default-og-image.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- ✅ Twitter Card -->
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'Valtara - Multipurpose eCommerce Website' }}">
    <meta name="twitter:description" content="{{ $description ?? 'Shop top-quality products online at Valtara.' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('assets/images/default-og-image.jpg') }}">

    <!-- ✅ Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- ✅ Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    
    <!-- ✅ CSS Files -->
    
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/mobile_menu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/scroll_button.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/venobox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.pwstabs.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/range_slider.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/multiple-image-video.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animated_barfiller.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom_spacing.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/chat-modal.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

 
    <!-- ✅ JSON-LD Schema (Google Structured Data) -->
    
    <script type="application/ld+json">
        
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Valtara',
            'url' => url('/'),
            'logo' => asset('assets/images/favicon.png'),
            'sameAs' => [
                'https://www.facebook.com/yourpage',
                'https://www.instagram.com/yourpage',
                'https://twitter.com/yourpage'
            ]
        ], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}
        
    </script>


 </head>

<body class="default_home">
 