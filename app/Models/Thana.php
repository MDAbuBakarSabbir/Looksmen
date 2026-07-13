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

    protected static function booted()
    {
        static::saved(function ($thana) {
            \Illuminate\Support\Facades\Cache::forget('thanas_by_district_' . $thana->district_id);
        });
        static::deleted(function ($thana) {
            \Illuminate\Support\Facades\Cache::forget('thanas_by_district_' . $thana->district_id);
        });
    }
}
