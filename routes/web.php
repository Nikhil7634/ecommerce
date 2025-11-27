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
    DashboardController,
    CartController,
    WishlistController,
    ProfileController,
    OrderController,
    DownloadController,
    ReturnController,
    AddressController,
    ReviewController
};

// BECOME SELLER CONTROLLER
use App\Http\Controllers\Auth\RegisterController;



// Seller Routes
Route::post('/become-seller', [RegisterController::class, 'registerSeller'])->name('become.seller.store');


 


// ======================
// SELLER ROUTES – FINAL & CORRECT (Matches Your Controller Names)
// ======================
Route::prefix('seller')->name('seller.')->middleware([
    'auth',
    \App\Http\Middleware\EnsureIsSeller::class
])->group(function () {

    Route::middleware(\App\Http\Middleware\SellerInactive::class)->group(function () {

        // Pending Approval Page
        Route::get('/pending', fn() => view('seller.pending'))->name('pending');

        // Dashboard
        Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('/profile', [SellerProfileController::class, 'index'])->name('profile');
        Route::get('/profile/edit', [SellerProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [SellerProfileController::class, 'update'])->name('profile.update');

        // Store
        Route::get('/store', [SellerStoreController::class, 'index'])->name('store');
        Route::get('/store/edit', [SellerStoreController::class, 'edit'])->name('store.edit');
        Route::patch('/store', [SellerStoreController::class, 'update'])->name('store.update');

        // Products
        Route::resource('products', SellerProductController::class)->except(['show']);

        // Orders
        Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders');
        Route::get('/orders/{id}', [SellerOrderController::class, 'show'])->name('order.show');
        Route::patch('/orders/{id}/status', [SellerOrderController::class, 'updateStatus'] ?? fn() => back())
            ->name('order.update-status');

        // Earnings
        Route::get('/earnings', [SellerEarningController::class, 'index'])->name('earnings');

        // Withdraw
        Route::get('/withdraw', [SellerWithdrawController::class, 'index'])->name('withdraw');
        Route::post('/withdraw/request', [SellerWithdrawController::class, 'request'] ?? fn() => back())
            ->name('withdraw.request');

        // Subscription
        Route::get('/subscription', [SellerSubscriptionController::class, 'index'])->name('subscription');

        // Reviews
        Route::get('/reviews', [SellerReviewController::class, 'index'])->name('reviews');

        // Support Tickets
        Route::get('/tickets', [SellerTicketController::class, 'index'])->name('tickets');
        Route::get('/tickets/create', [SellerTicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [SellerTicketController::class, 'store'] ?? fn() => back())->name('tickets.store');
        Route::get('/tickets/{id}', [SellerTicketController::class, 'show'] ?? fn() => back())->name('tickets.show');

        // Settings
        Route::get('/settings', [SellerSettingController::class, 'index'])->name('settings');
        Route::patch('/settings', [SellerSettingController::class, 'update'] ?? fn() => back())->name('settings.update');

    });
});

// ======================
// BUYER ROUTES – ONLY REAL BUYERS CAN ENTER
// ======================
Route::prefix('buyer')->name('buyer.')->middleware(['auth', \App\Http\Middleware\EnsureIsBuyer::class])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/password', [ProfileController::class, 'password']);
    Route::post('/profile/avatar/update', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

    // Orders & Others
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('order.show');
    Route::get('/downloads', [DownloadController::class, 'index'])->name('downloads');
    Route::get('/return-policy', [ReturnController::class, 'index'])->name('return.policy');
    Route::get('/address', [AddressController::class, 'index'])->name('address');
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');
});


// ======================
// PUBLIC ROUTES
// ======================

Route::get('/', function () {
    return view('home');
})->name('home');

// Become a Seller Page (GET = show form)
Route::get('/become-seller', [SellerController::class, 'index'])->name('become.seller');

// Become a Seller Form Submit (POST = save data)
Route::post('/become-seller', [RegisterController::class, 'registerSeller'])->name('become.seller.store');

// Other Public Pages
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/stores', [StoreController::class, 'index'])->name('stores');
Route::get('/flash-deals', [FlashDealController::class, 'index'])->name('flash.deals');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/category', [CategoryController::class, 'index'])->name('category');

// ======================
// SOCIAL LOGIN
// ======================
Route::get('/auth/{provider}/redirect', [SocialController::class, 'redirectToProvider'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialController::class, 'handleProviderCallback'])->name('social.callback');

 


 
// ======================
// ADMIN ROUTES
// ======================

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

// ======================
// RAZORPAY WEBHOOK
// ======================
Route::post('/webhook/razorpay', [SubscriptionController::class, 'webhook'])
    ->name('webhook.razorpay')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// ======================
// DEFAULT AUTH ROUTES (Login, Register, etc.)
// ======================
require __DIR__.'/auth.php';