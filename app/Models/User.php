<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function affiliate_user()
    {
        return $this->hasOne(AffiliateUser::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Orders::class, 'user_id');
    }

    public function wallet_transactions()
    {
        return $this->hasMany(WalletTransaction::class, 'user_id')->latest();
    }

    public function point_transactions()
    {
        return $this->hasMany(PointTransaction::class, 'user_id')->latest();
    }
}
