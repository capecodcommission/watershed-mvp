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
	
	public function start($id, $scenarioid = null)
	{
		$user = Auth::user();
		$embayment = Embayment::find($id);
		
		// Need to create a new scenario or find existing one that the user is editing
		if(!$scenarioid)
		{
			if (session('scenarioid')) 
			{
				$scenarioid = Session::get('scenarioid');
				$scenario = Scenario::find($scenarioid);
				if ($scenario->AreaID == $id) 
				{
					// user is still working on the same scenario. 
				}
				else
				{
					$scenario = $user
						->scenarios()
						->create(
							[
								'AreaID'=>$id, 
								'ScenarioPeriod'=>'Existing', 
								'AreaName'=>$embayment->EMBAY_DISP
							]
						);
					// user selected a different embayment, need to create a new scenario 
					$scenarioid = $scenario->ScenarioID;		
					Session::put('scenarioid', $scenarioid);
					Session::put('n_removed', 0);
					Session::put('fert_applied', 0);
					Session::put('storm_applied', 0);
					Session::save();
				}
			}
			else
			{
				//  need to create a new scenario 
				$scenario = $user
					->scenarios()
					->create(
						[
							'AreaID'=>$id, 
							'ScenarioPeriod'=>'Existing', 
							'AreaName'=>$embayment->EMBAY_DISP
						]
					);

					$scenarioid = $scenario->ScenarioID;

					Session::put('scenarioid', $scenarioid);
					Session::put('n_removed', 0);
					Session::put('fert_applied', 0);
					Session::put('storm_applied', 0);
					Session::save();
			}
			
			Session::put('embay_id', $id);
			Session::save();
			
		}
		else
		{
			Session::put('scenarioid', $scenarioid);
			Session::save();
		}

		$scenario = Scenario::find($scenarioid);
		$treatments = $scenario->treatments;

		foreach ($treatments as $key) {
			if ($key->TreatmentType_Name == 'Fertilizer Management') {
				Session::put('fert_applied', 1);
			}
			else if ($key->TreatmentType_Name == 'Stormwater Management') {
				Session::put('storm_applied', 1);
			}
			else {
				Session::put('fert_applied', 0);
				Session::put('storm_applied', 0);
			}
		}
		// TODO: Determine if global values can be initially set and updated without initializing additoinal variables
		// Can we use existing global values? If so, use those, else init
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

		if ($total_goal == 0 || $n_load_orig == 0) 
		{
			$progress = 100;
		}
		else
		{
			$progress = round($total_goal/$current * 100);
		}

		if ($progress > 0 && $progress <= 100) {

			$progress;
		}
		else
		{
			
			$progress = 100;
		}
		$remaining = $current - $total_goal;
		if($remaining < 0)
		{
			$remaining = 0;
		}
		$nitrogen = DB::select('exec dbo.GET_AreaNitrogen_Unattenuated ' . $id);

		$nitrogen_att = DB::select('exec dbo.GET_AreaNitrogen_attenuated ' . $id);
		$nitrogen_att = [
			'Total_Att' => $n_load_orig
		];

		JavaScript::put([
			'nitrogen_unatt' => $nitrogen[0],
			'nitrogen_att' => $nitrogen_att,
			'center_x'	=> $embayment->longitude,
			'center_y'	=> $embayment->latitude,
			'selectlayer' => $embayment->embay_id,
			'treatments' => $treatments
		]);
		

		return view(
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


	// Associate parcels within user-definied polygon to user's scenario
	public function getPointsInCustomPolygon(Request $data)
	{
		$data = $data->all();
		$treatment_id = $data['treatment'];
		$poly = $data['polystring'];
		$scenarioid = Session::get('scenarioid');
		$scenario = Scenario::find($scenarioid);
		$embay_id = $scenario->AreaID;
		$parcels = DB::select('exec dbo.GET_PointsFromPolygon1 ' . $embay_id . ', ' . $scenarioid . ', ' . $treatment_id . ', \'' . $poly . '\'');	
		if ($parcels) {
			$poly_nitrogen = $parcels[0]->Septic;
		}
		else
		{
			$parcels = 0;
			$poly_nitrogen = 0;
		}
		JavaScript::put([
			'poly_nitrogen' => $parcels
		]);
		return $parcels;
	}
}
