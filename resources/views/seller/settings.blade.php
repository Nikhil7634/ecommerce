@include('seller.layouts.header')
@include('seller.layouts.topnavbar')
@include('seller.layouts.aside')

<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="mb-4 page-breadcrumb d-none d-sm-flex align-items-center">
            <div class="breadcrumb-title pe-3">Settings</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="p-0 mb-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Left Column - Settings Navigation -->
            <div class="col-12 col-xl-3">
                <div class="mb-4 card rounded-4">
                    <div class="p-3 card-body">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active" id="v-pills-general-tab" data-bs-toggle="pill" 
                                    data-bs-target="#v-pills-general" type="button" role="tab">
                                <i class="bi bi-gear me-2"></i> General Settings
                            </button>
                            <button class="nav-link" id="v-pills-notifications-tab" data-bs-toggle="pill" 
                                    data-bs-target="#v-pills-notifications" type="button" role="tab">
                                <i class="bi bi-bell me-2"></i> Notifications
                            </button>
                            <button class="nav-link" id="v-pills-security-tab" data-bs-toggle="pill" 
                                    data-bs-target="#v-pills-security" type="button" role="tab">
                                <i class="bi bi-shield-lock me-2"></i> Security
                            </button>
                            <button class="nav-link" id="v-pills-payment-tab" data-bs-toggle="pill" 
                                    data-bs-target="#v-pills-payment" type="button" role="tab">
                                <i class="bi bi-credit-card me-2"></i> Payment Settings
                            </button>
                            <button class="nav-link" id="v-pills-shipping-tab" data-bs-toggle="pill" 
                                    data-bs-target="#v-pills-shipping" type="button" role="tab">
                                <i class="bi bi-truck me-2"></i> Shipping Settings
                            </button>
                            <button class="nav-link" id="v-pills-privacy-tab" data-bs-toggle="pill" 
                                    data-bs-target="#v-pills-privacy" type="button" role="tab">
                                <i class="bi bi-eye-slash me-2"></i> Privacy
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card rounded-4">
                    <div class="p-3 card-body">
                        <h6 class="mb-3 fw-semibold">Settings Status</h6>
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="text-muted">Profile Complete</span>
                            <span class="fw-semibold">80%</span>
                        </div>
                        <div class="mb-3 progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 80%"></div>
                        </div>
                        
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="text-muted">Security Level</span>
                            <span class="fw-semibold">High</span>
                        </div>
                        <div class="mb-3 progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 90%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Settings Content -->
            <div class="col-12 col-xl-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <!-- General Settings Tab -->
                    <div class="tab-pane fade show active" id="v-pills-general" role="tabpanel">
                        <div class="card rounded-4">
                            <div class="p-4 card-body">
                                <div class="mb-4 d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-0 fw-bold">General Settings</h5>
                                        <p class="mb-0 text-muted">Manage your account preferences</p>
                                    </div>
                                </div>

                                <form action="{{ route('seller.settings.update') }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <!-- Store Settings -->
                                    <div class="mb-4">
                                        <h6 class="pb-2 mb-3 fw-semibold border-bottom">Store Settings</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="store_name" class="form-label">Store Name</label>
                                                <input type="text" name="store_name" id="store_name" 
                                                       class="form-control" 
                                                       value="{{ old('store_name', $seller->business_name) }}"
                                                       placeholder="Your store name">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="store_slug" class="form-label">Store URL</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">yourstore.com/</span>
                                                    <input type="text" name="store_slug" id="store_slug" 
                                                           class="form-control" 
                                                           value="{{ old('store_slug') }}"
                                                           placeholder="store-slug">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label for="store_description" class="form-label">Store Description</label>
                                                <textarea name="store_description" id="store_description" 
                                                          class="form-control" rows="3"
                                                          placeholder="Brief description about your store">{{ old('store_description') }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Settings -->
                                    <div class="mb-4">
                                        <h6 class="pb-2 mb-3 fw-semibold border-bottom">Contact Information</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="contact_email" class="form-label">Contact Email</label>
                                                <input type="email" name="contact_email" id="contact_email" 
                                                       class="form-control" 
                                                       value="{{ old('contact_email', $seller->email) }}"
                                                       placeholder="contact@example.com">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                                <input type="text" name="contact_phone" id="contact_phone" 
                                                       class="form-control" 
                                                       value="{{ old('contact_phone', $seller->phone) }}"
                                                       placeholder="+91 9876543210">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="support_hours" class="form-label">Support Hours</label>
                                                <select name="support_hours" id="support_hours" class="form-select">
                                                    <option value="9-5">9 AM - 5 PM (Business Hours)</option>
                                                    <option value="24-7">24/7 Support</option>
                                                    <option value="custom">Custom Hours</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="timezone" class="form-label">Timezone</label>
                                                <select name="timezone" id="timezone" class="form-select">
                                                    <option value="Asia/Kolkata" selected>India (IST)</option>
                                                    <option value="UTC">UTC</option>
                                                    <option value="America/New_York">EST</option>
                                                    <option value="Europe/London">GMT</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Display Settings -->
                                    <div class="mb-4">
                                        <h6 class="pb-2 mb-3 fw-semibold border-bottom">Display Settings</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="currency" class="form-label">Default Currency</label>
                                                <select name="currency" id="currency" class="form-select">
                                                    <option value="INR" selected>Indian Rupee (₹)</option>
                                                    <option value="USD">US Dollar ($)</option>
                                                    <option value="EUR">Euro (€)</option>
                                                    <option value="GBP">British Pound (£)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="language" class="form-label">Language</label>
                                                <select name="language" id="language" class="form-select">
                                                    <option value="en" selected>English</option>
                                                    <option value="hi">Hindi</option>
                                                    <option value="es">Spanish</option>
                                                    <option value="fr">French</option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="show_stock" name="show_stock" checked>
                                                    <label class="form-check-label" for="show_stock">
                                                        Show stock status on product pages
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="show_reviews" name="show_reviews" checked>
                                                    <label class="form-check-label" for="show_reviews">
                                                        Display customer reviews
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="show_prices" name="show_prices" checked>
                                                    <label class="form-check-label" for="show_prices">
                                                        Show prices including tax
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="pt-3 border-top">
                                        <button type="submit" class="px-4 btn btn-primary">
                                            <i class="bi bi-save me-2"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications Tab -->
                    <div class="tab-pane fade" id="v-pills-notifications" role="tabpanel">
                        <div class="card rounded-4">
                            <div class="p-4 card-body">
                                <div class="mb-4 d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-0 fw-bold">Notification Settings</h5>
                                        <p class="mb-0 text-muted">Manage how you receive notifications</p>
                                    </div>
                                </div>

                                <form action="{{ route('seller.settings.notifications.update') }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <!-- Email Notifications -->
                                    <div class="mb-4">
                                        <h6 class="pb-2 mb-3 fw-semibold border-bottom">Email Notifications</h6>
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="email_orders" name="email_orders" checked>
                                                <label class="form-check-label" for="email_orders">
                                                    New order notifications
                                                </label>
                                            </div>
                                            <small class="text-muted">Receive email when a new order is placed</small>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="email_payments" name="email_payments" checked>
                                                <label class="form-check-label" for="email_payments">
                                                    Payment notifications
                                                </label>
                                            </div>
                                            <small class="text-muted">Get notified about successful payments</small>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="email_reviews" name="email_reviews" checked>
                                                <label class="form-check-label" for="email_reviews">
                                                    New review notifications
                                                </label>
                                            </div>
                                            <small class="text-muted">Be notified when customers leave reviews</small>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="email_support" name="email_support" checked>
                                                <label class="form-check-label" for="email_support">
                                                    Support ticket updates
                                                </label>
                                            </div>
                                            <small class="text-muted">Receive updates on support tickets</small>
                                        </div>
                                    </div>

                                    <!-- Push Notifications -->
                                    <div class="mb-4">
                                        <h6 class="pb-2 mb-3 fw-semibold border-bottom">Push Notifications</h6>
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="push_orders" name="push_orders">
                                                <label class="form-check-label" for="push_orders">
                                                    New order alerts
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="push_messages" name="push_messages">
                                                <label class="form-check-label" for="push_messages">
                                                    New messages
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="push_stock" name="push_stock">
                                                <label class="form-check-label" for="push_stock">
                                                    Low stock alerts
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Notification Frequency -->
                                    <div class="mb-4">
                                        <h6 class="pb-2 mb-3 fw-semibold border-bottom">Notification Frequency</h6>
                                        <div class="mb-3">
                                            <label class="form-label">Email Summary</label>
                                            <select name="email_frequency" id="email_frequency" class="form-select">
                                                <option value="daily">Daily Summary</option>
                                                <option value="weekly" selected>Weekly Summary</option>
                                                <option value="monthly">Monthly Summary</option>
                                                <option value="never">Never</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="pt-3 border-top">
                                        <button type="submit" class="px-4 btn btn-primary">
                                            <i class="bi bi-save me-2"></i> Save Notification Settings
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Security Tab -->
                    <div class="tab-pane fade" id="v-pills-security" role="tabpanel">
                        <div class="card rounded-4">
                            <div class="p-4 card-body">
                                <div class="mb-4 d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-0 fw-bold">Security Settings</h5>
                                        <p class="mb-0 text-muted">Manage your account security</p>
                                    </div>
                                </div>

                                <!-- Change Password Form -->
                                <div class="mb-4">
                                    <h6 class="pb-2 mb-3 fw-semibold border-bottom">Change Password</h6>
                                    <form action="{{ route('seller.settings.password.update') }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label for="current_password" class="form-label">Current Password</label>
                                                <input type="password" name="current_password" id="current_password" 
                                                       class="form-control" placeholder="Enter current password" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="new_password" class="form-label">New Password</label>
                                                <input type="password" name="new_password" id="new_password" 
                                                       class="form-control" placeholder="Enter new password" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                                                       class="form-control" placeholder="Confirm new password" required>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-key me-2"></i> Update Password
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Two-Factor Authentication -->
                                <div class="mb-4">
                                    <h6 class="pb-2 mb-3 fw-semibold border-bottom">Two-Factor Authentication</h6>
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i> 
                                        Add an extra layer of security to your account by enabling two-factor authentication.
                                    </div>
                                    <div class="mb-3 form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="two_factor" name="two_factor">
                                        <label class="form-check-label fw-semibold" for="two_factor">
                                            Enable Two-Factor Authentication
                                        </label>
                                    </div>
                                    <p class="text-muted small">
                                        When enabled, you'll be required to enter a verification code from your authenticator app each time you log in.
                                    </p>
                                </div>

                                <!-- Session Management -->
                                <div class="mb-4">
                                    <h6 class="pb-2 mb-3 fw-semibold border-bottom">Active Sessions</h6>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Device</th>
                                                    <th>Location</th>
                                                    <th>Last Active</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <i class="bi bi-laptop me-2"></i> Windows Chrome
                                                    </td>
                                                    <td>Mumbai, India</td>
                                                    <td>2 minutes ago</td>
                                                    <td>
                                                        <span class="badge bg-success">Current</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="bi bi-phone me-2"></i> Android Chrome
                                                    </td>
                                                    <td>Delhi, India</td>
                                                    <td>1 hour ago</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-danger">Logout</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3">
                                        <button class="btn btn-outline-danger">
                                            <i class="bi bi-door-closed me-2"></i> Logout from all devices
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Settings Tab -->
                    <div class="tab-pane fade" id="v-pills-payment" role="tabpanel">
                        <div class="card rounded-4">
                            <div class="p-4 card-body">
                                <div class="mb-4 d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-0 fw-bold">Payment Settings</h5>
                                        <p class="mb-0 text-muted">Manage payment methods and settings</p>
                                    </div>
                                </div>

                                <!-- Payment Methods -->
                                <div class="mb-4">
                                    <h6 class="pb-2 mb-3 fw-semibold border-bottom">Payment Methods</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="border card">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="payment_method" id="razorpay" checked>
                                                        <label class="form-check-label" for="razorpay">
                                                            <i class="bi bi-credit-card me-2"></i> Razorpay
                                                        </label>
                                                    </div>
                                                    <p class="mt-2 mb-0 text-muted small">Accept payments via Razorpay</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="border card">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="payment_method" id="cod">
                                                        <label class="form-check-label" for="cod">
                                                            <i class="bi bi-cash-coin me-2"></i> Cash on Delivery
                                                        </label>
                                                    </div>
                                                    <p class="mt-2 mb-0 text-muted small">Accept cash on delivery</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payout Settings -->
                                <div class="mb-4">
                                    <h6 class="pb-2 mb-3 fw-semibold border-bottom">Payout Settings</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="payout_method" class="form-label">Payout Method</label>
                                            <select name="payout_method" id="payout_method" class="form-select">
                                                <option value="bank_transfer">Bank Transfer</option>
                                                <option value="upi">UPI</option>
                                                <option value="paypal">PayPal</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="payout_threshold" class="form-label">Payout Threshold</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₹</span>
                                                <input type="number" name="payout_threshold" id="payout_threshold" 
                                                       class="form-control" value="1000" min="100">
                                            </div>
                                            <small class="text-muted">Minimum amount required for payout</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="payout_schedule" class="form-label">Payout Schedule</label>
                                            <select name="payout_schedule" id="payout_schedule" class="form-select">
                                                <option value="weekly">Weekly</option>
                                                <option value="biweekly" selected>Bi-weekly</option>
                                                <option value="monthly">Monthly</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tax Settings -->
                                <div class="mb-4">
                                    <h6 class="pb-2 mb-3 fw-semibold border-bottom">Tax Settings</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                                            <input type="number" name="tax_rate" id="tax_rate" 
                                                   class="form-control" value="18" min="0" max="100" step="0.1">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mt-4 form-check">
                                                <input class="form-check-input" type="checkbox" id="tax_inclusive" name="tax_inclusive" checked>
                                                <label class="form-check-label" for="tax_inclusive">
                                                    Prices include tax
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Settings Tab -->
                    <div class="tab-pane fade" id="v-pills-shipping" role="tabpanel">
                        <div class="card rounded-4">
                            <div class="p-4 card-body">
                                <div class="mb-4 d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-0 fw-bold">Shipping Settings</h5>
                                        <p class="mb-0 text-muted">Configure shipping methods and rates</p>
                                    </div>
                                </div>

                                <!-- Shipping Methods -->
                                <div class="mb-4">
                                    <h6 class="pb-2 mb-3 fw-semibold border-bottom">Shipping Methods</h6>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="free_shipping" name="free_shipping">
                                            <label class="form-check-label" for="free_shipping">
                                                Offer free shipping
                                            </label>
                                        </div>
                                        <small class="text-muted">Provide free shipping for all orders</small>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="flat_rate" name="flat_rate" checked>
                                            <label class="form-check-label" for="flat_rate">
                                                Flat rate shipping
                                            </label>
                                        </div>
                                        <small class="text-muted">Charge a fixed rate for shipping</small>
                                    </div>
                                </div>

                                <!-- Shipping Rates -->
                                <div class="mb-4">
                                    <h6 class="pb-2 mb-3 fw-semibold border-bottom">Shipping Rates</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="domestic_rate" class="form-label">Domestic Rate (₹)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₹</span>
                                                <input type="number" name="domestic_rate" id="domestic_rate" 
                                                       class="form-control" value="50" min="0">
                                            </div>
                                            <small class="text-muted">Shipping rate within India</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="international_rate" class="form-label">International Rate (₹)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₹</span>
                                                <input type="number" name="international_rate" id="international_rate" 
                                                       class="form-control" value="500" min="0">
                                            </div>
                                            <small class="text-muted">Shipping rate outside India</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="processing_time" class="form-label">Processing Time</label>
                                            <select name="processing_time" id="processing_time" class="form-select">
                                                <option value="1">1 business day</option>
                                                <option value="2" selected>2 business days</option>
                                                <option value="3-5">3-5 business days</option>
                                                <option value="5-7">5-7 business days</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Privacy Tab -->
                    <div class="tab-pane fade" id="v-pills-privacy" role="tabpanel">
                        <div class="card rounded-4">
                            <div class="p-4 card-body">
                                <div class="mb-4 d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-0 fw-bold">Privacy Settings</h5>
                                        <p class="mb-0 text-muted">Manage your privacy preferences</p>
                                    </div>
                                </div>

                                <!-- Profile Privacy -->
                                <div class="mb-4">
                                    <h6 class="pb-2 mb-3 fw-semibold border-bottom">Profile Privacy</h6>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="show_profile" name="show_profile" checked>
                                            <label class="form-check-label" for="show_profile">
                                                Show profile to public
                                            </label>
                                        </div>
                                        <small class="text-muted">Allow others to view your seller profile</small>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="show_email" name="show_email">
                                            <label class="form-check-label" for="show_email">
                                                Show email address
                                            </label>
                                        </div>
                                        <small class="text-muted">Display your email address on your profile</small>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="show_phone" name="show_phone">
                                            <label class="form-check-label" for="show_phone">
                                                Show phone number
                                            </label>
                                        </div>
                                        <small class="text-muted">Display your phone number on your profile</small>
                                    </div>
                                </div>

                                <!-- Data Preferences -->
                                <div class="mb-4">
                                    <h6 class="pb-2 mb-3 fw-semibold border-bottom">Data Preferences</h6>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="data_analytics" name="data_analytics" checked>
                                            <label class="form-check-label" for="data_analytics">
                                                Allow data analytics
                                            </label>
                                        </div>
                                        <small class="text-muted">Help us improve by sharing anonymous usage data</small>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="marketing_emails" name="marketing_emails">
                                            <label class="form-check-label" for="marketing_emails">
                                                Marketing emails
                                            </label>
                                        </div>
                                        <small class="text-muted">Receive promotional emails and offers</small>
                                    </div>
                                </div>

                                <!-- Data Management -->
                                <div class="mb-4">
                                    <h6 class="pb-2 mb-3 fw-semibold border-bottom">Data Management</h6>
                                    <div class="alert alert-warning">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        These actions are permanent and cannot be undone.
                                    </div>
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#exportDataModal">
                                            <i class="bi bi-download me-2"></i> Export My Data
                                        </button>
                                        <small class="mt-1 text-muted d-block">Download a copy of your personal data</small>
                                    </div>
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                            <i class="bi bi-trash me-2"></i> Delete Account
                                        </button>
                                        <small class="mt-1 text-muted d-block">Permanently delete your account and all data</small>
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

@include('seller.layouts.footer')

<style>
    .nav-pills .nav-link {
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 8px;
        color: var(--bs-body-color);
        font-weight: 500;
        text-align: left
    }
    
    .nav-pills .nav-link.active {
        background-color: #0d6efd;
        color: white;
    }
    
    .nav-pills .nav-link i {
        font-size: 18px;
        width: 24px;
    }
    
    .card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .form-check.form-switch .form-check-input {
        height: 1.5em;
        width: 3em;
    }
    
    .form-check.form-switch .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .text-muted{
        color: var(--bs-body-color) !important;
    }
    
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }
        
        .nav-pills .nav-link {
            padding: 10px 12px;
            font-size: 0.9rem;
        }
    }
</style>