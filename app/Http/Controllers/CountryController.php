<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CountryController extends Controller
{


    public function get(Request $request)
    {
        $this->validate($request, [
            'id' => 'numeric',
        ]);

        if(!empty(Input::get('id'))) {
            $group = Country::find(Input::get('id'));

            if ($group) {
                return response()->json([
                    $group->toArray()
                ], 201);
            } else {
                return response()->json([
                    'error' => 'No country with that id'
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
            return $this->doSearch($params, 'Country');
        } else {
            return $this->doSearch([], 'Country');
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function options(Request $request)
    {

        return response()->json(
            parent::getEnums('countries')
            , 200);
    }


}
