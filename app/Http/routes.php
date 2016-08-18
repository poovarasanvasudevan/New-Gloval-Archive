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

Route::get('/', function () {
    if (Auth::user()) {
        return response()->redirectTo("/home");
    } else {
        return view('welcome');
    }
});

Route::post("/login", 'GlobalController@login');
Route::post("/saveArtefact", 'GlobalController@saveArtefact');
Route::get("/home", 'GlobalController@home');
Route::get("/logout", 'GlobalController@logout');
Route::get("/reset-password", 'GlobalController@resetPassword');
Route::get("/definition", 'GlobalController@definition');
Route::get("/userrole", 'GlobalController@roleUsers');
Route::get("/userEdit/{id}", 'GlobalController@userEdit');
Route::get("/loadTree/{artefacttype}/{id}", 'GlobalController@loadTree');
Route::get("/getArtefact/{type}/{id}", 'GlobalController@getArtefact');
Route::get("/attrs", 'GlobalController@attrs');
Route::get("/addArtefact/{type}/{id}/{val}", 'GlobalController@addArtefact');
Route::get("/deleteArtefact/{id}", 'GlobalController@deleteArtefact');
Route::get("/renameArtefact/{id}/{newName}", 'GlobalController@renameArtefact');
Route::get("/moveArtefact/{id}/{newParent}", 'GlobalController@moveArtefact');
Route::get("/allartefact", 'GlobalController@allartefact');
Route::get("/cico", 'GlobalController@cico');
Route::get("/cin", 'GlobalController@cin');
Route::get("/newUser", 'GlobalController@newUser');
Route::get("/editRole/{id}", 'GlobalController@editRole');
Route::get("/allroles", 'GlobalController@allroles');
Route::get("/maintenence", 'GlobalController@maintenence');
Route::get("/help", 'GlobalController@help');
Route::get("/search/{id?}", 'GlobalController@search');
Route::get("/reports", 'GlobalController@reports');
Route::get("/getSchedule/{id}", 'GlobalController@getSchedule');
Route::get("/task/{id?}", 'GlobalController@task');
Route::get("/doTask/{taskId?}", 'GlobalController@doTask');
Route::get("/crview/{id?}", 'GlobalController@crview');
Route::get("/crReportPrint/{id?}", 'GlobalController@crReportPrint');
Route::get("/cicoReportPrint/{start?}/{end?}", 'GlobalController@cicoReportPrint');


Route::post("/userEdit/{id}/update", 'GlobalController@updateUser');
Route::post("/resetmypassword", 'GlobalController@resetMyPassword');
Route::post("/saveConditionalReport", 'GlobalController@saveConditionalReport');
Route::post("/checkout", 'GlobalController@checkout');
Route::post("/checkin", 'GlobalController@checkin');
Route::post("/userCreate", 'GlobalController@userCreate');
Route::post("/addRole", 'GlobalController@addRole');
Route::post("/updateRole", 'GlobalController@updateRole');
Route::post("/addSperodicMaintenance", 'GlobalController@addSperodicMaintenance');
Route::post("/addPerodicMaintenance", 'GlobalController@addPerodicMaintenance');
Route::post("/getCicoWithDates", 'GlobalController@getCicoWithDates');
Route::post("/getCRWithDates", 'GlobalController@getCRWithDates');
Route::any("/searchTable/{page?}", 'GlobalController@searchTable');


Route::get("/getCheckout", 'GlobalController@getCheckout');
Route::any("/checkInAutocomplete", 'GlobalController@checkInAutocomplete');