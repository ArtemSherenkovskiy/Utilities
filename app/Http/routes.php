<?php

use App\Http;
use App\Http\Services;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/a', 'ServiceController@index');

Route::get('/', ['middleware' => 'auth' , function () {
    return view('test_layout');
}]);


Route::get('/service{id?}', ['as'=>'service','uses'=>'ServiceController@getService']);
Route::post('service{id}/save',['as'=>'saveService','uses'=>'ServiceController@store']);
Route::get('edit/service{service_id}',['as' => 'editService', 'uses' => 'ServiceController@editService']);



// Authentication routes...
Route::get('auth/login', ['as'=>'login','uses'=>'Auth\AuthController@getLogin']);
Route::post('auth/login', ['as'=>'loginPost','uses'=>'Auth\AuthController@postLogin']);
Route::get('auth/logout', ['as'=>'logout','uses'=>'Auth\AuthController@getLogout']);

// Registration routes...
Route::get('auth/register', ['as'=>'register','uses'=>'Auth\AuthController@getRegister']);
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::resource('home','HomeController');


