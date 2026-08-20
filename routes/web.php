<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\affiliate\AffiliateController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\GeneralWebSettingsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\Customer\WalletPointController;
use App\Http\Controllers\FacebookCatalogController;
use App\Http\Controllers\FrontCategoryController;
use App\Http\Controllers\Frontend\AiSupportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SupportTicketController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('guest:admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login']);
});

// Public Catalog Feeds (Unrestricted for Web Crawlers & Meta)
Route::get('/facebook-catalog.xml', [FacebookCatalogController::class, 'index'])->name('facebook.catalog.xml');
Route::get('/facebook-feed.xml', [FacebookCatalogController::class, 'index']);
Route::get('/products-feed.xml', [FacebookCatalogController::class, 'index']);
Route::get('/facebook-catalog.csv', [FacebookCatalogController::class, 'csv'])->name('facebook.catalog.csv');
Route::get('/facebook-feed.csv', [FacebookCatalogController::class, 'csv']);
Route::get('/products-feed.csv', [FacebookCatalogController::class, 'csv']);

Route::middleware(['maintainance'])->group(function () {

    Route::match(['get', 'post'], '/verifyEmail', [HomeController::class, 'verifyEmail'])->name('front.verifyEmail');
    Route::match(['get', 'post'], '/otpEmail', [HomeController::class, 'otpEmail'])->name('front.otpEmail');
    Route::match(['get', 'post'], '/welcomeEmail', [HomeController::class, 'welcomeEmail'])->name('front.welcomeEmail');

    Route::get('/user-dashboard', [HomeController::class, 'userDash'])->middleware(['auth', 'verified'])->name('dashboard');
    Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::match(['get', 'post'], '/track-order', [HomeController::class, 'trackOrder'])->name('front.trackOrder');
    Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
    Route::get('/flash-sale', [HomeController::class, 'flashSale'])->name('front.flashSale');
    Route::get('/search', [HomeController::class, 'search'])->name('front.search');
    Route::post('/ajax-search', [HomeController::class, 'ajaxSearch'])->name('front.ajaxSearch');

    // AI Customer Support Routes
    Route::get('/ai-support/history', [AiSupportController::class, 'getHistory'])->name('aiSupport.history');
    Route::post('/ai-support/send', [AiSupportController::class, 'sendMessage'])->name('aiSupport.send');
    Route::post('/ai-support/transfer', [AiSupportController::class, 'transferToAgent'])->name('aiSupport.transfer');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/purchase-history', [ProfileController::class, 'purchaseHistory'])->name('purchaseHistory');
        Route::get('/to-review', [ProfileController::class, 'toReview'])->name('toReview');
        Route::post('/review/store', [ProfileController::class, 'storeReview'])->name('review.store');
        Route::post('/review/update', [ProfileController::class, 'updateReview'])->name('review.update');
        Route::post('/review/get-data', [ProfileController::class, 'getReviewData'])->name('review.getData');
        Route::get('/wishlist', [ProfileController::class, 'wishlist'])->name('wishlist');
        Route::post('/wishlist/add', [ProfileController::class, 'addWishlist'])->name('wishlist.add');
        Route::post('/wishlist/remove', [ProfileController::class, 'removeWishlist'])->name('wishlist.remove');
        Route::get('/compare', [ProfileController::class, 'compare'])->name('compare');
        Route::get('/conversation', [ChatController::class, 'index'])->name('conversation');
        Route::get('/conversation/messages', [ChatController::class, 'getMessages'])->name('conversation.messages');
        Route::post('/conversation/send', [ChatController::class, 'sendMessage'])->name('conversation.send');
        Route::get('/my-wallet', [WalletPointController::class, 'myWallet'])->name('myWallet');
        Route::post('/wallet/recharge', [WalletPointController::class, 'recharge'])->name('wallet.recharge');
        Route::get('/wallet/recharge/bkash/callback', [WalletPointController::class, 'bkashCallback'])->name('wallet.recharge.bkash.callback');
        Route::post('/wallet/convert-points', [WalletPointController::class, 'convertPoints'])->name('wallet.convert-points');
        Route::get('/support-ticket', [SupportTicketController::class, 'index'])->name('supportTicket');
        Route::post('/support-ticket', [SupportTicketController::class, 'store'])->name('supportTicket.store');
    });

    Route::controller(CartController::class)->group(function () {
        Route::get('/cart/view', 'cartView')->name('cartView');
        Route::post('/cart/add', 'addToCart')->name('cart.add');
        Route::get('/cart/show-modal', 'showModal')->name('cart.showModal');
        Route::post('/cart/update', 'updateCart')->name('cart.update');
        Route::post('/cart/remove', 'removeFromCart')->name('cart.remove');
        Route::get('/cart/count', 'getCartCount')->name('cart.count');
    });

    Route::controller(CheckoutController::class)->group(function () {
        Route::match(['get', 'post'], '/checkout', 'checkout')->name('checkout');
        Route::post('/checkout/coupon-apply', 'applyCoupon')->name('coupon.apply');
        Route::post('/check-customer-fraud', 'checkFraud')->name('check.fraud');
        Route::post('/storeIncompleteOrder', 'storeIncompleteOrder')->name('order.incomplete.store');
        Route::post('/storeOrder', 'storeOrder')->name('order.store');

        Route::post('/bkashPayment', 'bkashPayment')->name('bkash.payment');
        Route::get('/bkash/callback', 'bkashCallback')->name('bkash.callback');
        Route::post('/bkash/refund/{id}', 'bkashRefund')->name('bkash.refund');

        Route::post('/othersPayment', 'othersPayment')->name('others.payment');
        Route::post('/ssl/success', 'success')->name('ssl.success');
        Route::post('/ssl/fail', function () {
            return redirect()->route('checkout')->with('error', 'Payment Failed');
        })->name('ssl.fail');
        Route::post('/ssl/cancel', function () {
            return redirect()->route('checkout')->with('error', 'Payment Cancelled');
        })->name('ssl.cancel');

        Route::post('/storeOrder/test', 'storeOrderTest')->name('order.store.test');
        Route::get('/order/success/{id}', 'showInvoice')->name('order.invoice');
        Route::get('/order/print/{id}', 'printInvoice')->name('order.print');
        Route::post('/test-run', 'testrun')->name('check.test');
    });

    Route::controller(FrontCategoryController::class)->group(function () {
        Route::get('/all-category', 'allcategory')->name('front.allCategory');
        Route::get('/category/{id}/{slug}', 'catProductView')->name('catProductView')->where('id', '[0-9]+');
        Route::get('/sub-category/{id}/{slug}', 'subCatProductView')->name('subCatProductView')->where('id', '[0-9]+');
        Route::get('/child-category/{id}/{slug}', 'childCatProductView')->name('childCatProductView')->where('id', '[0-9]+');
        Route::get('/product/compare', 'ProductCompare')->name('ProductCompare');
        Route::get('/product/{id}/{slug}', 'ProductView')->name('ProductView')->where('id', '[0-9]+');
        Route::get('/page/{slug}', 'pages')->name('pages');
        Route::get('/category-filter', 'filterProducts')->name('front.category.filter');
    });

    Route::controller(AddressController::class)->group(function () {
        Route::post('addresses/store', 'store')->name('addresses.store');
        Route::get('addresses/edit/{id}', 'edit')->name('address.edit');
        Route::post('addresses/update/{id}', 'update')->name('address.update');
        Route::get('/get-thanas/{district_id}', 'getThanasByDistrict')->name('get.thanas');
        Route::post('/addresses/set-default', 'set_default')->name('addresses.default');
        Route::delete('/addresses/destroy/{id}', 'destroy')->name('addresses.destroy');
    });

    Route::controller(CompareController::class)->group(function () {
        Route::post('/compare/add', 'addToCompare')->name('compare.add');
        Route::post('/compare/remove', 'removeFromCompare')->name('compare.remove');
        Route::get('/compare/reset', 'resetCompare')->name('compare.reset');
        Route::get('/compare/count', 'countCompare')->name('compare.count');
    });

    // Affiliate System Frontend Routes
    Route::controller(AffiliateController::class)->group(function () {
        Route::get('/affiliate', 'index')->name('affiliate.index');
        Route::get('/affiliate/apply', 'apply_for_affiliate')->name('affiliate.apply');
        Route::post('/affiliate/apply', 'store_affiliate_user')->name('affiliate.apply.store');

        Route::middleware(['auth'])->group(function () {
            Route::get('/affiliate/dashboard', 'user_index')->name('affiliate.user.index');
            Route::get('/affiliate/payment-history', 'user_payment_history')->name('affiliate.user.payment_history');
            Route::get('/affiliate/withdraw-history', 'user_withdraw_request_history')->name('affiliate.user.withdraw_request_history');
            Route::get('/affiliate/payment-settings', 'payment_settings')->name('affiliate.user.payment_settings');
            Route::post('/affiliate/payment-settings', 'payment_settings_store')->name('affiliate.user.payment_settings_store');
            Route::post('/affiliate/withdraw-request', 'withdraw_request_store')->name('affiliate.user.withdraw_request_store');
        });
    });

});

Route::get('/under-maintainance', [GeneralWebSettingsController::class, 'maintainance'])->name('maintainance.mode');

// Route to clear and optimize cache on live server without terminal access
Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');

    return 'Cache is cleared! You can go back to the homepage.';
});

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

require __DIR__.'/auth.php';
