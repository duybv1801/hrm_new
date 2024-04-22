<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\HasPermission;

class RemotePolicy
{
    use HandlesAuthorization, HasPermission;

    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['admin', 'hr', 'po']);
    }

    public function create(User $user)
    {
        return $user->hasAnyRole(['admin', 'hr', 'po']);
    }

    public function update(User $user)
    {

        return $user->hasAnyRole(['admin',  'hr', 'po']);
    }

    public function delete(User $user)
    {
        return $user->hasAnyRole(['admin', 'hr']);
    }
}
