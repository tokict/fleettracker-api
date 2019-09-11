<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Auth\Access\HandlesAuthorization;

class VehiclePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->super_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the vehicle.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Vehicle $vehicle
     * @return mixed
     */
    public function get(User $user, Vehicle $vehicle)
    {

        return $user->company_id == $vehicle->company_id;


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
        return true;
    }

    /**
     * Determine whether the user can update the vehicle.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Vehicle $vehicle
     * @return mixed
     */
    public function update(User $user, Vehicle $vehicle)
    {
        return $user->company_id == $vehicle->company_id;
    }

    /**
     * Determine whether the user can delete the vehicle.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Vehicle $vehicle
     * @return mixed
     */
    public function delete(User $user, Vehicle $vehicle)
    {
        return $user->company_id == $vehicle->company_id;
    }
}
