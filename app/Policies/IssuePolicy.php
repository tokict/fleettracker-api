<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;
use App\Models\Issue;
use App\Models\Vehicle;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Input;

class IssuePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->super_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the issue.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Issue $issue
     * @return mixed
     */
    public function get(User $user, Issue $issue)
    {

        return $user->company_id == $issue->vehicle->company_id;
    }

    /**
     * Determine whether the user can create companies.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //make sure we own the vehicle the task is for and all involved are in same company
        $vehicle = Vehicle::find(Input::get('vehicle.id'));
        $reporter = !empty(Input::get('reporter.id'))?Contact::find(Input::get('reporter.id')):null;
        $assignee = !empty(Input::get('assignee.id'))?Contact::find(Input::get('assignee.id')):null;


        if(isset($reporter) && $reporter->company_id != $user->company_id){
            return false;
        }

        if(isset($assignee) && $assignee->company_id != $user->company_id){
            return false;
        }


        return $vehicle->company_id == $user->company_id;

    }

    /**
     * Determine whether the user can update the issue.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Issue $issue
     * @return mixed
     */
    public function update(User $user, Issue $issue)
    {
        //make sure we own the vehicle the task is for and all involved are in same company
        $vehicle = Vehicle::find(Input::get('vehicle.id'));
        $reporter = !empty(Input::get('reporter.id'))?Contact::find(Input::get('reporter.id')):null;
        $assignee = !empty(Input::get('assignee.id'))?Contact::find(Input::get('assignee.id')):null;


        if(isset($reporter) && $reporter->company_id != $user->company_id){
            return false;
        }

        if(isset($assignee) && $assignee->company_id != $user->company_id){
            return false;
        }


        return $vehicle->company_id == $user->company_id;
    }

    /**
     * Determine whether the user can delete the issue.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Issue $issue
     * @return mixed
     */
    public function delete(User $user, Issue $issue)
    {
        //anyone from company can delete
        return $user->company_id == $issue->vehicle->company_id;
    }
}
