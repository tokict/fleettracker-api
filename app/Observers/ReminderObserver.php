<?php

namespace App\Observers;


use App\Models\Comment;
use App\Models\Reminder;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class ReminderObserver
{
    /**
     * Listen to the Reminder creating event.
     *
     * @param  Reminder  $service
     * @return void
     */
    public function creating(Reminder $reminder)
    {
        $reminder->created_by = Auth::user()->id;
        $reminder->company_id = Auth::user()->company_id;

    }

    /**
     * Listen to the Reminder deleting event.
     *
     * @param  Reminder  $reminder
     * @return void
     */
    public function deleting(Reminder $reminder)
    {
        $reminder->deleted_by = Auth::user()->id;
        //Delete subscriptions
        $reminder->subscribers()->each(function ($item) {
            $item->delete();
        });

    }
}