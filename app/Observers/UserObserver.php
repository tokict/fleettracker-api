<?php

namespace App\Observers;


use App\Models\Comment;
use App\Models\Company;
use App\Models\Subscription;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class UserObserver
{

    /**
     * Listen to the User creating event.
     *
     * @param  User  $user
     * @return void
     */
    public function creating(User $user)
    {
        $user->company_id = !empty(Auth::user())?Auth::user()->company_id:null;

    }


    /**
     * Listen to the User deleting event.
     *
     * @param  User  $user
     * @return void
     */
    public function deleting(User $user)
    {
        $user->deleted_by = Auth::user()->id;

    }
}