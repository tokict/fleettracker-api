<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Renewaltype;
use Illuminate\Auth\Access\HandlesAuthorization;

class RenewaltypePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->super_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the renewaltype.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Renewaltype $renewaltype
     * @return mixed
     */
    public function get(User $user, Renewaltype $renewaltype)
    {

            return true;

    }

    /**
     * Determine whether the user can create companies.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return false;
    }

    /**
     * Determine whether the user can update the renewaltype.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Renewaltype $renewaltype
     * @return mixed
     */
    public function update(User $user, Renewaltype $renewaltype)
    {
        //
        return false;
    }

    /**
     * Determine whether the user can delete the renewaltype.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Renewaltype $renewaltype
     * @return mixed
     */
    public function delete(User $user, Renewaltype $renewaltype)
    {
        //
        return false;
    }
}
