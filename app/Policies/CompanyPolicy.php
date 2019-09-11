<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Company;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->super_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the company.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Company $company
     * @return mixed
     */
    public function get(User $user, Company $company)
    {
        //We can get only our own company
        return $user->company->id == $company->id;

    }

    /**
     * Determine whether the user can create companies.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //Only super user can create companies
        return  !$user->company;
    }

    /**
     * Determine whether the user can update the company.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Company $company
     * @return mixed
     */
    public function update(User $user, Company $company)
    {
        //we can update only our own company

        return $user->company->id == $company->id;
    }

    /**
     * Determine whether the user can delete the company.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Company $company
     * @return mixed
     */
    public function delete(User $user, Company $company)
    {
        //only super user can delete companies
        return $user->super_admin;
    }
}
