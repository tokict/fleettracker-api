<?php

namespace App\Observers;


use App\Models\Comment;
use App\Models\Group;
use App\Models\Issue;
use App\Models\Reminder;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class GroupObserver
{
    /**
     * Listen to the Group created event.
     *
     * @param  Group  $group
     * @return void
     */
    public function creating(Group $group)
    {
        $group->company_id = Auth::user()->company_id;

    }

    /**
     * Listen to the Group deleting event.
     *
     * @param  Group  $group
     * @return void
     */
    public function deleting(Group $group)
    {
        $group->deleted_by = Auth::user()->id;
        //Remove from vehicles
        foreach ($group->vehicles()->get() as $vehicle) {
            $vehicle->group_id = null;
            $vehicle->save();
        }


    }
}