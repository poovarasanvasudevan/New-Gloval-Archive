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


Route::post("/userEdit/{id}/update", 'GlobalController@updateUser');
Route::post("/resetmypassword", 'GlobalController@resetMyPassword');
Route::post("/checkout", 'GlobalController@checkout');
Route::get("/getCheckout", 'GlobalController@getCheckout');
Route::get("/checkInAutocomplete", 'GlobalController@checkInAutocomplete');