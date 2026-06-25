<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateUser extends Model
{
    protected $guarded = [''];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function affiliate_payments()
    {
        return $this->hasMany(AffiliatePayment::class, 'affiliate_user_id');
    }
}
