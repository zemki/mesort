<?php

namespace App\Policies;

use App\Interview;
use App\Sorting;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SortingPolicy
{
    use HandlesAuthorization;

    /**
     * Don't check for authorization if the user is a superAdmin.
     *
     * @return bool
     */
    public function before($user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the sorting.
     *
     * @return mixed
     */
    public function view(User $user, Interview $interview)
    {
        if ($user->notOwnerNorInvited($interview->study)) {
            abort(403, 'You are not allowed to read this study');
        }

        return true;
    }

    /**
     * Determine whether the user can create sortings.
     *
     * @return void
     */
    public function create(User $user)
    {
    }

    /**
     * Determine whether the user can update the sorting.
     *
     * @return void
     */
    public function update(User $user, Sorting $sorting)
    {
    }

    /**
     * Determine whether the user can delete the sorting.
     *
     * @return mixed
     */
    public function delete(User $user, Sorting $sorting)
    {
    }

    /**
     * Determine whether the user can restore the sorting.
     *
     * @return void
     */
    public function restore(User $user, Sorting $sorting)
    {
    }

    /**
     * Determine whether the user can permanently delete the sorting.
     *
     * @return void
     */
    public function forceDelete(User $user, Sorting $sorting)
    {
    }
}
