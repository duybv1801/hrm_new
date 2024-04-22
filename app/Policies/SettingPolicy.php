<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\HasPermission;

class SettingPolicy
{
    use HandlesAuthorization, HasPermission;

    public function before(User $user)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }
    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['admin', 'accounter', 'hr', 'member']);
    }

    public function view(User $user)
    {
        return $user->hasAnyRole(['admin', 'accounter', 'hr', 'member']);
    }

    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    public function update(User $user)
    {

        return $user->hasAnyRole(['admin', 'hr']);
    }

    public function delete(User $user)
    {
        return $user->hasRole('admin');
    }
}
