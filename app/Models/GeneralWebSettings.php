<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralWebSettings extends Model
{
    protected $guarded = [''];

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('boot_general_web_settings_map');
            \Illuminate\Support\Facades\Cache::forget('global_webinfo_first');
            \Illuminate\Support\Facades\Cache::forget('global_webconfig_pluck');
        });
        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('boot_general_web_settings_map');
            \Illuminate\Support\Facades\Cache::forget('global_webinfo_first');
            \Illuminate\Support\Facades\Cache::forget('global_webconfig_pluck');
        });
    }
}
