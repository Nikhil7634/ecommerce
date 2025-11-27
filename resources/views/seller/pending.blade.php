@include('seller.layouts.header')
<!--end top header-->
@include('seller.layouts.topnavbar')

@include('seller.layouts.aside')
<!--end sidebar-->

<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">

        <!--breadcrumb-->
        <div class="mb-3 page-breadcrumb d-none d-sm-flex align-items-center">
            <div class="breadcrumb-title pe-3">Seller Account</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="p-0 mb-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pending Approval</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="mx-auto col-12 col-lg-8 col-xl-6">
                <!-- Main Pending Approval Card -->
                <div class="border-0 shadow-sm card rounded-4">
                    <div class="p-4 card-body p-lg-5">
                        
                        <!-- Header Section -->
                        <div class="mb-5 text-center">
                            <div class="mx-auto mb-4 bg-opacity-50 wh-100 rounded-circle bg-light d-flex align-items-center justify-content-center">
                                <div class="border wh-80 rounded-circle bg-light d-flex align-items-center justify-content-center">
                                    <i class="material-icons-outlined display-5 text-warning">hourglass_top</i>
                                </div>
                            </div>
                            
                            <span class="px-3 py-2 mb-3 border border-opacity-25 badge bg-warning bg-opacity-10 text-warning border-warning rounded-pill">
                                <i class="material-icons-outlined me-1 fs-6">schedule</i>
                                Account Under Review
                            </span>
                            
                            <h2 class="mb-3 fw-bold text-body">We're Reviewing Your Application</h2>
                            <p class="mb-0 text-body">
                                Thank you for choosing to sell with us! Your seller account is currently undergoing verification.
                            </p>
                        </div>

                        <!-- Progress Steps -->
                        <div class="mb-5">
                            <div class="mb-4 d-flex align-items-center justify-content-between position-relative">
                                <div class="h-2 bg-opacity-25 position-absolute top-50 start-0 end-0 bg-body-secondary rounded-pill"></div>
                                <div class="h-2 position-absolute top-50 start-0 w-66 bg-primary rounded-pill"></div>
                                
                                <div class="d-flex flex-column align-items-center position-relative">
                                    <div class="mb-2 text-white shadow-sm wh-50 rounded-circle bg-primary d-flex align-items-center justify-content-center">
                                        <i class="material-icons-outlined fs-6">check</i>
                                    </div>
                                    <span class="small fw-semibold text-body">Registered</span>
                                    <span class="small text-success">Completed</span>
                                </div>
                                
                                <div class="d-flex flex-column align-items-center position-relative">
                                    <div class="mb-2 text-white shadow-sm wh-50 rounded-circle bg-primary d-flex align-items-center justify-content-center">
                                        <i class="material-icons-outlined fs-6">search</i>
                                    </div>
                                    <span class="small fw-semibold text-body">Verification</span>
                                    <span class="small text-body">In Progress</span>
                                </div>
                                
                                <div class="d-flex flex-column align-items-center position-relative">
                                    <div class="mb-2 bg-opacity-25 shadow-sm wh-50 rounded-circle bg-body-secondary text-body d-flex align-items-center justify-content-center">
                                        <i class="material-icons-outlined fs-6">pending</i>
                                    </div>
                                    <span class="small fw-semibold text-body">Approval</span>
                                    <span class="small text-body">Pending</span>
                                </div>
                            </div>
                        </div>

                        <!-- Info Cards -->
                        <div class="mb-5 row g-3">
                            <div class="col-md-4">
                                <div class="p-3 text-center border rounded-3 h-100">
                                    <div class="mx-auto mb-2 wh-50 rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center">
                                        <i class="material-icons-outlined text-warning">schedule</i>
                                    </div>
                                    <h6 class="mb-1 fw-semibold text-body">1-3 Days</h6>
                                    <small class="text-body">Review timeframe</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 text-center border rounded-3 h-100">
                                    <div class="mx-auto mb-2 wh-50 rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center">
                                        <i class="material-icons-outlined text-info">email</i>
                                    </div>
                                    <h6 class="mb-1 fw-semibold text-body">Email Alert</h6>
                                    <small class="text-body">Get notified</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 text-center border rounded-3 h-100">
                                    <div class="mx-auto mb-2 wh-50 rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center">
                                        <i class="material-icons-outlined text-success">store</i>
                                    </div>
                                    <h6 class="mb-1 fw-semibold text-body">Go Live</h6>
                                    <small class="text-body">Start selling</small>
                                </div>
                            </div>
                        </div>

                        <!-- Status Message -->
                        <div class="p-4 mb-4 border rounded-3 bg-body-secondary bg-opacity-10">
                            <div class="d-flex align-items-start">
                                <i class="mt-1 material-icons-outlined text-info me-3">info</i>
                                <div>
                                    <h6 class="mb-2 fw-semibold text-body">What happens next?</h6>
                                    <p class="mb-2 text-body small">
                                        Our team is reviewing your seller information to ensure everything meets our marketplace standards. 
                                        This process typically takes 1-3 business days.
                                    </p>
                                    <p class="mb-0 text-body small">
                                        Once approved, you'll gain full access to your seller dashboard where you can add products, manage orders, and track your sales performance.
                                    </p>
                                </div>
                            </div>
                        </div>

                         <!-- Countdown Timer -->
                        <div class="p-4 mb-4 border rounded-3 bg-primary bg-opacity-5 border-primary border-opacity-10">
                            <div class="text-center">
                                <h6 class="mb-3 fw-semibold text-primary">
                                    <i class="material-icons-outlined me-2">update</i>
                                    Time Remaining
                                </h6>
                                <div class="gap-4 d-flex justify-content-center" id="countdownTimer">
                                    <div class="text-center">
                                        <div class="mx-auto mb-2 wh-50 rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                                            <span style="font-size: 2rem" class="fw-bold text-body" id="hours">--</span>
                                        </div>
                                        <small class="text-body">Hours</small>
                                    </div>
                                    <div class="text-center">
                                        <div class="mx-auto mb-2 wh-50 rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                                            <span style="font-size: 2rem" class="fw-bold text-body" id="minutes">--</span>
                                        </div>
                                        <small class="text-body">Minutes</small>
                                    </div>
                                    <div class="text-center">
                                        <div class="mx-auto mb-2 wh-50 rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                                            <span style="font-size: 2rem" class="fw-bold text-body" id="seconds">--</span>
                                        </div>
                                        <small class="text-body">Seconds</small>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <small class="text-body" id="countdownStatus">Initializing countdown...</small>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-center">
                            <p class="mb-3 text-body">
                                Need help or have questions about the verification process?
                            </p>
                            <div class="flex-wrap gap-2 d-flex justify-content-center">
                                <a href="mailto:support@yourwebsite.com" class="px-4 btn btn-primary">
                                    <i class="material-icons-outlined me-2 fs-6">support</i>
                                    Contact Support
                                </a>
                                <a href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                   class="px-4 btn btn-outline-secondary">
                                    <i class="material-icons-outlined me-2 fs-6">logout</i>
                                    Logout
                                </a>
                            </div>
                        </div>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>

                <!-- Additional Info Card -->
                <div class="mt-4 border-0 shadow-sm card rounded-4">
                    <div class="p-4 card-body">
                        <h6 class="mb-3 fw-semibold text-body">Verification Steps</h6>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="p-3 border d-flex align-items-center rounded-3">
                                    <div class="wh-45 rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center me-3">
                                        <i class="material-icons-outlined text-success">checklist</i>
                                    </div>
                                    <div>
                                        <p class="mb-1 fw-medium text-body">Document Review</p>
                                        <small class="text-body">ID & Business docs</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 border d-flex align-items-center rounded-3">
                                    <div class="wh-45 rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center me-3">
                                        <i class="material-icons-outlined text-info">security</i>
                                    </div>
                                    <div>
                                        <p class="mb-1 fw-medium text-body">Security Check</p>
                                        <small class="text-body">Account safety</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 border d-flex align-items-center rounded-3">
                                    <div class="wh-45 rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center me-3">
                                        <i class="material-icons-outlined text-warning">storefront</i>
                                    </div>
                                    <div>
                                        <p class="mb-1 fw-medium text-body">Store Setup</p>
                                        <small class="text-body">Ready for products</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 border d-flex align-items-center rounded-3">
                                    <div class="wh-45 rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3">
                                        <i class="material-icons-outlined text-body">rocket_launch</i>
                                    </div>
                                    <div>
                                        <p class="mb-1 fw-medium text-body">Go Live</p>
                                        <small class="text-body">Start selling</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
<!--end main wrapper-->

@include('seller.layouts.footer')

<style>
.wh-40 { width: 40px; height: 40px; }
.wh-45 { width: 45px; height: 45px; }
.wh-50 { width: 50px; height: 50px; }
.wh-80 { width: 80px; height: 80px; }
.wh-100 { width: 100px; height: 100px; }

.rounded-4 { border-radius: 1rem !important; }

.w-66 { width: 66.66%; }

.text-body, .text-body-secondar{
    color: var(--bs-card-color) !important;
}
</style>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cookie functions
    function setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = name + '=' + value + ';expires=' + expires.toUTCString() + ';path=/';
    }

    function getCookie(name) {
        const nameEQ = name + '=';
        const ca = document.cookie.split(';');
        for(let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    // Countdown timer functionality
    const countdownTimer = {
        totalSeconds: 72 * 60 * 60, // 72 hours in seconds
        remainingSeconds: 0,
        interval: null,

        init() {
            // Check if we have a saved countdown
            const savedEndTime = getCookie('approvalCountdownEnd');
            
            if (savedEndTime) {
                const endTime = parseInt(savedEndTime);
                const now = Math.floor(Date.now() / 1000);
                this.remainingSeconds = Math.max(0, endTime - now);
                
                if (this.remainingSeconds > 0) {
                    this.startCountdown();
                } else {
                    this.showCompleted();
                }
            } else {
                // Start new countdown (72 hours from now)
                const endTime = Math.floor(Date.now() / 1000) + this.totalSeconds;
                setCookie('approvalCountdownEnd', endTime, 7); // Save for 7 days
                this.remainingSeconds = this.totalSeconds;
                this.startCountdown();
            }
        },

        startCountdown() {
            this.updateDisplay();
            
            this.interval = setInterval(() => {
                this.remainingSeconds--;
                this.updateDisplay();
                
                if (this.remainingSeconds <= 0) {
                    this.completeCountdown();
                }
            }, 1000);
        },

        updateDisplay() {
            const hours = Math.floor(this.remainingSeconds / 3600);
            const minutes = Math.floor((this.remainingSeconds % 3600) / 60);
            const seconds = this.remainingSeconds % 60;
            
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
            
            // Update status message
            if (hours > 48) {
                document.getElementById('countdownStatus').textContent = 'Review process started recently';
            } else if (hours > 24) {
                document.getElementById('countdownStatus').textContent = 'Review in progress - halfway there!';
            } else if (hours > 1) {
                document.getElementById('countdownStatus').textContent = 'Review nearing completion';
            } else {
                document.getElementById('countdownStatus').textContent = 'Final stages - check back soon!';
            }
        },

        completeCountdown() {
            clearInterval(this.interval);
            document.getElementById('hours').textContent = '00';
            document.getElementById('minutes').textContent = '00';
            document.getElementById('seconds').textContent = '00';
            document.getElementById('countdownStatus').textContent = 'Review period completed! Please check your email for updates.';
            
            // Change the timer style to indicate completion
            const timerElement = document.getElementById('countdownTimer');
            timerElement.classList.add('opacity-75');
            
            // Remove the cookie since countdown is complete
            setCookie('approvalCountdownEnd', '', -1);
        },

        showCompleted() {
            document.getElementById('hours').textContent = '00';
            document.getElementById('minutes').textContent = '00';
            document.getElementById('seconds').textContent = '00';
            document.getElementById('countdownStatus').textContent = 'Review period completed! Please check your email for updates.';
            
            const timerElement = document.getElementById('countdownTimer');
            timerElement.classList.add('opacity-75');
        }
    };

    // Initialize the countdown timer
    countdownTimer.init();

    // Optional: Add reset functionality for testing (remove in production)
    window.resetCountdown = function() {
        setCookie('approvalCountdownEnd', '', -1);
        location.reload();
    };
});
</script>