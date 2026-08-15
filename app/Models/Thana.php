<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thana extends Model
{
        use HasFactory;
    protected $guarded = [''];
    public function district(){
        return $this->belongsTo(District::class);
    }

    public static function getNameById($id)
    {
        if (! $id) {
            return 'N/A';
        }
        $thanas = \Illuminate\Support\Facades\Cache::rememberForever('thanas_id_name_map', function () {
            return self::pluck('name', 'id')->toArray();
        });

        return $thanas[$id] ?? 'N/A';
    }

    protected static function booted()
    {
        static::saved(function ($thana) {
            \Illuminate\Support\Facades\Cache::forget('thanas_id_name_map');
            \Illuminate\Support\Facades\Cache::forget('thanas_by_district_' . $thana->district_id);
        });
        static::deleted(function ($thana) {
            \Illuminate\Support\Facades\Cache::forget('thanas_id_name_map');
            \Illuminate\Support\Facades\Cache::forget('thanas_by_district_' . $thana->district_id);
        });
    }
}
