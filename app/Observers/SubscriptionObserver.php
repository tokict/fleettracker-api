<?php

namespace App\Observers;


use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class SubscriptionObserver
{

    /**
     * Listen to the Subscription deleting event.
     *
     * @param  Subscription $subscription
     * @return void
     */
    public function deleting(Subscription $subscription)
    {
        $subscription->deleted_by = Auth::user()->id;

    }
}