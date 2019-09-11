<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Comment;
use App\Models\Issue;
use App\Models\Reminder;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{

    public function get()
    {
        $data = [
            'issues' => [],
            'reminders' => [
                'service' => [],
                'renewal' => [],
            ],
            'vehicles' => [],
            'comments' => [],
            'meters' => []
        ];

        //get issues data
        $data['issues']['open'] = $this->User->company->issues()->whereIn('status',
            ['open', 'feedback', 'in_progress'])->get();
        $data['issues']['resolved'] = $this->User->company->issues()->where('status', 'resolved')->get();


        //get renewal reminders data
        $data['reminders']['renewal'] = [
            'overdue' => Reminder::where('due_date', '<', date("Y-m-d H:i:s"))
                ->whereNull('service_type_id')
                ->where('company_id', Auth::user()->company_id)
                ->count(),
            'due_soon' => Reminder::where('due_date', '>', date("Y-m-d H:i:s"))
                ->where('due_date', '<', date("Y-m-d H:i:s", strtotime('+ 3 days')))
                ->whereNull('service_type_id')
                ->where('company_id', Auth::user()->company_id)
                ->count()
        ];
        $sReminders = Reminder::whereNotNull('service_type_id')->where('status', 'active')->where('company_id',
            Auth::user()->company_id)->get();

        $serviceReminders = ['overdue' => [], 'due_soon' => []];


        foreach ($sReminders as $reminder) {
            if ($reminder->vehicles()->count()) {
                foreach ($reminder->vehicles()->get() as $sub) {
                    $last_odo = $sub->vehicle->getOdometerState();
                    $service_last_odo = null;
                    $service_last_time = null;

                    //Find when was the task last performed
                    $services = $sub->vehicle->services()->orderBy('serviced_at', 'desc')->get();
                    foreach ($services as $service) {
                        $tasks_done_arr = explode(",", $service->performed_service_types);
                        //is this reminders service type present in this service entry
                        if (in_array($reminder->service_type_id, $tasks_done_arr)) {
                            //we found it
                            $service_last_odo = $service->odometer;
                            $service_last_time = $service->serviced_at->timestamp;
                            break;
                        }

                    }

                    if (isset($reminder->odometer_interval)) {
                        //CHECK ODO TRIGGERS
                        //If there is no service record for this service type, disregard the whole reminder for this car
                        if ($service_last_odo) {
                            $service_at = $service_last_odo + $reminder->odometer_interval;
                            $notice_at = $service_at - $reminder->odometer_threshold;
                            $note1000 = $service_at - 1000;
                            $note500 = $service_at - 500;

                            //If the last odo reading is past service odo trigger, it is overdue
                            if ($service_at < $last_odo) {

                                $serviceReminders['overdue'][$reminder->id] = $reminder->toArray();

                            }

                            $n = $notice_at < $last_odo;
                            $n1000 = $note1000 < $last_odo;
                            $n500 = $note500 < $last_odo;
                            //If the last odo reading is past service notify odo trigger, it is due soon. Excluding ones already in the array which are overdue
                            //Hardcoding notices on 1000 and 500 km prior
                            if (($n) && !isset($serviceReminders['overdue'][$reminder->id])
                            ) {

                                $serviceReminders['due_soon'][$reminder->id] = $reminder->toArray();


                            }


                        }
                    }
                    //CHECK FOR TIME TRIGGERS
                    if (isset($reminder->time_interval)) {

                        //If there is no service record for this service type, disregard the whole reminder for this car
                        if ($service_last_time) {
                            $service_at = strtotime('+' . $reminder->time_interval . ' ' . $reminder->time_interval_unit,
                                $service_last_time);
                            $notice_at = $service_at - strtotime($reminder->time_threshold . ' ' . $reminder->time_threshold_unit,
                                    0);
                            $note15 = $service_at - 1296000; // 15 days
                            $note3 = $service_at - 259200; // 3 days


                            //If the time is past service odo trigger, it is overdue
                            if ($service_at < time()
                                && !isset($serviceReminders['overdue'][$reminder->id])
                                && !isset($serviceReminders['due_soon'][$reminder->id])
                            ) {

                                $serviceReminders['overdue'][$reminder->id] = $reminder->toArray();

                            }

                            $n = $notice_at < time();
                            $n15 = $note15 < time();
                            $n3 = $note3 < time();
                            //If the time is past service notify time trigger, it is due soon. Excluding ones already in arrays

                            if (($n)
                                && !isset($serviceReminders['overdue'][$reminder->id])
                                && !isset($serviceReminders['due_soon'][$reminder->id])
                            ) {

                                $serviceReminders['due_soon'][$reminder->id] = $reminder->toArray();


                            }


                        }
                    }

                }
            }
        }


        //get service reminders data
        $data['reminders']['service'] = $serviceReminders;

        //Vehicle data
        $data['vehicles']['assigned'] = $this->User->company->getActiveVehicleAssignments();
        $data['vehicles']['unassigned'] = $this->User->company->vehicles->count() - count($data['vehicles']['assigned']);


        $data['meters'] = null;

        $data['comments'] = [];

        foreach ($this->User->company->comments()->whereNull('parent_comment_id')->distinct()->orderBy('id',
            'desc')->limit(5)->get() as $comment) {
            $com = $comment->getPublicData();
            $com['replies'] = [];
            $replies = Comment::where('parent_comment_id', $comment->id)->orderBy('id', 'asc')->limit(5)->get();
            foreach ($replies as $reply) {
                $com['replies'][] = $reply->getPublicData();
            }
            $data['comments'][] = $com;
        }


        return response()->json(
            $data
            , 200);
    }

}
