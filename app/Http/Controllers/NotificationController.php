<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {

        $this->validate($request, [
            'first_name' => 'required|max:45',
            'last_name' => 'required|max:45',
            'date_of_birth' => 'date_format:"Y-m-d"|nullable',
            'group_id' => 'numeric|nullable',
            'email' => 'max:100|nullable',
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
            'start_date' => 'date_format:"Y-m-d"|nullable',
            'leave_date' => 'date_format:"Y-m-d"|nullable',
            'driver' => 'boolean',
            'driver_license_number' => 'max:45|nullable',
            'driver_license_class' => 'max:45|nullable',
            'driver_license_region' => 'max:45|nullable',
            'hourly_rate' => 'numeric|nullable',

        ]);

        $contact = Contact::create(Input::all());
        if($contact){
            return response()->json([
                $contact->toArray()
            ]);
        }else{
            return response()->json([
                'errors' => 'Could not save user to db'
            ], 500);
        }

    }

    public function get(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
        ]);

        $contact = Contact::find(Input::get('id'));
        if($contact){
            return response()->json([
                $contact->toArray()
            ], 200);
        }else{
            return response()->json([
                'error' => 'No contact with that id'
            ], 422);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function options(Request $request)
    {

        return response()->json([
                'type' => parent::getEnumOptions('notifications', 'type'),
                'subtype' => parent::getEnumOptions('notifications', 'subtype')
            ]
            , 200);
    }
}
