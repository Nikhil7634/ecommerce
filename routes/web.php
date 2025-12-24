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
Route::get('/stores', [StoreController::class, 'index'])->name('stores');
Route::get('/flash-deals', [FlashDealController::class, 'index'])->name('flash.deals');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/category', [CategoryController::class, 'index'])->name('category');
// Single category with products (optional)
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');

// Become Seller
Route::get('/become-seller', [SellerController::class, 'index'])->name('become.seller');
Route::post('/become-seller', [RegisterController::class, 'registerSeller'])->name('become.seller.store');

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
        Route::get('/orders/{id}', [SellerOrderController::class, 'show'])->name('order.show');
        Route::patch('/orders/{id}/status', [SellerOrderController::class, 'updateStatus'])->name('order.update-status');

        Route::get('/earnings', [SellerEarningController::class, 'index'])->name('earnings');

        Route::get('/withdraw', [SellerWithdrawController::class, 'index'])->name('withdraw');
        Route::post('/withdraw/request', [SellerWithdrawController::class, 'request'])->name('withdraw.request');

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
    });
});

// ======================
// BUYER ROUTES
// ======================
Route::prefix('buyer')->name('buyer.')->middleware(['auth', \App\Http\Middleware\EnsureIsBuyer::class])->group(function () {

    Route::get('/dashboard', [BuyerDashboardController::class, 'index'])->name('dashboard');

    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    Route::get('/profile', [BuyerProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [BuyerProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [BuyerProfileController::class, 'update']);
    Route::post('/profile/password', [BuyerProfileController::class, 'password']);
    Route::post('/profile/avatar/update', [BuyerProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

    Route::get('/orders', [BuyerOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{id}', [BuyerOrderController::class, 'show'])->name('order.show');

    Route::get('/downloads', [DownloadController::class, 'index'])->name('downloads');
    Route::get('/return-policy', [ReturnController::class, 'index'])->name('return.policy');
    Route::get('/address', [AddressController::class, 'index'])->name('address');
    Route::get('/reviews', [BuyerReviewController::class, 'index'])->name('reviews');
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