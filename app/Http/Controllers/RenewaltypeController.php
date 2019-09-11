<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\RenewalType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class RenewaltypeController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if (!$this->User->can('create', RenewalType::class)) {
            abort(500);
        }
        $this->validate($request, [
            'id' => 'numeric|required',
            'name' => 'required|max:200',
        ]);

        $input  = Input::all();
        $input['created_by'] = $this->User->id;

        $type = RenewalType::create($input);
        if($type){
            return response()->json([
                $type->toArray(), 201
            ]);
        }else{
            return response()->json([
                'errors' => 'Could not save using to db'
            ], 500);
        }

    }

    public function get(Request $request)
    {
        $this->validate($request, [
            'id' => 'numeric',
        ]);

        $type = null;
        if(!empty(Input::get('id'))) {
            $type = RenewalType::find(Input::get('id'));
        }else{
            $p = Input::get('params');
            $params = null;
            if ($p) {
                $params = json_decode($p, true);
            }

            if (is_array($params)) {
                return $this->doSearch($params, 'RenewalType');
            } else {
                return $this->doSearch([], 'RenewalType');
            }
        }

        if($type){
            return response()->json([
                $type->toArray()
            ], 200);
        }else{
            return response()->json([
                'error' => 'No type with that id'
            ], 422);
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

        $type = RenewalType::find(Input::get('id'));
        if (!$this->User->can('delete', $type)) {
            abort(500);
        }
        if ($type) {
            if($type->delete()) {
                return response()->json([
                    'success' => true
                ], 200);
            }else{
                return response()->json([
                    'error' => 'Could not delete type using provided parameters'
                ], 422);
            }
        } else {
            return response()->json([
                'error' => 'No using with that id'
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
            'name' => 'required|max:200',
        ];

        $this->validate($request, $val);

        $input = Input::all();
        $type = Reminder::find(Input::get('id'));
        if (!$this->User->can('update', $type)) {
            abort(500);
        }
        if ($type) {
            if($type->update($this->filterParams($input))) {
                return response()->json([
                    $type->toArray()
                ], 200);
            }else{
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
