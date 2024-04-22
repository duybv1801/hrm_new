<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\HasPermission;
use App\Models\Timesheet;

class TimesheetPolicy
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
        return $user->hasAnyRole(['admin', 'hr', 'po']);
    }

    public function view(User $user)
    {
        return $user->hasAnyRole(['admin', 'hr', 'member', 'po']);
    }

    public function import(User $user)
    {
        return $user->hasAnyRole(['admin', 'hr']);
    }
}
