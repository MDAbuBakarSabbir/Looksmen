<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeatureActivation;
use App\Models\GeneralWebSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

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

            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $image = $manager->decode($file);
            $image->scaleDown(width: 300);
            $image->save(public_path('adminDash/assets/img/layouts/'.$newname), quality: 60);

            GeneralWebSettings::where('name', 'web_logo')->update([
                'value' => $newname,
            ]);
            \Illuminate\Support\Facades\Cache::flush();

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

            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $image = $manager->decode($file);
            $image->scaleDown(width: 300);
            $image->save(public_path('adminDash/assets/img/layouts/'.$newname), quality: 60);

            GeneralWebSettings::where('name', 'footer_logo')->update([
                'value' => $newname,
            ]);
            \Illuminate\Support\Facades\Cache::flush();

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

            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $image = $manager->decode($file);
            $image->scaleDown(width: 150);
            $image->save(public_path('adminDash/assets/img/layouts/'.$newname), quality: 60);

            GeneralWebSettings::where('name', 'web_favicon')->update([
                'value' => $newname,
            ]);
            \Illuminate\Support\Facades\Cache::flush();

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

            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $image = $manager->decode($file);
            $image->scaleDown(width: 1200);
            $image->save(public_path('adminDash/assets/img/layouts/'.$newname), quality: 80);

            GeneralWebSettings::updateOrCreate(
                ['name' => 'social_banner'],
                ['value' => $newname, 'status' => '1']
            );
            \Illuminate\Support\Facades\Cache::flush();

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
        \Illuminate\Support\Facades\Cache::flush();

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
        \Illuminate\Support\Facades\Cache::flush();

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
                        $envContent = preg_replace('/^APP_TIMEZONE=.*/m', 'APP_TIMEZONE="' . $tz . '"', $envContent);
                    } else {
                        $envContent .= "\nAPP_TIMEZONE=\"" . $tz . "\"\n";
                    }
                    file_put_contents($envPath, $envContent);
                }
                putenv("APP_TIMEZONE={$tz}");
                $_ENV['APP_TIMEZONE'] = $tz;
                $_SERVER['APP_TIMEZONE'] = $tz;
                \Illuminate\Support\Facades\Artisan::call('config:clear');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('ENV Timezone update error: ' . $e->getMessage());
            }

            date_default_timezone_set($tz);
            config(['app.timezone' => $tz]);
        }

        \Illuminate\Support\Facades\Cache::forget('boot_general_web_settings_map');
        \Illuminate\Support\Facades\Cache::flush();

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

        \Illuminate\Support\Facades\Cache::flush();

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
        \Illuminate\Support\Facades\Cache::flush();

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
        \Illuminate\Support\Facades\Cache::flush();

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

    public function gtag_fbpixel()
    {
        return redirect()->route('websettings.index');
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

        return back()->with('success', 'Facebook Pixel Settings Updated Successfully!');
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
        $rawTables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
        
        foreach ($rawTables as $table) {
            $tableName = array_values((array)$table)[0];
            $tables[] = $tableName;
        }

        return view('adminDash.settings.system_commands', compact('tables'));
    }

    public function truncateTable(Request $request)
    {
        $request->validate([
            'table_name' => 'required|string',
        ]);

        $table = $request->table_name;
        
        // Exclude critical tables to prevent catastrophic failure
        $restrictedTables = ['users', 'admins', 'roles', 'permissions', 'migrations', 'general_web_settings', 'feature_activations'];
        
        if (in_array($table, $restrictedTables)) {
            return response()->json(['success' => false, 'message' => "Truncating the '{$table}' table is restricted for safety reasons."], 403);
        }

        try {
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            \Illuminate\Support\Facades\DB::table($table)->truncate();
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return response()->json(['success' => true, 'message' => "Table '{$table}' has been successfully truncated!"]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return response()->json(['success' => false, 'message' => 'Error truncating table: ' . $e->getMessage()], 500);
        }
    }
}
