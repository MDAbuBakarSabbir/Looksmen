<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\GeneralWebSettingsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FrontCategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('guest:admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login']);
});

Route::middleware(['maintainance'])->group(function () {

    Route::get('/user-dashboard', [HomeController::class, 'userDash'])->middleware(['auth', 'verified'])->name('dashboard');
    Route::get('/', [HomeController::class, 'home'])->name('home');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/purchase-history', [ProfileController::class, 'purchaseHistory'])->name('purchaseHistory');
        Route::get('/wishlist', [ProfileController::class, 'wishlist'])->name('wishlist');
        Route::get('/compare', [ProfileController::class, 'compare'])->name('compare');
        Route::get('/conversation', [\App\Http\Controllers\ChatController::class, 'index'])->name('conversation');
        Route::get('/conversation/messages', [\App\Http\Controllers\ChatController::class, 'getMessages'])->name('conversation.messages');
        Route::post('/conversation/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('conversation.send');
        Route::get('/my-wallet', [\App\Http\Controllers\Customer\WalletPointController::class, 'myWallet'])->name('myWallet');
        Route::post('/wallet/recharge', [\App\Http\Controllers\Customer\WalletPointController::class, 'recharge'])->name('wallet.recharge');
        Route::get('/wallet/recharge/bkash/callback', [\App\Http\Controllers\Customer\WalletPointController::class, 'bkashCallback'])->name('wallet.recharge.bkash.callback');
        Route::post('/wallet/convert-points', [\App\Http\Controllers\Customer\WalletPointController::class, 'convertPoints'])->name('wallet.convert-points');
        Route::get('/support-ticket', [\App\Http\Controllers\SupportTicketController::class, 'index'])->name('supportTicket');
        Route::post('/support-ticket', [\App\Http\Controllers\SupportTicketController::class, 'store'])->name('supportTicket.store');
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
        Route::post('/test-run', 'testrun')->name('check.test');
    });

    Route::controller(FrontCategoryController::class)->group(function () {
        Route::get('/all-category', 'allcategory')->name('front.allCategory');
        Route::get('/category/{slug}{id}', 'catProductView')->name('catProductView');
        Route::get('/category/sub-{slug}{id}', 'subCatProductView')->name('subCatProductView');
        Route::get('/category/child-{slug}{id}', 'childCatProductView')->name('childCatProductView');
        Route::get('/product/compare', 'ProductCompare')->name('ProductCompare');
        Route::get('/product/{slug}{id}', 'ProductView')->name('ProductView');
        Route::get('/page/{slug}', 'pages')->name('pages');
    });

    Route::controller(AddressController::class)->group(function () {
        Route::post('addresses/store', 'store')->name('addresses.store');
        Route::post('addresses/update', 'update')->name('address.update');
        Route::get('/get-thanas/{district_id}', 'getThanasByDistrict')->name('get.thanas');
        Route::post('/addresses/set-default', 'set_default')->name('addresses.default');
        Route::delete('/addresses/destroy/{id}', 'destroy')->name('addresses.destroy');
    });

    Route::controller(\App\Http\Controllers\CompareController::class)->group(function () {
        Route::post('/compare/add', 'addToCompare')->name('compare.add');
        Route::post('/compare/remove', 'removeFromCompare')->name('compare.remove');
        Route::get('/compare/reset', 'resetCompare')->name('compare.reset');
        Route::get('/compare/count', 'countCompare')->name('compare.count');
    });

    // Affiliate System Frontend Routes
    Route::controller(\App\Http\Controllers\Admin\affiliate\AffiliateController::class)->group(function () {
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

require __DIR__.'/auth.php';
