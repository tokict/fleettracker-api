<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Group;
use App\Models\Medium;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ContactController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $val = [
            'first_name' => 'required|max:45|min:2',
            'last_name' => 'required|max:45|min:2',
            'birth_date' => 'date|nullable',
            'group_id' => 'numeric|nullable',
            'email' => 'max:100|nullable|email',
            'mobile_phone' => 'max:45|nullable',
            'home_phone' => 'max:45|nullable',
            'work_phone' => 'max:45|nullable',
            'other_phone' => 'max:45|nullable',
            'address' => 'max:100|nullable',
            'address_2' => 'max:100|nullable',
            'country_id' => 'nullable',
            'city' => 'max:45|nullable',
            'region' => 'max:45|nullable',
            'zip' => 'max:45|nullable',
            'employee_number' => 'max:45|nullable',
            'job_title' => 'max:100|nullable',
            'start_date' => 'nullable',
            'leave_date' => 'nullable',
            'driver' => 'boolean',
            'driver_license_number' => 'max:45|nullable',
            'driver_license_class' => 'max:45|nullable',
            'driver_license_region' => 'max:45|nullable',
            'hourly_rate' => 'numeric|nullable',

        ];

        $this->validate($request, $val);

        $input = $this->filterParams($val);
        if (isset($input['group_id'])) {
            $group = Group::find($input['group_id']);
            //Make sure he can only assign to own vendors
            if ($group->company_id != $this->User->company_id) {
                return response()->json([
                    'errors' => 'Selected group id not available to user'
                ], 422);
            }
        }

        if (isset($input['photo_id'])) {
            $img = Medium::find($input['photo_id']);
            //Make sure he can only assign to own vendors
            if ($img->company_id != $this->User->company_id) {
                return response()->json([
                    'errors' => 'Selected photo id not available to user'
                ], 422);
            }
        }

        if(!empty($input['date_of_birth'])){
            $input['date_of_birth'] =  date('Y-m-d H:i:s', strtotime(Input::get('date_of_birth')));
        }

        if (!$this->User->can('create', Contact::class)) {
            abort(500);
        }

        $start_date = !empty($input['start_date']) ? new Carbon($input['start_date']) : null;

        $input['start_date'] = $start_date ? $start_date->format('Y-m-d H:i:s') : null;

        $birth_date = !empty($input['birth_date'])?new Carbon($input['birth_date']):null;
        $input['birth_date'] = $birth_date ? $birth_date->format('Y-m-d H:i:s') : null;

        $leave_date = !empty($input['leave_date'])?new Carbon($input['leave_date']):null;
        $input['leave_date'] = $leave_date ? $leave_date->format('Y-m-d H:i:s') : null;
        $contact = Contact::create($input);
        if ($contact) {
            return response()->json(
                $contact->getPublicData(), 201
            );
        } else {
            return response()->json([
                'errors' => 'Could not save user to db'
            ], 500);
        }

    }

    public function get(Request $request)
    {
        $this->validate($request, [
            'id' => 'numeric',
        ]);

        if (!empty(Input::get('id'))) {
            $contact = Contact::find(Input::get('id'));
            if (!$this->User->can('get', $contact)) {
                abort(500);;
            }
            if ($contact) {
                return response()->json(
                    $contact->getPublicData()
                , 200);
            } else {
                return response()->json([
                    'error' => 'No contact with that id'
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
            return $this->doSearch($params, 'Contact');
        } else {
            return $this->doSearch([], 'Contact');
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

        $contact = Contact::find(Input::get('id'));
        if (!$this->User->can('delete', $contact)) {
            abort(500);;
        }
        if ($contact) {
            if ($contact->delete()) {
                return response()->json([
                    'success' => true
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Could not delete contact using provided parameters'
                ], 422);
            }
        } else {
            return response()->json([
                'error' => 'No contact with that id'
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
            'first_name' => 'max:45|min:2',
            'last_name' => 'max:45|min:2',
            'birth_date' => 'nullable',
            'group' => 'nullable',
            'country' => 'nullable',
            'email' => 'max:100|nullable|email',
            'mobile_phone' => 'max:45|nullable',
            'home_phone' => 'max:45|nullable',
            'work_phone' => 'max:45|nullable',
            'other_phone' => 'max:45|nullable',
            'address' => 'max:100|nullable',
            'address_2' => 'max:100|nullable',
            'city' => 'max:45|nullable',
            'region' => 'max:45|nullable',
            'zip' => 'max:45|nullable',
            'employee_number' => 'max:45|nullable',
            'job_title' => 'max:100|nullable',
            'start_date' => 'nullable',
            'leave_date' => 'nullable',
            'driver' => 'boolean',
            'driver_license_number' => 'max:45|nullable',
            'driver_license_class' => 'max:45|nullable',
            'driver_license_region' => 'max:45|nullable',
            'hourly_rate' => 'numeric|nullable',

        ];

        $this->validate($request, $val);

        $input = $this->filterParams($val);
        if (isset($input['group'])) {
            $input['group_id'] = $input['group']['id'];
            $group = Group::find($input['group_id']);
            //Make sure he can only assign to own vendors
            if ($group->company_id != $this->User->company_id) {
                return response()->json([
                    'errors' => 'Selected group id not available to user'
                ], 422);
            }
        }

        if (isset($input['photo_id'])) {
            $img = Medium::find($input['photo_id']);
            //Make sure he can only assign to own vendors
            if ($img->company_id != $this->User->company_id) {
                return response()->json([
                    'errors' => 'Selected photo id not available to user'
                ], 422);
            }
        }

        if(!empty($input['date_of_birth'])){
            $input['date_of_birth'] =  date('Y-m-d H:i:s', strtotime(\Input::get('date_of_birth')));
        }


        $contact = Contact::find(Input::get('id'));
        if (!$this->User->can('update', $contact)) {
            abort(500);;
        }
        if ($contact) {
            $start_date = !empty($input['start_date']) ? new Carbon($input['start_date']) : null;

            $input['start_date'] = $start_date ? $start_date->format('Y-m-d H:i:s') : null;

            $birth_date = !empty($input['birth_date']) ? new Carbon($input['birth_date']) : null;
            $input['birth_date'] = $birth_date ? $birth_date->format('Y-m-d H:i:s') : null;

            $leave_date = !empty($input['leave_date']) ? new Carbon($input['leave_date']) : null;
            $input['leave_date'] = $leave_date ? $leave_date->format('Y-m-d H:i:s') : null;

            $input['country_id'] = isset($input['country'] )?$input['country']['id']:null;

            if ($contact->update($input)) {
                return response()->json(
                    $contact->getPublicData()
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
