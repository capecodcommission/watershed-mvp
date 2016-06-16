<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Scenario;


class ScenarioController extends Controller
{
    

	public function getProgress()
	{
		// need to get current N level for this scenario




		return view('common/progress');
	}

}
