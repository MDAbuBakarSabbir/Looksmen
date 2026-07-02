<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookMessage extends Model
{
    protected $guarded = [];

    public function contact()
    {
        return $this->belongsTo(FacebookContact::class, 'facebook_contact_id');
    }
}
