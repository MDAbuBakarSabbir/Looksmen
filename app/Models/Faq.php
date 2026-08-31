<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected $fillable = [
        'question',
        'answer',
        'category',
        'order',
        'status',
    ];

    protected $casts = [
        'order' => 'integer',
        'status' => 'integer',
    ];

    public static function categories(): array
    {
        return [
            'shipping' => 'Shipping & Delivery',
            'orders'   => 'Orders & Tracking',
            'payments' => 'Payments & Refunds',
            'returns'  => 'Returns & Warranty',
            'account'  => 'Account & Safety',
            'general'  => 'General / Other',
        ];
    }
}
