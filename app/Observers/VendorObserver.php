<?php

namespace App\Observers;


use App\Models\Vendor;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class VendorObserver
{


    /**
     * Listen to the Vendor deleting event.
     *
     * @param  Vendor  $vendor
     * @return void
     */
    public function deleting(Vendor $vendor)
    {
        $vendor->deleted_by = Auth::User()->id;

        //services
        foreach ($vendor->services()->get() as $service) {
            $service->vendor_id = null;
            $service->save();
        }

        //comments
        $vendor->comments()->each(function ($item) {
            $item->delete();
        });

    }
}