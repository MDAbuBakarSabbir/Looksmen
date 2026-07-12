<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    protected $guarded = [''];

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('global_pages_list');
        });
        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('global_pages_list');
        });
    }
}
