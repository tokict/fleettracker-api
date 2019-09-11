<?php

namespace App\Http\Controllers;

use App\Models\VehicleMaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class MakerController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if (!$this->User->can('create', VehicleMaker::class)) {
            abort(500);
        }
        $this->validate($request, [
            'name' => 'required|max:45',
            'country_id' => 'numeric|required',
        ]);

        $maker = VehicleMaker::create(Input::all());
        if ($maker) {
            return response()->json([
                $maker->getPublicData(), 201
            ]);
        } else {
            return response()->json([
                'errors' => 'Could not save maker to db'
            ], 500);
        }

    }

    public function get(Request $request)
    {
        $this->validate($request, [
            'id' => 'numeric',
        ]);
        if (!empty(Input::get('id'))) {
            $maker = VehicleMaker::find(Input::get('id'));
            if ($maker) {
                return response()->json([
                    $maker->getPublicData()
                ], 200);
            } else {
                return response()->json([
                    'error' => 'No maker with that id'
                ], 422);
            }
        } //At this point if he did not search by id and there is no params, something is wrong
        $p = Input::get('params');
        $params = null;
        if ($p) {
            $params = json_decode($p, true);
        }

        if (is_array($params)) {
            return $this->doSearch($params, 'VehicleMaker');
        } else {
            return $this->doSearch([], 'VehicleMaker');
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

        $make = VehicleMaker::find(Input::get('id'));
        if (!$this->User->can('delete', $make)) {
            abort(500);
        }
        if ($make) {
            if ($make->delete()) {
                return response()->json([
                    'success' => true
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Could not delete maker sent parameters'
                ], 422);
            }
        } else {
            return response()->json([
                'error' => 'No maker with that id'
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
            'country_id' => 'numeric',
        ];

        $this->validate($request, $val);

        $input = Input::all();
        $maker = VehicleMaker::find(Input::get('id'));
        if (!$this->User->can('get', $maker)) {
            abort(500);
        }
        if ($maker) {
            if ($maker->update($this->filterParams($input))) {
                return response()->json([
                    $maker->getPublicData()
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
