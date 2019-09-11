<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Medium;
use App\Models\Service;
use App\Models\Vehicle;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ServiceController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if (!$this->User->can('create', Service::class)) {
            abort(500);
        }
        $val = [
            'serviced_at' => 'date|required',
            'vehicle' => 'required',
            'odometer' => 'numeric|required_with:service_tasks',
            'resolved_issues' => 'max:200|nullable',
            'comments_attributes' => 'nullable',
            'resolved_issue_ids' => 'nullable',
            'service_task_ids' => 'nullable',
            'renewal_type_ids' => 'nullable',
            'vendor' => 'nullable',
            'reference' => 'max:45|nullable',
            'labor_price' => 'numeric|nullable',
            'parts_price' => 'numeric|nullable',
            'tax' => 'numeric|nullable',
            'total' => 'numeric|required_with:labor_price,parts_price|nullable',
            'tax_type' => 'max:45|required_with:tax'
        ];

        $this->validate($request, $val);

        $input = $this->filterParams($val);
        $vehicle = Vehicle::find($input['vehicle']['id']);



        //Make sure he can only assign to own vehicles
        if ($vehicle->company_id != $this->User->company_id) {
            return response()->json([
                'errors' => 'Selected vehicle id not available to user'
            ], 422);
        }

        //Make sure he can only assign to own vendors
        if (isset($input['vendor']['id'])) {
            $vendor = Vendor::find($input['vendor']['id']);
            if ($vendor) {
                if ($vendor->company_id != $this->User->company_id) {
                    return response()->json([
                        'errors' => 'Selected vendor id not available to user'
                    ], 422);
                }
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
        }


        if (isset($input['document_ids'])) {
            $documentIds = $input['document_ids'];
            $documents = count($documentIds) ? Medium::whereIn('id', $documentIds)->get() : [];
            foreach ($documents as $doc) {
                if ($doc->company_id != $this->User->company_id) {
                    return response()->json([
                        'errors' => 'Selected document id not available to user'
                    ], 422);
                }
            }
        }
        $input['vendor_id'] = isset($input['vendor']['id']) ? $input['vendor']['id'] : null;
        $input['vehicle_id'] = isset($input['vehicle']['id']) ? $input['vehicle']['id'] : null;
        $input['performed_service_types'] = isset($input['service_task_ids']) ? implode(",", $input['service_task_ids']) : null;
        $input['performed_renewal_types'] = isset($input['renewal_type_ids']) ? implode(",", $input['renewal_type_ids']) : null;
        $input['resolved_issues'] = isset($input['resolved_issue_ids']) ? implode(",", $input['resolved_issue_ids']) : null;

        //Resolve issues
        foreach ($input['resolved_issue_ids'] as $id) {
            $issue = Issue::find($id);
            if ($issue) {
                $issue->status = 'resolved';
                $issue->save();
            }

        }

        $serviced_at = new Carbon($input['serviced_at']);
        $input['serviced_at'] = $serviced_at->format("Y-m-d H:i:s");

        $service = Service::create($input);
        if ($service) {
            //Save comments
            if (isset($input['comments_attributes'])) {
                $comment = $input['comments_attributes']['comment'];
                if (!empty($comment)) {
                    Comment::create([
                        'type' => 'service',
                        'text' => $comment,
                        'item_id' => $service->id,
                        'status' => 'active'
                    ]);
                }
            }
            return response()->json(
                $service->getPublicData(), 201
            );
        } else {
            return response()->json([
                'errors' => 'Could not save service to db'
            ], 500);
        }

    }

    public function get(Request $request)
    {
        $this->validate($request, [
            'id' => 'numeric',
        ]);

        if (!empty(Input::get('id'))) {
            $service = Service::find(Input::get('id'));
            if (!$this->User->can('get', $service)) {
                abort(500);
            }
            if ($service) {
                return response()->json(
                    $service->getPublicData()
                    , 200);
            } else {
                return response()->json([
                    'error' => 'No service with that id'
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
            return $this->doSearch($params, 'Service');
        } else {
            return $this->doSearch([], 'Service');
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

        $service = Service::find(Input::get('id'));
        if (!$this->User->can('delete', $service)) {
            abort(500);
        }
        if ($service) {
            if ($service->delete()) {
                return response()->json([
                    'success' => true
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Could not delete service using provided parameters'
                ], 422);
            }
        } else {
            return response()->json([
                'error' => 'No service with that id'
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
            'id' => 'required',
            'vehicle' => 'required',
            'serviced_at' => 'date|required',
            'odometer' => 'nullable|numeric|required_with:service_tasks',
            'comments_attributes' => 'nullable',
            'resolved_issues' => 'max:200|nullable',
            'resolved_issue_ids' => 'nullable',
            'service_task_ids' => 'nullable',
            'renewal_type_ids' => 'nullable',
            'service_tasks' => 'nullable',
            'vendor' => 'nullable',
            'renewal_tasks' => 'nullable',
            'document_ids' => 'nullable',
            'photo_ids' => 'nullable',
            'reference' => 'max:45|nullable',
            'labor_price' => 'numeric|nullable',
            'parts_price' => 'numeric|nullable',
            'tax' => 'numeric|nullable',
            'total' => 'numeric|required_with:labor_price,parts_price|nullable',
            'tax_type' => 'max:45|required_with:tax'
        ];

        $this->validate($request, $val);

        $input = $this->filterParams($val);
        $input['vehicle_id'] = isset($input['vehicle']['id']) ? $input['vehicle']['id'] : null;
        if (isset($input['vehicle_id'])) {
            $vehicle = Vehicle::find($input['vehicle_id']);
            //Make sure he can only assign to own vehicles
            if ($vehicle->company_id != $this->User->company_id) {
                return response()->json([
                    'errors' => 'Selected vehicle id not available to user'
                ], 422);
            }
        }


        if (isset($input['vendor'])) {
            //Make sure he can only assign to own vendors
            $vendor = Vendor::find($input['vendor']['id']);
            if ($vendor) {
                if ($vendor->company_id != $this->User->company_id) {
                    return response()->json([
                        'errors' => 'Selected vendor id not available to user'
                    ], 422);
                }
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
            $input['photo_ids'] = count($input['photo_ids']) ? json_encode($input['photo_ids']) : null;
        }

        if (isset($input['document_ids'])) {
            $documentIds = $input['document_ids'];
            $documents = count($documentIds) ? Medium::whereIn('id', $documentIds)->get() : [];
            foreach ($documents as $doc) {
                if ($doc->company_id != $this->User->company_id) {
                    return response()->json([
                        'errors' => 'Selected document id not available to user'
                    ], 422);
                }
            }
            $input['document_ids'] = count($input['document_ids']) ? json_encode($input['document_ids']) : null;
        }

        $serviceTasks = [];
        if (isset($input['service_tasks']) && is_array($input['service_tasks'])) {
            foreach ($input['service_tasks'] as $service_task) {
                $serviceTasks[] = $service_task['id'];
            }
        }

        $renewalTasks = [];
        if (isset($input['renewal_tasks']) && is_array($input['renewal_tasks'])) {
            foreach ($input['renewal_tasks'] as $renewal_task) {
                $renewalTasks[] = $renewal_task['id'];
            }
        }

        $input['vendor_id'] = isset($input['vendor']['id']) ? $input['vendor']['id'] : null;
        $input['performed_service_types'] = count($serviceTasks) ? implode(",", $serviceTasks) : null;
        $input['performed_renewal_types'] = count($renewalTasks) ? implode(",", $renewalTasks) : null;
        $input['resolved_issues'] = isset($input['resolved_issue_ids']) ? implode(",", $input['resolved_issue_ids']) : null;
        if(isset($input['service_task_ids'])) {
            $input['performed_service_types'] = isset($input['service_task_ids']) ? implode(",",
                $input['service_task_ids']) : null;
        }
        if(isset($input['renewal_type_ids'])) {
            $input['performed_renewal_types'] = isset($input['renewal_type_ids']) ? implode(",",
                $input['renewal_type_ids']) : null;
        }



        $serviced_at = new Carbon($input['serviced_at']);
        $input['serviced_at'] = $serviced_at->format("Y-m-d H:i:s");
        $service = Service::find(Input::get('id'));
        if (!$this->User->can('update', $service)) {
            abort(500);
        }



        if ($service) {


            //Resolve issues
            foreach ($input['resolved_issue_ids'] as $id) {
                $issue = Issue::find($id);
                if ($issue) {
                    $issue->status = 'resolved';
                    $issue->save();
                }

            }


            //unresolve issues. Compare current state with new and revert status on missing ones
            $diff = array_diff(explode(",", $service->resolved_issues), $input['resolved_issue_ids']);

            foreach ($diff as $d){
                $issue = Issue::find($d);
                if ($issue) {
                    $issue->status = 'open';
                    $issue->save();
                }
            }



            if ($service->update($input)) {
                if (isset($input['comments_attributes'])) {
                    $comment = $input['comments_attributes']['comment'];
                    if (!empty($comment)) {
                        Comment::create([
                            'type' => 'service',
                            'text' => $comment,
                            'item_id' => $service->id,
                            'status' => 'active'
                        ]);
                    }
                }
                return response()->json(
                    $service->getPublicData()
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
                'tax_type' => parent::getEnumOptions('service', 'tax_type'),
            ]
            , 200);
    }
}
