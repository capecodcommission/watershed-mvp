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

Route::get('/', 'StartController@index');

Route::get('/map/{embayment}', 'WizardController@start');

Route::get('/test/{embayment}', 'WizardController@test');

Route::get('/testmap', function(){
	return view('testmap');
});
Route::get('/testleaf', function(){
	return view('testleaf');
});

// this route should be changed or recreated to be more accurate for what it does
// which is take a polygon string and retrieve the parcels contained within, along with N load, etc.
Route::get('/testmap/Nitrogen/{treatment}/{poly}', 'WizardController@getPolygon');

Route::get('/tech/{tech}', 'TechnologyController@get');

Route::get('/tech-collect/{tech}', 'TechnologyController@getCollection');

Route::get('/map/point/{x}/{y}', 'MapController@point');

Route::resource('/api/treatments', 'ApiTreatmentController');
// Route::auth();

// Route::get('/home', 'HomeController@index');
