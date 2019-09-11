<?php

namespace App\Http\Controllers\Auth;

use App\Models\Contact;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\UserActivation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Psy\Util\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(\Illuminate\Http\Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
            'first_name' => 'required',
            'last_name' => 'required'
        ]);

        $user = new User();
        $user->email = Input::get('email');
        $user->password = Hash::make(Input::get('password'));
        $user->admin = 1;
        $user->save();

        //This is here because our User class does not extend Eloquent
        $User = User::where('email', Input::get('email'))->get()->first();


        if ($User) {
            //Create contact for user
            $contact = new Contact();
            $contact->first_name = Input::get('first_name');
            $contact->last_name = Input::get('last_name');
            $contact->email = Input::get('email');
            $contact->user_id = $User->id;
            $contact->save();

            $User->contact_id = $contact->id;
            $User->save();

            $token = hash_hmac('sha256', str_random(40), config('app.key'));
            UserActivation::create([
                'user_id' => $User->id,
                'token' => $token
            ]);

            Mail::send('emails.registration', ['user' => $User, 'token' => $token], function ($m) use ($User, $token) {

                $m->to($User->email)->subject('Activate your Fleet360.io account');
            });
            event(new Registered($User));
            return response()->json([
            ], 201);
        } else {
            return response()->json([
                'errors' => 'Failed to create user'
            ], 500);
        }
    }


    public function activateUser($token)
    {
        $activation = UserActivation::where('token', $token)->get()->first();
        if ($activation) {
            if ($activation->activated == true && !isset($activation->user->company)) {
                //Allow user to reuse link just to fill in company data if he closed the tab.
                $activation->user->remember_token = str_random(60);
                $activation->user->save();
                $u = $activation->user->toArray();
                $u['contact'] = $activation->user->contact;
                return response()->json($activation->user->toArray(), 200);
            }


            $user = User::find($activation->user->id);

            $token = str_random(60);
            $user->remember_token = $token;
            $activation->activated = true;
            $activation->update();
            $user->save();

            $u = $user->toArray();
            $u['contact'] = $activation->user->contact;
            return response()->json(
                [
                    'token' => $token,
                    'user' => $u
                ], 200);
        } else {
            return response()->json([
                'errors' => 'Incorrect activation token'
            ], 400);
        }
    }
}
