<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
###################### User Authentication #####################

Route::group([

    'middleware' => 'api',
    'prefix' => 'user',
    'namespace'=>'User'

], function ($router) {
    Route::post('register', 'AuthController@registerUser');
    Route::get('login', 'AuthController@login')->name('login');

});

###################### Admin Authentication #####################

Route::group([

    'middleware' => 'api',
    'prefix' => 'admin',
    'namespace'=>'Admin'

], function ($router) {
    Route::get('register', 'AuthController@registerAdmin');
    Route::get('login', 'AuthController@login')->name('login');

});
###################### Admin route Categories ###########################
Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>['api','assignGuard:admin-api']],function (){
    Route::post('logout', 'AuthController@logout');
    Route::get('EditCategories/{id}','Categories@EditCategories');
    Route::post('creat-item','Categories@store');
    Route::post('update-item/{id}','Categories@update');
    Route::get('delete-item/{id}','Categories@delete');
    Route::get('show-user','Categories@showuser');
    Route::get('delete-user/{id}','Categories@deleteuser');



});
Route::group(['namespace'=>'Admin'],function (){
Route::get('Show-item','Categories@read');
Route::get('Show-item-home','Categories@readhome');
Route::get('Show-item-discount','Categories@discount');
    });

############################# User Booking ##########################
Route::group(['namespace'=>'User','prefix'=>'user','middleware'=>['api','assignGuardUser:user-api']],function () {
    Route::post('logout', 'AuthController@logout');
    Route::get('Booking/{id}','Booking@reserve');
    Route::get('like/{id}','Booking@like');

});
