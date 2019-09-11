<?php

namespace App\Observers;


use App\Models\Comment;
use App\Models\Company;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class CompanyObserver
{


    /**
     * Listen to the Company deleting event.
     *
     * @param  Company  $company
     * @return void
     */
    public function deleting(Company $company)
    {
        $company->deleted_by = Auth::User()->id;

    }

    /**
     * Listen to the Company creating event.
     *
     * @param  Company  $company
     * @return void
     */
    public function creating(Company $company)
    {
        $company->contact_id = Auth::User()->id;

    }
}