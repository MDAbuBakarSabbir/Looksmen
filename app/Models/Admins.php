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
        
        // If the admin has "all_permissions" assigned individually, they can access everything
        $userPerms = json_decode($this->permission_id ?? '[]', true) ?: [];
        if (in_array('all_permissions', $userPerms)) {
            return true;
        }

        // Get Role permissions
        $role = \App\Models\Roles::where('role', $this->role_id)->first();
        $rolePerms = $role ? (json_decode($role->permission_id ?? '[]', true) ?: []) : [];
        if (in_array('all_permissions', $rolePerms)) {
            return true;
        }

        // Merge both arrays to allow either role or individual to grant access
        $allPerms = array_unique(array_merge($userPerms, $rolePerms));

        return in_array($permission, $allPerms);
    }
}
