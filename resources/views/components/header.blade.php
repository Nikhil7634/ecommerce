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


    <style>
         /* Order Status Banner */
    .order-status-banner {
        animation: slideDown 0.5s ease;
    }
    
    .bg-opacity-20 {
        --bs-bg-opacity: 0.2;
    }
    
    /* Vertical Timeline */
    .order-timeline-vertical {
        position: relative;
        padding-left: 30px;
    }
    
    .order-timeline-vertical::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #e9ecef 0%, #e9ecef 100%);
    }
    
    .timeline-item-vertical {
        position: relative;
        margin-bottom: 25px;
    }
    
    .timeline-marker-vertical {
        position: absolute;
        left: -30px;
        top: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        z-index: 1;
    }
    
    .timeline-item-vertical.completed .timeline-marker-vertical {
        animation: markerPulse 0.5s ease;
    }
    
    @keyframes markerPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
    
    .timeline-content-vertical {
        padding-bottom: 10px;
    }
    
    .timeline-content-vertical h6 {
        margin-bottom: 2px;
        font-weight: 600;
    }
    
    /* Action Buttons */
    .btn-warning, .btn-outline-primary, .btn-outline-success, .btn-outline-danger, .btn-outline-secondary {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        border-width: 2px;
        text-align: left;
    }
    
    .btn-warning:hover, .btn-outline-primary:hover, .btn-outline-success:hover, .btn-outline-danger:hover, .btn-outline-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    
    /* Order Items Table */
    .table th {
        font-weight: 600;
        color: #495057;
        background-color: #f8f9fa;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .badge.bg-light {
        background-color: #f8f9fa !important;
        border: 1px solid #e9ecef;
    }
    
    /* Animations */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Hover Effects */
    .list-group-item-action {
        transition: all 0.3s ease;
        border-radius: 8px !important;
        margin-bottom: 5px;
        border: none !important;
    }
    
    .list-group-item-action:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .order-timeline-vertical {
            padding-left: 25px;
        }
        
        .timeline-marker-vertical {
            left: -25px;
            width: 25px;
            height: 25px;
            font-size: 10px;
        }
        
        .btn-warning, .btn-outline-primary, .btn-outline-success, .btn-outline-danger, .btn-outline-secondary {
            text-align: center;
        }
        
        .btn-warning small, .btn-outline-primary small, .btn-outline-success small, .btn-outline-danger small, .btn-outline-secondary small {
            display: none;
        }
        
        .table {
            font-size: 14px;
        }
        
        .table img {
            width: 50px !important;
            height: 50px !important;
        }
        
        .table td:last-child {
            text-align: center;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
    </style>

    <style>
    /* Rating Styles */
    .rating-container {
        transition: all 0.3s ease;
    }
    
    .rating-option {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    
    .rating-option:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        transform: translateX(5px);
    }
    
    .rating-input {
        display: none;
    }
    
    .rating-label {
        display: flex;
        align-items: center;
        cursor: pointer;
        margin-bottom: 0;
        width: 100%;
    }
    
    .rating-label i {
        font-size: 1.5rem;
        margin-right: 2px;
        transition: all 0.2s ease;
    }
    
    .rating-option:hover .rating-label i {
        transform: scale(1.1);
    }
    
    .rating-input:checked + .rating-label i {
        transform: scale(1.1);
    }
    
    .rating-input:checked + .rating-label .fas.fa-star {
        animation: starPulse 0.3s ease;
    }
    
    @keyframes starPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1.1); }
    }
    
    .rating-text {
        font-size: 1rem;
        font-weight: 500;
        color: #495057;
    }
    
    .rating-input:checked + .rating-label .rating-text {
        color: #0d6efd;
        font-weight: 600;
    }
    
    .selected-rating {
        animation: slideDown 0.3s ease;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Review Textarea */
    .review-textarea-wrapper {
        position: relative;
    }
    
    .review-textarea-wrapper textarea {
        resize: vertical;
        min-height: 150px;
        transition: all 0.3s ease;
    }
    
    .review-textarea-wrapper textarea:focus {
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
    }
    
    .char-counter {
        font-size: 0.875rem;
        font-weight: 500;
        padding: 4px 12px;
        background-color: #f8f9fa;
        border-radius: 20px;
    }
    
    .char-counter.warning {
        color: #856404;
        background-color: #fff3cd;
    }
    
    .char-counter.danger {
        color: #721c24;
        background-color: #f8d7da;
    }
    
    .progress {
        border-radius: 2px;
        overflow: hidden;
    }
    
    .progress-bar {
        transition: width 0.3s ease;
    }
    
    /* Upload Area */
    .upload-area-wrapper {
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        transition: all 0.3s ease;
        background-color: #f8f9fa;
    }
    
    .upload-area-wrapper:hover,
    .upload-area-wrapper.dragover {
        border-color: #0d6efd;
        background-color: #e7f1ff;
    }
    
    .upload-area {
        cursor: pointer;
    }
    
    .upload-icon {
        transition: all 0.3s ease;
    }
    
    .upload-area-wrapper:hover .upload-icon i {
        transform: translateY(-5px);
        color: #0d6efd !important;
    }
    
    /* Image Preview Grid */
    .image-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }
    
    .preview-item {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        aspect-ratio: 1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    .preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .preview-item:hover img {
        transform: scale(1.1);
    }
    
    .preview-item .remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
        opacity: 0;
        transform: scale(0.8);
    }
    
    .preview-item:hover .remove-btn {
        opacity: 1;
        transform: scale(1);
    }
    
    .preview-item .remove-btn:hover {
        background-color: #dc3545;
        transform: scale(1.1);
    }
    
    .preview-item .file-size {
        position: absolute;
        bottom: 5px;
        left: 5px;
        background-color: rgba(0,0,0,0.6);
        color: white;
        padding: 2px 6px;
        border-radius: 12px;
        font-size: 10px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .preview-item:hover .file-size {
        opacity: 1;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .rating-label i {
            font-size: 1.2rem;
        }
        
        .rating-text {
            font-size: 0.9rem;
        }
        
        .image-preview-grid {
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 10px;
        }
        
        .btn {
            width: 100%;
        }
        
        .d-flex.gap-3 {
            flex-direction: column;
        }
    }
</style>


 </head>

<body class="default_home">
 