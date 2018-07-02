<?php

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

Route::get('auth/facebook', 'Auth\AuthController@redirectToProvider');
Route::get('auth/facebook/callback', 'Auth\AuthController@handleProviderCallback');

Route::get('auth/me', 'Auth\AuthController@getMe');
Route::get('auth/logout', 'Auth\AuthController@logout');

Route::get('auth/login/{id?}', 'Auth\AuthController@loginMe');

Route::get('/', function () {
    return view('home');
});

Route::get('/policy', function () {
    return 'You get no privacy';
});

Route::group(['middleware' => ['auth', 'permission:manage-schedule']], function(){
    Route::post('/events/add', 'BandController@postEvent');
    Route::post('/events/edit', 'BandController@postEvent');
    
});

Route::group(['middleware' => ['auth']], function(){
    Route::get('/band', 'BandController@getBand');
    Route::get('/band/members/{email}', 'BandController@getMember');
    
    Route::get('/events', 'BandController@getEvents');
    Route::get('/events/{slug}', 'BandController@getEvent');
    
});

Route::group(['middleware' => ['auth', 'permission:manage-details']], function(){
    Route::post('/band/edit', 'BandController@postBand');
});

Route::group(['middleware' => ['auth', 'permission:manage-band-users']], function(){
    Route::post('/band/members/add', 'BandController@postMember');
    Route::post('/band/members/edit', 'BandController@postMember');
});
