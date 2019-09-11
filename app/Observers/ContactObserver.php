<?php

namespace App\Observers;


use App\Models\Comment;
use App\Models\Contact;
use App\Models\Issue;
use App\Models\Reminder;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class ContactObserver
{


    /**
     * Listen to the Contact deleting event.
     *
     * @param  Contact  $contact
     * @return void
     */
    public function deleting(Contact $contact)
    {
        $contact->deleted_by = isset(Auth::user()->id)?Auth::user()->id:1;
        // companies. We must not delete company assigned contact. This is checked in policy

        // issues. Update assigned to in issues and reported by
        foreach ($contact->issues()->get() as $issue) {
            $issue->assigned_to = null;
            $issue->save();
        }
        foreach ($contact->reported_issues()->get() as $issue) {
            $issue->reported_by = null;
            $issue->save();
        }
        // users. Update contact id for user
        if(isset($contact->user)) {
            $u = $contact->user;
            $u->contact_id = null;
            $u->save();
        }


        // vehicles. Update operator id
        foreach ($contact->vehicles()->get() as $vehicle) {
            $vehicle->operator_id = null;
            $vehicle->save();
        }
        // vehicle_assignments
        $contact->assignments()->each(function ($item) {
            $item->delete();
        });

        //Subscriptions
        $contact->subscriptions()->each(function ($item) {
            $item->delete();
        });
    }

    /**
     * Listen to the Contact creating event.
     *
     * @param  Contact  $contact
     * @return void
     */
    public function creating(Contact $contact)
    {

            $contact->company_id = isset(Auth::user()->company_id)?Auth::user()->company_id:1;


    }

    /**
     * Listen to the Contact updating event.
     *
     * @param  Contact  $contact
     * @return void
     */
    public function updating(Contact $contact)
    {

    }
}