<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class IsAuthorized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {


        // respond to preflights
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            // return only the headers and not the content
            // only allow CORS if we're doing a GET - i.e. no saving for now.

            exit;
        }

        $token = Request::header('Token');
        $currentAction = \Route::currentRouteAction();
        list($controller, $method) = explode('@', $currentAction);
        // $controller now is "App\Http\Controllers\FooBarController"

        $controller = preg_replace('/.*\\\/', '', $controller);
        // $controller now is "FooBarController"

        if (!$token) {
            if($controller !== 'MediaController' && $method != 'show') {

                return response()->json(
                    [
                        'errors' => [
                            'Invalid, missing or expired token'
                        ],
                    ], 401);
            }

        }

        $user = User::where('remember_token', $token)->get()->first();
        if(!empty(Request::header('company')) && Request::header('company') != 'null'  && (isset($user) && $user->super_admin == 1)){
            $user->company_id = Request::header('company');

        }

        if ($user && $user->company_id) {
            return $next($request);
        } else {
            //Allow authenticated user without company to create company
            if($controller == 'CompanyController' && $method == 'add' && $user){
                return $next($request);
            }
            return response()->json(
                [
                    'errors' => [
                        'Invalid or expired token'
                    ],
                ], 401);

        }


    }
}
