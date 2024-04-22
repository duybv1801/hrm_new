<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Overtime;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\HasPermission;
use Illuminate\Support\Facades\Auth;


class OvertimePolicy
{
    use HandlesAuthorization, HasPermission;

    public function before(User $user)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['admin', 'hr', 'po']);
    }

    public function view(User $user)
    {
        return $user->hasAnyRole(['admin', 'hr', 'member']);
    }

    public function details(User $user, $id)
    {
        $userId = Overtime::find($id)->user_id;
        return $user->hasAnyRole(['admin', 'hr', 'po']) || $user->id === $userId;
    }

    public function update(User $user, $id)
    {
        $userId = Overtime::find($id)->user_id;
        return $user->id === $userId;
    }

    public function delete(User $user, $id)
    {
        $userId = Overtime::find($id)->user_id;
        return $user->id === $userId;
    }

    public function approve(User $user)
    {
        return $user->hasAnyRole(['admin', 'hr', 'po']);
    }
}
