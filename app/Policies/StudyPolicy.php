<?php

namespace App\Policies;

use App\Study;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudyPolicy
{
    use HandlesAuthorization;

    /**
     * Don't check for authorization if the user is a superadmin.
     *
     * @return bool|void
     */
    public function before($user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return true;
    }

    /**
     * Determine whether the user can view the study.
     *
     * @return void
     */
    public function view(User $user, Study $study)
    {
    }

    /**
     * Determine whether the user can create studies.
     *
     * @return mixed
     */
    public function create(User $user)
    {
    }

    /**
     * Determine whether the user can update the study.
     *
     * @return mixed
     */
    public function update(User $user, Study $study)
    {
        if ($user->isNot(User::find($study->author))) {
            abort(403, __("You can't edit this study"));
        }

        return true;
    }

    /**
     * Determine whether the user can delete the study.
     *
     * @return void
     */
    public function delete(User $user, Study $study)
    {
    }

    /**
     * Determine whether the user can restore the study.
     *
     * @return void
     */
    public function restore(User $user, Study $study)
    {
    }

    /**
     * Determine whether the user can permanently delete the study.
     *
     * @return void
     */
    public function forceDelete(User $user, Study $study)
    {
    }
}
