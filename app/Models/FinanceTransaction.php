<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceTransaction extends Model
{
    use HasFactory;

    protected $table = 'finance_transactions';

    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    protected $appends = ['receipt_url'];

    public function getReceiptUrlAttribute()
    {
        if ($this->receipt_image && file_exists(public_path('Uploads/finance/'.$this->receipt_image))) {
            return asset('Uploads/finance/'.$this->receipt_image);
        }

        return null;
    }

    public function admin()
    {
        return $this->belongsTo(Admins::class, 'admin_id');
    }
}
