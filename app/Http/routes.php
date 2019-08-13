<?php

// TODO: Apply camel-casing to all routes and associated controller functions
Route::group(['middleware' => 'auth'], function () {
   
	Route::get('/start', 'StartController@index');
	Route::get('/map/{embayment}/{scenarioid?}', 'WizardController@start');
	Route::get('/save/{id}', 'ScenarioController@saveScenario');
	Route::get('/tech/{type}/{tech}', 'TechnologyController@associateTech');
	Route::get('/edit/{treatment}', 'TechnologyController@edit');
	Route::get('/update/{type}/{treatment}/{rate}/{units?}/{subemid?}', 'TechnologyController@update');
	// TODO: Fix 'delete_treatment' to delete/{treatment}/{type?}
	Route::get('/delete_treatment/{treatment}/{type?}', 'TechnologyController@delete');
	Route::get('/cancel/{treatment}/{type?}', 'TechnologyController@cancel');
	Route::get('/delete_scenario/{scenarioid}', 'ScenarioController@deleteScenario');
	Route::get('/apply_percent/{treatment}/{rate}/{type}/{units?}', 'TechnologyController@ApplyTreatment_Percent');
	Route::get('/apply_storm/{treatment}/{rate}/{units}/{location}', 'TechnologyController@ApplyTreatment_Storm');
	Route::get('/apply_septic/{treatment}/{rate}', 'TechnologyController@ApplyTreatment_Septic');
	Route::get('/apply_embayment/{treatment}/{rate}/{units}/{subemid?}', 'TechnologyController@ApplyTreatment_Embayment');
	Route::get('/apply_groundwater/{treatment}/{rate}/{units}', 'TechnologyController@ApplyTreatment_Groundwater');
	Route::get('/polygon/{type}/{treatment}/{polygon}', 'TechnologyController@getPolygon');
	Route::get('/map/point/{x}/{y}/{treatment}', 'MapController@point');
	Route::get('/map/move/{x}/{y}/{treatment}', 'MapController@moveNitrogen');
	Route::get('/getScenarioNitrogen', 'ScenarioController@GetScenarioNitrogen');
	Route::get('/getScenarioProgress', 'ScenarioController@getCurrentProgress');
	Route::get('/results/{scenarioid}', 'ScenarioController@getScenarioResults');
	Route::get('/download/{scenarioid}', 'ScenarioController@downloadScenarioResults');
	Route::get('progress', 'ScenarioController@getProgress');
	Route::get('/', 'HomeController@index');
	Route::get('/home', 'HomeController@index');
	// TODO: Rename /poly route to reference custom polygon creation
	Route::post('/poly', 'WizardController@getPolygon2');
	Route::post('/sumTotalsWithinPolygon', 'WelcomeController@sumTotalsWithinPoly');
	Route::post('/getIDArrayWithinPolygon', 'WelcomeController@getIDArrayWithinPoly');
	Route::post('/update_polygon', 'TechnologyController@updatePolygon');
});

Route::auth();

Route::get('/help', function() {

	return view('help');
});