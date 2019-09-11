<?php

namespace App\Policies;

use App\Models\Medium;
use App\Models\User;
use App\Models\Service;
use App\Models\Vehicle;
use App\Models\Vendor;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->super_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the service.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Service $service
     * @return mixed
     */
    public function get(User $user, Service $service)
    {

        return $user->company_id == $service->creator->company_id;


    }

    /**
     * Determine whether the user can create companies.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the service.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Service $service
     * @return mixed
     */
    public function update(User $user, Service $service)
    {
        //
        return $user->company_id == $service->creator->company_id;
    }

    /**
     * Determine whether the user can delete the service.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Service $service
     * @return mixed
     */
    public function delete(User $user, Service $service)
    {
        //
        return $user->company_id == $service->creator->company_id;
    }
}
