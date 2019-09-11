<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/*LOGIN*/
Route::auth();
Route::get('/activation/{token}', 'Auth\RegisterController@activateUser');
/*COUNTRY*/
Route::get('/country/get', 'CountryController@get');


Route::group(['middleware' => 'apiAuth'], function () {

    /*DASHBOARD*/
    Route::get('/dashboard/get', 'DashboardController@get');


    /*CONTACT*/
    Route::post('/contact/create', 'ContactController@create');
    Route::post('/contact/update', 'ContactController@update');
    Route::delete('/contact/delete', 'ContactController@delete');
    Route::get('/contact/get', 'ContactController@get');
    Route::get('/contact/options', 'ContactController@options');

    /*COMPANY*/
    Route::post('/company/create', 'CompanyController@create');
    Route::post('/company/update', 'CompanyController@update');
    Route::post('/company/add', 'CompanyController@add');
    Route::delete('/company/delete', 'CompanyController@delete');
    Route::get('/company/get', 'CompanyController@get');
    Route::get('/company/options', 'CompanyController@options');
    Route::get('/company/sync', 'CompanyController@syncData');

    /*GROUP*/
    Route::post('/group/create', 'GroupController@create');
    Route::post('/group/update', 'GroupController@update');
    Route::delete('/group/delete', 'GroupController@delete');
    Route::get('/group/get', 'GroupController@get');
    Route::get('/group/options', 'GroupController@options');

    /*USER*/
    Route::post('/user/create', 'UserController@create');
    Route::post('/user/update', 'UserController@update');
    Route::delete('/user/delete', 'UserController@delete');
    Route::get('/user/get', 'UserController@get');
    Route::get('/user/options', 'UserController@options');

    /*MAKER*/
    Route::post('/maker/create', 'MakerController@create');
    Route::post('/maker/update', 'MakerController@update');
    Route::delete('/maker/delete', 'MakerController@delete');
    Route::get('/maker/get', 'MakerController@get');
    Route::get('/maker/options', 'MakerController@options');

    /*MODEL*/
    Route::post('/model/create', 'ModelController@create');
    Route::post('/model/update', 'ModelController@update');
    Route::delete('/model/delete', 'ModelController@delete');
    Route::get('/model/get', 'ModelController@get');
    Route::get('/model/options', 'ModelController@options');

    /*VEHICLE*/
    Route::post('/vehicle/create', 'VehicleController@create');
    Route::post('/vehicle/update', 'VehicleController@update');
    Route::delete('/vehicle/delete', 'VehicleController@delete');
    Route::get('/vehicle/get', 'VehicleController@get');
    Route::get('/vehicle/documents/{vehicleId}', 'VehicleController@documents');
    Route::post('/vehicle/odometerNew', 'VehicleController@odometerNew');
    Route::get('/vehicle/odometerList', 'VehicleController@odometerList');
    Route::post('/vehicle/odometerEdit', 'VehicleController@odometerEdit');
    Route::delete('/vehicle/odometerDelete', 'VehicleController@odometerDelete');
    Route::get('/vehicle/options', 'VehicleController@options');

    /*VENDOR*/
    Route::post('/vendor/create', 'VendorController@create');
    Route::post('/vendor/update', 'VendorController@update');
    Route::delete('/vendor/delete', 'VendorController@delete');
    Route::get('/vendor/get', 'VendorController@get');
    Route::get('/vendor/options', 'VendorController@options');

    /*ISSUE*/
    Route::post('/issue/create', 'IssueController@create');
    Route::post('/issue/update', 'IssueController@update');
    Route::delete('/issue/delete', 'IssueController@delete');
    Route::get('/issue/get', 'IssueController@get');
    Route::get('/issue/options', 'IssueController@options');

    /*REMINDER*/
    Route::post('/reminder/create', 'ReminderController@create');
    Route::post('/reminder/update', 'ReminderController@update');
    Route::delete('/reminder/delete', 'ReminderController@delete');
    Route::get('/reminder/get', 'ReminderController@get');
    Route::get('/reminder/options', 'ReminderController@options');
    Route::get('/reminder/overview', 'ReminderController@overview');

    /*COMMENT*/
    Route::post('/comment/create', 'CommentController@create');
    Route::post('/comment/update', 'CommentController@update');
    Route::delete('/comment/delete', 'CommentController@delete');
    Route::get('/comment/get', 'CommentController@get');
    Route::get('/comment/options', 'CommentController@options');

    /*SERVICE*/
    Route::post('/service/create', 'ServiceController@create');
    Route::post('/service/update', 'ServiceController@update');
    Route::delete('/service/delete', 'ServiceController@delete');
    Route::get('/service/get', 'ServiceController@get');
    Route::get('/service/options', 'ServiceController@options');

    /*SERVICE TYPE*/
    Route::post('/servicetype/create', 'ServicetypeController@create');
    Route::post('/servicetype/update', 'ServicetypeController@update');
    Route::delete('/servicetype/delete', 'ServicetypeController@delete');
    Route::get('/servicetype/get', 'ServicetypeController@get');
    Route::get('/servicetype/options', 'ServicetypeController@options');


    /*RENEWAL TYPE*/
    Route::post('/renewaltype/create', 'RenewaltypeController@create');
    Route::post('/renewaltype/update', 'RenewaltypeController@upddate');
    Route::delete('/renewaltype/delete', 'RenewaltypeController@delete');
    Route::get('/renewaltype/get', 'RenewaltypeController@get');
    Route::get('/renewaltype/options', 'RenewaltypeController@options');

    /*MEDIA*/
    Route::post('/media/upload/{category}/{id}', 'MediaController@upload');
    Route::get('/media/edit', 'MediaController@edit');
    Route::get('/media/get', 'MediaController@get');
    Route::delete('/media/delete', 'MediaController@delete');
    Route::get('/media/show/{directory}/{company}/{size?}/{filename}', 'MediaController@show');


});
//exit(print_r($_REQUEST));
//dd(Route::getRoutes());