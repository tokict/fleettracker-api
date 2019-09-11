<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->super_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the notification.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Notification $notification
     * @return mixed
     */
    public function get(User $user, Notification $notification)
    {

        if ($user->notification->id == $notification->id) {
            return true;
        }
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
    }

    /**
     * Determine whether the user can update the notification.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Notification $notification
     * @return mixed
     */
    public function update(User $user, Notification $notification)
    {
        //
    }

    /**
     * Determine whether the user can delete the notification.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Notification $notification
     * @return mixed
     */
    public function delete(User $user, Notification $notification)
    {
        //
    }
}
