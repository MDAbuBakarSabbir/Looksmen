<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureActivation extends Model
{
    protected $guarded = [''];

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('feature_activations_map');
        });
        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('feature_activations_map');
        });
    }
}
