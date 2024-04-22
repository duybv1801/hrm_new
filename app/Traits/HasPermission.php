<?php

namespace App\Traits;

use App\Models\Role;


trait HasPermission
{
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasAnyRole($roles)
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
