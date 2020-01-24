<?php

// TODO: Apply camel-casing to all routes and associated controller functions
Route::group(['middleware' => 'auth'], function () {
   
	Route::get('/start', 'StartController@index');
	Route::get('/map/{embayment}/{scenarioid?}', 'WizardController@start');
	Route::get('/save/{id}', 'ScenarioController@saveScenario');
	Route::get('/tech/{techId}', 'TechnologyController@associateTech');
	Route::get('/edit/{treatment}', 'TechnologyController@edit');
	Route::get('/update/{treatment}/{treatmentValue}/{units?}/{subemid?}', 'TechnologyController@update');
	// TODO: Fix 'delete_treatment' to delete/{treatment}/{type?}
	Route::get('/delete_treatment/{treatment}/{type?}', 'TechnologyController@delete');
	Route::get('/cancel/{treatment}/{type?}', 'TechnologyController@cancel');
	Route::get('/delete_session_geometry/{treatmentId?}', 'TechnologyController@deleteSessionGeometry');
	Route::get('/delete_scenario/{scenarioid}', 'ScenarioController@deleteScenario');
	Route::get('/apply_management/{rate}/{techId}/{treat_id?}', 'TechnologyController@ApplyTreatment_Management');
	Route::get('/apply_storm/{rate}/{techId}/{treat_id?}', 'TechnologyController@ApplyTreatment_Storm');
	Route::get('/apply_collectStay/{rate}/{techId}/{units?}/{treat_id?}', 'TechnologyController@ApplyTreatment_CollectStay');
	Route::get('/apply_embayment/{rate}/{units}/{techId}/{treat_id?}', 'TechnologyController@ApplyTreatment_Embayment');
	Route::get('/apply_groundwater/{treatment}/{rate}/{units}', 'TechnologyController@ApplyTreatment_Groundwater');
	Route::get('/map/point/{x}/{y}/{techId?}', 'MapController@setPointCoords');
	Route::post('/map/poly', 'MapController@setCoordArray');
	Route::get('/map/move/{x}/{y}/{treatment}', 'MapController@moveNitrogen');
	Route::get('/getScenarioNitrogen', 'ScenarioController@GetScenarioNitrogen');
	Route::get('/getScenarioProgress', 'ScenarioController@getCurrentProgress');
	Route::get('/results/{scenarioid}', 'ScenarioController@getScenarioResults');
	Route::get('/download/{scenarioid}', 'ScenarioController@downloadScenarioResults');
	Route::get('progress', 'ScenarioController@getProgress');
	Route::get('/', 'HomeController@index');
	Route::get('/home', 'HomeController@index');
	Route::post('/sumTotalsWithinPolygon', 'WelcomeController@sumTotalsWithinPoly');
	Route::post('/getIDArrayWithinPolygon', 'WelcomeController@getIDArrayWithinPoly');
	Route::post('/update_geometry', 'TechnologyController@updateGeometry');
	Route::get('/get_treatment/{id}', 'TechnologyController@getTreatment');
	Route::get('/get_treatments', 'TechnologyController@getTreatments');
	Route::get('/get_subembayment/{pointCoords}', 'TechnologyController@getSubembayment');
});

Route::auth();

Route::get('/help', function() {

	return view('help');
});