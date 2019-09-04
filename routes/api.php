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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::post('login', 'APIController@appUserLogin');
Route::post('register-user', 'APIController@appUserRegister');
Route::post('add-new-survey', 'APIController@addNewRiskSurvey');
Route::post('add-initial-info', 'APIController@addInitialInfo');
Route::post('add-location-info', 'APIController@addLocationInfo');
Route::post('add-remaining-info', 'APIController@addRemainingInfo');
Route::post('get-surveys', 'APIController@getSurveys');
//Route::get('show-data', function ()
//{
//
//    $user = \App\User::find(8);
//
//    $survey = \App\RiskSurveyInfo::find(35);
//
//    dd($survey->locations);
//
//});
