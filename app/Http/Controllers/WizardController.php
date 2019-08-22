<?php

namespace App\Http\Controllers;
use Log;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;

use App\Embayment;
use App\Scenario;
use App\Treatment;

use DB;
use JavaScript;

use Session;
use Excel;

class WizardController extends Controller
{
	// TODO: If possible, look into saving all variables to the session, then just using from there (e.g. $id, $scenarioid, etc.)
	// TODO: Clean up below logic
	// Start a scenario, establish the id and scenario is as null 
	public function start($id, $scenarioid = null)
	{
		$user = Auth::user();
		$embayment = Embayment::find($id);
		
		// Need to create a new scenario or find existing one that the user is editing
		if(!$scenarioid)
		{
			if (session('scenarioid')) 
			{
				$scenarioid = session()->get('scenarioid');
				$scenario = Scenario::find($scenarioid);

				if ($scenario->AreaID == $id) 
				{
					// user is still working on the same scenario
				}
				else
				{
					$scenario = $user->scenarios()->create([
						'AreaID'=>$id, 'ScenarioPeriod'=>'Existing', 'AreaName'=>$embayment->EMBAY_DISP
					]);
					// user selected a different embayment, need to create a new scenario 
					$scenarioid = $scenario->ScenarioID;
					session(['scenarioid' => $scenarioid]);
					session(['n_removed' => 0]);
					session(['fert_applied' => 0]);
					session(['storm_applied' => 0]);
				}
			}
			else
			{
				//  Need to create a new scenario
				$scenario = $user->scenarios()->create([
					'AreaID'=>$id, 'ScenarioPeriod'=>'Existing', 'AreaName'=>$embayment->EMBAY_DISP
				]);

				$scenarioid = $scenario->ScenarioID;

				session(['scenarioid' => $scenarioid]);
				session(['n_removed' => 0]);
				session(['fert_applied' => 0]);
				session(['storm_applied' => 0]);
			}
			session(['embay_id' => $id]);
		}
		else
		{
			session(['scenarioid' => $scenarioid]);
		}

		// Find scenario row by id, parse treatments
		$scenario = Scenario::find($scenarioid);
		$treatments = $scenario->treatments;

		// Set default management applied session variables
		session(['fert_applied' => 0]);
		session(['storm_applied' => 0]);

		// If fert/storm applied previously, update management session variables
		foreach ($treatments as $key) {
			if ($key->TreatmentType_ID == 400) {
				session(['fert_applied' => 1]);
			}
			if ($key->TreatmentType_ID == 401) {
				session(['storm_applied' => 1]);
			}
		}

		// TODO: Determine if global values can be initially set and updated without initializing additonal variables
		// Make these session variables (e.g. is $removed == session n_removed, can we add n_load_orig (is it att or unatt)
		// a session variable, etc.)
		$removed = 0;
		$n_load_orig = 0;
		// $subembayments = DB::select('exec CapeCodMA.Calc_ScenarioNitrogen_Subembayments ' . $scenarioid);
		$subembayments = DB::select('exec dbo.Calc_ScenarioNitrogen_Subembayments1 ' . $scenarioid);
		$total_goal = 0;

		foreach ($subembayments as $key) 
		{
			$n_load_orig += $key->n_load_att;
			$removed += $key->n_load_att_removed;
			$total_goal += $key->n_load_target;
		}
		
		$current = $n_load_orig - $removed;

		if ($total_goal == 0 || $n_load_orig == 0) {
			$progress = 100;
		}
		else {
			$progress = round($total_goal/$current * 100);
		}

		if ($progress > 0 && $progress <= 100) {
			$progress;
		}
		else {
			$progress = 100;
		}

		$remaining = $current - $total_goal;

		if($remaining < 0) {
			$remaining = 0;
		}

		$nitrogen = DB::select('exec dbo.GET_AreaNitrogen_Unattenuated ' . $id);
		$nitrogen_att = DB::select('exec dbo.GET_AreaNitrogen_attenuated ' . $id);

		JavaScript::put (
			[
				'nitrogen_unatt' => $nitrogen[0],
				'nitrogen_att' => $nitrogen_att[0],
				'center_x'	=> $embayment->longitude,
				'center_y'	=> $embayment->latitude,
				'selectlayer' => $embayment->embay_id,
				'treatments' => $treatments
			]
		);
		
		return view (
			'layouts/wizard',
			[ 
				'embayment'=>$embayment,
				'subembayments'=>$subembayments,
				'goal'=>$total_goal,
				'treatments'=>$treatments,
				'progress'=>$progress,
				'remaining'=>$remaining
			]
		);

	}

	/**
	 * Test page to show all Nitrogen values for Embayment
	 *
	 * @return void
	 * @author 
	 **/
	
	// TODO: Either use or abandon for another test
	public function test($id)
	{
		$embayment = Embayment::find($id);
		$subembayments = DB::select('exec dbo.GET_SubembaymentNitrogen ' . $id);
		$nitrogen = DB::select('exec dbo.GET_EmbaymentNitrogen ' . $id);
		
		JavaScript::put (
			['nitrogen' => $nitrogen[0] ]
		);
		return view('layouts/test', ['embayment'=>$embayment, 'subembayments'=>$subembayments]);
	}

	// Associate parcels within custom polygon geometry with a given treatment and scenario
	public function getParcelsWithinPoly(Request $data)
	{
		$data = $data->all();
		$treatment_id = $data['treatment'];
		$poly = $data['polystring'];
		$scenarioid = session()->get('scenarioid');
		$scenario = Scenario::find($scenarioid);
		$embay_id = $scenario->AreaID;
		$parcels = DB::select('exec dbo.GETpointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treatment_id . ', \'' . $poly . '\'');	

		if ($parcels) {
			$poly_nitrogen = $parcels[0]->Septic;
		}
		
		else {
			$parcels = 0;
			$poly_nitrogen = 0;
		}
		
		JavaScript::put (
			[ 'poly_nitrogen' => $parcels ]
		);

		return $parcels;
	}
}
