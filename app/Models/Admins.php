<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Admins extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard='admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [''];
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

    public function hasPermission($permission)
    {
        // Master admin has all permissions
        if ($this->role_id === 'admin') {
            return true;
        }

        if (!$this->permission_id) {
            return false;
        }

        $permissions = json_decode($this->permission_id, true);
        if (!is_array($permissions)) {
            return false;
        }

        return in_array($permission, $permissions);
    }
}
