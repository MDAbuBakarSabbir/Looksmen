<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeatureActivation;
use App\Models\GeneralWebSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class GeneralWebSettingsController extends Controller
{
    public function index()
    {
        $webinfo = GeneralWebSettings::all();
        // $webConfig = $webinfo->pluck('value', 'name', 'status')->toArray();
        $webConfig = $webinfo->keyBy('name')->map(function ($item) {
            return [
                'value' => $item->value,
                'status' => $item->status,
            ];
        })->toArray();
        $features = FeatureActivation::all();
        $featuresConfig = $features->pluck('status', 'name')->toArray();

        return view('adminDash.settings.general', compact('webConfig', 'featuresConfig'));
    }

    public function headerLogo(Request $request)
    {
        $request->validate([
            'header_logo_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('header_logo_image')) {
            $file = $request->file('header_logo_image');
            $newname = 'logo_'.time().'_'.Str::random(5).'.webp';

            // Delete old logo file if it exists and is not a default seeded file
            $setting = GeneralWebSettings::where('name', 'web_logo')->first();
            if ($setting && $setting->value) {
                $oldPath = public_path('adminDash/assets/img/layouts/'.$setting->value);
                if (file_exists($oldPath) && is_file($oldPath)) {
                    if (! in_array($setting->value, ['Logo.png', 'footer_logo.png', 'favicon.png'])) {
                        unlink($oldPath);
                    }
                }
            }

            $manager = new ImageManager(new Driver);
            $image = $manager->decode($file);
            $image->scaleDown(width: 300);
            $image->save(public_path('adminDash/assets/img/layouts/'.$newname), quality: 60);

            GeneralWebSettings::where('name', 'web_logo')->update([
                'value' => $newname,
            ]);
            Cache::flush();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Header logo updated successfully!',
                ]);
            }

            return back()->with('success', 'Header logo updated successfully!');
        }

        return back()->with('error', 'No file was uploaded.');
    }

    public function footerLogo(Request $request)
    {
        $request->validate([
            'footer_logo_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('footer_logo_image')) {
            $file = $request->file('footer_logo_image');
            $newname = 'footer_'.time().'_'.Str::random(5).'.webp';

            // Delete old footer logo file if it exists and is not a default seeded file
            $setting = GeneralWebSettings::where('name', 'footer_logo')->first();
            if ($setting && $setting->value) {
                $oldPath = public_path('adminDash/assets/img/layouts/'.$setting->value);
                if (file_exists($oldPath) && is_file($oldPath)) {
                    if (! in_array($setting->value, ['Logo.png', 'footer_logo.png', 'favicon.png'])) {
                        unlink($oldPath);
                    }
                }
            }

            $manager = new ImageManager(new Driver);
            $image = $manager->decode($file);
            $image->scaleDown(width: 300);
            $image->save(public_path('adminDash/assets/img/layouts/'.$newname), quality: 60);

            GeneralWebSettings::where('name', 'footer_logo')->update([
                'value' => $newname,
            ]);
            Cache::flush();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Footer logo updated successfully!',
                ]);
            }

            return back()->with('success', 'Footer logo updated successfully!');
        }

        return back()->with('error', 'No file was uploaded.');
    }

    public function favicon(Request $request)
    {
        $request->validate([
            'favicon_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,ico,webp|max:1024',
        ]);

        if ($request->hasFile('favicon_image')) {
            $file = $request->file('favicon_image');
            $newname = 'favicon_'.time().'_'.Str::random(5).'.webp';

            // Delete old favicon file if it exists and is not a default seeded file
            $setting = GeneralWebSettings::where('name', 'web_favicon')->first();
            if ($setting && $setting->value) {
                $oldPath = public_path('adminDash/assets/img/layouts/'.$setting->value);
                if (file_exists($oldPath) && is_file($oldPath)) {
                    if (! in_array($setting->value, ['Logo.png', 'footer_logo.png', 'favicon.png'])) {
                        unlink($oldPath);
                    }
                }
            }

            $manager = new ImageManager(new Driver);
            $image = $manager->decode($file);
            $image->scaleDown(width: 150);
            $image->save(public_path('adminDash/assets/img/layouts/'.$newname), quality: 60);

            GeneralWebSettings::where('name', 'web_favicon')->update([
                'value' => $newname,
            ]);
            Cache::flush();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Favicon updated successfully!',
                ]);
            }

            return back()->with('success', 'Favicon updated successfully!');
        }

        return back()->with('error', 'No file was uploaded.');
    }

    public function socialBanner(Request $request)
    {
        $request->validate([
            'social_banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ]);

        if ($request->hasFile('social_banner_image')) {
            $file = $request->file('social_banner_image');
            $newname = 'social_banner_'.time().'_'.Str::random(5).'.webp';

            $setting = GeneralWebSettings::where('name', 'social_banner')->first();
            if ($setting && $setting->value) {
                $oldPath = public_path('adminDash/assets/img/layouts/'.$setting->value);
                if (file_exists($oldPath) && is_file($oldPath)) {
                    unlink($oldPath);
                }
            }

            $manager = new ImageManager(new Driver);
            $image = $manager->decode($file);
            $image->scaleDown(width: 1200);
            $image->save(public_path('adminDash/assets/img/layouts/'.$newname), quality: 80);

            GeneralWebSettings::updateOrCreate(
                ['name' => 'social_banner'],
                ['value' => $newname, 'status' => '1']
            );
            Cache::flush();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Social share banner updated successfully!',
                ]);
            }

            return back()->with('success', 'Social share banner updated successfully!');
        }

        return back()->with('error', 'No file was uploaded.');
    }

    public function maintainance_mode(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|in:0,1',
        ]);
        $maintainance = $request->name;
        $newStatus = $request->status;
        $value = $newStatus == 1 ? 'Activated' : 'Deactivated';

        $webinfo = GeneralWebSettings::where('name', $maintainance)->first();

        if (! $webinfo) {
            return response()->json(['message' => 'Record not found.'], 404);
        }

        $webinfo->status = $newStatus;
        $webinfo->value = $value;
        $webinfo->save();

        $action = $newStatus == 1 ? 'Activated' : 'Deactivated';

        return response()->json([
            'success' => true,
            // 'status' => $webinfo->maintainance,
            'message' => "Maintenance has been {$action} successfully!",
        ]);
    }

    public function smtp(Request $request)
    {
        //
    }

    public function gtag(Request $request)
    {
        $webDetailsToUpdate = [
            'web_name' => $request->webName,
            'web_description' => $request->webDescription,
            'web_tags' => $request->webTags,
        ];

        foreach ($webDetailsToUpdate as $name => $value) {

            if (! empty(trim($value))) {

                GeneralWebSettings::where('name', $name)->update([
                    'value' => $value,
                ]);
            }
        }
        Cache::flush();

        return back()->with('success', 'Website Details Updated Successfull !');
    }

    public function webDetails(Request $request)
    {

        $webDetailsToUpdate = [
            'web_name' => $request->webName,
            'web_description' => $request->webDescription,
            'web_tags' => $request->webTags,
        ];

        foreach ($webDetailsToUpdate as $name => $value) {

            if (! empty(trim($value))) {

                GeneralWebSettings::where('name', $name)->update([
                    'value' => $value,
                ]);
            }
        }
        Cache::flush();

        return back()->with('success', 'Website Details Updated Successfull !');
    }

    public function webTimezone(Request $request)
    {
        $tz = trim($request->timezone);

        if (! empty($tz) && in_array($tz, timezone_identifiers_list())) {
            GeneralWebSettings::updateOrCreate(
                ['name' => 'timezone'],
                [
                    'value' => $tz,
                    'status' => '1',
                ]
            );

            try {
                $envPath = base_path('.env');
                if (file_exists($envPath)) {
                    $envContent = file_get_contents($envPath);
                    if (preg_match('/^APP_TIMEZONE=.*/m', $envContent)) {
                        $envContent = preg_replace('/^APP_TIMEZONE=.*/m', 'APP_TIMEZONE="'.$tz.'"', $envContent);
                    } else {
                        $envContent .= "\nAPP_TIMEZONE=\"".$tz."\"\n";
                    }
                    file_put_contents($envPath, $envContent);
                }
                putenv("APP_TIMEZONE={$tz}");
                $_ENV['APP_TIMEZONE'] = $tz;
                $_SERVER['APP_TIMEZONE'] = $tz;
                Artisan::call('config:clear');
            } catch (\Exception $e) {
                Log::error('ENV Timezone update error: '.$e->getMessage());
            }

            date_default_timezone_set($tz);
            config(['app.timezone' => $tz]);
        }

        Cache::forget('boot_general_web_settings_map');
        Cache::flush();

        return back()->with('success', 'Timezone Setting Updated Successfully!');
    }

    public function webThemeColor(Request $request)
    {
        if (! empty($request->primary_color)) {
            GeneralWebSettings::updateOrCreate(
                ['name' => 'primary_color'],
                [
                    'value' => $request->primary_color,
                    'status' => '1',
                ]
            );
        }

        Cache::flush();

        return back()->with('success', 'Theme Primary Color Updated Successfully!');
    }

    public function webContact(Request $request)
    {
        $webContactToUpdate = [
            'contact_address' => $request->contact_address,
            'contact_phone' => $request->contact_phone,
            'contact_email' => $request->contact_email,
        ];

        foreach ($webContactToUpdate as $name => $value) {

            if (! empty(trim($value))) {

                GeneralWebSettings::where('name', $name)->update([
                    'value' => $value,
                ]);
            }
        }
        Cache::flush();

        return back()->with('success', 'Website Contact Details Updated Successfull !');
    }

    public function webMeta(Request $request)
    {
        $webMetaToUpdate = [
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keyword' => $request->meta_keyword,
        ];

        foreach ($webMetaToUpdate as $name => $value) {

            if (! empty(trim($value))) {

                GeneralWebSettings::where('name', $name)->update([
                    'value' => $value,
                ]);
            }
        }
        Cache::flush();

        return back()->with('success', 'Website Meta Details Updated Successfull !');
    }

    public function webDomain(Request $request)
    {
        $request->validate([
            'app_domain' => 'required|string|max:255',
            'admin_domain' => 'required|string|max:255',
        ]);

        $domainSettings = [
            'app_domain' => trim($request->app_domain),
            'admin_domain' => trim($request->admin_domain),
        ];

        foreach ($domainSettings as $name => $value) {
            GeneralWebSettings::updateOrCreate(
                ['name' => $name],
                ['value' => $value, 'status' => 1]
            );
        }

        // Also update the .env file so middleware and config pick up changes
        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);

            // Update or add APP_DOMAIN
            if (str_contains($envContent, 'APP_DOMAIN=')) {
                $envContent = preg_replace('/^APP_DOMAIN=.*/m', 'APP_DOMAIN='.$domainSettings['app_domain'], $envContent);
            } else {
                $envContent .= "\nAPP_DOMAIN=".$domainSettings['app_domain'];
            }

            // Update or add ADMIN_DOMAIN
            if (str_contains($envContent, 'ADMIN_DOMAIN=')) {
                $envContent = preg_replace('/^ADMIN_DOMAIN=.*/m', 'ADMIN_DOMAIN='.$domainSettings['admin_domain'], $envContent);
            } else {
                $envContent .= "\nADMIN_DOMAIN=".$domainSettings['admin_domain'];
            }

            // Update SESSION_DOMAIN to match main domain with leading dot
            $sessionDomain = '.'.$domainSettings['app_domain'];
            if (str_contains($envContent, 'SESSION_DOMAIN=')) {
                $envContent = preg_replace('/^SESSION_DOMAIN=.*/m', 'SESSION_DOMAIN='.$sessionDomain, $envContent);
            } else {
                $envContent .= "\nSESSION_DOMAIN=".$sessionDomain;
            }

            file_put_contents($envPath, $envContent);
        }

        // Clear config cache so new values take effect immediately
        Artisan::call('config:clear');

        return back()->with('success', 'Domain settings updated successfully! Changes will take effect immediately.');
    }

    public function productTrustSettings(Request $request)
    {
        $fields = [
            'show_product_trust_box' => $request->has('show_product_trust_box') ? '1' : '0',
            'trust_delivery_inside_dhaka' => $request->trust_delivery_inside_dhaka ?? 'ঢাকা সিটি: ৳৬০',
            'trust_delivery_outside_dhaka' => $request->trust_delivery_outside_dhaka ?? 'ঢাকার বাইরে: ৳১২০',
            'trust_delivery_time' => $request->trust_delivery_time ?? 'ঢাকা: ২৪-৪৮ ঘণ্টা | সারা দেশ: ২-৩ দিন',
            'trust_badge_1' => $request->trust_badge_1 ?? 'ক্যাশ অন ডেলিভারি',
            'trust_badge_2' => $request->trust_badge_2 ?? 'চেক করে নেওয়ার সুযোগ',
            'trust_badge_3' => $request->trust_badge_3 ?? '৭ দিনে সহজ রিটার্ন',
            'trust_badge_4' => $request->trust_badge_4 ?? '১০০% অরিজিনাল পণ্য',
        ];

        foreach ($fields as $name => $value) {
            GeneralWebSettings::updateOrCreate(
                ['name' => $name],
                ['value' => $value, 'status' => 1]
            );
        }

        Cache::forget('global_webconfig_pluck');
        Cache::forget('boot_general_web_settings_map');
        Cache::forget('global_webinfo_first');
        Cache::flush();

        return back()->with('success', 'Product Page Trust & Delivery settings updated successfully!');
    }

    public function toggleTrustStatus(Request $request)
    {
        $status = $request->status == '1' ? '1' : '0';

        GeneralWebSettings::updateOrCreate(
            ['name' => 'show_product_trust_box'],
            ['value' => $status, 'status' => (int) $status]
        );

        Cache::forget('global_webconfig_pluck');
        Cache::forget('boot_general_web_settings_map');
        Cache::forget('global_webinfo_first');
        Cache::flush();

        $msg = $status == '1' 
            ? 'Product trust & delivery box activated!' 
            : 'Product trust & delivery box deactivated!';

        return response()->json([
            'success' => true,
            'status' => $status,
            'message' => $msg,
        ]);
    }

    public function trackingSettings()
    {
        $webinfo = GeneralWebSettings::all();
        $webConfig = $webinfo->keyBy('name')->map(function ($item) {
            return [
                'value' => $item->value,
                'status' => $item->status,
            ];
        })->toArray();
        $features = FeatureActivation::all();
        $featuresConfig = $features->pluck('status', 'name')->toArray();

        return view('adminDash.settings.tracking', compact('webConfig', 'featuresConfig'));
    }

    public function gtag_fbpixel()
    {
        return redirect()->route('tracking.settings');
    }

    public function webGtag(Request $request)
    {
        $gtagDetails = [
            'gtagid' => $request->gtagid,
            'gdomainverify' => $request->gdomainverify,
        ];

        foreach ($gtagDetails as $name => $value) {
            GeneralWebSettings::updateOrCreate(
                ['name' => $name],
                ['value' => $value ?? '', 'status' => 1]
            );
        }

        return back()->with('success', 'Google Tag Settings Updated Successfully!');
    }

    public function webFbpixel(Request $request)
    {
        $fbDetails = [
            'fb_pixel_id' => $request->fb_pixel_id,
            'fb_capi_access_token' => $request->fb_capi_access_token,
            'fb_capi_test_code' => $request->fb_capi_test_code,
            'fb_capi_status' => $request->has('fb_capi_status') ? '1' : ($request->fb_capi_status ?? '0'),
            'fb_pixel' => $request->fb_pixel,
            'fbdomainverify' => $request->fbdomainverify,
            'fbiframe' => $request->fbiframe,
            'fbchatplugin' => $request->fbchatplugin,
        ];

        foreach ($fbDetails as $name => $value) {
            GeneralWebSettings::updateOrCreate(
                ['name' => $name],
                ['value' => $value ?? '', 'status' => 1]
            );
        }

        Cache::forget('global_webconfig_pluck');
        Cache::forget('global_webinfo_first');

        return back()->with('success', 'Facebook Pixel & Meta CAPI Settings Updated Successfully!');
    }

    public function maintainance()
    {
        $webinfo = GeneralWebSettings::all();
        // $webConfig = $webinfo->pluck('value', 'name', 'status')->toArray();
        $webConfig = $webinfo->keyBy('name')->map(function ($item) {
            return [
                'value' => $item->value,
                'status' => $item->status,
            ];
        })->toArray();
        $maintainanceStatus = $webConfig['maintainance']['status'] == '0';
        if ($maintainanceStatus) {
            return redirect(url('/'));
        }

        return view('maintainance');
    }

    public function systemCommands()
    {
        $tables = [];
        try {
            $rawTables = DB::select('SHOW TABLES');
            foreach ($rawTables as $table) {
                $tableName = array_values((array) $table)[0];
                $tables[] = $tableName;
            }
        } catch (\Exception $e) {
            $tables = [];
        }

        $restrictedTables = [
            'admins', 'roles', 'permissions', 'migrations', 'general_web_settings',
            'feature_activations', 'courier_apis', 'payment_apis', 'districts',
            'thanas', 'pages', 'social_media', 'affiliate_options', 'affiliate_configs',
            'password_reset_tokens', 'failed_jobs', 'personal_access_tokens', 'sessions', 'cache', 'cache_locks', 'jobs', 'job_batches',
        ];

        $predefinedGroups = [
            'Orders & Sales' => [
                'icon' => 'fa-solid fa-cart-shopping',
                'color' => '#3b82f6',
                'description' => 'Customer orders, invoices, abandoned carts and checkout transactions',
                'tables' => [
                    'orders' => 'Orders',
                    'order_details' => 'Order Line Items / Details',
                    'incomplete_orders' => 'Incomplete / Abandoned Orders',
                    'payments' => 'Payment Records',
                    'fraud_checks' => 'Fraud Verification History',
                    'carts' => 'Active Shopping Carts',
                    'wishlists' => 'User Wishlists',
                ],
            ],
            'Products & Catalog' => [
                'icon' => 'fa-solid fa-box-open',
                'color' => '#10b981',
                'description' => 'Products, category trees, attributes, variants and reviews',
                'tables' => [
                    'products' => 'Products',
                    'product_images' => 'Product Gallery Images',
                    'product_attributes' => 'Product Attribute Mappings',
                    'product_colors' => 'Product Color Mappings',
                    'categories' => 'Main Categories',
                    'sub_categories' => 'Sub Categories',
                    'child_categories' => 'Child Categories',
                    'attributes' => 'Attribute Types',
                    'attribute_values' => 'Attribute Options & Values',
                    'colors' => 'Color Options',
                    'reviews' => 'Product Ratings & Reviews',
                    'comments' => 'Product Comments / Q&A',
                ],
            ],
            'Customers & Accounts' => [
                'icon' => 'fa-solid fa-users',
                'color' => '#8b5cf6',
                'description' => 'Registered customer accounts, addresses, points and wallets',
                'tables' => [
                    'users' => 'Registered Customer Accounts',
                    'addresses' => 'Saved Customer Addresses',
                    'wallet_transactions' => 'Wallet Balance Transactions',
                    'point_transactions' => 'Reward Point Logs',
                    'blocked_ips' => 'Security Blocked IPs',
                ],
            ],
            'Promotions & Marketing' => [
                'icon' => 'fa-solid fa-tags',
                'color' => '#f59e0b',
                'description' => 'Promo discount coupons, storefront banners and sliders',
                'tables' => [
                    'coupons' => 'Discount Coupons & Vouchers',
                    'sliders' => 'Homepage Sliders',
                    'banners' => 'Promotional Banners',
                ],
            ],
            'Affiliate Program' => [
                'icon' => 'fa-solid fa-handshake',
                'color' => '#06b6d4',
                'description' => 'Affiliate partners, commissions, stats and withdraw requests',
                'tables' => [
                    'affiliate_users' => 'Affiliate Partner Accounts',
                    'affiliate_withdraw_requests' => 'Affiliate Payout Requests',
                    'affiliate_payments' => 'Affiliate Payment History',
                    'affiliate_logs' => 'Affiliate Commission Logs',
                    'affiliate_stats' => 'Affiliate Performance Stats',
                ],
            ],
            'Communications & Support' => [
                'icon' => 'fa-solid fa-comments',
                'color' => '#ec4899',
                'description' => 'Live chats, support tickets, WhatsApp, FB & IG customer messages',
                'tables' => [
                    'support_tickets' => 'Support Tickets',
                    'support_chats' => 'Support Live Chats',
                    'chat_messages' => 'Customer Support Messages',
                    'whatsapp_messages' => 'WhatsApp Chat Messages',
                    'whatsapp_contacts' => 'WhatsApp Contacts',
                    'facebook_messages' => 'Facebook Chat Messages',
                    'facebook_contacts' => 'Facebook Contacts',
                    'instagram_messages' => 'Instagram Chat Messages',
                    'instagram_contacts' => 'Instagram Contacts',
                ],
            ],
            'Logs & System Activity' => [
                'icon' => 'fa-solid fa-file-lines',
                'color' => '#64748b',
                'description' => 'System activity audits and operational logs',
                'tables' => [
                    'logs' => 'System & Activity Audit Logs',
                ],
            ],
        ];

        $tableGroups = [];
        $accountedTables = [];
        $totalRecords = 0;
        $totalClearableTables = 0;

        foreach ($predefinedGroups as $groupTitle => $groupData) {
            $groupItems = [];
            $groupRecordCount = 0;
            foreach ($groupData['tables'] as $tbl => $label) {
                if (in_array($tbl, $tables) && ! in_array($tbl, $restrictedTables)) {
                    $accountedTables[] = $tbl;
                    try {
                        $cnt = DB::table($tbl)->count();
                    } catch (\Exception $e) {
                        $cnt = 0;
                    }
                    $groupItems[] = [
                        'table' => $tbl,
                        'label' => $label,
                        'count' => $cnt,
                    ];
                    $groupRecordCount += $cnt;
                    $totalRecords += $cnt;
                    $totalClearableTables++;
                }
            }
            if (! empty($groupItems)) {
                $tableGroups[$groupTitle] = [
                    'icon' => $groupData['icon'],
                    'color' => $groupData['color'],
                    'description' => $groupData['description'],
                    'items' => $groupItems,
                    'total_count' => $groupRecordCount,
                ];
            }
        }

        // Capture any other database tables that exist but are not restricted or predefined
        $otherTables = array_diff($tables, $accountedTables, $restrictedTables);
        if (! empty($otherTables)) {
            $otherItems = [];
            $otherRecordCount = 0;
            foreach ($otherTables as $tbl) {
                try {
                    $cnt = DB::table($tbl)->count();
                } catch (\Exception $e) {
                    $cnt = 0;
                }
                $otherItems[] = [
                    'table' => $tbl,
                    'label' => ucwords(str_replace('_', ' ', $tbl)),
                    'count' => $cnt,
                ];
                $otherRecordCount += $cnt;
                $totalRecords += $cnt;
                $totalClearableTables++;
            }
            if (! empty($otherItems)) {
                $tableGroups['Other System Data'] = [
                    'icon' => 'fa-solid fa-cubes',
                    'color' => '#6b7280',
                    'description' => 'Miscellaneous database tables in this environment',
                    'items' => $otherItems,
                    'total_count' => $otherRecordCount,
                ];
            }
        }

        return view('adminDash.settings.system_commands', compact('tables', 'tableGroups', 'totalRecords', 'totalClearableTables', 'restrictedTables'));
    }

    public function truncateTable(Request $request)
    {
        $request->validate([
            'table_name' => 'required|string',
        ]);

        $table = $request->table_name;

        // Exclude critical tables to prevent catastrophic failure
        $restrictedTables = [
            'admins', 'roles', 'permissions', 'migrations', 'general_web_settings',
            'feature_activations', 'courier_apis', 'payment_apis', 'districts',
            'thanas', 'pages', 'social_media', 'affiliate_options', 'affiliate_configs',
            'password_reset_tokens', 'failed_jobs', 'personal_access_tokens', 'sessions', 'cache', 'cache_locks', 'jobs', 'job_batches',
        ];

        if (in_array($table, $restrictedTables)) {
            return response()->json(['success' => false, 'message' => "Truncating the '{$table}' table is restricted for safety reasons."], 403);
        }

        if (! Schema::hasTable($table)) {
            return response()->json(['success' => false, 'message' => "Table '{$table}' does not exist."], 404);
        }

        try {
            $countBefore = DB::table($table)->count();
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table($table)->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return response()->json([
                'success' => true,
                'message' => "Table '{$table}' has been successfully cleared! ({$countBefore} records removed)",
                'table' => $table,
                'deleted_count' => $countBefore,
            ]);
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return response()->json(['success' => false, 'message' => 'Error truncating table: '.$e->getMessage()], 500);
        }
    }

    public function bulkClearDatabase(Request $request)
    {
        $request->validate([
            'tables' => 'required|array|min:1',
            'tables.*' => 'required|string',
        ]);

        $restrictedTables = [
            'admins', 'roles', 'permissions', 'migrations', 'general_web_settings',
            'feature_activations', 'courier_apis', 'payment_apis', 'districts',
            'thanas', 'pages', 'social_media', 'affiliate_options', 'affiliate_configs',
            'password_reset_tokens', 'failed_jobs', 'personal_access_tokens', 'sessions', 'cache', 'cache_locks', 'jobs', 'job_batches',
        ];

        $tablesToClear = array_unique($request->tables);
        $clearedTables = [];
        $totalDeletedCount = 0;
        $errors = [];

        // Check for restricted tables
        $violatingTables = array_intersect($tablesToClear, $restrictedTables);
        if (! empty($violatingTables)) {
            return response()->json([
                'success' => false,
                'message' => 'Operation aborted: Table(s) ['.implode(', ', $violatingTables).'] are protected system tables and cannot be cleared.',
            ], 403);
        }

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            foreach ($tablesToClear as $table) {
                if (Schema::hasTable($table)) {
                    try {
                        $countBefore = DB::table($table)->count();
                        DB::table($table)->truncate();
                        $clearedTables[] = [
                            'table' => $table,
                            'records' => $countBefore,
                        ];
                        $totalDeletedCount += $countBefore;
                    } catch (\Exception $e) {
                        $errors[] = "Failed to clear {$table}: ".$e->getMessage();
                    }
                }
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            if (! empty($errors) && empty($clearedTables)) {
                return response()->json([
                    'success' => false,
                    'message' => implode(' | ', $errors),
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully cleared '.count($clearedTables).' table(s) with a total of '.number_format($totalDeletedCount).' record(s) purged.',
                'cleared_tables' => array_column($clearedTables, 'table'),
                'total_deleted' => $totalDeletedCount,
            ]);
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return response()->json([
                'success' => false,
                'message' => 'Error clearing database: '.$e->getMessage(),
            ], 500);
        }
    }
}
