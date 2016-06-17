<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Scenario;
use DB;


class ScenarioController extends Controller
{
    

	public function getProgress()
	{
		// need to get current N level for this scenario

		return view('common/progress-svg');
	}


	public function getCurrentProgress()
	{
		
		$scenarioid = session('scenarioid');
		$scenario = Scenario::find($scenarioid);
		$removed = DB::select('exec CapeCodMA.CALC_ScenarioNitrogen ' . $scenarioid);
		
		$removed = $removed[0]->N_Removed;
		$current = $scenario->Nload_Existing - $removed;
		$progress = round($scenario->Nload_Total_Target/$current * 100);
		
		// need to return a (rounded) int that represents the user's current progress toward the embayment's target.
		// first get the embayment's starting and target N levels
		// then get the current N_Removed


		return $progress;
	}

}
