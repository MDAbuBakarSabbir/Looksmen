<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $guarded = [''];

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('home_sliders_v2');
        });
        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('home_sliders_v2');
        });
    }
}
