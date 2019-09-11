<?php

namespace App\Observers;


use App\Models\Comment;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class ServiceObserver
{
    /**
     * Listen to the Service created event.
     *
     * @param  Service  $service
     * @return void
     */
    public function creating(Service $service)
    {
        $service->created_by = Auth::user()->id;
        $service->company_id = Auth::user()->company_id;

    }

    /**
     * Listen to the User deleting event.
     *
     * @param  Service  $service
     * @return void
     */
    public function deleted(Service $service)
    {
        $service->deleted_by = Auth::user()->id;
        //Delete comments
        $service->comments()->each(function ($item) {
            $item->delete();
        });

    }
}