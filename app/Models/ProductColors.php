<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductColors extends Model
{
    protected $guarded = [''];
        public function color()
{
    return $this->belongsTo(Color::class,'color_id');
}
}
