<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function store(User $user)
    {
        /*   if (! $user->can('create-users')) {
               // abort(403,'you cannot create users');
               return response('you cannot create users');
           }*/

        return true;
    }
}
