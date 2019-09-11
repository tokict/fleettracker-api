<?php

namespace App\Policies;

use App\Models\Medium;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MediaPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->super_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can get the media info.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Medium $media
     * @return mixed
     */
    public function get(User $user, Medium $media)
    {

        return $user->company_id == $media->company_id;

    }


    /**
     * Determine whether the user can update the media.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Medium $media
     * @return mixed
     */
    public function update(User $user, Medium $media)
    {
        return $user->company_id == $media->company_id;
    }



    /**
     * Determine whether the user can delete the media.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Medium $media
     * @return mixed
     */
    public function delete(User $user, Medium $media)
    {
        return $user->company_id == $media->company_id;
    }
}
