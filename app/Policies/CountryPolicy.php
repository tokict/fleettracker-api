<?php

namespace App\Policies;

use App\Models\Country;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class CountryPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->super_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the contact.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Contact $contact
     * @return mixed
     */
    public function get(User $user, Country $contact)
    {
        //We can get contacts only owned by our own company
        return true;


    }

    /**
     * Determine whether the user can create contact.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //Contacts are by default created for users company, no need for verification
        return false;
    }

    /**
     * Determine whether the user can update the contact.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Contact $contact
     * @return mixed
     */
    public function update(User $user, Country $contact)
    {
        //Update only contacts in same company
        return false;
    }

    /**
     * Determine whether the user can delete the contact.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Contact $contact
     * @return mixed
     */
    public function delete(User $user, Country $contact)
    {

            return false;

    }
}
