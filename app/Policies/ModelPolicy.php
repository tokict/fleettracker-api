<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Model;
use App\Models\VehicleModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModelPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->super_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\VehicleModel $model
     * @return mixed
     */
    public function get(User $user, VehicleModel $model)
    {

            return false;

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
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\VehicleModel $model
     * @return mixed
     */
    public function update(User $user, VehicleModel $model)
    {
        //
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\VehicleModel $model
     * @return mixed
     */
    public function delete(User $user, VehicleModel $model)
    {
        //
        return false;
    }
}
