<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    protected $guarded = [''];

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::flush();
        });
        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::flush();
        });
    }
}
