<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class VendorController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if (!$this->User->can('create', Vendor::class)) {
            abort(500);

        }
        $val = [
            'name' => 'required|max:45',
            'phone' => 'max:45|nullable',
            'address' => 'max:100|nullable',
            'city' => 'max:45|nullable',
            'zip' => 'max:45|nullable',
            'region' => 'max:45|nullable',
            'country_id' => 'numeric|nullable',
            'contact_person_name' => 'max:45|nullable',
            'contact_person_email' => 'max:100|nullable',
            'contact_person_phone' => 'max:45|nullable',
        ];
        $this->validate($request, $val);

        $input = $this->filterParams($val);
        $input['company_id'] = $this->User->company_id;

        $vendor = Vendor::create($input);
        if ($vendor) {
            return response()->json(
                $vendor->getPublicData(), 201
            );
        } else {
            return response()->json([
                'errors' => 'Could not save vendor to db'
            ], 500);
        }

    }

    public function get(Request $request)
    {
        $this->validate($request, [
            'id' => 'numeric',
        ]);

        if (!empty(Input::get('id'))) {
            $vendor = Vendor::find(Input::get('id'));
            if (!$this->User->can('get', $vendor)) {
                abort(500);
            }
            if ($vendor) {
                return response()->json(
                    $vendor->getPublicData()
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
            return $this->doSearch($params, 'Vendor');
        } else {
            return $this->doSearch([], 'Vendor');
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

        $vendor = Vendor::find(Input::get('id'));
        if (!$this->User->can('delete', $vendor)) {
            abort(500);
        }
        if ($vendor) {
            if ($vendor->delete()) {
                return response()->json([
                    'success' => true
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Could not delete vendor using provided parameters'
                ], 422);
            }
        } else {
            return response()->json([
                'error' => 'No vendor with that id'
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
            'name' => 'required|max:45',
            'phone' => 'max:45',
            'address' => 'max:100',
            'city' => 'max:45',
            'zip' => 'max:45',
            'region' => 'max:45',
            'country' => 'nullable',
            'contact_person_name' => 'max:45',
            'contact_person_email' => 'max:100',
            'contact_person_phone' => 'max:45',
        ];

        $this->validate($request, $val);

        $input = $this->filterParams($val);
        $input['country_id'] = isset($input['country'])?$input['country']['id']:null;
        $vendor = Vendor::find(Input::get('id'));
        if (!$this->User->can('update', $vendor)) {
            abort(500);
        }
        if ($vendor) {
            if ($vendor->update($input)) {
                return response()->json([
                    $vendor->getPublicData()
                ], 200);
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
