<?php

namespace App\Http\Controllers;

use App\Models\VehicleMaker;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ModelController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if (!$this->User->can('create', VehicleModel::class)) {
            abort(500);
        }
        $this->validate($request, [
            'name' => 'required|max:45',
            'vehicle_maker_id' => 'numeric|required',
        ]);

        $model = VehicleModel::create(Input::all());
        if ($model) {
            return response()->json([
                $model->getPublicData(), 201
            ]);
        } else {
            return response()->json([
                'errors' => 'Could not save model to db'
            ], 500);
        }

    }

    public function get(Request $request)
    {
        $this->validate($request, [
            'id' => 'numeric',
        ]);

        if(!empty(Input::get('id'))) {
            $model = VehicleModel::find(Input::get('id'));
            if ($model) {
                return response()->json([
                    $model->getPublicData()
                ], 200);
            } else {
                return response()->json([
                    'error' => 'No model with that id'
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
            return $this->doSearch($params, 'VehicleModel');
        } else {
            return $this->doSearch([], 'VehicleModel');
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

        $model = VehicleModel::find(Input::get('id'));
        if (!$this->User->can('delete', $model)) {
            abort(500);
        }
        if ($model) {
            if ($model->delete()) {
                return response()->json([
                    'success' => true
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Could not delete model using provided parameters'
                ], 422);
            }
        } else {
            return response()->json([
                'error' => 'No model with that id'
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
            'name' => 'max:45',
            'vehicle_maker_id' => 'numeric',
        ];

        $this->validate($request, $val);

        $input = Input::all();
        $model = VehicleModel::find(Input::get('id'));
        if (!$this->User->can('update', $model)) {
            abort(500);
        }
        if ($model) {
            $input = $this->filterParams($val);
            if ($model->update($input)) {
                return response()->json([
                    $model->getPublicData()
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
