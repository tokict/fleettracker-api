<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contact;
use App\Models\OdometerEntries;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Input;

class CompanyController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $val = [
            'name' => 'required|max:45|min:2',
            'contact_phone' => 'nullable|max:45',
            'address' => 'max:100|nullable',
            'city' => 'max:45|nullable',
            'region' => 'max:45|nullable',
            'country_id' => 'numeric|nullable',
            'tax_id' => 'max:45|nullable'

        ];

        $this->validate($request, $val);

        if (!$this->User->can('create', Company::class)) {
            abort(500);;
        }
        $input = $this->filterParams($val);
        $company = Company::create($input);
        if ($company) {
            return response()->json(
                $company->getPublicData(), 201
            );
        } else {
            return response()->json([
                'errors' => 'Could not save company to db'
            ], 500);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $val = [
            'name' => 'required|max:45|min:2',
            'contact_phone' => 'nullable|max:45',
            'address' => 'max:100|nullable',
            'city' => 'max:45|nullable',
            'region' => 'max:45|nullable',
            'country' => 'nullable',
            'tax_id' => 'max:45|nullable',
            'contact_id' => 'nullable|numeric',

        ];

        $this->validate($request, $val);

        if (!$this->User->can('create', Company::class)) {
            abort(500);
        }
        $input = $this->filterParams($val);

        $input['country_id'] = isset($input['country']) ? $input['country']['id'] : null;

        $company = Company::create($input);
        if ($company) {
            $this->User->company_id = $company->id;
            $this->User->save();

            //Assign contact
            $C = Contact::where('user_id', $this->User->id)->where('company_id', 1)->get()->first();
            $C->company_id = $company->id;
            $C->save();

            return response()->json(
                $company->getPublicData(), 201
            );
        } else {
            return response()->json([
                'errors' => 'Could not save company to db'
            ], 500);
        }

    }

    public function get(Request $request)
    {

        $this->validate($request, [
            'id' => 'numeric',
        ]);
        $p = Input::get('params');
        $params = null;
        if ($p) {
            $params = json_decode($p, true);
        }


        //If the user is looking for specific company, it must be his
        if (!empty(Input::get('id'))) {
            $company = Company::find(Input::get('id'));
            if (!$this->User->can('get', $company)) {
                abort(500);
            }
            if ($company) {
                return response()->json(
                    $company->getPublicData()
                    , 200);
            } else {
                return response()->json([
                    'error' => 'No company with that id'
                ], 422);
            }
        } //At this point if he did not search by id and there is no params, something is wrong
        if (is_array($params) && $this->User->super_admin) {
            return $this->doSearch($params, 'Company');
        } else {
            return $this->doSearch([], 'Company');
        }


    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {

        $val = [
            'name' => 'required|min:2|max:45',
            'contact_phone' => 'required|max:45',
            'address' => 'max:100|nullable',
            'city' => 'max:45|nullable',
            'region' => 'max:45|nullable',
            'country' => 'nullable',
            'tax_id' => 'max:45|nullable',
            'itrack_token' => 'nullable'

        ];

        $this->validate($request, $val);

        $input = $this->filterParams($val);
        $company = Company::find(Input::get('id'));
        $oldToken = $company->itrack_token;
        if (!$this->User->can('update', $company)) {
            abort(500);;
        }
        if ($company) {
            $input['country_id'] = $input['country']['id'];


            if ($company->update($input)) {
                if(isset($input['itrack_token']) && $oldToken != $input['itrack_token']){
                    $this->syncData($company->id);
                }
                return response()->json(
                    $company->getPublicData()
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
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
        ]);

        $company = Company::find(Input::get('id'));
        if (!$this->User->can('delete', $company)) {
            abort(500);;
        }
        if ($company) {
            if ($company->delete()) {
                return response()->json([
                    'success' => true
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Could not delete company using provided parameters'
                ], 422);
            }
        } else {
            return response()->json([
                'error' => 'No company with that id'
            ], 422);
        }
    }

    public function syncData($id = null)
    {


        Artisan::call("SyncITrack",[
            'company' => $id?$id:null,
        ]);





    }

}
