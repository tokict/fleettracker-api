<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Group;
use App\Models\Issue;
use App\Models\MediaLink;
use App\Models\Medium;
use App\Models\OdometerEntries;
use App\Models\RenewalType;
use App\Models\Service;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class VehicleController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if (!$this->User->can('create', Vehicle::class)) {
           abort(500);
        }

        $val = [
            'name' => 'required|max:45',
            'vin' => 'max:45',
            'type' => 'max:45|required',
            'year' => 'numeric',
            'plate' => 'max:45|nullable',
            'status' => 'max:45|required',
            'group_id' => 'nullable',
            'odo' => 'nullable|numeric',
            'operator' => 'nullable',
            'ownership' => 'max:45|required',
            'color' => 'max:45',
            'body' => 'max:45',
            'msrp' => 'numeric|nullable',
            'length' => 'numeric|nullable',
            'bed_length' => 'numeric|nullable',
            'curb_weight' => 'numeric|nullable',
            'max_payload' => 'numeric|nullable',
            'cargo_volume' => 'numeric|nullable',
            'epa_city' => 'numeric|nullable',
            'photo_ids' => 'nullable',
            'document_ids' => 'nullable',
            'epa_highway' => 'numeric|nullable',
            'epa_combined' => 'numeric|nullable',
            'drive_type' => 'max:45',
            'front_tire_type' => 'max:45|nullable',
            'rear_tire_type' => 'max:45|nullable',
            'fuel_type' => 'max:45|nullable',
            'fuel_tank_1_capacity' => 'numeric|nullable',
            'fuel_tank_2_capacity' => 'numeric|nullable',
            'oil_capacity' => 'numeric|nullable',
            'renewals' => 'nullable',
            'model' =>'nullable',
            'maker' =>'nullable'

        ];
        $this->validate($request, $val);

        $input = $this->filterParams($val);

        if (isset($input['group'])) {
            $group = Group::find($input['group']['id']);
            //Make sure he can only assign to own vendors
            if ($group->company_id != $this->User->company_id) {
                return response()->json([
                    'errors' => 'Selected group id not available to user'
                ], 422);
            }
        }

        if (isset($input['operator'])) {
            $operator = Contact::find($input['operator']['id']);
            //Make sure he can only assign to own contacts
            if ($operator->company_id != $this->User->company_id) {
                return response()->json([
                    'errors' => 'Selected operator id not available to user'
                ], 422);
            }
        }


        //Make sure he can only assign to own media
        if (isset($input['photo_ids'])) {
            $photosIds = $input['photo_ids'];
            $photos = count($photosIds) ? Medium::whereIn('id', $photosIds)->get() : [];
            foreach ($photos as $photo) {
                if ($photo->company_id != $this->User->company_id) {
                    return response()->json([
                        'errors' => 'Selected image id not available to user'
                    ], 422);
                }
            }
            $input['photo_ids'] = count($input['photo_ids'])?json_encode($input['photo_ids']):null;
        }

        if (isset($input['document_ids'])) {
            $documentIds = json_decode($input['document_ids']);
            $documents = count($documentIds) ? Medium::whereIn('id', $documentIds)->get() : [];
            foreach ($documents as $doc) {
                if ($doc->company_id != $this->User->company_id) {
                    return response()->json([
                        'errors' => 'Selected document id not available to user'
                    ], 422);
                }
            }
        }






        $input['company_id'] = $this->User->company_id;

        $input['operator_id'] = isset($input['operator']['id']) ? $input['operator']['id'] : null;
        $input['maker_id'] = isset($input['maker']['id']) ? $input['maker']['id'] : null;
        $input['model_id'] = isset($input['model']['id']) ? $input['model']['id'] : null;
        $input['group_id'] = isset($input['group']['id']) ? $input['group']['id'] : null;

        $vehicle = Vehicle::create($input);
        if ($vehicle) {
            if (isset($input['odo'])) {
                OdometerEntries::create(
                    [
                        'date' => date("Y-m-d H:i:s"),
                        'odo_end' => $input['odo'],
                        'vehicle_id' => $vehicle->id,
                    ]);
            }
            //Save renewals
            /*if (isset($input['renewals'])) {
                foreach ($input['renewals'] as $name => $values) {
                    //get renewal
                    $ren = RenewalType::where('name', $name)->get()->first();
                    if (!$ren) {
                        return response()->json([
                            'errors' => 'Unknown renewal type'
                        ], 422);
                    }
                    if(isset($values['odo']) && isset($values['date'])) {
                        $odo = $values['odo'];
                        $date = date('Y-m-d H:i:s', strtotime($values['date']));
                        $type = $ren->id;

                        $service = new Service([
                            'odometer' => $odo,
                            'serviced_at' => $date,
                            'vehicle_id' => $vehicle->id,
                            'performed_renewal_types' => $type
                        ]);
                        $service->save();
                    }
                }

            }*/
            return response()->json(
                $vehicle->getPublicData(), 201
            );
        } else {
            return response()->json([
                'errors' => 'Could not save vehicle to db'
            ], 500);
        }

    }

    public function get(Request $request)
    {
        $this->validate($request, [
            'id' => 'numeric',
        ]);

        if (!empty(Input::get('id'))) {
            $vehicle = Vehicle::find(Input::get('id'));
            if (!$this->User->can('get', $vehicle)) {
               abort(500);
            }
            if ($vehicle) {
                return response()->json(
                    $vehicle->getPublicData()
                    , 200);
            } else {
                return response()->json([
                    'error' => 'No vehicle with that id'
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
            return $this->doSearch($params, 'Vehicle');
        } else {
            return $this->doSearch([], 'Vehicle');
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

        $vehicle = Vehicle::find(Input::get('id'));
        if (!$this->User->can('delete', $vehicle)) {
           abort(500);
        }
        if ($vehicle) {
            if ($vehicle->delete()) {
                return response()->json([
                    'success' => true
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Could not delete vehicle using provided parameters'
                ], 422);
            }
        } else {
            return response()->json([
                'error' => 'No suer with that id'
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
            'name' => 'max:45|nullable',
            'vin' => 'max:45|nullable',
            'type' => 'max:45|nullable',
            'year' => 'numeric|nullable',
            'plate' => 'max:45|nullable',
            'status' => 'max:45|nullable',
            'ownership' => 'max:45|nullable',
            'color' => 'max:45|nullable',
            'body' => 'max:45|nullable',
            'msrp' => 'numeric|nullable',
            'length' => 'numeric|nullable',
            'group' => 'nullable',
            'bed_length' => 'numeric|nullable',
            'curb_weight' => 'numeric|nullable',
            'max_payload' => 'numeric|nullable',
            'cargo_volume' => 'numeric|nullable',
            'epa_city' => 'numeric|nullable',
            'epa_highway' => 'numeric|nullable',
            'photo_ids' => 'nullable',
            'epa_combined' => 'numeric|nullable',
            'drive_type' => 'max:45|nullable',
            'front_tire_type' => 'max:45|nullable',
            'rear_tire_type' => 'max:45|nullable',
            'fuel_type' => 'max:45|nullable',
            'fuel_tank_1_capacity' => 'numeric|nullable',
            'fuel_tank_2_capacity' => 'numeric|nullable',
            'oil_capacity' => 'numeric|nullable',
            'renewals' => 'nullable',
            'operator' => 'nullable',
            'model' =>'nullable',
            'maker' =>'nullable'
        ];

        $this->validate($request, $val);

        $input = $this->filterParams($val);
        if (isset($input['group']['id'])) {
            $group = Group::find($input['group']['id']);
            //Make sure he can only assign to own vendors
            if ($group->company_id != $this->User->company_id) {
                return response()->json([
                    'errors' => 'Selected group id not available to user'
                ], 422);
            }
        }

        if (isset($input['operator'])) {
            $operator = Contact::find($input['operator']['id']);
            //Make sure he can only assign to own contacts
            if ($operator->company_id != $this->User->company_id) {
                return response()->json([
                    'errors' => 'Selected operator id not available to user'
                ], 422);
            }

        }
        $input['operator_id'] = !empty($input['operator']) ? $input['operator']['id'] : null;

        //Make sure he can only assign to own media
        if (isset($input['photo_ids']) && count($input['photo_ids'])) {
            $photosIds = $input['photo_ids'];
            $photos = count($photosIds) ? Medium::whereIn('id', $photosIds)->get() : [];
            foreach ($photos as $photo) {
                if ($photo->company_id != $this->User->company_id) {
                    return response()->json([
                        'errors' => 'Selected image id not available to user'
                    ], 422);
                }
            }
            $input['photo_ids'] = count($input['photo_ids'])?json_encode($input['photo_ids']):null;
        }else{
            unset($input['photo_ids']);
        }

        if (isset($input['document_ids'])) {
            $documentIds = json_decode($input['document_ids']);
            $documents = count($documentIds) ? Medium::whereIn('id', $documentIds)->get() : [];
            foreach ($documents as $doc) {
                if ($doc->company_id != $this->User->company_id) {
                    return response()->json([
                        'errors' => 'Selected document id not available to user'
                    ], 422);
                }
            }
        }

        $vehicle = Vehicle::find(Input::get('id'));
        $input['maker_id'] = isset($input['maker']['id']) ? $input['maker']['id'] : null;
        $input['model_id'] = isset($input['model']['id']) ? $input['model']['id'] : null;
        $input['group_id'] = isset($input['group']['id']) ? $input['group']['id'] : null;
       
        if (!$this->User->can('update', $vehicle)) {
           abort(500);
        }
        if ($vehicle) {
            if ($vehicle->update($input)) {
                return response()->json(
                    $vehicle->getPublicData()
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function options(Request $request)
    {

        return response()->json([
                'type' => parent::getEnumOptions('vehicles', 'type'),
                'status' => parent::getEnumOptions('vehicles', 'status'),
                'ownership' => parent::getEnumOptions('vehicles', 'ownership'),
                'body' => parent::getEnumOptions('vehicles', 'body'),
                'drive_type' => parent::getEnumOptions('vehicles', 'drive_type'),
                'fuel_type' => parent::getEnumOptions('vehicles', 'fuel_type'),
            ]
            , 200);
    }


    public function odometerNew(Request $request)
    {
        $this->validate($request, [
            'vehicle_id' => 'numeric|required',
            'value' => 'numeric|required',
            'date' => 'required'
        ]);

            $reported_at = new Carbon(Input::get('date'));
            $date = $reported_at->format("Y-m-d H:i:s");


        $lastEntry = OdometerEntries::where('vehicle_id', Input::get('vehicle_id'))->get()->first();
        if($lastEntry){
            $lastOdo = $lastEntry->odometer_end;
        }

        $vehicle = Vehicle::find(Input::get('vehicle_id'));
        if (!$this->User->can('update', $vehicle)) {
           abort(500);
        }

        $entry = OdometerEntries::create(
            [
                'date' => $date,
                'odo_end' => Input::get('value'),
                'vehicle_id' => Input::get('vehicle_id'),
            ]);

        if ($entry) {
            return response()->json(
                Vehicle::where('id', Input::get('vehicle_id'))->get()->first()->getPublicData()
                , 200);
        } else {
            return response()->json([
                'error' => 'Could not save odo entry'
            ], 500);
        }

    }


    /**
     * Get all vehicle related documents
     * @param Request $request
     * @param $vehicleId
     * @return \Illuminate\Http\JsonResponse
     */
    public function documents(Request $request, $vehicleId)
    {

        $p = Input::get('type');
        $sort = Input::get('sort');


            switch ($sort){
                case 'newest_first':
                    $sortByField = 'id';
                    $sortbyDir = 'desc';
                    break;

                case 'oldest_first':
                    $sortByField = 'id';
                    $sortbyDir = 'asc';
                    break;

                case 'document_name':
                    $sortByField = 'title';
                    $sortbyDir = 'asc';
                    break;

                case 'file_size':
                    $sortByField = 'size';
                    $sortbyDir = 'desc';
                    break;

                default:
                    $sortByField = 'id';
                    $sortbyDir = 'desc';
            }


        $docs = [];
        if ($p && in_array($p, ['vehicle_documents', 'issue_documents', 'service_entry_documents'])) {

            if ($p == 'issue_documents') {
                $issueDocs = Issue::where('vehicle_id', $vehicleId)->get();
                if ($issueDocs) {
                    foreach ($issueDocs as $item) {
                        $d = $item->document_ids &&  json_decode($item->document_ids, true) ? json_decode($item->document_ids, true) : [];
                        $docs = array_merge($docs, $d);
                    }
                }
            }

            if ($p == 'service_entry_documents') {
                $serviceDocs = Service::where('vehicle_id', $vehicleId)->get();
                if ($serviceDocs) {
                    foreach ($serviceDocs as $item) {
                        $d = $item->document_ids && json_decode($item->document_ids, true) ? json_decode($item->document_ids, true) : [];
                        $docs = array_merge($docs, $d);
                    }
                }
            }
            if ($p == 'vehicle_documents') {
                $vehicle = Vehicle::find($vehicleId);
                $docs = $vehicle->document_ids && json_decode($vehicle->document_ids, true)?array_merge($docs, json_decode($vehicle->document_ids, true)) : [];
            }


        } else {
            $issueDocs = Issue::where('vehicle_id', $vehicleId)->get();

            if ($issueDocs) {
                foreach ($issueDocs as $item) {
                    $d = isset($item->document_ids) && json_decode($item->document_ids, true) ? json_decode($item->document_ids, true) : [];
                    $docs = array_merge($docs, $d);
                }
            }


            $serviceDocs = Service::where('vehicle_id', $vehicleId)->get();
            if ($serviceDocs) {
                foreach ($serviceDocs as $item) {
                    $d = isset($item->document_ids) && json_decode($item->document_ids, true) ? json_decode($item->document_ids, true) : [];
                    $docs = array_merge($docs, $d);
                }
            }


            $vehicle = Vehicle::find($vehicleId);
            $docsVeh = isset($vehicle->document_ids) && json_decode($vehicle->document_ids, true) ? json_decode($vehicle->document_ids, true) : [];
            $docs = array_merge($docsVeh, $docs);

        }

        $documents = Medium::whereIn('id', $docs)->orderBy($sortByField, $sortbyDir)->get();

        $data = [];
        if ($documents->count()) {
            foreach ($documents as $v) {

                $d = $v->getPublicData();
                $data[] = $d;
            }
        }
        return response()->json(
            [
                'data' => $data,

            ]

            , 200);
    }
}
