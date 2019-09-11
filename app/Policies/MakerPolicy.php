<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VehicleMaker;
use Illuminate\Auth\Access\HandlesAuthorization;

class MakerPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        //Only super admin can edit these. All else is false
        if ($user->super_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the maker.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\VehicleMaker $maker
     * @return mixed
     */
    public function get(User $user, VehicleMaker $maker)
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
     * Determine whether the user can update the maker.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\VehicleMaker $maker
     * @return mixed
     */
    public function update(User $user, VehicleMaker $maker)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the maker.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\VehicleMaker $maker
     * @return mixed
     */
    public function delete(User $user, VehicleMaker $maker)
    {
        return false;
    }
}
