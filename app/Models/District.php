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

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('active_districts_list');
        });
        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('active_districts_list');
        });
    }
}
