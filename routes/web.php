<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\FlashDealController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CategoryController;

// Seller Controllers
use App\Http\Controllers\Seller\DashboardController      as SellerDashboardController;
use App\Http\Controllers\Seller\ProfileController        as SellerProfileController;
use App\Http\Controllers\Seller\StoreController          as SellerStoreController;
use App\Http\Controllers\Seller\ProductController        as SellerProductController;
use App\Http\Controllers\Seller\OrderController          as SellerOrderController;
use App\Http\Controllers\Seller\EarningController        as SellerEarningController;
use App\Http\Controllers\Seller\WithdrawController       as SellerWithdrawController;
use App\Http\Controllers\Seller\SubscriptionController   as SellerSubscriptionController;
use App\Http\Controllers\Seller\ReviewController         as SellerReviewController;
use App\Http\Controllers\Seller\TicketController         as SellerTicketController;
use App\Http\Controllers\Seller\SettingController        as SellerSettingController;

// Buyer Controllers
use App\Http\Controllers\Buyer\{
    DashboardController as BuyerDashboardController,
    CartController,
    WishlistController,
    ProfileController as BuyerProfileController,
    OrderController as BuyerOrderController,
    DownloadController,
    ReturnController,
    AddressController,
    ReviewController as BuyerReviewController
};

// Become Seller
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Middleware\Seller;

// ======================
// PUBLIC ROUTES
// ======================

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');

 
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('product.show');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/buy-now', [CartController::class, 'buyNow'])->name('cart.buyNow');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
// To this

Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.get-count');
Route::post('/razorpay/create-order', [CartController::class, 'createRazorpayOrder'])->name('razorpay.create-order');
Route::get('/stores', [StoreController::class, 'index'])->name('stores');
Route::get('/flash-deals', [FlashDealController::class, 'index'])->name('flash.deals');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/category', [CategoryController::class, 'index'])->name('category');
// Single category with products (optional)
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');
Route::post('/razorpay/callback', [CartController::class, 'razorpayCallback'])->name('razorpay.callback');
Route::get('/checkout/success/{order}', [CartController::class, 'checkoutSuccess'])->name('checkout.success');
Route::get('/checkout/success/{order}', [CartController::class, 'checkoutSuccess'])->name('checkout.success');
Route::get('/checkout/cancel', [CartController::class, 'checkoutCancel'])->name('checkout.cancel');

// Become Seller
Route::get('/become-seller', [SellerController::class, 'index'])->name('become.seller');
Route::post('/become-seller', [RegisterController::class, 'registerSeller'])->name('become.seller.store');
Route::get('/store/{slug}', [StoreController::class, 'show'])->name('vendor.details');

// Social Login
Route::get('/auth/{provider}/redirect', [SocialController::class, 'redirectToProvider'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialController::class, 'handleProviderCallback'])->name('social.callback');

// ======================
// SELLER ROUTES
// ======================
Route::prefix('seller')->name('seller.')->middleware([
    'auth',
    \App\Http\Middleware\EnsureIsSeller::class
])->group(function () {

    Route::middleware(\App\Http\Middleware\SellerInactive::class)->group(function () {

        Route::get('/pending', fn() => view('seller.pending'))->name('pending');

        Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [SellerProfileController::class, 'index'])->name('profile');
        Route::get('/profile/edit', [SellerProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [SellerProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/avatar', [SellerProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

        Route::get('/store', [SellerStoreController::class, 'index'])->name('store');
        Route::get('/store/edit', [SellerStoreController::class, 'edit'])->name('store.edit');
        Route::patch('/store', [SellerStoreController::class, 'update'])->name('store.update');

        Route::resource('products', SellerProductController::class)->except(['show']);
        
        
        // IMAGE MANAGEMENT ROUTES - MUST COME BEFORE PRODUCTS RESOURCE
        Route::delete('/products/{product}/images/{image}', [SellerProductController::class, 'removeImage'])
            ->name('products.images.destroy');
        
        Route::post('/products/{product}/images/{image}/set-primary', [SellerProductController::class, 'setPrimaryImage'])
            ->name('products.images.set-primary');
 

        Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders');
        Route::get('/orders/export', [SellerOrderController::class, 'export'])->name('orders.export');
        Route::get('/orders/{order}', [SellerOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/status', [SellerOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('/orders/bulk-update', [SellerOrderController::class, 'bulkUpdateStatus'])->name('orders.bulk-update');
            
        // Earnings
        Route::get('/earnings', [SellerEarningController::class, 'index'])->name('earnings');
        Route::get('/earnings/report', [SellerEarningController::class, 'report'])->name('earnings.report');
        Route::get('/earnings/export', [SellerEarningController::class, 'export'])->name('earnings.export');
        
        // Withdrawals
        Route::get('/earnings/withdrawal', [SellerEarningController::class, 'withdrawalForm'])->name('earnings.withdrawal');
        Route::post('/earnings/withdrawal', [SellerEarningController::class, 'requestWithdrawal'])->name('earnings.withdrawal.request');
        Route::get('/earnings/withdrawals', [SellerEarningController::class, 'withdrawals'])->name('earnings.withdrawals');
        Route::get('/earnings/withdrawals/{id}', [SellerEarningController::class, 'withdrawalDetails'])->name('earnings.withdrawal.details');
        Route::post('/earnings/withdrawals/{id}/cancel', [SellerEarningController::class, 'cancelWithdrawal'])->name('earnings.withdrawal.cancel');

        Route::get('/subscription', [SellerSubscriptionController::class, 'index'])->name('subscription');
        Route::get('/subscription/checkout/{plan}', [SellerSubscriptionController::class, 'checkout'])->name('subscription.checkout');
        Route::post('/subscription/create-order/{plan}', [SellerSubscriptionController::class, 'createOrder'])->name('subscription.createOrder');
        Route::post('/subscription/verify-payment', [SellerSubscriptionController::class, 'verifyPayment'])->name('subscription.verifyPayment');

        Route::post('/webhook/razorpay-subscription', [SellerSubscriptionController::class, 'webhook'])->name('subscription.webhook');
        Route::post('/subscription/pay/{plan}', [SellerSubscriptionController::class, 'pay'])->name('subscription.pay');
        Route::get('/subscription/success', [SellerSubscriptionController::class, 'success'])->name('subscription.success');
        Route::post('/subscription/verify', [SellerSubscriptionController::class, 'verify'])->name('subscription.verify');

        Route::get('/reviews', [SellerReviewController::class, 'index'])->name('reviews');

        Route::get('/tickets', [SellerTicketController::class, 'index'])->name('tickets');
        Route::get('/tickets/create', [SellerTicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [SellerTicketController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{id}', [SellerTicketController::class, 'show'])->name('tickets.show');

        Route::get('/settings', [SellerSettingController::class, 'index'])->name('settings');
        Route::patch('/settings', [SellerSettingController::class, 'update'])->name('settings.update');

        Route::patch('/settings/notifications', [SellerSettingController::class, 'updateNotifications'])->name('settings.notifications.update');
        Route::patch('/settings/password', [SellerSettingController::class, 'updatePassword'])->name('settings.password.update');
        Route::get('/settings/check-slug', [SellerSettingController::class, 'checkSlug'])->name('settings.check-slug');
        Route::post('/settings/logout-all', [SellerSettingController::class, 'logoutAll'])->name('settings.logout-all');
    });
});

// ======================
// BUYER ROUTES
// ======================
Route::prefix('buyer')->name('buyer.')->middleware(['auth', \App\Http\Middleware\EnsureIsBuyer::class])->group(function () {

    Route::get('/dashboard', [BuyerDashboardController::class, 'index'])->name('dashboard');

    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/buy-now', [CartController::class, 'buyNow'])->name('cart.buyNow');
    Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.get-count');
 
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Checkout routes
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');
    Route::post('/razorpay/callback', [CartController::class, 'razorpayCallback'])->name('razorpay.callback');
    Route::get('/checkout/success/{order}', [CartController::class, 'checkoutSuccess'])->name('checkout.success');
    Route::get('/checkout/cancel', [CartController::class, 'checkoutCancel'])->name('checkout.cancel');
    Route::get('/checkout/failed', [CartController::class, 'checkoutFailed'])->name('checkout.failed');
    
    // Razorpay routes - FIXED: Added {order} parameter
    Route::post('/razorpay/create-order', [CartController::class, 'createRazorpayOrder'])->name('razorpay.create-order');
    Route::get('/razorpay/payment/{order}', [CartController::class, 'razorpayPaymentPage'])->name('razorpay.payment');
     
    
    Route::get('/profile', [BuyerProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [BuyerProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [BuyerProfileController::class, 'update']);
    Route::post('/profile/password', [BuyerProfileController::class, 'password']);
    Route::post('/profile/avatar/update', [BuyerProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

     
    Route::get('/downloads', [DownloadController::class, 'index'])->name('downloads');
    Route::get('/return-policy', [ReturnController::class, 'index'])->name('return.policy');
    Route::get('/address', [AddressController::class, 'index'])->name('address');
    Route::get('/reviews', [BuyerReviewController::class, 'index'])->name('reviews');
    Route::get('/reviews/create/{order}', [BuyerReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [BuyerReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit', [BuyerReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [BuyerReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [BuyerReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/orders', [BuyerOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [BuyerOrderController::class, 'show'])->name('order.show');
    Route::post('/orders/{order}/cancel', [BuyerOrderController::class, 'cancel'])->name('order.cancel');
    Route::get('/orders/{order}/invoice', [BuyerOrderController::class, 'invoice'])->name('order.invoice');
    Route::post('/orders/{order}/track', [BuyerOrderController::class, 'track'])->name('order.track');
    Route::post('/razorpay/callback', [CartController::class, 'razorpayCallback'])->name('razorpay.callback');
    Route::get('/razorpay/payment', [CartController::class, 'razorpayPaymentPage'])->name('razorpay.payment');
    Route::post('/razorpay/create-order', [CartController::class, 'createRazorpayOrder'])->name('razorpay.create-order');
});

// ======================
// ADMIN ROUTES – CLEAN & NO CONFLICTS
// ======================
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Sellers
        Route::get('/sellers', [App\Http\Controllers\Admin\SellerController::class, 'index'])->name('sellers.index');
        Route::get('/sellers/pending', [App\Http\Controllers\Admin\SellerController::class, 'pending'])->name('sellers.pending');
        Route::get('/sellers/suspended', [App\Http\Controllers\Admin\SellerController::class, 'suspended'])->name('sellers.suspended');

        Route::patch('/sellers/{user}/suspend', [App\Http\Controllers\Admin\SellerController::class, 'suspend'])->name('sellers.suspend');
        Route::patch('/sellers/{user}/activate', [App\Http\Controllers\Admin\SellerController::class, 'activate'])->name('sellers.activate');
        Route::patch('/sellers/{user}/approve', [App\Http\Controllers\Admin\SellerController::class, 'approve'])->name('sellers.approve');
        Route::patch('/sellers/{user}/reject', [App\Http\Controllers\Admin\SellerController::class, 'reject'])->name('sellers.reject');

        Route::resource('sellers', App\Http\Controllers\Admin\SellerController::class)->only(['index', 'show']);

        // Buyers
        Route::resource('buyers', App\Http\Controllers\Admin\BuyerController::class)->except(['show']);
        Route::get('/buyers/blocked', [App\Http\Controllers\Admin\BuyerController::class, 'blocked'])->name('buyers.blocked');
        Route::get('/buyers/{buyer}', [App\Http\Controllers\Admin\BuyerController::class, 'show'])->name('buyers.show');
        Route::patch('/buyers/{buyer}/ban', [App\Http\Controllers\Admin\BuyerController::class, 'ban'])->name('buyers.ban');
        Route::patch('/buyers/{buyer}/unban', [App\Http\Controllers\Admin\BuyerController::class, 'unban'])->name('buyers.unban');

        // Products & Categories
        Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);

        // Orders
        Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');

        // Subscriptions — CLEAN RESOURCE ROUTE (NO DUPLICATES)
        Route::resource('subscriptions', App\Http\Controllers\Admin\SubscriptionPlanController::class)->except(['show']);

        // Custom Toggle for Subscriptions
        Route::patch('/subscriptions/{subscription}/toggle', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'toggle'])
            ->name('subscriptions.toggle');

        // Payments
        Route::get('/payments/transactions', [App\Http\Controllers\Admin\PaymentController::class, 'transactions'])->name('payments.transactions');
        Route::get('/payments/withdrawals', [App\Http\Controllers\Admin\PaymentController::class, 'withdrawals'])->name('payments.withdrawals');
        Route::patch('/payments/withdrawals/{id}/approve', [App\Http\Controllers\Admin\PaymentController::class, 'approveWithdrawal'])->name('payments.withdrawal.approve');

        // Reports & Settings
        Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');

        Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::patch('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

        // Content
        Route::get('/content/pages', [App\Http\Controllers\Admin\ContentController::class, 'pages'])->name('content.pages');
        Route::get('/content/banners', [App\Http\Controllers\Admin\ContentController::class, 'banners'])->name('content.banners');

        // ========== MISSING ROUTES ==========

        // 1. SUPPORT & CHAT ROUTES
        Route::prefix('support')->name('support.')->group(function () {
            // Chat Interface
            Route::get('/chat', [App\Http\Controllers\Admin\SupportChatController::class, 'index'])->name('chat');
            
            // Get chat messages for a specific user
            Route::get('/chat/messages/{userId}', [App\Http\Controllers\Admin\SupportChatController::class, 'getMessages'])->name('chat.messages');
            
            // Send message
            Route::post('/chat/send', [App\Http\Controllers\Admin\SupportChatController::class, 'sendMessage'])->name('chat.send');
            
            // Mark messages as read
            Route::post('/chat/mark-read/{userId}', [App\Http\Controllers\Admin\SupportChatController::class, 'markAsRead'])->name('chat.mark-read');
            
            // Get unread message count
            Route::get('/chat/unread-count', [App\Http\Controllers\Admin\SupportChatController::class, 'unreadCount'])->name('chat.unread-count');
            
            // Get online users
            Route::get('/chat/online-users', [App\Http\Controllers\Admin\SupportChatController::class, 'onlineUsers'])->name('chat.online-users');
            
            // Support Tickets
            Route::get('/tickets', [App\Http\Controllers\Admin\SupportTicketController::class, 'index'])->name('tickets');
            Route::get('/tickets/{ticket}', [App\Http\Controllers\Admin\SupportTicketController::class, 'show'])->name('tickets.show');
            Route::post('/tickets/{ticket}/reply', [App\Http\Controllers\Admin\SupportTicketController::class, 'reply'])->name('tickets.reply');
            Route::patch('/tickets/{ticket}/status', [App\Http\Controllers\Admin\SupportTicketController::class, 'updateStatus'])->name('tickets.update-status');
        });

        // 2. CATEGORIES CREATE ROUTE (Missing from resource)
        // Note: The resource route already includes this, but since you have a specific link in sidebar:
        Route::get('/categories/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');

        // 3. CONTENT MANAGEMENT ADDITIONAL ROUTES
        Route::prefix('content')->name('content.')->group(function () {
            // Pages CRUD
            Route::post('/pages', [App\Http\Controllers\Admin\ContentController::class, 'storePage'])->name('pages.store');
            Route::get('/pages/create', [App\Http\Controllers\Admin\ContentController::class, 'createPage'])->name('pages.create');
            Route::get('/pages/{page}/edit', [App\Http\Controllers\Admin\ContentController::class, 'editPage'])->name('pages.edit');
            Route::put('/pages/{page}', [App\Http\Controllers\Admin\ContentController::class, 'updatePage'])->name('pages.update');
            Route::delete('/pages/{page}', [App\Http\Controllers\Admin\ContentController::class, 'destroyPage'])->name('pages.destroy');
            
            // Banners CRUD
            Route::post('/banners', [App\Http\Controllers\Admin\ContentController::class, 'storeBanner'])->name('banners.store');
            Route::get('/banners/create', [App\Http\Controllers\Admin\ContentController::class, 'createBanner'])->name('banners.create');
            Route::get('/banners/{banner}/edit', [App\Http\Controllers\Admin\ContentController::class, 'editBanner'])->name('banners.edit');
            Route::put('/banners/{banner}', [App\Http\Controllers\Admin\ContentController::class, 'updateBanner'])->name('banners.update');
            Route::delete('/banners/{banner}', [App\Http\Controllers\Admin\ContentController::class, 'destroyBanner'])->name('banners.destroy');
        });

        // 4. REPORT ADDITIONAL ROUTES (if needed for exports or filters)
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/sales', [App\Http\Controllers\Admin\ReportController::class, 'salesReport'])->name('sales');
            Route::get('/users', [App\Http\Controllers\Admin\ReportController::class, 'usersReport'])->name('users');
            Route::get('/products', [App\Http\Controllers\Admin\ReportController::class, 'productsReport'])->name('products');
            Route::get('/export/{type}', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('export');
        });

        // 5. PAYMENT ADDITIONAL ROUTES
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/transactions/export', [App\Http\Controllers\Admin\PaymentController::class, 'exportTransactions'])->name('transactions.export');
            Route::get('/withdrawals/{id}', [App\Http\Controllers\Admin\PaymentController::class, 'showWithdrawal'])->name('withdrawals.show');
            Route::patch('/withdrawals/{id}/reject', [App\Http\Controllers\Admin\PaymentController::class, 'rejectWithdrawal'])->name('withdrawals.reject');
        });

        // 6. SELLER ADDITIONAL ROUTES (for completeness)
        Route::prefix('sellers')->name('sellers.')->group(function () {
            Route::get('/{seller}/edit', [App\Http\Controllers\Admin\SellerController::class, 'edit'])->name('edit');
            Route::put('/{seller}', [App\Http\Controllers\Admin\SellerController::class, 'update'])->name('update');
            Route::delete('/{seller}', [App\Http\Controllers\Admin\SellerController::class, 'destroy'])->name('destroy');
        });

        // 7. PRODUCT ADDITIONAL ROUTES
        Route::prefix('products')->name('products.')->group(function () {
            Route::patch('/{product}/approve', [App\Http\Controllers\Admin\ProductController::class, 'approve'])->name('approve');
            Route::patch('/{product}/reject', [App\Http\Controllers\Admin\ProductController::class, 'reject'])->name('reject');
            Route::patch('/{product}/feature', [App\Http\Controllers\Admin\ProductController::class, 'toggleFeature'])->name('toggle-feature');
            Route::get('/pending', [App\Http\Controllers\Admin\ProductController::class, 'pending'])->name('pending');
        });

        // 8. ORDER ADDITIONAL ROUTES
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/today', [App\Http\Controllers\Admin\OrderController::class, 'today'])->name('today');
            Route::get('/pending', [App\Http\Controllers\Admin\OrderController::class, 'pending'])->name('pending');
            Route::get('/completed', [App\Http\Controllers\Admin\OrderController::class, 'completed'])->name('completed');
            Route::get('/cancelled', [App\Http\Controllers\Admin\OrderController::class, 'cancelled'])->name('cancelled');
            Route::post('/{order}/refund', [App\Http\Controllers\Admin\OrderController::class, 'refund'])->name('refund');
        });

        // 9. DASHBOARD ANALYTICS ROUTES
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/analytics', [App\Http\Controllers\Admin\DashboardController::class, 'analytics'])->name('analytics');
            Route::get('/revenue', [App\Http\Controllers\Admin\DashboardController::class, 'revenueData'])->name('revenue');
            Route::get('/top-products', [App\Http\Controllers\Admin\DashboardController::class, 'topProducts'])->name('top-products');
            Route::get('/recent-orders', [App\Http\Controllers\Admin\DashboardController::class, 'recentOrders'])->name('recent-orders');
        });

        // 10. USER ACTIVITY LOGS (Optional but useful)
        Route::get('/activity-logs', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs');
        Route::get('/activity-logs/{log}', [App\Http\Controllers\Admin\ActivityLogController::class, 'show'])->name('activity-logs.show');
    });

// ======================
// RAZORPAY WEBHOOK
// ======================
Route::post('/webhook/razorpay', [SubscriptionController::class, 'webhook'])
    ->name('webhook.razorpay')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

 

// ======================
// AUTH ROUTES
// ======================
require __DIR__.'/auth.php';