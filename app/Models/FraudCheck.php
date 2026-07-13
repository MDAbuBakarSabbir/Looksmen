<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FraudCheck extends Model
{
    protected $guarded = [''];

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('fraud_check_settings_first');
        });
        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('fraud_check_settings_first');
        });
    }
}
