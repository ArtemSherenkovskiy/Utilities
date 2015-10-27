<?php

use App\Http;
use App\Http\Vendors;
include 'Vendors\VendorControl.php';
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

Route::get('/a', function () {
    return "edd";
});

Route::get('/', [/*'middleware' => 'auth' ,*/ function () {
    return view('welcome');
}]);

Route::get('/vendor/{id?}', function($id = null)
{
    $vC = new Vendors\VendorControl();
    return $vC->generate($id);

});

// Authentication routes...
Route::get('auth/login', ['as'=>'login','uses'=>'Auth\AuthController@getLogin']);
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', ['as'=>'register','uses'=>'Auth\AuthController@getRegister']);
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::resource('home','HomeController');


