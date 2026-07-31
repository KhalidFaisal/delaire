<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /** @var string Table name for admin authentication. */
    protected $table = 'admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    /**
     * Check if the admin has a specific role.
     *
     * @param  string  $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if the admin has a specific permission key.
     *
     * @param string $permissionKey
     * @return bool
     */
    public function hasPermission($permissionKey)
    {
        if ($this->role === 'super_admin') {
            return true;
        }

        $role = Role::where('slug', $this->role)->first();
        if (!$role) {
            return false;
        }

        return in_array($permissionKey, $role->permissions ?? []);
    }

    /**
     * Check if the admin is permitted to access a specific route.
     *
     * @param string $routeName
     * @return bool
     */
    public function hasRoutePermission($routeName)
    {
        if ($this->role === 'super_admin') {
            return true;
        }

        $map = Role::PERMISSION_MAP;
        $permissionKey = null;

        foreach ($map as $key => $routes) {
            if (in_array($routeName, $routes)) {
                $permissionKey = $key;
                break;
            }
        }

        // If the route name is not mapped to any permission, default to true
        if (!$permissionKey) {
            return true;
        }

        return $this->hasPermission($permissionKey);
    }
}
