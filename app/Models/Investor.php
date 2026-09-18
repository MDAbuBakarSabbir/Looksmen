<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    use HasFactory;

    protected $table = 'investors';

    protected $guarded = [];

    protected $casts = [
        'share_percentage' => 'decimal:2',
    ];

    /**
     * Get all finance transactions associated with this investor.
     */
    public function transactions()
    {
        return FinanceTransaction::whereIn('category', [
            'Investment / Capital',
            'Investment Withdrawal',
            'Capital Withdrawal',
            'Investment',
            'Capital'
        ])->where('staff_name', $this->name);
    }

    /**
     * Cumulative total capital deposited/injected by this investor.
     */
    public function getTotalInvestedAttribute(): float
    {
        return (float) $this->transactions()
            ->where('entry_type', 'INCOME')
            ->sum('amount');
    }

    /**
     * Cumulative total capital withdrawn by this investor.
     */
    public function getTotalWithdrawnAttribute(): float
    {
        return (float) $this->transactions()
            ->where('entry_type', 'EXPENSE')
            ->sum('amount');
    }

    /**
     * Current active capital balance for this investor.
     */
    public function getActiveBalanceAttribute(): float
    {
        return $this->total_invested - $this->total_withdrawn;
    }

    /**
     * Total number of investment operations for this investor.
     */
    public function getTransactionCountAttribute(): int
    {
        return $this->transactions()->count();
    }
}
