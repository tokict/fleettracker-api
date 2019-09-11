<?php

namespace App\Observers;


use App\Models\Comment;
use App\Models\Issue;
use App\Models\Reminder;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class IssueObserver
{
    /**
     * Listen to the Issue creating event.
     *
     * @param  Issue  $issue
     * @return void
     */
    public function creating(Issue $issue)
    {
        $issue->submitted_by = Auth::user()->id;
        $issue->company_id = Auth::user()->company_id;

    }

    /**
     * Listen to the Reminder deleting event.
     *
     * @param  Issue  $issue
     * @return void
     */
    public function deleting(Issue $issue)
    {
        $issue->deleted_by = Auth::user()->id;
        //Comments
        $issue->comments()->each(function ($item) {
            $item->delete();
        });

        //Service entries for resolved issue ids
        $services = $issue->company->services;
        foreach ($services as $service) {
            $issue_ids = explode(",",$service->resolved_issues);
            if(in_array($issue->id, $issue_ids)){
                foreach ($issue_ids as $key => $id){
                    if($id == $issue->id){
                        unset($issue_ids[$key]);
                    }
                }
            }
            $service->resolved_issues = implode(",", $issue_ids);
            $service->save();
        }
    }
}