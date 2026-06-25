<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $guarded = [''];

    public function sender()
    {
        return $this->sender_type === 'admin' 
            ? $this->belongsTo(Admins::class, 'sender_id') 
            : $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->receiver_type === 'admin' 
            ? $this->belongsTo(Admins::class, 'receiver_id') 
            : $this->belongsTo(User::class, 'receiver_id');
    }
}
