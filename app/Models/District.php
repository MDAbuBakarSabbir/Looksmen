<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $guarded = [''];


    public function thanas()
    {
        return $this->hasMany(Thana::class);
    }

    public function district()
    {
        return $this->belongsTo(Address::class, 'district_id');
    }

    public static function getNameById($id)
    {
        if (! $id) {
            return 'N/A';
        }
        $districts = \Illuminate\Support\Facades\Cache::rememberForever('active_districts_map', function () {
            return self::pluck('name', 'id')->toArray();
        });

        return $districts[$id] ?? 'N/A';
    }

    public static function getById($id)
    {
        if (! $id) {
            return null;
        }
        $districts = \Illuminate\Support\Facades\Cache::rememberForever('active_districts_models_map', function () {
            return self::all()->keyBy('id');
        });

        return $districts->get($id);
    }

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('active_districts_list');
            \Illuminate\Support\Facades\Cache::forget('active_districts_map');
            \Illuminate\Support\Facades\Cache::forget('active_districts_models_map');
        });
        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('active_districts_list');
            \Illuminate\Support\Facades\Cache::forget('active_districts_map');
            \Illuminate\Support\Facades\Cache::forget('active_districts_models_map');
        });
    }
}
