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

Route::get('/map/{embayment}/{scenarioid?}', 'WizardController@start');



// this route should be changed or recreated to be more accurate for what it does
// which is take a polygon string and retrieve the parcels contained within, along with N load, etc.
Route::get('/testmap/Nitrogen/{treatment}/{poly}', 'WizardController@getPolygon');

Route::get('/tech/{type}/{tech}', 'TechnologyController@get');
Route::get('/edit/{treatment}', 'TechnologyController@edit');
Route::get('/update/{type}/{treatment}/{rate}', 'TechnologyController@update');
Route::get('/delete/{treatment}', 'TechnologyController@delete');
Route::get('/cancel/{treatment}', 'TechnologyController@cancel');



Route::get('/apply_percent/{treatment}/{rate}/{type}/{units?}', 'TechnologyController@ApplyTreatment_Percent');
Route::get('/apply_storm/{treatment}/{rate}/{units}/{location}', 'TechnologyController@ApplyTreatment_Storm');
Route::get('/apply_septic/{treatment}/{rate}/{type}', 'TechnologyController@ApplyTreatment_Septic');

Route::get('/tech-collect/{tech}', 'TechnologyController@getCollection');

Route::get('/polygon/{type}/{treatment}/{polygon}', 'TechnologyController@getPolygon');

Route::get('/map/point/{x}/{y}/{treatment}', 'MapController@point');
Route::get('/map/move/{x}/{y}/{treatment}', 'MapController@moveNitrogen');

Route::get('/getScenarioNitrogen', 'WizardController@GetScenarioNitrogen');
Route::get('/getScenarioProgress', 'ScenarioController@getCurrentProgress');

Route::get('/results/{scenarioid}', 'WizardController@getScenarioResults');
Route::get('/download/{scenarioid}', 'WizardController@downloadScenarioResults');

Route::get('progress', 'ScenarioController@getProgress');


Route::resource('/api/treatments', 'ApiTreatmentController');
Route::get('/test/{embayment}', 'WizardController@test');

Route::get('/testmap', function(){
	return view('testmap');
});
Route::get('/testleaf', function(){
	return view('testleaf');
});