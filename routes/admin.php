<?php

use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\Admin\AdminSupportController;
use App\Http\Controllers\Admin\AdminWalletPointController;
use App\Http\Controllers\Admin\affiliate\AffiliateController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ChildCategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\CourierApiController;
use App\Http\Controllers\Admin\FeatureActivationController;
use App\Http\Controllers\Admin\GeneralWebSettingsController;
use App\Http\Controllers\Admin\OrderManageController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\ReviewsController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SmtpController;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\APIController;
use App\Http\Controllers\IncompleteOrdersController;
use App\Http\Controllers\UserController;
use App\Models\Orders;
use App\Models\PaymentAPIS;
use App\Models\Product;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('auth:admin')->group(function () {
    // Admin Dashboard with safe fallback checks and permission check
    Route::get('dashboard', function () {
        $topSellingProducts = collect([]);
        $orders = collect([]);

        if (class_exists('App\Models\Product')) {
            try {
                $topSellingProducts = Product::with('firstImage')
                    ->withCount('orderDetails')
                    ->orderBy('order_details_count', 'desc')
                    ->take(5)
                    ->get();
            } catch (Exception $e) {
            }
        }

        if (class_exists('App\Models\Orders')) {
            try {
                $orders = Orders::all();
            } catch (Exception $e) {
            }
        }

        return view('adminDash.dashboard', compact('orders', 'topSellingProducts'));
    })->name('admin.dashboard')->middleware('admin.permission:view_dashboard');

    // Secure Admin Logout route
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::get('/clear-cache', function () {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return response()->json(['success' => true, 'message' => 'Cache cleared successfully!']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong!'], 500);
        }
    })->name('clear.cache')->middleware('admin.permission:setup_general_settings');

    Route::controller(AdminsController::class)->group(function () {
        Route::get('admins', 'index')->name('admin.index')->middleware('admin.permission:manage_admin');
        Route::get('admins/search', 'search')->name('admin.search')->middleware('admin.permission:manage_admin');
        Route::get('admins/create', 'create')->name('admin.create')->middleware('admin.permission:manage_admin');
        Route::post('admins/store', 'store')->name('admin.store')->middleware('admin.permission:manage_admin');
        Route::get('admins/role', 'role')->name('admin.role')->middleware('admin.permission:manage_admin');
        Route::post('admins/status', 'status')->name('admin.status')->middleware('admin.permission:manage_admin');
        Route::get('admins/permission/assaign/{id}', 'permission')->name('admin.permission')->middleware('admin.permission:manage_admin');
        Route::post('admins/permission/assaign/{id}', 'updatePermission')->name('admin.permission.update')->middleware('admin.permission:manage_admin');

        // Admin Profile Update
        Route::get('profile', 'profile')->name('admin.profile');
        Route::post('profile/update', 'profileUpdate')->name('admin.profile.update');
    });

    Route::controller(OrderManageController::class)->group(function () {
        Route::get('orders', 'index')->name('order-index')->middleware('admin.permission:manage_order');
        Route::get('new-orders', 'new')->name('order-new')->middleware('admin.permission:hold_order');
        Route::get('pending-orders', 'pending')->name('order-pending')->middleware('admin.permission:pending_order');
        Route::get('approved-orders', 'approved')->name('order-approved')->middleware('admin.permission:approved_order');
        Route::get('packaging-orders', 'packaging')->name('order-packaging')->middleware('admin.permission:packaging_order');
        Route::get('incourier-orders', 'incourier')->name('order-incourier')->middleware('admin.permission:shipment_order');
        Route::get('delivered-orders', 'delivered')->name('order-delivered')->middleware('admin.permission:delivered_order');
        Route::get('canceled-orders', 'canceled')->name('order-canceled')->middleware('admin.permission:canceled_order');
        Route::get('returned-orders', 'returned')->name('order-returned')->middleware('admin.permission:return_order');
        Route::get('orders/create', 'create')->name('admin.order-create')->middleware('admin.permission:create_order');
        Route::post('orders/store', 'store')->name('admin.order-store')->middleware('admin.permission:create_order');
        Route::get('orders/details/{id}', 'show')->name('admin.order-show')->middleware('admin.permission:manage_order,pending_order,hold_order,approved_order,packaging_order,shipment_order,delivered_order,canceled_order,return_order');
        Route::post('orders/refresh-courier-history/{id}', 'refreshCourierHistory')->name('admin.orders.refresh-courier-history')->middleware('admin.permission:manage_order,pending_order,hold_order,approved_order,packaging_order,shipment_order,delivered_order,canceled_order,return_order');
        Route::get('order-invoice/{id}', 'invoice')->name('order-invoice')->middleware('admin.permission:manage_order,pending_order,hold_order,approved_order,packaging_order,shipment_order,delivered_order,canceled_order,return_order');
        Route::get('orders/edit/{id}', 'edit')->name('admin.order-edit')->middleware('admin.permission:manage_order,pending_order,hold_order,approved_order,packaging_order,shipment_order,delivered_order,canceled_order,return_order');
        Route::post('orders/update', 'update')->name('admin.order-update')->middleware('admin.permission:manage_order,pending_order,hold_order,approved_order,packaging_order,shipment_order,delivered_order,canceled_order,return_order');
        Route::get('orders/destroy', 'destroy')->name('admin.order-destroy')->middleware('admin.permission:manage_order,pending_order,hold_order,approved_order,packaging_order,shipment_order,delivered_order,canceled_order,return_order');
        Route::post('orders/status', 'updateStatus')->name('order.update.status')->middleware('admin.permission:manage_order,pending_order,hold_order,approved_order,packaging_order,shipment_order,delivered_order,canceled_order,return_order');
        Route::get('/orders/filter', 'orderFilter')->name('admin.order-filter');
        Route::get('orders/search', 'orderSearch')->name('admin.order-search');
        Route::get('orders/autocomplete', 'orderAutocomplete')->name('admin.orders.autocomplete');
        Route::get('/api/check-new-orders', 'checkNewOrders')->name('check.new.orders');
        Route::get('/get-upazilas/{district}', 'getUpazilas');
        Route::get('/admin/product-search', 'searchProducts')->name('admin.product-search');

        // {orderId} হলো আপনার নির্দিষ্ট অর্ডারটির আইডি (যেমন: 1, 10, 50)
        Route::post('orders/steadfast-entry/{id}', 'placeCourierOrder')->name('entry.steadfast')->middleware('admin.permission:manage_order,pending_order,hold_order,approved_order,packaging_order,shipment_order,delivered_order,canceled_order,return_order');
        Route::get('orders/courier-track/{id}', 'trackCourierOrder')->name('admin.orders.courier-track')->middleware('admin.permission:manage_order,pending_order,hold_order,approved_order,packaging_order,shipment_order,delivered_order,canceled_order,return_order');
        Route::post('/orders/popup-seen/{id}', 'popupSeen')->name('order.popup_seen');

        Route::post('/courier/history', 'getCourierHistory')->name('courier.history');

        Route::get('/live', 'live')->name('live');
    });

    Route::controller(IncompleteOrdersController::class)->group(function () {
        Route::get('/incomplete/orders', 'index')->name('incomplete.index')->middleware('admin.permission:incomplete_order');
        Route::post('/fraud-check', 'checkFraud')->name('fraud.check')->middleware('admin.permission:incomplete_order');
        Route::delete('/incomplete/orders/{id}', 'destroy')->name('incomplete.destroy')->middleware('admin.permission:incomplete_order');
        Route::put('/incomplete/orders/{id}', 'update')->name('incomplete.update')->middleware('admin.permission:incomplete_order');
        Route::post('/incomplete/orders/bulk-action', 'bulkAction')->name('incomplete.bulk-action')->middleware('admin.permission:incomplete_order');
    });

    Route::prefix('orders')->middleware('auth:admin')->group(function () {
        Route::get('/filter', [OrderManageController::class, 'filter'])
            ->name('admin.orders.filter');

        Route::post('/update-status', [OrderManageController::class, 'updateStatus'])
            ->name('admin.orders.update-status')->middleware('admin.permission:manage_order,pending_order,hold_order,approved_order,packaging_order,shipment_order,delivered_order,canceled_order,return_order');

        Route::post('/bulk-update', [OrderManageController::class, 'bulkUpdate'])
            ->name('admin.orders.bulk-update')->middleware('admin.permission:manage_order,pending_order,hold_order,approved_order,packaging_order,shipment_order,delivered_order,canceled_order,return_order');

        Route::get('/status-count', [OrderManageController::class, 'statusCount'])
            ->name('admin.orders.status-count');
    });

    // Products Routes
    Route::controller(ProductController::class)->group(function () {
        Route::get('products', 'index')->name('product.index')->middleware('admin.permission:manage_product');
        Route::get('products/details/{id}', 'view')->name('product.view')->middleware('admin.permission:manage_product');
        Route::get('products/bulk-create', 'bulk')->name('product.bulk')->middleware('admin.permission:create_product');
        Route::get('products/create', 'create')->name('product.create')->middleware('admin.permission:create_product');
        Route::get('products/sample-csv', 'downloadSampleCSV')->name('product.sample-csv')->middleware('admin.permission:create_product');
        Route::post('products/bulk-import', 'importCSV')->name('product.bulk-import')->middleware('admin.permission:create_product');

        Route::get('products/attributevalue{attribute_id}', 'AttributeValue')->name('product.attributevalues')->middleware('admin.permission:manage_product');

        Route::post('products/store', 'store')->name('product.store')->middleware('admin.permission:create_product');
        Route::get('products/edit{id}', 'edit')->name('product.edit')->middleware('admin.permission:manage_product');
        Route::post('products/update{id}', 'update')->name('product.update')->middleware('admin.permission:manage_product');
        Route::delete('products/destroy/{id}', 'destroy')->name('product.destroy')->middleware('admin.permission:manage_product');
        Route::post('products/status', 'status')->name('product.status')->middleware('admin.permission:manage_product');
        Route::post('products/todays_deal_status', 'todays_deal_status')->name('product.todays_deal_status')->middleware('admin.permission:manage_product');
        Route::post('products/image-delete/{id}', 'deleteImage')->name('product.image.delete')->middleware('admin.permission:manage_product');

        // Routes for Category cascading
        Route::get('get-subcategories/{category_id}', 'getSubcategories');
        Route::get('get-childcategories/{subcategory_id}', 'getChildCategory');

        Route::post('product-copy', 'copy')->name('product.copy')->middleware('admin.permission:create_product');
        Route::get('get-attribute-values/{attribute_id}', 'getAttributeValues');
    });

    // Attribute Routes
    Route::controller(AttributeController::class)->group(function () {
        Route::get('attributes', 'index')->name('attribute.index')->middleware('admin.permission:manage_attribute');
        Route::get('attributes/create/{id}', 'create')->name('attribute.create')->middleware('admin.permission:create_attribute');
        Route::post('attributes/store', 'store')->name('attribute.store')->middleware('admin.permission:create_attribute');
        Route::post('attributevalues/store/{id}', 'valuestore')->name('value.store')->middleware('admin.permission:create_attribute');
        Route::get('attributes/edit/{id}', 'edit')->name('attribute.edit')->middleware('admin.permission:manage_attribute');
        Route::post('attributes/update/{id}', 'update')->name('attribute.update')->middleware('admin.permission:manage_attribute');
        Route::get('attributes/destroy/{id}', 'destroy')->name('attribute.destroy')->middleware('admin.permission:manage_attribute');
        Route::get('attributes/value/destroy/{id}', 'valueDestroy')->name('attribute.value.destroy')->middleware('admin.permission:create_attribute');
        Route::post('attributes/status', 'status')->name('attribute.status')->middleware('admin.permission:manage_attribute');
    });

    // We keep other routes intact here...
    Route::controller(CategoryController::class)->group(function () {
        Route::get('category', 'index')->name('category.index')->middleware('admin.permission:manage_category');
        Route::get('category/create', 'create')->name('category.create')->middleware('admin.permission:create_category');
        Route::post('category/store', 'store')->name('category.store')->middleware('admin.permission:create_category');
        Route::get('category/edit/{id}', 'edit')->name('category.edit')->middleware('admin.permission:manage_category');
        Route::post('category/update/{id}', 'update')->name('category.update')->middleware('admin.permission:manage_category');
        Route::post('category/status', 'status')->name('category.status')->middleware('admin.permission:manage_category');
        Route::get('category/destroy/{id}', 'destroy')->name('category.destroy')->middleware('admin.permission:manage_category');
    });

    Route::controller(SubCategoryController::class)->group(function () {
        Route::get('sub-category', 'index')->name('sub-category.index')->middleware('admin.permission:manage_subcategory');
        Route::get('sub-category/create', 'create')->name('sub-category.create')->middleware('admin.permission:manage_subcategory');
        Route::post('sub-category/store', 'store')->name('sub-category.store')->middleware('admin.permission:manage_subcategory');
        Route::get('sub-category/edit/{id}', 'edit')->name('sub-category.edit')->middleware('admin.permission:manage_subcategory');
        Route::post('sub-category/update/{id}', 'update')->name('sub-category.update')->middleware('admin.permission:manage_subcategory');
        Route::post('sub-category/status', 'status')->name('sub-category.status')->middleware('admin.permission:manage_subcategory');
        Route::get('sub-category/destroy/{id}', 'destroy')->name('sub-category.destroy')->middleware('admin.permission:manage_subcategory');
    });

    Route::controller(ChildCategoryController::class)->group(function () {
        Route::get('child-category', 'index')->name('child-category.index')->middleware('admin.permission:manage_childcategory');
        Route::get('child-category/create', 'create')->name('child-category.create')->middleware('admin.permission:manage_childcategory');
        Route::post('child-category/store', 'store')->name('child-category.store')->middleware('admin.permission:manage_childcategory');
        Route::get('child-category/edit/{id}', 'edit')->name('child-category.edit')->middleware('admin.permission:manage_childcategory');
        Route::post('child-category/update/{id}', 'update')->name('child-category.update')->middleware('admin.permission:manage_childcategory');
        Route::post('child-category/status', 'status')->name('child-category.status')->middleware('admin.permission:manage_childcategory');
        Route::get('child-category/destroy/{id}', 'destroy')->name('child-category.destroy')->middleware('admin.permission:manage_childcategory');
    });

    // Colour Routes
    Route::controller(ColorController::class)->group(function () {
        Route::get('color', 'index')->name('color.index')->middleware('admin.permission:manage_attribute');
        Route::get('color/create', 'create')->name('color.create')->middleware('admin.permission:manage_attribute');
        Route::post('color/store', 'store')->name('color.storee')->middleware('admin.permission:manage_attribute');
        Route::get('color/edit/{id}', 'edit')->name('color.edit')->middleware('admin.permission:manage_attribute');
        Route::post('color/update/{id}', 'update')->name('color.update')->middleware('admin.permission:manage_attribute');
        Route::post('color/joma', 'joma')->name('color.joma')->middleware('admin.permission:manage_attribute');
        Route::get('color/destroy/{id}', 'destroy')->name('color.destroy')->middleware('admin.permission:manage_attribute');
        Route::post('color/status', 'status')->name('color.status')->middleware('admin.permission:manage_attribute');

        Route::post('color/store-images', 'imgstore')->name('img.store')->middleware('admin.permission:manage_attribute');
    });

    // Reviews Routes
    Route::controller(ReviewsController::class)->group(function () {
        Route::get('reviews', 'index')->name('reviews.index')->middleware('admin.permission:manage_reviews');
        Route::post('reviews/view', 'view')->name('reviews.view')->middleware('admin.permission:manage_reviews');
        Route::post('reviews/status', 'status')->name('reviews.status')->middleware('admin.permission:manage_reviews');
        Route::post('reviews/admin?destroy', 'admin_destroy')->name('reviews.admin_destroy')->middleware('admin.permission:manage_reviews');
    });

    // Promotions And Coupons
    Route::controller(CouponsController::class)->group(function () {
        Route::get('/coupons', 'coupons')->name('coupons')->middleware('admin.permission:manage_coupons');
        Route::post('/coupon/store', 'store')->name('coupon.store')->middleware('admin.permission:manage_coupons');
        Route::post('/coupon/status', 'status')->name('coupon.status')->middleware('admin.permission:manage_coupons');
        Route::get('/coupon/edit/{id}', 'edit')->middleware('admin.permission:manage_coupons');
        Route::post('/coupon/update', 'update')->name('coupon.update')->middleware('admin.permission:manage_coupons');
        Route::delete('/coupon/delete/{id}', 'destroy')->name('coupon.delete')->middleware('admin.permission:manage_coupons');
    });

    // Report Routes
    Route::controller(ReportsController::class)->group(function () {
        Route::get('reports/order', 'order')->name('report.order')->middleware('admin.permission:report_order');
        Route::get('reports/Product', 'Product')->name('report.Product')->middleware('admin.permission:report_product');
        Route::get('reports/WebOrder', 'WebOrder')->name('report.WebOrder')->middleware('admin.permission:report_web_order');
        Route::get('reports/MetaAds', 'MetaAds')->name('report.MetaAds')->middleware('admin.permission:report_meta_ads');
        Route::get('reports/Profit&sales', 'profitSales')->name('report.Profit&sales')->middleware('admin.permission:report_profit_sales');
        Route::get('reports/Employee', 'Employee')->name('report.Employee')->middleware('admin.permission:report_employee');
        Route::get('reports/MyLimits', 'MyLimits')->name('report.MyLimits')->middleware('admin.permission:report_my_limits');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('customer/registered', 'regCustomer')->name('regCustomer')->middleware('admin.permission:view_registered_customers');
        Route::post('customer/toggle-block/{id}', 'toggleBlock')->name('customer.toggleBlock')->middleware('admin.permission:manage_blocked_customers');
        Route::get('customer/non-registered', 'nonRegCustomer')->name('nonRegCustomer')->middleware('admin.permission:view_nonregistered_customers');
        Route::get('customer/customer-block', 'customerBlock')->name('customerBlock')->middleware('admin.permission:manage_blocked_customers');
        Route::get('customer/customer-ip-block', 'customeripBlock')->name('customeripBlock')->middleware('admin.permission:manage_ip_blocked_customers');
        Route::post('customer/ip-block/store', 'storeIpBlock')->name('ip_block.store')->middleware('admin.permission:manage_ip_blocked_customers');
        Route::delete('customer/ip-block/destroy/{id}', 'destroyIpBlock')->name('ip_block.destroy')->middleware('admin.permission:manage_ip_blocked_customers');
    });

    // Slider & Banner Routes
    Route::controller(SliderController::class)->group(function () {
        Route::get('sliders', 'index')->name('slider.index')->middleware('admin.permission:manage_slider');
        Route::get('sliders/create', 'create')->name('slider.create')->middleware('admin.permission:manage_slider');
        Route::post('sliders/store', 'store')->name('slider.store')->middleware('admin.permission:manage_slider');
        Route::post('sliders/status', 'status')->name('slider.status')->middleware('admin.permission:manage_slider');
        Route::get('sliders/edit/{id}', 'edit')->name('slider.edit')->middleware('admin.permission:manage_slider');
        Route::post('sliders/update/{id}', 'update')->name('slider.update')->middleware('admin.permission:manage_slider');
        Route::get('sliders/destroy/{id}', 'destroy')->name('slider.destroy')->middleware('admin.permission:manage_slider');
    });

    Route::controller(BannerController::class)->group(function () {
        Route::get('banners', 'index')->name('banner.index')->middleware('admin.permission:manage_banner');
        Route::get('banners/create', 'create')->name('banner.create')->middleware('admin.permission:manage_banner');
        Route::post('banners/store', 'store')->name('banner.store')->middleware('admin.permission:manage_banner');
        Route::post('banners/status', 'status')->name('banner.status')->middleware('admin.permission:manage_banner');
        Route::get('banners/edit/{id}', 'edit')->name('banner.edit')->middleware('admin.permission:manage_banner');
        Route::post('banners/update/{id}', 'update')->name('banner.update')->middleware('admin.permission:manage_banner');
        Route::get('banners/destroy/{id}', 'destroy')->name('banner.destroy')->middleware('admin.permission:manage_banner');
    });

    // Affiliate Routes
    Route::controller(AffiliateController::class)->group(function () {
        Route::get('/affiliate', 'index')->name('affiliate.index')->middleware('admin.permission:manage_affiliate_configs');
        Route::post('/affiliate/affiliate_option_store', 'affiliate_option_store')->name('affiliate.store')->middleware('admin.permission:manage_affiliate_configs');

        Route::get('/affiliate/configs', 'configs')->name('affiliate.configs')->middleware('admin.permission:manage_affiliate_configs');
        Route::post('/affiliate/configs/store', 'config_store')->name('affiliate.configs.store')->middleware('admin.permission:manage_affiliate_configs');

        Route::get('/affiliate/users', 'users')->name('affiliate.users')->middleware('admin.permission:manage_affiliate_users');
        Route::get('/affiliate/verification/{id}', 'show_verification_request')->name('affiliate_users.show_verification_request')->middleware('admin.permission:manage_affiliate_users');

        Route::get('/affiliate/approve/{id}', 'approve_user')->name('affiliate_user.approve')->middleware('admin.permission:manage_affiliate_users');
        Route::get('/affiliate/reject/{id}', 'reject_user')->name('affiliate_user.reject')->middleware('admin.permission:manage_affiliate_users');

        Route::post('/affiliate/approved', 'updateApproved')->name('affiliate_user.approved')->middleware('admin.permission:manage_affiliate_users');

        Route::post('/affiliate/payment_modal', 'payment_modal')->name('affiliate_user.payment_modal')->middleware('admin.permission:manage_affiliate_withdraw');
        Route::post('/affiliate/pay/store', 'payment_store')->name('affiliate_user.payment_store')->middleware('admin.permission:manage_affiliate_withdraw');

        Route::get('/affiliate/payments/show/{id}', 'payment_history')->name('affiliate_user.payment_history')->middleware('admin.permission:manage_affiliate_withdraw');
        Route::get('/refferal/users', 'refferal_users')->name('refferals.users')->middleware('admin.permission:manage_referral_users');

        // Affiliate Withdraw Request
        Route::get('/affiliate/withdraw_requests', 'affiliate_withdraw_requests')->name('affiliate.withdraw_requests')->middleware('admin.permission:manage_affiliate_withdraw');
        Route::post('/affiliate/affiliate_withdraw_modal', 'affiliate_withdraw_modal')->name('affiliate_withdraw_modal')->middleware('admin.permission:manage_affiliate_withdraw');
        Route::post('/affiliate/withdraw_request/payment_store', 'withdraw_request_payment_store')->name('withdraw_request.payment_store')->middleware('admin.permission:manage_affiliate_withdraw');
        Route::get('/affiliate/withdraw_request/reject/{id}', 'reject_withdraw_request')->name('affiliate.withdraw_request.reject')->middleware('admin.permission:manage_affiliate_withdraw');

        Route::get('/affiliate/logs', 'affiliate_logs_admin')->name('affiliate.logs')->middleware('admin.permission:manage_affiliate_logs');
    });

    Route::controller(SocialMediaController::class)->group(function () {
        Route::get('/socialmedia', 'index')->name('social.index')->middleware('admin.permission:setup_social_links');
        Route::post('/socialmedia/status', 'status')->name('social.status')->middleware('admin.permission:setup_social_links');
        Route::post('/socialmedia/store', 'store')->name('social.store')->middleware('admin.permission:setup_social_links');
        Route::post('/socialmedia/edit', 'edit')->name('social.edit')->middleware('admin.permission:setup_social_links');
        Route::post('/socialmedia/destroy/{id}', 'destroy')->name('social.destroy')->middleware('admin.permission:setup_social_links');
    });

    // --------------------API----------------------
    Route::controller(CourierApiController::class)->group(function () {
        Route::get('/courier-api', 'index')->name('courier.index')->middleware('admin.permission:setup_courier_api');
        Route::post('/update-courier-status', 'updateStatus')->name('courier.update.status')->middleware('admin.permission:setup_courier_api');
        Route::post('/update-courier-credentials', 'updateCredentials')->name('courier.update.credentials')->middleware('admin.permission:setup_courier_api');
        Route::get('/courier/balance', 'getBalance')->name('courier.balance')->middleware('admin.permission:setup_courier_api');
    });

    Route::controller(PaymentAPIS::class)->group(function () {
        Route::get('/payment-api', 'index')->name('payment.index')->middleware('admin.permission:setup_payment_api');
    });

    Route::controller(PaymentController::class)->group(function () {});

    Route::controller(FeatureActivationController::class)->group(function () {
        Route::get('/feature', 'index')->name('feature.index')->middleware('admin.permission:setup_feature_activation');
        Route::post('/feature/status', 'status')->name('feature.status')->middleware('admin.permission:setup_feature_activation');
    });

    Route::controller(APIController::class)->group(function () {
        Route::get('/froud-check/api', 'froudCheck')->name('fraudCheck.index')->middleware('admin.permission:setup_fraud_check');
        Route::post('/froud-check/api/update', 'updateFraudCheck')->name('admin.fraudCheck.update')->middleware('admin.permission:setup_fraud_check');
    });

    Route::controller(GeneralWebSettingsController::class)->group(function () {
        Route::get('/websettings', 'index')->name('websettings.index')->middleware('admin.permission:setup_general_settings');
        Route::post('/websettings/webDetails', 'webDetails')->name('websettings.webDetails')->middleware('admin.permission:setup_general_settings');
        Route::post('/websettings/webContact', 'webContact')->name('websettings.webContact')->middleware('admin.permission:setup_general_settings');
        Route::post('/websettings/webMeta', 'webMeta')->name('websettings.webMeta')->middleware('admin.permission:setup_general_settings');
        Route::post('/websettings/headerLogo', 'headerLogo')->name('websettings.headerLogo')->middleware('admin.permission:setup_general_settings');
        Route::post('/websettings/footerLogo', 'footerLogo')->name('websettings.footerLogo')->middleware('admin.permission:setup_general_settings');
        Route::post('/websettings/favicon', 'favicon')->name('websettings.favicon')->middleware('admin.permission:setup_general_settings');

        Route::post('/websettings/maintainance', 'maintainance_mode')->name('websettings.maintainance')->middleware('admin.permission:setup_general_settings');
        Route::get('/smtpsettings', 'smtp')->name('websettings.smtp')->middleware('admin.permission:setup_general_settings');
        Route::get('/gtag&fbpixel', 'gtag_fbpixel')->name('websettings.gtag_fbpixel')->middleware('admin.permission:setup_general_settings');
        Route::post('/websettings/webGtag', 'webGtag')->name('websettings.webGtag')->middleware('admin.permission:setup_general_settings');
        Route::post('/websettings/webFbpixel', 'webFbpixel')->name('websettings.webFbpixel')->middleware('admin.permission:setup_general_settings');
        Route::post('/websettings/webDomain', 'webDomain')->name('websettings.webDomain')->middleware('admin.permission:setup_general_settings');
    });

    Route::controller(AddressController::class)->group(function () {
        Route::get('/address', 'address')->name('address.index')->middleware('admin.permission:setup_address');
        Route::post('/address-districtstore', 'districtstore')->name('district.store')->middleware('admin.permission:setup_address');
        Route::post('/address-districtstatus', 'districtstatus')->name('district.status')->middleware('admin.permission:setup_address');
        Route::post('/address-districtupdate/{id}', 'districtupdate')->name('district.update')->middleware('admin.permission:setup_address');
        Route::post('/address-districtdestroy/{id}', 'districtdestroy')->name('district.destroy')->middleware('admin.permission:setup_address');
        Route::post('/address-thanastore', 'thanastore')->name('thana.store')->middleware('admin.permission:setup_address');
        Route::post('/address-thanastatus', 'thanastatus')->name('thana.status')->middleware('admin.permission:setup_address');
        Route::post('/address-thanaupdate/{id}', 'thanaupdate')->name('thana.update')->middleware('admin.permission:setup_address');
        Route::post('/address-thanadestroy/{id}', 'thanadestroy')->name('thana.destroy')->middleware('admin.permission:setup_address');
    });

    Route::controller(PagesController::class)->group(function () {
        Route::get('/pages', 'index')->name('pages.index')->middleware('admin.permission:manage_pages');
        Route::get('/pages/create', 'create')->name('pages.create')->middleware('admin.permission:manage_pages');
        Route::post('/pages/store', 'store')->name('pages.store')->middleware('admin.permission:manage_pages');
        Route::post('/pages/status', 'status')->name('pages.status')->middleware('admin.permission:manage_pages');
        Route::get('/pages/edit/{id}', 'edit')->name('pages.edit')->middleware('admin.permission:manage_pages');
        Route::post('/pages/destroy/{id}', 'destroy')->name('pages.destroy')->middleware('admin.permission:manage_pages');
    });

    Route::controller(SmtpController::class)->group(function () {
        Route::get('/smtp', 'index')->name('smtp.index')->middleware('admin.permission:setup_smtp');
    });

    // Support Ticket & Customer Chat routes
    Route::controller(AdminSupportController::class)->group(function () {
        Route::get('/tickets', 'tickets')->name('admin.tickets')->middleware('admin.permission:manage_support_tickets');
        Route::post('/tickets/{id}/update', 'updateTicket')->name('admin.tickets.update')->middleware('admin.permission:manage_support_tickets');

        Route::get('/chat', 'chatDashboard')->name('admin.chat')->middleware('admin.permission:manage_support_tickets');
        Route::get('/chat/users', 'getChatUsers')->name('admin.chat.users')->middleware('admin.permission:manage_support_tickets');
        Route::get('/chat/messages/{user_id}', 'getUserMessages')->name('admin.chat.messages')->middleware('admin.permission:manage_support_tickets');
        Route::post('/chat/send', 'sendChatMessage')->name('admin.chat.send')->middleware('admin.permission:manage_support_tickets');
    });

    // Admin Wallet & Point System Routes
    Route::controller(AdminWalletPointController::class)->group(function () {
        Route::get('/wallet/transactions', 'transactions')->name('admin.wallet.transactions')->middleware('admin.permission:manage_wallet');
        Route::get('/wallet/manual-recharges', 'manualRecharges')->name('admin.wallet.manual-recharges')->middleware('admin.permission:manage_wallet');
        Route::post('/wallet/recharge/approve/{id}', 'approveRecharge')->name('admin.wallet.recharge.approve')->middleware('admin.permission:manage_wallet');
        Route::post('/wallet/recharge/reject/{id}', 'rejectRecharge')->name('admin.wallet.recharge.reject')->middleware('admin.permission:manage_wallet');
        Route::post('/wallet/adjust', 'adjustWallet')->name('admin.wallet.adjust')->middleware('admin.permission:manage_wallet');
        Route::get('/wallet/points-config', 'pointConfig')->name('admin.wallet.points-config')->middleware('admin.permission:manage_wallet');
        Route::post('/wallet/points-config/store', 'pointConfigStore')->name('admin.wallet.points-config.store')->middleware('admin.permission:manage_wallet');
    });
});
