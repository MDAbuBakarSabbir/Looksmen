<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramMessage extends Model
{
    protected $guarded = [];

    public function contact()
    {
        return $this->belongsTo(InstagramContact::class, 'instagram_contact_id');
    }
}
