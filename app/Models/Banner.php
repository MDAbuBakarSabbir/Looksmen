<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $guarded = [''];

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('home_banners_v2');
        });
        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('home_banners_v2');
        });
    }
}
