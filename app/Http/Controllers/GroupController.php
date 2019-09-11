<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class GroupController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {

        $val = [
            'name' => 'required|max:45'
        ];
        $this->validate($request, $val);


        $input = $this->filterParams($val);

        $group = Group::create($input);
        if ($group) {
            return response()->json([
                $group->getPublicData()
            ]);
        } else {
            return response()->json([
                'errors' => 'Could not save group to db'
            ], 500);
        }

    }

    public function get(Request $request)
    {
        $this->validate($request, [
            'id' => 'numeric',
        ]);

        if(!empty(Input::get('id'))) {
            $group = Group::find(Input::get('id'));

            if (!$this->User->can('get', $group)) {
                abort(500);
            }

            if ($group) {
                return response()->json([
                    $group->toArray()
                ], 201);
            } else {
                return response()->json([
                    'error' => 'No group with that id'
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
            return $this->doSearch($params, 'Group');
        } else {
            return $this->doSearch([], 'Group');
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

        $group = Group::find(Input::get('id'));
        if(!$this->User->can('delete', $group)){
            abort(500);;
        }
        if ($group) {
            if ($group->delete()) {
                return response()->json([
                    'success' => true
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Could not delete group using provided parameters'
                ], 422);
            }
        } else {
            return response()->json([
                'error' => 'No group with that id'
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
            'name' => 'required|max:45'
        ];

        $this->validate($request, $val);

        $input = Input::all();
        $contact = Group::find(Input::get('id'));
        if(!$this->User->can('update', $contact)){
            abort(500);;
        }
        if ($contact) {
            if($contact->update($this->filterParams($input))) {
                return response()->json([
                    $contact->getPublicData()
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
