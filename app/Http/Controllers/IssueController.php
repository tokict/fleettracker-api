<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Medium;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class IssueController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if (!$this->User->can('create', Issue::class)) {
            abort(500);
        }
        $val = [
            'summary' => 'required|max:140',
            'description' => 'nullable',
            'odometer' => 'nullable|numeric',
            'reporter' => 'nullable',
            'reported_at' => 'nullable',
            'status' => 'nullable',
            'photo_ids' => 'nullable',
            'document_ids' => 'nullable',
            'vehicle' => 'required',
            'assignee' => 'nullable'
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
        }else{unset($input['photo_ids']);}

        if (isset($input['document_ids']) && count($input['document_ids'])) {
            $documentIds = $input['document_ids'];
            $documents = count($documentIds) ? Medium::whereIn('id', $documentIds)->get() : [];
            foreach ($documents as $doc) {
                if ($doc->company_id != $this->User->company_id) {
                    return response()->json([
                        'errors' => 'Selected document id not available to user'
                    ], 422);
                }
            }
        }else{
            unset($input['document_ids']);
        }

        $input['reported_by'] = isset($input['reporter']['id'])?$input['reporter']['id']:null;
        $input['assigned_to'] = isset($input['assignee']['id'])?$input['assignee']['id']:null;
        $input['vehicle_id'] = isset($input['vehicle']['id'])?$input['vehicle']['id']:null;
        if(!empty($input['reported_at'])) {
            $reported_at = new Carbon($input['reported_at']);
            $input['reported_at'] = $reported_at->format("Y-m-d H:i:s");
        }
        unset($input['vehicle'], $input['photo_ids'], $input['document_ids']);

        $issue = Issue::create($input);
        if ($issue) {
            return response()->json(
                $issue->getPublicData(), 201
            );
        } else {
            return response()->json([
                'errors' => 'Could not save issue to db'
            ], 500);
        }

    }

    public function get(Request $request)
    {
        $this->validate($request, [
            'id' => 'numeric',
        ]);

        if (!empty(Input::get('id'))) {
            $issue = Issue::find(Input::get('id'));
            if (!$this->User->can('get', $issue)) {
                abort(500);
            }
            if ($issue) {
                return response()->json(
                    $issue->getPublicData()
                , 200);
            } else {
                return response()->json([
                    'error' => 'No issue with that id'
                ], 422);
            }
        } //At this point if he did not search by id and there is no params, something is wrong
        $p = Input::get('params');
        $params = null;
        if ($p) {
            $params = json_decode($p, true);
        }

        if (is_array($params)) {
            return $this->doSearch($params, 'Issue');
        } else {
            return $this->doSearch([], 'Issue');
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

        $issue = Issue::find(Input::get('id'));
        if (!$this->User->can('delete', $issue)) {
            abort(500);
        }
        if ($issue) {
            if ($issue->delete()) {
                return response()->json([
                    'success' => true
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Could not delete issue using provided parameters'
                ], 422);
            }
        } else {
            return response()->json([
                'error' => 'No issue with that id'
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
            'summary' => 'max:140',
            'description' => 'nullable',
            'odometer' => 'nullable|numeric',
            'reporter' => 'nullable',
            'status' => 'nullable',
            'photo_ids' => 'nullable',
            'reported_at' => 'nullable',
            'document_ids' => 'nullable',
            'vehicle' => 'nullable',
            'assignee' => 'nullable'
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
        }else{
            unset($input['photo_ids']);
        }

        if (isset($input['document_ids'])) {
            $documentIds =  $input['document_ids'];
            $documents = count($documentIds) ? Medium::whereIn('id', $documentIds)->get() : [];
            foreach ($documents as $doc) {
                if ($doc->company_id != $this->User->company_id) {
                    return response()->json([
                        'errors' => 'Selected document id not available to user'
                    ], 422);
                }
            }
            $input['document_ids'] = count($input['document_ids'])?json_encode($input['document_ids']):null;
        }else{
            unset($input['document_ids']);
        }
        $issue = Issue::find(Input::get('id'));
        if (!$this->User->can('update', $issue)) {
            abort(500);
        }
        $input['reported_by'] = isset($input['reporter']['id'])?$input['reporter']['id']:null;
        $input['assigned_to'] = isset($input['assignee']['id'])?$input['assignee']['id']:null;
        $input['vehicle_id'] = isset($input['vehicle']['id'])?$input['vehicle']['id']:null;
        if(!empty($input['reported_at'])) {
            $reported_at = new Carbon($input['reported_at']);
            $input['reported_at'] = $reported_at->format("Y-m-d H:i:s");
        }
        if ($issue) {
            if ($issue->update($input)) {
                return response()->json(
                    $issue->getPublicData()
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
            'status' => parent::getEnumOptions('issues', 'status')
            ]
            , 200);
    }
}
