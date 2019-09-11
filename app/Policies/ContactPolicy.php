<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
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
    public function get(User $user, Contact $contact)
    {
        //We can get contacts only owned by our own company
        return $user->company_id == $contact->company_id;


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
        return true;
    }

    /**
     * Determine whether the user can update the contact.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Contact $contact
     * @return mixed
     */
    public function update(User $user, Contact $contact)
    {
        //Update only contacts in same company
        return $user->company_id == $contact->company_id;
    }

    /**
     * Determine whether the user can delete the contact.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Contact $contact
     * @return mixed
     */
    public function delete(User $user, Contact $contact)
    {
        //Don't allow deleting company contact
        if (\Auth::user()->company->contact_id == $contact->id) {
            return false;
        }
        //Deletes only for company owned contacts
        return $user->company_id == $contact->company_id;
    }
}
