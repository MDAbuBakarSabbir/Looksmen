<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model
{
    protected $guarded = [];

    public function contact()
    {
        return $this->belongsTo(WhatsappContact::class, 'whatsapp_contact_id');
    }
}
