<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reminder;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Input;

class ReminderPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->super_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the reminder.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Reminder $reminder
     * @return mixed
     */
    public function get(User $user, Reminder $reminder)
    {

        return $user->company_id == $reminder->creator->company_id;
    }

    /**
     * Determine whether the user can create companies.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //No need for checking
        return true;
    }

    /**
     * Determine whether the user can update the reminder.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Reminder $reminder
     * @return mixed
     */
    public function update(User $user, Reminder $reminder)
    {
        return $user->company_id == $reminder->creator->company_id;
    }

    /**
     * Determine whether the user can delete the reminder.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Reminder $reminder
     * @return mixed
     */
    public function delete(User $user, Reminder $reminder)
    {
        return $user->company_id == $reminder->creator->company_id;
    }
}
