<?php

namespace App\Observers;


use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;

class AssignmentObserver
{


    /**
     * Listen to the Assignment deleting event.
     *
     * @param  Assignment  $contact
     * @return void
     */
    public function deleting(Assignment $assignment)
    {
        $assignment->deleted_by = Auth::user()->id;

    }
}