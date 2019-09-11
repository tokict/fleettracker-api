<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->super_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the user.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $user2
     * @return mixed
     */
    public function get(User $user, User $user2 = null)
    {

        if($user2){
            return $user->company_id == $user2->company_id ;
        }
        return true;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {

        return $user->admin;
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function update(User $user, User $user2)
    {
        if($user2){
            return $user->company_id == $user2->company_id && $user->admin;
        }
        return true;
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function delete(User $user, User $user2)
    {
        if($user2){
            return $user->company_id == $user2->company_id && $user->admin;
        }
        return true;
    }
}
