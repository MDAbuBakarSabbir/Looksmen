<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'sender',
        'message',
        'is_transferred',
    ];

    protected $casts = [
        'is_transferred' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
