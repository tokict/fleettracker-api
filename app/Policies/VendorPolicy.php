<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->super_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the vendor.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Vendor $vendor
     * @return mixed
     */
    public function get(User $user, Vendor $vendor)
    {

        return $user->company_id == $vendor->company_id;
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
     * Determine whether the user can update the vendor.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Vendor $vendor
     * @return mixed
     */
    public function update(User $user, Vendor $vendor)
    {
        return $user->company_id == $vendor->company_id;
    }

    /**
     * Determine whether the user can delete the vendor.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Vendor $vendor
     * @return mixed
     */
    public function delete(User $user, Vendor $vendor)
    {
        return $user->company_id == $vendor->company_id;
    }
}
