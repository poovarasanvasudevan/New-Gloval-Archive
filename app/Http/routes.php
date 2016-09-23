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
Route::get("/myprofile", 'GlobalController@myprofile');
Route::post("/myUserUpdate", 'GlobalController@myUserUpdate');


Route::get("/artefactview/{id?}", 'GlobalController@artefactview');
Route::get("/artefactprint/{id?}", 'GlobalController@artefactprint');
Route::get("/forget", 'GlobalController@forget');
Route::post("/saveArtefact", 'GlobalController@saveArtefact');
Route::get("/home", 'GlobalController@home');
Route::get("/about", 'GlobalController@about');
Route::get("/logout", 'GlobalController@logout');
Route::post("/resetPassword", 'GlobalController@reset');
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
Route::get("/nac/{attr}", 'GlobalController@getAutocomplete');
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



Route::group(['middleware' => 'App\Http\Middleware\AdminMiddleware'], function () {
    Route::get('/admin', 'AdminController@index');
    Route::get('/admin/artefacttypes', 'AdminController@artefacttypes');
    Route::get('/admin/getAllArtefactTypes', 'AdminController@getAllArtefactTypes');
    Route::get('/admin/pages', 'AdminController@pages');
    Route::get('/admin/cacheclear','AdminController@cacheclear');

    Route::post('/admin/saveMail','AdminController@saveMail');
    Route::post('/admin/setVersion','AdminController@setVersion');
    Route::post('/admin/saveCicoMail','AdminController@saveCicoMail');

    Route::any('/admin/updateArtefactTypes', 'AdminController@updateArtefactTypes');
    Route::any('/admin/deleteArtefactTypes', 'AdminController@deleteArtefactTypes');
    Route::any('/admin/addArtefactTypes', 'AdminController@addArtefactTypes');
    Route::any('/admin/updateAttributes', 'AdminController@updateAttributes');
    Route::any('/admin/addAttributes/{id}', 'AdminController@addAttributes');
    Route::any('/admin/deleteAttributes', 'AdminController@deleteAttributes');
    Route::any('/admin/updatepick', 'AdminController@updatepick');
    Route::any('/admin/deletepick', 'AdminController@deletepick');
    Route::any('/admin/insertpick', 'AdminController@insertpick');
    Route::any('/admin/updatesegment', 'AdminController@updatesegment');
    Route::any('/admin/deletesegment', 'AdminController@deletesegment');
    Route::any('/admin/insertsegment/{id}', 'AdminController@insertsegment');
    Route::any('/admin/updatesegmentvalue', 'AdminController@updatesegmentvalue');
    Route::any('/admin/insertsegmentvalue/{id}', 'AdminController@insertsegmentvalue');
    Route::any('/admin/updatePage', 'AdminController@updatePage');
    Route::any('/admin/deletePage', 'AdminController@deletePage');
    Route::any('/admin/addPage', 'AdminController@addPage');
    Route::any('/admin/insertlocation', 'AdminController@insertlocation');
    Route::any('/admin/updatelocation', 'AdminController@updatelocation');
    Route::any('/admin/updateuser', 'AdminController@updateuser');

    Route::get("/admin/git", 'AdminController@git');
    Route::get("/admin/notify", 'AdminController@firetask');
    Route::get("/admin/up", 'AdminController@upServer');
    Route::get("/admin/down", 'AdminController@downServer');
    Route::get("/admin/location", 'AdminController@location');
    Route::get("/admin/importartefact", 'AdminController@importExcel');
    Route::get("/admin/exportexcel/{id}", 'AdminController@excelImportSheet');


    Route::get('/admin/attributes/{id}', 'AdminController@attributes');
    Route::get('/admin/logs', 'AdminController@logs');
    Route::get('/admin/attributelist', 'AdminController@attributelist');
    Route::get('/admin/users', 'AdminController@users');
    Route::get('/admin/config', 'AdminController@config');
    Route::post('/admin/importartefact', 'AdminController@importArtefactExcel');
    Route::get('/admin/crreport/{type?}/{section?}', 'AdminController@crreport');


    Route::any('/admin/adminer', '\Miroc\LaravelAdminer\AdminerController@index');
});
