<?php

namespace App\Policies;

use App\Models\Salary;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\HasPermission;

class SalaryPolicy
{
    use HandlesAuthorization, HasPermission;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['admin', 'accouter']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Salary $salary)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Salary $salary)
    {
        return $user->hasAnyRole(['admin', 'accouter']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Salary $salary)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Salary $salary)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Salary $salary)
    {
        return false;
    }
}
