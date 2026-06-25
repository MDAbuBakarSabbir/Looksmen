<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $guarded = [''];
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }
    public function thana()
    {
        return $this->belongsTo(Thana::class, 'thana_id', 'id');
    }
}
