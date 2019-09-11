<?php

namespace App\Observers;


use App\Models\Comment;
use App\Models\MediaLink;
use App\Models\Medium;
use App\Models\Reminder;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class MediaObserver
{
    /**
     * Listen to the Medium creating event.
     *
     * @param  Medium  $media
     * @return void
     */
    public function creating(Medium $media)
    {
        $media->uploaded_by = Auth::user()->id;
        $media->company_id = Auth::user()->company_id;
    }

    /**
     * Listen to the Medium deleting event.
     *
     * @param  Medium  $media
     * @return void
     */
    public function deleting(Medium $media)
    {
        $media->deleted_by = Auth::user()->id;

    }
}