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
Route::group(['middleware'=>'auth'],function(){
    Route::get('/a', ['as'=>'home','uses'=>'ServiceController@index']);

    Route::get('/',  function () {
        if(\Illuminate\Support\Facades\Auth::guest()) {
            return view('auth/register-modal');
        }
        else
        {
            return redirect()->route('home');
        }
    });


    Route::get('/service{id?}', ['as' => 'service', 'uses' => 'ServiceController@getService']);
    Route::get('/service/work{id?}', ['as' => 'workWithService', 'uses' => 'ServiceController@workWithService']);
    Route::get('/service/calculate{id?}', ['as' => 'beforeCalculate', 'uses' => 'ServiceController@beforeCalculate']);
    Route::post('service{id}/calculate/save', ['as' => 'calculate', 'uses' => 'ServiceController@calculate']);
    Route::post('service{id}/save', ['as' => 'saveService', 'uses' => 'ServiceController@store']);
    Route::get('edit/service{service_id}', ['as' => 'editService', 'uses' => 'ServiceController@editService']);

});

// Authentication routes...

Route::post('auth/login', ['as'=>'loginPost','uses'=>'Auth\AuthController@postLogin']);
Route::get('auth/logout', ['as'=>'logout','uses'=>'Auth\AuthController@getLogout']);

// Registration routes...

Route::post('auth/register', ['as'=>'register','uses'=>'Auth\AuthController@postRegister']);

Route::resource('home','HomeController');


