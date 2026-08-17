<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FraudCheck extends Model
{
    protected $guarded = [''];

    public static function getActiveProvider()
    {
        return Cache::rememberForever('fraud_check_settings_first', function () {
            $active = self::where('status', '1')->first();
            if ($active) {
                return [
                    'id' => $active->id,
                    'name' => $active->name,
                    'api_key' => $active->api_key,
                    'base_url' => $active->base_url,
                    'status' => (string) $active->status,
                ];
            }

            return null;
        });
    }

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('fraud_check_settings_first');
        });
        static::deleted(function () {
            Cache::forget('fraud_check_settings_first');
        });
    }
}
