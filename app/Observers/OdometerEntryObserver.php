<?php

namespace App\Observers;


use App\Models\Comment;
use App\Models\Issue;
use App\Models\OdometerEntries;
use App\Models\Reminder;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class OdometerEntryObserver
{
    /**
     * Listen to the OdometerEntries creating event.
     *
     * @param  OdometerEntries  $entry
     * @return void
     */
    public function creating(OdometerEntries $entry)
    {
        if(Auth::user()) {
            $entry->created_by = Auth::user()->id;
        }


    }

    /**
     * Listen to the OdometerEntries deleting event.
     *
     * @param  OdometerEntries  $entry
     * @return void
     */
    public function deleting(OdometerEntries $entry)
    {
        $entry->deleted_by = Auth::user()->id;

    }

    public function updating(OdometerEntries $entry)
    {
        $entry->updated_by = Auth::user()->id;

        if($entry->vehicle()->itrack_id && !empty($entry->end_odo)){
            throw new \Exception('Adding manual odo entries for vehicles with GPS module is not allowed!');
        }

    }

    public function getPublicData($excludeFields = [])
    {
        $entry = $this->toArray();
        unset(
            $entry['updated_by'],
            $entry['deleted_by'],
            $entry['created_by_by'],
            $entry['vehicle_id']
        );

        return $entry;
    }
}