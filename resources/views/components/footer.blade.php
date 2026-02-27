@php
    use App\Models\Setting;
    use App\Models\ContentPage;
    
    // Fetch settings
    $siteName = Setting::where('key', 'site_name')->value('value') ?? 'Comtodeal';
    $contactNumber = Setting::where('key', 'contact_number')->value('value') ?? '+123 324 5879 39';
    $adminEmail = Setting::where('key', 'admin_email')->value('value') ?? 'info@comtodeal.com';
    $copyrightText = Setting::where('key', 'copyright_text')->value('value') ?? 'All rights reserved.';
    $siteLogo = Setting::where('key', 'site_logo')->value('value');
    $favicon = Setting::where('key', 'favicon')->value('value');
    
    // Social media links
    $socialFacebook = Setting::where('key', 'social_facebook')->value('value');
    $socialInstagram = Setting::where('key', 'social_instagram')->value('value');
    $socialTwitter = Setting::where('key', 'social_twitter')->value('value');
    $socialLinkedin = Setting::where('key', 'social_linkedin')->value('value');
    
    // Fetch content pages for dynamic links
    $companyPages = ContentPage::whereIn('slug', ['about-us', 'contact-us', 'careers'])
        ->where('status', 'published')
        ->get()
        ->keyBy('slug');
    
    $legalPages = ContentPage::whereIn('slug', ['privacy-policy', 'terms-and-conditions', 'return-policy', 'faqs'])
        ->where('status', 'published')
        ->get()
        ->keyBy('slug');
    
    // Fallback values if pages don't exist
    $aboutPage = $companyPages['about-us'] ?? null;
    $contactPage = $companyPages['contact-us'] ?? null;
    $careersPage = $companyPages['careers'] ?? null;
    
    $privacyPage = $legalPages['privacy-policy'] ?? null;
    $termsPage = $legalPages['terms-and-conditions'] ?? null;
    $returnPage = $legalPages['return-policy'] ?? null;
    $faqPage = $legalPages['faqs'] ?? null;
@endphp

<!--=========================
    FOOTER 2 START
===========================-->
<footer class="footer_2 pt_100" style="background: url('{{ asset('assets/images/footer_2_bg_2.jpg') }}');">
    <div class="container">
        <div class="row justify-content-between">
            <!-- Logo + About -->
            <div class="col-xl-3 col-md-6 col-lg-3 wow fadeInUp" data-wow-delay=".7s">
                <div class="footer_2_logo_area">
                    <a class="footer_logo" href="{{ route('home') }}">
                        @if($siteLogo)
                            <img  src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" class="img-fluid w-100" style="max-width: 180px;filter: brightness(0) invert(1);">
                        @else
                            <img src="{{ asset('assets/images/footer_logo_2.png') }}" alt="{{ $siteName }}" class="img-fluid w-100">
                        @endif
                    </a>
                    <p>{{ $siteName }} is your trusted online shopping destination for quality products at competitive prices.</p>
                    <ul class="d-flex align-items-center">
                        <li><span>Follow :</span></li>
                        @if($socialFacebook)
                        <li><a href="{{ $socialFacebook }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a></li>
                        @endif
                        @if($socialTwitter)
                        <li><a href="{{ $socialTwitter }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a></li>
                        @endif
                        @if($socialInstagram)
                        <li><a href="{{ $socialInstagram }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a></li>
                        @endif
                        @if($socialLinkedin)
                        <li><a href="{{ $socialLinkedin }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in"></i></a></li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Company Links -->
            <div class="col-xl-2 col-sm-6 col-md-4 col-lg-2 wow fadeInUp" data-wow-delay="1s">
                <div class="footer_link">
                    <h3>Company</h3>
                    <ul>
                        @if($aboutPage)
                        <li><a href="{{ route('page.show', $aboutPage->slug) }}">About Us</a></li>
                        @else
                        <li><a href="{{ route('home') }}#about">About Us</a></li>
                        @endif
                        
                        @if($contactPage)
                        <li><a href="{{ route('page.show', $contactPage->slug) }}">Contact Us</a></li>
                        @else
                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        @endif
                        
                        <li><a href="#">Affiliate</a></li>
                        
                        @if($careersPage)
                        <li><a href="{{ route('page.show', $careersPage->slug) }}">Career</a></li>
                        @else
                        <li><a href="#">Career</a></li>
                        @endif
                        
                         
                    </ul>
                </div>
            </div>

            @php
            use App\Models\Category;
            
                // Fetch top 5 categories based on product count
                $topCategories = Category::withCount('products')
                    ->orderBy('products_count', 'desc')
                    ->limit(5)
                    ->get();
            @endphp

            <!-- Categories -->
            <div class="col-xl-2 col-sm-6 col-md-4 col-lg-2 wow fadeInUp" data-wow-delay="1.3s">
                <div class="footer_link">
                    <h3>Category</h3>
                    <ul>
                        @forelse($topCategories as $category)
                            <li>
                                <a href="{{ route('category.show', $category->slug) }}">
                                    {{ $category->name }}
                                    @if($category->products_count > 0)
                                        <span class="category-count">({{ $category->products_count }})</span>
                                    @endif
                                </a>
                            </li>
                        @empty
                            <!-- Fallback categories if no data -->
                            <li><a href="{{ route('category') }}?category=men-fashion">Men’s Fashion</a></li>
                            <li><a href="{{ route('category') }}?category=denim-collection">Denim Collection</a></li>
                            <li><a href="{{ route('category') }}?category=western-wear">Western Wear</a></li>
                            <li><a href="{{ route('category') }}?category=sport-wear">Sport Wear</a></li>
                            <li><a href="{{ route('category') }}?category=fashion-jewellery">Fashion Jewellery</a></li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-xl-2 col-sm-6 col-md-4 col-lg-2 wow fadeInUp" data-wow-delay="1.6s">
                <div class="footer_link">
                    <h3>Quick Links</h3>
                    <ul>
                        @if($privacyPage)
                        <li><a href="{{ route('page.show', $privacyPage->slug) }}">Privacy Policy</a></li>
                        @else
                        <li><a href="{{ url('/privacy-policy') }}">Privacy Policy</a></li>
                        @endif
                        
                        @if($termsPage)
                        <li><a href="{{ route('page.show', $termsPage->slug) }}">Terms & Conditions</a></li>
                        @else
                        <li><a href="{{ url('/terms-and-conditions') }}">Terms & Conditions</a></li>
                        @endif
                        
                        @if($returnPage)
                        <li><a href="{{ route('page.show', $returnPage->slug) }}">Return Policy</a></li>
                        @else
                        <li><a href="{{ url('/return-policy') }}">Return Policy</a></li>
                        @endif
                        
                        @if($faqPage)
                        <li><a href="{{ route('page.show', $faqPage->slug) }}">FAQ's</a></li>
                        @else
                        <li><a href="{{ url('/faq') }}">FAQ's</a></li>
                        @endif
                        
                        <li><a href="{{ route('become.seller') }}">Become a Vendor</a></li>
                    </ul>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-xl-3 col-sm-6 col-md-4 col-lg-3 wow fadeInUp" data-wow-delay="1.9s">
                <div class="footer_link footer_logo_area">
                    <h3>Contact Us</h3>
                    <p>Have questions? We're here to help. Reach out to us anytime.</p>
                    <span>
                        <b><img src="{{ asset('assets/images/location_icon_white.png') }}" alt="Map" class="img-fluid"></b>
                        123 Business Avenue, Tech Park, Mumbai - 400001
                    </span>
                    <span>
                        <b><img src="{{ asset('assets/images/phone_icon_white.png') }}" alt="Call" class="img-fluid"></b>
                        <a href="tel:{{ $contactNumber }}">{{ $contactNumber }}</a>
                    </span>
                    <span>
                        <b><img src="{{ asset('assets/images/mail_icon_white.png') }}" alt="Mail" class="img-fluid"></b>
                        <a href="mailto:{{ $adminEmail }}">{{ $adminEmail }}</a>
                    </span>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="row">
            <div class="col-12">
                <div class="footer_copyright mt_75">
                    <p>Copyright © <b>{{ $siteName }}</b> {{ date('Y') }}. {{ $copyrightText }}</p>
                    <ul class="payment">
                        <li>Payment by :</li>
                        <li><img src="{{ asset('assets/images/footer_payment_icon_1.jpg') }}" alt="Visa" class="img-fluid w-100"></li>
                        <li><img src="{{ asset('assets/images/footer_payment_icon_2.jpg') }}" alt="Mastercard" class="img-fluid w-100"></li>
                        <li><img src="{{ asset('assets/images/footer_payment_icon_3.jpg') }}" alt="PayPal" class="img-fluid w-100"></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!--=========================
    FOOTER 2 END
===========================-->

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


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ==================== RATING SYSTEM ====================
        const ratingInputs = document.querySelectorAll('.rating-input');
        const selectedRating = document.getElementById('selectedRating');
        
        const ratingMessages = {
            5: {
                text: 'You love it! Share why you think it\'s excellent.',
                icon: 'fa-grin-stars',
                color: 'success'
            },
            4: {
                text: 'You like it! Tell others what you enjoyed.',
                icon: 'fa-smile',
                color: 'info'
            },
            3: {
                text: 'It\'s okay. Share what could be improved.',
                icon: 'fa-meh',
                color: 'warning'
            },
            2: {
                text: 'You\'re not satisfied. Help others know what went wrong.',
                icon: 'fa-frown',
                color: 'danger'
            },
            1: {
                text: 'You\'re very disappointed. Share your experience.',
                icon: 'fa-angry',
                color: 'danger'
            }
        };
        
        ratingInputs.forEach(input => {
            input.addEventListener('change', function() {
                const rating = this.value;
                const message = ratingMessages[rating];
                
                selectedRating.innerHTML = `
                    <div class="p-3 rounded-3 bg-${message.color} bg-opacity-10">
                        <i class="fas ${message.icon} text-${message.color} me-2"></i>
                        <span class="text-${message.color}">${message.text}</span>
                    </div>
                `;
                
                // Add animation
                selectedRating.style.animation = 'none';
                selectedRating.offsetHeight;
                selectedRating.style.animation = 'slideDown 0.3s ease';
            });
        });

        // ==================== CHARACTER COUNTER ====================
        const reviewTextarea = document.getElementById('review');
        const currentChars = document.getElementById('currentChars');
        const charProgress = document.getElementById('charProgress');
        const charCounter = document.querySelector('.char-counter');
        
        const updateCharCount = () => {
            const length = reviewTextarea.value.length;
            const percentage = (length / 500) * 100;
            
            currentChars.textContent = length;
            charProgress.style.width = percentage + '%';
            
            // Update colors based on length
            if (length > 500) {
                charCounter.classList.add('danger');
                charCounter.classList.remove('warning');
                charProgress.style.backgroundColor = '#dc3545';
            } else if (length > 450) {
                charCounter.classList.add('warning');
                charCounter.classList.remove('danger');
                charProgress.style.backgroundColor = '#ffc107';
            } else {
                charCounter.classList.remove('warning', 'danger');
                charProgress.style.backgroundColor = '#0d6efd';
            }
            
            // Auto-resize textarea
            reviewTextarea.style.height = 'auto';
            reviewTextarea.style.height = (reviewTextarea.scrollHeight) + 'px';
        };
        
        reviewTextarea.addEventListener('input', updateCharCount);
        reviewTextarea.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        reviewTextarea.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
        
        // Initialize character count
        updateCharCount();

        // ==================== IMAGE UPLOAD ====================
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('images');
        const imagePreview = document.getElementById('imagePreview');
        const browseBtn = document.getElementById('browseBtn');
        const uploadWrapper = document.querySelector('.upload-area-wrapper');
        
        let selectedFiles = [];

        // Browse button click
        browseBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            fileInput.click();
        });

        // Upload area click
        uploadArea.addEventListener('click', (e) => {
            if (e.target !== browseBtn && !browseBtn.contains(e.target)) {
                fileInput.click();
            }
        });

        // Drag and drop handlers
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadWrapper.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadWrapper.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadWrapper.classList.remove('dragover');
            
            const files = Array.from(e.dataTransfer.files);
            handleFiles(files);
        });

        // File input change
        fileInput.addEventListener('change', (e) => {
            const files = Array.from(e.target.files);
            handleFiles(files);
        });

        // Handle selected files
        function handleFiles(files) {
            // Filter only image files
            const imageFiles = files.filter(file => file.type.startsWith('image/'));
            
            // Check total count
            if (selectedFiles.length + imageFiles.length > 5) {
                showNotification('error', 'You can only upload up to 5 images');
                return;
            }

            // Process each file
            imageFiles.forEach(file => {
                // Check file size
                if (file.size > 2 * 1024 * 1024) {
                    showNotification('warning', `File "${file.name}" is larger than 2MB and was skipped`);
                    return;
                }
                
                // Add to selected files
                selectedFiles.push(file);
                
                // Create preview
                const reader = new FileReader();
                reader.onload = (e) => {
                    addImagePreview(file, e.target.result);
                };
                reader.readAsDataURL(file);
            });

            updateFileInput();
        }

        // Add image preview
        function addImagePreview(file, dataUrl) {
            const previewItem = document.createElement('div');
            previewItem.className = 'preview-item';
            previewItem.dataset.filename = file.name;
            
            previewItem.innerHTML = `
                <img src="${dataUrl}" alt="Preview">
                <button type="button" class="remove-btn" title="Remove image">
                    <i class="fas fa-times"></i>
                </button>
                <span class="file-size">${formatFileSize(file.size)}</span>
            `;
            
            previewItem.querySelector('.remove-btn').addEventListener('click', function() {
                const index = selectedFiles.findIndex(f => f.name === file.name);
                if (index > -1) {
                    selectedFiles.splice(index, 1);
                    previewItem.remove();
                    updateFileInput();
                    
                    if (selectedFiles.length === 0) {
                        imagePreview.innerHTML = '';
                    }
                }
            });
            
            imagePreview.appendChild(previewItem);
        }

        // Format file size
        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        }

        // Update file input with selected files
        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            fileInput.files = dataTransfer.files;
            
            // Update upload area text
            const uploadText = uploadArea.querySelector('h6');
            if (selectedFiles.length > 0) {
                uploadText.textContent = `${selectedFiles.length} image(s) selected`;
            } else {
                uploadText.textContent = 'Drag & drop photos here';
            }
        }

        // Show notification
        function showNotification(type, message) {
            // You can implement a toast notification here
            alert(message); // Simple fallback
        }

        // ==================== FORM VALIDATION ====================
        document.getElementById('reviewForm').addEventListener('submit', function(e) {
            const rating = document.querySelector('input[name="rating"]:checked');
            const review = document.getElementById('review').value.trim();
            
            if (!rating) {
                e.preventDefault();
                alert('Please select a rating');
                return;
            }
            
            if (review.length < 10) {
                e.preventDefault();
                alert('Your review must be at least 10 characters long');
                return;
            }
            
            if (review.length > 500) {
                e.preventDefault();
                alert('Your review cannot exceed 500 characters');
                return;
            }
            
            // Disable submit button to prevent double submission
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Submitting...';
        });

        // ==================== ANIMATIONS ====================
        // Animate elements on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });
    });
</script>

<!-- Scripts -->
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