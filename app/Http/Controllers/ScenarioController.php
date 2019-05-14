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
		// dd(session('scenarioid'));
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

		$subembayments = DB::select('exec CapeCodMA.Calc_ScenarioNitrogen_Subembayments1 ' . $scenarioid);
		// $subembayments = DB::select('exec CapeCodMA.GET_SubembaymentNitrogen ' . $id);
		$total_goal = 0;
		foreach ($subembayments as $key) 
		{
			$n_load_orig += $key->n_load_att;
			$removed += $key->n_load_att_removed;
			$total_goal += $key->n_load_target;
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
		
		$data['remaining'] = $remaining;
		$data['embayment'] = $progress;
		$data['subembayments'] = $subembayments;
		// dd($data);

		return $data;
	}
	

	/**
	 * Show results page in the browser
	 *
	 * @return void
	 * @author 
	 **/
	public function getScenarioResults($scenarioid)
	{	
		// TODO: Can we get/set from global variable? If so, use that, else findorfail()
		$scenario = Scenario::findOrFail($scenarioid);
		
		// $towns = DB::table('CapeCodMA.parcelMaster')
		// 	->join('CapeCodMA.MAtowns','CapeCodMA.MAtowns.TOWN_ID', '=', 'CapeCodMA.parcelMaster.town_id')
		// 	->select(
		// 		DB::raw('CapeCodMA.MATowns.TOWN as town'), 
		// 		DB::raw('CapeCodMA.parcelMaster.treatment_id as wtt_treatment_id'),
		// 		DB::raw('count(CapeCodMA.parcelMaster.parcel_id) as wtt_tot_parcels'),
		// 		DB::raw('sum(CapeCodMA.parcelMaster.running_nload_removed) as wtt_unatt_n_removed')
		// 	)
		// 	->where('CapeCodMA.parcelMaster.scenario_id', '=', $scenarioid)
		// 	->groupBy('CapeCodMA.MAtowns.TOWN','CapeCodMA.parcelMaster.treatment_id')
		// 	->get();

		// TODO: Can $towns be set and gotten globally? If so, use that, else query
		$towns = DB::select('
			select 
				wtt.*, 
				t.town 

			from dbo.wiz_treatment_towns wtt 
			
			inner join capecodma.matowns t 
			on t.town_id = wtt.wtt_town_id 

			where wtt.wtt_unatt_n_removed is not null and wtt.wtt_scenario_id = ' . $scenarioid);

		// $subembayments = DB::select('exec CapeCodMA.Calc_ScenarioNitrogen_Subembayments ' . $scenarioid);

		// TODO: Check if we can remove stored proc and get $subembayments global variable to pass into view
		$subembayments = DB::select('exec CapeCodMA.Calc_ScenarioNitrogen_Subembayments1 ' . $scenarioid);

		//  'subembayments' => session('subembayments')
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
		$Nitrogen = DB::select('exec capecodma.calc_scenarioNitrogen ' . $scenarioid);
		return $Nitrogen;
	}




	/**
	 * Download Scenario as .xls
	 *
	 * @return void
	 * @author 
	 **/
	public function downloadScenarioResults($scenarioid)
	{
		$scenario = Scenario::find($scenarioid);
		// $embayment = DB::select('select * from capecodma.embayments where embay_id = ' . $scenario->AreaID);
		// $embay_id = $scenario->AreaID;
		Log::info($scenario->treatments);
		// $results = DB::select('exec CapeCodMA.Get_ScenarioResults '. $scenarioid);
		$towns = DB::select('select wtt.*, t.town from dbo.wiz_treatment_towns wtt inner join capecodma.matowns t on t.town_id = wtt.wtt_town_id where wtt.wtt_scenario_id = ' . $scenarioid);

		// $towns = DB::table('CapeCodMA.parcelMaster')
		// 	->join('CapeCodMA.MAtowns','CapeCodMA.MAtowns.TOWN_ID', '=', 'CapeCodMA.parcelMaster.town_id')
		// 	->select(
		// 		DB::raw('CapeCodMA.MATowns.TOWN as town'), 
		// 		DB::raw('CapeCodMA.parcelMaster.treatment_id as wtt_treatment_id'),
		// 		DB::raw('count(CapeCodMA.parcelMaster.parcel_id) as wtt_tot_parcels'),
		// 		DB::raw('sum(CapeCodMA.parcelMaster.running_nload_removed) as wtt_unatt_n_removed')
		// 	)
		// 	->where('CapeCodMA.parcelMaster.scenario_id', '=', $scenarioid)
		// 	->groupBy('CapeCodMA.MAtowns.TOWN','CapeCodMA.parcelMaster.treatment_id')
		// 	->get();
		// $subembayments = DB::select('exec CapeCodMA.Calc_ScenarioNitrogen_Subembayments ' . $scenarioid);
		$subembayments = DB::select('exec CapeCodMA.Calc_ScenarioNitrogen_Subembayments1 ' . $scenarioid);
		$filename = 'scenario_' . $scenarioid;
		Excel::create($filename, function($excel) use($scenario, $towns, $subembayments) 
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

		})->export('xls');
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
		
		$result = DB::select('exec CapeCodMA.DeleteScenario ' . $id);

		return 1;
	}

	public function saveScenario($id) 
	{
		$result = DB::select('exec CapeCodMA.SAVE_Scenario ' . $id);
	}
}
