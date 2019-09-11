<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\PasswordResets;
use App\Models\User;
use App\Models\UserActivation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\In;


class UserController extends Controller
{
    use RegistersUsers;
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if (!$this->User->can('create', User::class)) {
            abort(500);
        }
        $this->validate($request, [
            'email' => 'required|max:100',
            'username' => 'required|max:45',
            'contact' => 'nullable',
            'status' => 'max:45|nullable'
        ]);


        $contact = Contact::find(Input::get('contact.id'));
        if($contact){
            if ($contact->company_id != $this->User->company_id) {
                return response()->json([
                    'errors' => 'Selected contact id not available to user'
                ], 422);
            }
        }

        $input = Input::all();
        $input['contact_id'] = Input::get('contact.id');

        $user = new User();
        $user->email = Input::get('email');
        if($contact){
            $user->contact_id = $contact->id;
        }
        $user->password = bcrypt(Input::get('password'));
        $user->save();

        $user = User::where('email', $input['email'])->get()->first();

        if($user){
            if(Input::get('sendActivationMail')){
                $token = hash_hmac('sha256', str_random(40), config('app.key'));
                UserActivation::create([
                    'user_id' => $user->id,
                    'token' => $token
                ]);

                Mail::send('emails.registration', ['user' => $user, 'token' => $token], function ($m) use ($user, $token) {

                    $m->to($user->email)->subject('Activate your Fleet360.io account');
                });
                event(new Registered($user));
                return response()->json([
                ], 201);
            }
            return response()->json(
                $user->getPublicData(), 201
            );
        }else{
            return response()->json([
                'errors' => 'Could not save user to db'
            ], 500);
        }

    }

    public function get(Request $request)
    {

        $this->validate($request, [
            'id' => 'numeric|nullable',
        ]);

        if($this->User) {
            if (!$this->User->can('get', User::class)) {;
                abort(500);
            }
        }else{
            return response()->json([
            ], 401);
        }
        $params = Input::all();

        if (!empty(Input::get('id'))) {
            $user = User::find(Input::get('id'));

            if ($user) {
                if (!$this->User->can('get', $user)) {
                    abort(500);
                }

                return response()->json(
                    $user->getPublicData()
                    , 200);
            } else {
                return response()->json([
                    'error' => 'No company with that id'
                ], 422);
            }
        } //At this point if he did not search by id and there is no params, something is wrong
        if (is_array($params) && $this->User->super_admin) {
            return $this->doSearch($params, 'User');
        } else {
            return $this->doSearch([], 'User');
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

        $user = User::find(Input::get('id'));
        if (!$this->User->can('delete', $user)) {
            abort(500);
        }
        if ($user) {
            if($user->delete()) {
                return response()->json([
                    'success' => true
                ], 200);
            }else{
                return response()->json([
                    'error' => 'Could not delete user using provided parameters'
                ], 422);
            }
        } else {
            return response()->json([
                'error' => 'No user with that id'
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
            'email' => 'required|max:100',
            'username' => 'nullable|max:45',
            'contact' => 'nullable',
            'driver' => 'max:45|nullable',
            'password' => '',
            'status' => 'max:45|nullable',
            'super_admin' => 'max:100|nullable'
        ];

        $this->validate($request, $val);


        $input = Input::all();
        $user = User::find(Input::get('id'));
        if (!$this->User->can('update', $user)) {
            abort(500);
        }
        if ($user) {


            $contact_id = isset($input['contact'])?$input['contact']['id']:null;
            $contact = Contact::find($contact_id);

            if ($contact && $contact->company_id != $this->User->company_id) {
                return response()->json([
                    'errors' => 'Selected contact id not available to user'
                ], 422);
            }

            $params = $this->filterParams($input);
            $params['contact_id'] = $contact_id;

            if (isset($params['password'])){
                $params['password'] = Hash::make($params['password']);
            }


            if($user->update($params)) {
                return response()->json(
                    $user->getPublicData()
                , 200);
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function options(Request $request)
    {

        return response()->json([
                'status' => parent::getEnumOptions('users', 'status'),
            ]
            , 200);
    }



}
