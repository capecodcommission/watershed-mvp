<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Scenario;
use DB;

use App\Embayment;
use App\Treatment;

use JavaScript;

use Session;
use Excel;
use Auth;
use App\User;
use Log;

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

		// This will get the overall embayment progress
		// need to return a (rounded) int that represents the user's current progress toward the embayment's target.
		// first get the embayment's starting and target N levels
		// then get the current N_Removed

		$removed = 0;
		$n_load_orig = 0;
		$total_goal = 0;
		// $subembayments = DB::select('exec CapeCodMA.Calc_ScenarioNitrogen_Subembayments ' . $scenarioid);

		$subembayments = DB::select('exec dbo.CALCscenarioNitrogenSubembayments ' . $scenarioid);
		// $subembayments = DB::select('exec CapeCodMA.GET_SubembaymentNitrogen ' . $id);
		$total_goal = 0;
		foreach ($subembayments as $key) 
		{
			$n_load_orig += round($key->n_load_att);
			$removed += round($key->n_load_att_removed);
			$total_goal += round($key->n_load_target);
		}
		$current = $n_load_orig - $removed;
		$remaining = $current - $total_goal;
		$progress = round($total_goal/$current * 100);

		if($remaining < 0)
		{
			$remaining = 0;
		}
		if ($progress > 0 & $progress <= 100) {
			$progress;
		}
		else
		{
			$progress = 100;
		}

		$fertApplied = session()->get('fert_applied');
		$stormApplied = session()->get('storm_applied');

		$data['fertapplied'] = $fertApplied;
		$data['stormapplied'] = $stormApplied;
		
		$data['remaining'] = $remaining;
		$data['embayment'] = $progress;
		$data['subembayments'] = $subembayments;
		// dd($data);

		return $data;
	}
	

	// Retrieve scenario-wide technology nitrogen loads and costs by town and subembayment
	public function getScenarioResults($scenarioid)
	{	
		$scenario = Scenario::find($scenarioid);
		$towns =  DB::select('exec dbo.GETtreatmentCostsByTown ' . $scenarioid);
		$subembayments = DB::select('exec dbo.CALCscenarioNitrogenSubembayments ' . $scenarioid);

		return view('layouts/results', ['scenario'=>$scenario, 'towns'=>$towns, 'subembayments'=>$subembayments]);
	}

	/**
	 * GetScenarioNitrogen
	 *
	 * @return void
	 * @author 
	 **/
	function GetScenarioNitrogen()
	{
		$scenarioid = session('scenarioid');
		$Nitrogen = DB::select('exec dbo.calc_scenarioNitrogen ' . $scenarioid);
		return $Nitrogen;
	}




	// Export subembayment, town, and scenario-wide totals into Excel (laravel-excel https://docs.laravel-excel.com/2.1/export/)
	public function downloadScenarioResults($scenarioid)
	{
		// Retrieve scenario, town, and subembayment arrays from ORM, query and stored proc
		$subembayments = DB::select('exec dbo.CALCscenarioNitrogenSubembayments ' . $scenarioid);
		$towns = DB::select('select wtt.*, t.town from dbo.wiz_treatment_towns wtt inner join dbo.matowns t on t.town_id = wtt.wtt_town_id where wtt.wtt_scenario_id = ' . $scenarioid);
		$scenario = Scenario::find($scenarioid);
		
		$filename = 'wMVP_Export_' . $scenarioid;

		// Create subsheets for each breakdown
		// Use comma-separation and currency formatting on relevant columns for cost and nitrogen outputs
		// Export sheet as '.xls' format
		Excel::create(
			$filename, 
			function($excel) use($scenario, $towns, $subembayments) 
			{
				$excel->sheet('Scenario Results', function($sheet) use ($scenario, $towns){
					$sheet->setColumnFormat(array('C:D' => '#,##0_-', 'E' => '"$"#,##0_-', 'F' => '"$"#,##0.00_-'));
					$sheet->loadView('downloads.treatments', array( 'scenario'=>$scenario,  'towns'=>$towns));

				});

				$excel->sheet('Subembayment Results', function($sheet) use ($scenario, $subembayments){
					$sheet->setColumnFormat(array('B:F' => '#,##0_-'));
					$sheet->loadView('downloads.subembayments', array( 'scenario'=>$scenario, 'subembayments'=>$subembayments));
				});

				$excel->sheet('Cost Breakdown', function($sheet) use ($scenario){
					$sheet->setColumnFormat(array('C:D' => '#,##0_-', 'E:L' => '"$"#,##0_-'));
					$sheet->loadView('downloads.costs', array('scenario'=>$scenario));

				});			
			}
		)->export('xls');
	}

	/**
	 * Delete the scenario for the logged-in user
	 *
	 * @return void
	 * @author 
	 **/
	public function deleteScenario($id)
	{
		$user = Auth::user();
		$scenario = Scenario::find($id);
		
		$result = DB::select('exec dbo.DeleteScenario ' . $id);

		return 1;
	}

	public function saveScenario($id) 
	{
		$result = DB::select('exec dbo.SAVE_Scenario ' . $id);
	}
}
