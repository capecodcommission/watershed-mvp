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
		$removed = DB::select('exec CapeCodMA.CALC_ScenarioNitrogen ' . $scenarioid);
		$removed = $removed[0]->N_Removed;
		$current = $scenario->Nload_Existing - $removed;
		$progress = round($scenario->Nload_Total_Target/$current * 100);

		// Need to get the progress for each subembayment.
		$sub_removed = DB::select('exec CapeCodMA.Calc_ScenarioNitrogen_Subembayments ' . $scenarioid);
		// dd($sub_removed);
		$data['embayment'] = $progress;
		$data['subembayments'] = $sub_removed;


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
		// $scenarioid = session('scenarioid');
		$scenario = Scenario::find($scenarioid);
		// $embay_id = session('embay_id');
		$results = DB::select('exec CapeCodMA.Get_ScenarioResults '. $scenarioid);
		// dd($results);
		$towns = DB::select('select wtt.*, t.town from dbo.wiz_treatment_towns wtt inner join capecodma.matowns t on t.town_id = wtt.wtt_town_id
  where wtt.wtt_scenario_id = ' . $scenarioid);
		$subembayments = DB::select('exec CapeCodMA.Calc_ScenarioNitrogen_Subembayments ' . $scenarioid);
		// Need to calculate all the treatments applied and Nitrogen removed from this scenario

		return view('layouts/results', ['results'=>$results, 'scenario'=>$scenario, 'towns'=>$towns, 'subembayments'=>$subembayments]);

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
		// $embay_id = $scenario->AreaID;
		// dd($scenario);
		$results = DB::select('exec CapeCodMA.Get_ScenarioResults '. $scenarioid);
		$towns = DB::select('select wtt.*, t.town from dbo.wiz_treatment_towns wtt inner join capecodma.matowns t on t.town_id = wtt.wtt_town_id
  where wtt.wtt_scenario_id = ' . $scenarioid);
		$subembayments = DB::select('exec CapeCodMA.Calc_ScenarioNitrogen_Subembayments ' . $scenarioid);
		$filename = 'scenario_' . $scenarioid;
		Excel::create($filename, function($excel) use($scenario, $results, $towns, $subembayments) 
		{

			$excel->sheet('Scenario Results', function($sheet) use ($scenario, $results, $towns){
				$sheet->setColumnFormat(array('C:D' => '#,##0_-', 'E' => '"$"#,##0_-', 'F' => '"$"#,##0.00_-'));
				$sheet->loadView('downloads.treatments', array('results'=>$results, 'scenario'=>$scenario,  'towns'=>$towns));

			});

			$excel->sheet('Subembayment Results', function($sheet) use ($scenario, $subembayments){
				$sheet->setColumnFormat(array('B:F' => '#,##0_-'));
				$sheet->loadView('downloads.subembayments', array( 'scenario'=>$scenario, 'subembayments'=>$subembayments));
			});

			$excel->sheet('Cost Breakdown', function($sheet) use ($scenario, $results){
				$sheet->setColumnFormat(array('C:D' => '#,##0_-', 'E:L' => '"$"#,##0_-'));
				$sheet->loadView('downloads.costs', array('results'=>$results, 'scenario'=>$scenario));

			});			

		})->export('xls');
	}
}
