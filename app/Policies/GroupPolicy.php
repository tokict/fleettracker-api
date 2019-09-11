<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Group;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->super_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the group.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Group $group
     * @return mixed
     */
    public function get(User $user, Group $group)
    {
        //Get only owned groups
        return $user->company_id == $group->company_id;

    }

    /**
     * Determine whether the user can create group.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //Automatic company_id assignment so no need to verify
        return true;
    }

    /**
     * Determine whether the user can update the group.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Group $group
     * @return mixed
     */
    public function update(User $user, Group $group)
    {
        //Update only same company
        return $user->company_id == $group->company_id;
    }

    /**
     * Determine whether the user can delete the group.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Group $group
     * @return mixed
     */
    public function delete(User $user, Group $group)
    {
        //Delete only same company
        return $user->company_id == $group->company_id;
    }
}
