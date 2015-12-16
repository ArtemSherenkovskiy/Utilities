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
    return view('auth/register-modal');
}]);


Route::get('/service{id?}', 'ServiceController@getService');




// Authentication routes...
Route::get('auth/login', ['as'=>'login','uses'=>'Auth\AuthController@getLogin']);
Route::post('auth/login', ['as'=>'loginPost','uses'=>'Auth\AuthController@postLogin']);
Route::get('auth/logout', ['as'=>'logout','uses'=>'Auth\AuthController@getLogout']);

// Registration routes...
Route::get('auth/register', ['as'=>'register','uses'=>'Auth\AuthController@getRegister']);
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::resource('home','HomeController');


