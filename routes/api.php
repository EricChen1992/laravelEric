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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/main123','IndexController@main123');

Route::post('/worktimein', 'WorkTimeController@workclockin');

Route::post('/worktimeout', 'WorkTimeController@workclockout');

Route::any('/getcua', 'WorkTimeController@getCua');

Route::any('/gettime', 'WorkTimeController@getTime');

Route::any('/getUserAllData', 'WorkTimeController@getUserAllData');

Route::any('/downloadExcel/{value?}', 'WorkTimeController@downloadExcel')->name('downloadexcel');