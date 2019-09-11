<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Reminder;
use App\Models\Subscription;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ReminderController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if (!$this->User->can('create', Reminder::class)) {
            abort(500);
        }
        $val = [
            'service_task' => 'numeric|required_without:renewal',
            'odometer_interval' => 'numeric|nullable',
            'time_interval' => 'numeric|nullable',
            'time_interval_unit' => 'required_with:time_interval',
            'odometer_threshold' => 'numeric|nullable',
            'time_threshold' => 'numeric|nullable',
            'time_threshold_unit' => 'required_with:time_threshold',
            'vehicle_ids' => 'nullable',
            'user_ids' => 'nullable',
            'email' => 'boolean|required',
            'renewal' => 'required_without:service_task|nullable',
            'due_date' => 'date|required_without:service_task|nullable',
            'sms' => 'boolean|required'
        ];
        $this->validate($request, $val);

        $input = $this->filterParams($val);


        $userIds = [];
        if (isset($input['user_ids']) && is_array($input['user_ids'])) {
            $userIds = $input['user_ids'];
            if ($userIds) {
                $users = Contact::whereIn('id', $userIds)->get();
                foreach ($users as $u) {
                    if ($u->company_id != $this->User->company_id) {
                        return response()->json([
                            'errors' => 'User id selected for subscription is not allowed'
                        ], 422);
                    }
                }
            }
        }

        $vehicleIds = [];
        if (isset($input['vehicle_ids']) && is_array($input['vehicle_ids'])) {
            $vehicleIds = $input['vehicle_ids'];
            if ($vehicleIds) {
                $vehicles = Vehicle::whereIn('id', $vehicleIds)->get();
                foreach ($vehicles as $v) {
                    if ($v->company_id != $this->User->company_id) {
                        return response()->json([
                            'errors' => 'Vehicle id selected for subscription is not allowed'
                        ], 422);
                    }
                }
            }
        }


        $input['created_by'] = $this->User->id;
        $input['service_type_id'] = isset($input['service_task']) ? $input['service_task'] : null;
        $input['renewal_type_id'] = isset($input['renewal']) ? $input['renewal']['id'] : null;

        if(isset($input['due_date'])) {
            $due_date = new Carbon($input['due_date']);
            $input['due_date'] = $due_date->format("Y-m-d H:i:s");
        }

        $reminder = Reminder::create($input);
        if ($reminder) {
            if (!empty($vehicleIds)) {
                foreach ($vehicleIds as $item) {
                    $data = [
                        'item_id' => $reminder->id,
                        'type' => isset($reminder->renewal_type_id) ? 'renewal' : 'service',
                        'vehicle_id' => $item
                    ];

                    $sub = Subscription::create($data);

                }
            }

            if (!empty($userIds)) {
                foreach ($userIds as $item) {
                    $data = [
                        'item_id' => $reminder->id,
                        'type' => isset($reminder->renewal_type_id) ? 'renewal' : 'service',
                        'contact_id' => $item
                    ];

                    $sub = Subscription::create($data);

                }
            }


            return response()->json(
                $reminder->getPublicData(), 201
            );
        } else {
            return response()->json([
                'errors' => 'Could not save reminder to db'
            ], 500);
        }

    }

    public function get(Request $request)
    {
        $this->validate($request, [
            'id' => 'numeric',
        ]);

        if (!empty(Input::get('id'))) {
            $reminder = Reminder::find(Input::get('id'));
            if (!$this->User->can('get', $reminder)) {
                abort(500);
            }
            if ($reminder) {
                return response()->json(
                    $reminder->getPublicData()
                    , 200);
            } else {
                return response()->json([
                    'error' => 'No reminder with that id'
                ], 422);
            }
        }
        //At this point if he did not search by id and there is no params, something is wrong
        $p = Input::get('params');
        $params = null;
        if ($p) {
            $params = json_decode($p, true);
        }

        if (is_array($params)) {
            return $this->doSearch($params, 'Reminder');
        } else {
            return $this->doSearch([], 'Reminder');
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric'
        ]);

        $reminder = Reminder::find(Input::get('id'));
        if (!$this->User->can('delete', $reminder)) {
            abort(500);
        }
        if ($reminder) {
            if ($reminder->delete()) {
                return response()->json([
                    'success' => true
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Could not delete reminder using provided parameters'
                ], 422);
            }
        } else {
            return response()->json([
                'error' => 'No reminder with that id'
            ], 422);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $val = [
            'id' => 'required|numeric',
            'service' => 'required_without:renewal',
            'odometer_interval' => 'numeric|nullable',
            'time_interval' => 'numeric|nullable',
            'time_interval_unit' => 'required_with:time_interval',
            'odometer_threshold' => 'numeric|nullable',
            'time_threshold' => 'numeric|nullable',
            'time_threshold_unit' => 'required_with:time_threshold',
            'vehicles' => 'nullable',
            'users' => 'nullable',
            'email' => 'boolean|required',
            'renewal' => 'required_without:service|nullable',
            'due_date' => 'date|required_without:service|nullable',
            'sms' => 'boolean|required'
        ];

        $this->validate($request, $val);

        $input = $this->filterParams($val);
        $reminder = Reminder::find(Input::get('id'));
        if (!$this->User->can('update', $reminder)) {
            abort(500);
        }


        $userIds = [];
        if (isset($input['users']) && is_array($input['users'])) {
            foreach ($input['users'] as $user) {
                $userIds[] = $user['id'];
            }
            if ($userIds) {
                $contacts = Contact::whereIn('id', $userIds)->get();
                foreach ($contacts as $c) {
                    if ($c->company_id != $this->User->company_id) {
                        return response()->json([
                            'errors' => 'Contact id selected for subscription is not allowed'
                        ], 422);
                    }
                }
            }
        }

        $vehicleIds = [];
        if (isset($input['vehicles']) && is_array($input['vehicles'])) {
            foreach ($input['vehicles'] as $vehicle) {
                $vehicleIds[] = $vehicle['id'];
            }
            if ($vehicleIds) {
                $vehicles = Vehicle::whereIn('id', $vehicleIds)->get();
                foreach ($vehicles as $v) {
                    if ($v->company_id != $this->User->company_id) {
                        return response()->json([
                            'errors' => 'Vehicle id selected for subscription is not allowed'
                        ], 422);
                    }
                }
            }
        }
        $input['service_type_id'] = isset($input['service']) ? $input['service']['id'] : null;
        $input['renewal_type_id'] = isset($input['renewal']) ? $input['renewal']['id'] : null;
        if ($reminder) {
            if(isset($input['due_date'])) {
                $due_date = new Carbon($input['due_date']);
                $input['due_date'] = $due_date->format("Y-m-d H:i:s");
            }
            if ($reminder->update($input)) {
                //get everything subscribed to this item
                $subs = Subscription::where('item_id', $reminder->id)->where('type', isset($reminder->renewal_type) ? 'renewal' : 'service')
                    ->whereNull('contact_id')->get();

                //Remove any subscription that are not present in update
                foreach ($subs as $sub) {
                    if (!in_array($sub->vehicle_id, $vehicleIds)) {
                        $sub->delete();
                    }
                }
                if (isset($vehicleIds)) {
                    foreach ($vehicleIds as $item) {
                        $data = [
                            'item_id' => $reminder->id,
                            'type' => isset($reminder->renewal_type) ? 'renewal' : 'service',
                            'vehicle_id' => $item
                        ];

                        //add new ones only
                        $check = Subscription::where('vehicle_id', $item)->where('type' , isset($reminder->renewal_type) ? 'renewal' : 'service')
                            ->where('item_id', $reminder->id)->get()->first();
                        if(!$check) {
                            Subscription::create($data);
                        }

                    }
                }


                if (!empty($userIds)) {
                    $subs = Subscription::where('item_id', $reminder->id)->where('type', isset($reminder->renewal_type) ? 'renewal' : 'service')
                        ->whereNull('vehicle_id')->get();


                    //Remove any subscription that are not present in update
                    foreach ($subs as $sub) {
                        if (!in_array($sub->contact_id, $userIds)) {
                            $sub->delete();
                        }
                    }

                    foreach ($userIds as $item) {
                        $data = [
                            'item_id' => $reminder->id,
                            'type' => isset($reminder->renewal_type) ? 'renewal' : 'service',
                            'contact_id' => $item
                        ];

                        //add new ones only
                        $check = Subscription::where('contact_id', $item)->where('type' , isset($reminder->renewal_type) ? 'renewal' : 'service')
                            ->where('item_id', $reminder->id)->get()->first();
                        if(!$check) {
                            Subscription::create($data);
                        }

                    }
                    return response()->json(
                        $reminder->getPublicData()
                        , 200);
                } else {
                    return response()->json([
                        'error' => 'Could not update entry using provided parameters'
                    ], 422);
                }
            } else {
                return response()->json([
                    'error' => 'No entry with that id'
                ], 422);
            }
        }
        }

        /**
         * @param Request $request
         * @return \Illuminate\Http\JsonResponse
         */
        public
        function options(Request $request)
        {

            return response()->json([
                    'status' => parent::getEnumOptions('reminders', 'status'),

                ]
                , 200);
        }


        public function overview(){
            return response()->json([
                    'serviceAlerts' => Reminder::getAlerts('service', $this->User),
                    'renewalAlerts' => Reminder::getAlerts('renewal', $this->User),

                ]
                , 200);
        }

    }
