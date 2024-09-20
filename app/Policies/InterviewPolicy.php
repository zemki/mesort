<?php

namespace App\Policies;

use App\Interview;
use App\Study;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InterviewPolicy
{
    use HandlesAuthorization;

    /**
     * Don't check for authorization if the user is a superadmin.
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
     * Determine whether the user can view the interview.
     *
     * @return mixed
     */
    public function view(User $user, Interview $interview)
    {
        if (auth()->user()->notOwnerNorInvited($interview->study)) {
            abort(403, 'You are not allowed to read this interview');
        }

        return true;
    }

    /**
     * Determine whether the user can create interviews.
     *
     * @param  Study  $study
     * @return mixed
     */
    public function create(User $user, $study)
    {
        if (auth()->user()->notOwnerNorInvited($study)) {
            abort(403, 'You are not allowed to create interviews for this study');
        }

        return true;
    }

    /**
     * Determine whether the user can update the interview.
     *
     * @return void
     */
    public function update(User $user, Interview $interview)
    {
    }

    /**
     * Determine whether the user can delete the interview.
     *
     * @return void
     */
    public function delete(User $user, Interview $interview)
    {
    }

    /**
     * Determine whether the user can restore the interview.
     *
     * @return void
     */
    public function restore(User $user, Interview $interview)
    {
    }

    /**
     * Determine whether the user can permanently delete the interview.
     *
     * @return void
     */
    public function forceDelete(User $user, Interview $interview)
    {
    }

    public function exportall(User $user, $study)
    {
        if (auth()->user()->notOwnerNorInvited($study)) {
            abort(403, 'You are not allowed to read this study');
        }

        return true;
    }
}
