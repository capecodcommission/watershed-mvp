<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// Splash Route
$app->get('/', function () use ($app) {

  $query = "select * from [WMVP_Wizard].[CapeCodMA].[Scenario_Wiz] where ScenarioID = 366";
  
  $result = app('db')->select($query);

  dd($result);


  $splash = "Cape Cod Commission - Financial Model API";
  return $splash;

});
