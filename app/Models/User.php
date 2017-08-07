<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'sw_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name', 'avatar', 'email', 'password', 'login_count'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'sw_role_users');
    }

    /**
     * Check jika user punya akses ke $permission.
     */
     public function hasAccess(array $permissions) : bool
     {
         // check if the permission is available in any role
         foreach ($this->roles as $role) {
             if($role->hasAccess($permissions)) {
                 return true;
             }
         }
         return false;
     }


    /**
     * Check jika user punya role
     */
    public function inRole(string $roleSlug)
    {
        return $this->roles()->where('slug', $roleSlug)->count() == 1;
    }
}
