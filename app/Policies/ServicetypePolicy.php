<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Servicetype;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicetypePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->super_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the servicetype.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Servicetype $servicetype
     * @return mixed
     */
    public function get(User $user, Servicetype $servicetype)
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
     * Determine whether the user can update the servicetype.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Servicetype $servicetype
     * @return mixed
     */
    public function update(User $user, Servicetype $servicetype)
    {
        //
    }

    /**
     * Determine whether the user can delete the servicetype.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Servicetype $servicetype
     * @return mixed
     */
    public function delete(User $user, Servicetype $servicetype)
    {
        //
    }
}
