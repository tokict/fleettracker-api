<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 19 Feb 2017 12:06:44 +0000.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Reminder
 *
 * @property int $id
 * @property int $service_type_id
 * @property int $odometer_interval
 * @property int $time_interval
 * @property int $odometer_threshold
 * @property int $time_threshold
 * @property string $time_threshold_unit
 * @property string $time_interval_unit
 * @property string $status
 * @property string $email
 * @property \Carbon\Carbon $due_date
 * @property int $sms
 * @property int $company_id
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property Carbon $deleted_at
 * @property int $deleted_by
 *
 * @property \App\Models\ServiceType $service_type
 * @property \App\Models\RenewalType $renewal_type
 * @property User $creator;
 * @property Collection $notifications
 * @property Collection $subscribers
 * @property User $deleter
 *
 * @package App\Models
 */
class Reminder extends Eloquent
{
    use SoftDeletes;
    public $timestamps = false;

    protected $casts = [
        'service_type_id' => 'int',
        'renewal_type_id' => 'int',
        'odometer_interval' => 'int',
        'time_interval' => 'int',
        'odometer_threshold' => 'int',
        'time_threshold' => 'int',
        'service_reminder' => 'int',
        'sms' => 'int',
        'email' => 'int',
        'created_by' => 'int'
    ];

    protected $dates = [
        'due_date'
    ];

    protected $fillable = [
        'service_type_id',
        'odometer_interval',
        'time_interval',
        'time_interval_unit',
        'odometer_threshold',
        'time_threshold',
        'time_threshold_unit',
        'email',
        'renewal_type_id',
        'due_date',
        'sms',
        'created_by',
        'status'
    ];

    public static function getAlerts($what, $User = null, $vehicle_id = null)
    {
        $reminderCollection = ['due_soon' => [], 'overdue' => [], 'ok' => []];
        $reminderCollectionFull = ['due_soon' => [], 'overdue' => [], 'ok' => []];


        $type = $what == 'service' ? 'service_type_id' : 'renewal_type_id';

        //get all vehicles ans check their reminder subscriptions. Push ones that meet conditions

        if ($User) {
            $reminders = self::whereNotNull($type)->where('status', 'active')->where('company_id',
                $User->company_id)->get();
        } else {
            $reminders = self::whereNotNull($type)->where('status', 'active')->get();
        }
        if ($what === 'service') {
            foreach ($reminders as $reminder) {
                if ($reminder->vehicles()->count()) {
                    foreach ($reminder->vehicles()->get() as $sub) {
                        //If we are looking for specific vehicle, skip others
                        if (empty($sub->vehicle)) {
                            continue;
                        }
                        if ($vehicle_id) {
                            if ($sub->vehicle->id != $vehicle_id) {
                                continue;
                            }
                        }

                        //get vehicle last odo reading

                        $last_odo = $sub->vehicle->getOdometerState();
                        $service_last_odo = null;
                        $service_last_time = null;

                        if (!$last_odo) {
                            continue;
                        }
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


                                //If the reminders has not passed original set date
                                $first = strtotime("+" . $reminder->time_interval . ' ' . $reminder->time_interval_unit,
                                    $service_last_time);

                                $service_at = $first;
                                $notice_at = $service_at - $reminder->odometer_threshold;
                                $note1000 = $service_at - 1000;
                                $note500 = $service_at - 500;
                                if ($first > time()) {
                                    $service_at = $first;
                                } else {
                                    //If it passed it, we use array of due kms
                                    if ($reminder->odometer_interval) {
                                        $due_km = [];

                                        $lastUsedKm = $service_last_odo;
                                        for ($i = 0; $i <= 500; $i++) {
                                            $x = $lastUsedKm + $reminder->odometer_interval;
                                            $lastUsedKm = $x;
                                            $due_km[] = $x;
                                        }

                                        foreach ($due_km as $key => $km) {
                                            //If this due km is after current km state, but last one was before now it means we have the next due km with reference to current odo
                                            if ($km > $last_odo && (isset($due_km[$key - 1]) && $due_km[$key - 1] < $last_odo)) {
                                                $service_at = $km;
                                                $notice_at = $service_at - $reminder->odometer_threshold;
                                                $note1000 = $service_at - 1000;
                                                $note500 = $service_at - 500;
                                            }
                                        }
                                    }
                                }


                                //If the last odo reading is past service odo trigger, it is overdue
                                if ($service_at < $last_odo) {

                                    $reminderCollection['overdue'][] = $sub->vehicle->id;
                                    $reminderCollectionFull['overdue'][] = [
                                        'type' => 'odo',
                                        'trigger_type' => 'noticeOdoUser',
                                        'trigger' => $service_at,
                                        'vehicle' => $sub->vehicle,
                                        'reminder' => $reminder
                                    ];

                                }

                                $n = $notice_at < $last_odo;
                                $n1000 = $note1000 < $last_odo;
                                $n500 = $note500 < $last_odo;
                                //If the last odo reading is past service notify odo trigger, it is due soon. Excluding ones already in the array which are overdue
                                //Hardcoding notices on 1000 and 500 km prior
                                if ($n) {

                                    $reminderCollection['due_soon'][] = $vehicle_id ? $reminder->toArray() : $sub->vehicle->id;
                                    $trigger_type = 'noticeOdo';
                                    /*if($n1000){
                                        $trigger_type = 'noticeOdo1000';
                                        if($n500){
                                            $trigger_type = 'noticeOdo500';*/
                                    if ($n) {
                                        $trigger_type = 'noticeOdoUser';
                                    }
                                    /*   }
                                   }*/
                                    $reminderCollectionFull['due_soon'][] = [
                                        'type' => 'odo',
                                        'trigger_type' => $trigger_type,
                                        'trigger' => $service_at,
                                        'vehicle' => $sub->vehicle,
                                        'reminder' => $reminder
                                    ];

                                }


                            }
                        }
                        //CHECK FOR TIME TRIGGERS
                        if (isset($reminder->time_interval)) {

                            //If there is no service record for this service type, disregard the whole reminder for this car
                            if ($service_last_time) {


                                //array of repeating due dates

                                if ($reminder->time_interval_unit) {
                                    switch ($reminder->time_interval_unit) {
                                        case('days'):
                                            $multiplier = 1000;
                                            break;
                                        case('weeks'):
                                            $multiplier = 500;
                                            break;
                                        case('months'):
                                            $multiplier = 120;
                                            break;
                                        case('years'):
                                            $multiplier = 10;
                                            break;
                                    }

                                    $due_dates = [];

                                    $lastUsedDate = $reminder->due_date;
                                    for ($i = 0; $i <= $multiplier; $i++) {
                                        $x = strtotime("+" . $reminder->time_interval . ' ' . $reminder->time_interval_unit,
                                            $lastUsedDate);
                                        $lastUsedDate = $x;
                                        $due_dates[] = $x;
                                    }


                                }

                                //If the reminders has not passed original set date
                                $first = strtotime("+" . $reminder->time_interval . ' ' . $reminder->time_interval_unit,
                                    $service_last_time);
                                $service_at = $first;
                                $notice_at = strtotime('-' . $reminder->time_threshold . ' ' . $reminder->time_threshold_unit,
                                    $service_at);
                                $note15 = $service_at - 1296000; // 15 days
                                $note3 = $service_at - 259200; // 3 days
                                if ($first > time()) {
                                    $service_at = $first;
                                } else {
                                    //If it passed it, we use array of due dates
                                    if ($reminder->time_interval) {
                                        foreach ($due_dates as $key => $time) {
                                            //If this due date is after now, but last one was before now it means we have the next due date with reference to current time
                                            if ($time > time() && (isset($due_dates[$key - 1]) && $due_dates[$key - 1] < time())) {
                                                $service_at = $time;
                                                $notice_at = strtotime('-' . $reminder->time_threshold . ' ' . $reminder->time_threshold_unit,
                                                    $service_at);
                                                $note15 = $service_at - 1296000; // 15 days
                                                $note3 = $service_at - 259200; // 3 days
                                            }
                                        }
                                    }
                                }


                                //If the time is past service odo trigger, it is overdue
                                if ($service_at < time()
                                ) {

                                    $reminderCollection['overdue'][] = $sub->vehicle->id;
                                    $reminderCollectionFull['overdue'][] = [
                                        'type' => 'time',
                                        'trigger_type' => 'noticeTimeUser',
                                        'trigger' => $service_at,
                                        'vehicle' => $sub->vehicle,
                                        'reminder' => $reminder
                                    ];

                                }

                                $n = $notice_at < time();
                                $n15 = $note15 < time();
                                $n3 = $note3 < time();
                                //If the time is past service notify time trigger, it is due soon. Excluding ones already in arrays

                                if (($n)
                                    && !in_array($sub->vehicle->id, $reminderCollection['overdue'])
                                    && !in_array($sub->vehicle->id, $reminderCollection['due_soon'])
                                ) {
                                    $trigger_type = 'noticeTime';
                                    /*if($n15){
                                        $trigger_type = 'noticeTime15';
                                        if($n3){
                                            $trigger_type = 'noticeTime3';*/
                                    if ($n) {
                                        $trigger_type = 'noticeTimeUser';
                                    }

                                    /*    }
                                    }*/
                                    $reminderCollection['due_soon'][] = $vehicle_id ? $reminder->toArray() : $sub->vehicle->id;
                                    $reminderCollectionFull['due_soon'][] = [
                                        'type' => 'time',
                                        'trigger_type' => $trigger_type,
                                        'trigger' => $service_at,
                                        'vehicle' => $sub->vehicle,
                                        'reminder' => $reminder
                                    ];

                                }


                            }
                        }

                    }
                }
            }
        } else {
            //Its renewal reminder


            foreach ($reminders as $reminder) {
                if ($reminder->vehicles()->count()) {
                    foreach ($reminder->vehicles()->get() as $sub) {
                        //If we are looking for specific vehicle, skip others
                        if ($vehicle_id) {
                            if (!$sub->vehicle) {
                                continue;
                            }
                            if ($sub->vehicle->id != $vehicle_id) {
                                continue;
                            }

                        }

                        /*$renewals_history = Service::where('vehicle_id',
                            $vehicle_id)->whereNotNull('performed_renewal_types')->get();
                        $renewals_history = $renewals_history ? $renewals_history->toArray() : $renewals_history;
                        foreach ($renewals_history as $index => $values) {
                            $renewalTypeNames = RenewalType::whereIn('id',
                                explode(",", $values['performed_renewal_types']))->get();
                            if ($renewalTypeNames) {
                                foreach ($renewalTypeNames as $renewalTypeName) {
                                    $renewals_history[$index]['type_names'][$renewalTypeName->name] = true;

                                }
                            }
                        }*/

                        /*    //Check this reminder subtype and find it in history and then in local history in services
                            foreach ($renewals_history as $key => $hist) {
                                //It will take tha last entry with the name ie 'registration' and use that as reference

                                if (isset($hist['type_names'][$reminder->renewal_type->name])) {
                                    $renewals_history = $hist;
                                }
                            }


                            if (!isset($renewals_history['type_names'][$reminder->renewal_type->name])) {
                                continue;
                            }

                            $last_renewal_date = $renewals_history['serviced_at'];*/


                        //for time

                        //array of repeating due dates

                        if ($reminder->time_interval_unit) {
                            switch ($reminder->time_interval_unit) {
                                case('days'):
                                    $multiplier = 1000;
                                    break;
                                case('weeks'):
                                    $multiplier = 500;
                                    break;
                                case('months'):
                                    $multiplier = 120;
                                    break;
                                case('years'):
                                    $multiplier = 10;
                                    break;
                            }

                            $due_dates = [];

                            $lastUsedDate = $reminder->due_date->timestamp;
                            for ($i = 0; $i <= $multiplier; $i++) {
                                $x = strtotime("+" . $reminder->time_interval . ' ' . $reminder->time_interval_unit,
                                    $lastUsedDate);
                                $lastUsedDate = $x;
                                $due_dates[] = $x;
                            }


                        }

                        $renewal_at = strtotime($reminder->due_date);
                        $notice_at = strtotime('-' . $reminder->time_threshold . ' ' . $reminder->time_threshold_unit,
                            $renewal_at);
                        //If the reminders has not passed original set date
                        if (strtotime($reminder->due_date) > time()) {
                            $renewal_at = strtotime($reminder->due_date);
                        } else {
                            //If it passed it, we use array of due dates
                            if ($reminder->time_interval) {
                                foreach ($due_dates as $key => $time) {
                                    //If this due date is after now, but last one was before now it means we have the next due date with reference to current time
                                    if ($time > time() && (isset($due_dates[$key - 1]) && $due_dates[$key - 1] < time())) {
                                        $renewal_at = $time;
                                        $notice_at = strtotime('-' . $reminder->time_threshold . ' ' . $reminder->time_threshold_unit,
                                            $renewal_at);
                                    }
                                }
                            }


                        }


                        //If the time is past service  trigger, it is overdue
                        if ($renewal_at < time()

                        ) {

                            $reminderCollection['overdue'][] = $vehicle_id ? $reminder->toArray() : $sub->vehicle->id;
                            $reminderCollectionFull['overdue'][] = [
                                'type' => 'time',
                                'trigger_type' => 'noticeTime',
                                'trigger' => $renewal_at,
                                'vehicle' => $sub->vehicle,
                                'reminder' => $reminder
                            ];

                        }

                        $n = $notice_at < time();

                        //If the time is past service notify time trigger, it is due soon. Excluding ones already in arrays

                        if (($n)

                        ) {
                            $trigger_type = 'noticeTimeUser';

                            if ($n) {
                                $trigger_type = 'noticeTimeUser';
                            }

                            $reminderCollection['due_soon'][] = $vehicle_id ? $reminder->toArray() : $sub->vehicle->id;
                            $reminderCollectionFull['due_soon'][] = [
                                'type' => 'time',
                                'trigger_type' => $trigger_type,
                                'trigger' => $renewal_at,
                                'vehicle' => $sub->vehicle,
                                'reminder' => $reminder
                            ];


                        }
                        //If the reminder is not due soo  or overdue put it in ok
                        if (!self::checkIsRenewalorServiceAdded($reminder->id, $reminderCollection['due_soon'])
                            && !self::checkIsRenewalorServiceAdded($reminder->id,
                                $reminderCollection['overdue'])) {
                            $reminderCollection['ok'][] = $vehicle_id ? $reminder->toArray() : $sub->vehicle->id;
                        }


                    }
                }

            }
        }
        //If the system asks, give it full
        return !$User ? $reminderCollectionFull : $reminderCollection;


    }

    private static function checkIsRenewalorServiceAdded($id, $array)
    {

        foreach ($array as $item) {
            if (isset($item['id']) && $item['id'] == $id) {
                return true;
            }
        }

        return false;
    }

    public function service_type()
    {
        return $this->belongsTo(\App\Models\ServiceType::class, 'service_type_id');
    }

    public function renewal_type()
    {
        return $this->belongsTo(\App\Models\RenewalType::class, 'renewal_type_id');
    }

    public function vehicles()
    {
        return $this->hasMany(\App\Models\Subscription::class,
            'item_id')->whereNotNull('vehicle_id')->whereNull('contact_id');
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }

    public function subscribers()
    {
        return $this->hasMany(\App\Models\Subscription::class,
            'item_id')->whereNotNull('contact_id')->whereNull('vehicle_id');
    }

    public function comments()
    {
        if (!empty($this->getAttribute('service_type_id'))) {
            return $this->hasMany(\App\Models\Comment::class, 'item_id')->where('type', 'service');
        }

        if (!empty($this->getAttribute('renewal_type_id'))) {
            return $this->hasMany(\App\Models\Comment::class, 'item_id')->where('type', 'renewal');
        }

    }

    public function notifications()
    {
        if (!empty($this->getAttribute('service_type_id'))) {
            return $this->hasMany(\App\Models\Notification::class, 'item_id')->where('type', 'service');
        }

        if (!empty($this->getAttribute('renewal_type'))) {
            return $this->hasMany(\App\Models\Notification::class, 'item_id')->where('type', 'renewal');
        }
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function deleter()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * @return array
     */
    public function getPublicData($excludeFields = [], $deep = true)
    {

        $reminder = $this->toArray();
        unset(
            $reminder['vehicle_ids'],
            $reminder['service_type_id'],
            $reminder['subscribed_vehicle_ids'],
            $reminder['subscribed_user_ids'],
            $reminder['renewal_type_id']

        );
        if (!empty($this->getAttribute('service_type'))) {
            $reminder['service'] = $this->getAttribute('service_type')->getPublicData();
        }
        if (!empty($this->getAttribute('renewal_type'))) {
            $reminder['renewal'] = $this->getAttribute('renewal_type')->getPublicData();
        }
        $reminder['creator'] = !empty($this->getAttribute('creator')) ? $this->getAttribute('creator')->getPublicData([],
            false) : null;

        $reminder['vehicles'] = [];
        if ($this->getAttribute('vehicles') && $deep) {
            foreach ($this->getAttribute('vehicles') as $vehicleSub) {
                if (!isset($vehicleSub->vehicle) || $vehicleSub->vehicle->deleted_at) {
                    continue;
                }
                $reminder['vehicles'][] =
                    $vehicleSub->vehicle->getPublicData();


            }
        }

        $reminder['users'] = [];
        if ($this->getAttribute('subscribers') && $deep) {
            foreach ($this->getAttribute('subscribers') as $usersSub) {
                $reminder['users'][] = $usersSub->contact->getPublicData();
            }
        }

        $reminder['comments'] = [];
        if ($this->getAttribute('comments') && $deep) {
            foreach ($this->getAttribute('comments') as $comment) {
                $issue['comments'][] = $comment->getPublicData();

            }
        }

        $reminder['last_notification'] = count($this->getAttribute('notifications')) ? $this->getAttribute('notifications')[0] : null;


        return $reminder;
    }

}


