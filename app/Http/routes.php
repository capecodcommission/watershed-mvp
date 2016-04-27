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

Route::get('/testmap/Nitrogen/{poly}', 'WizardController@getNitrogen');

Route::get('/tech/{tech}', 'TechnologyController@get');

Route::get('/map/point/{x}/{y}', 'MapController@point');

Route::resource('/api/treatments', 'ApiTreatmentController');
// Route::auth();

// Route::get('/home', 'HomeController@index');
