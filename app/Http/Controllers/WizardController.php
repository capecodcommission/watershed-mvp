<?php

namespace App\Http\Controllers;

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
	//
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
					$scenario = $user->scenarios()->create([
						'AreaID'=>$id, 'ScenarioPeriod'=>'Existing'
					]);
					// user selected a different embayment, need to create a new scenario 
					// $scenario = new Scenario;
					// $scenario->areaid = $id;
					// $scenario->save();
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
					// $scenarioid = DB::select('exec CapeCodMA.CreateScenario ' . $id);
					// $scenarioid = $scenarioid[0]->scenarioid;
				$scenario = $user->scenarios()->create([
						'AreaID'=>$id, 'ScenarioPeriod'=>'Existing'
					]);
					// $scenario = new Scenario;
					// $scenario->areaid = $id;
					// $scenario->save();

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
			// dd($scenarioid);
			Session::put('scenarioid', $scenarioid);
			// dd(session('scenarioid'));
			Session::save();
		}


		$scenario = Scenario::find($scenarioid);
		$treatments = $scenario->treatments;

		// Get the scenario's current progress
		// $removed = DB::select('exec CapeCodMA.CALC_ScenarioNitrogen ' . $scenarioid);
		// $removed = $removed[0]->N_Removed;
		// $current = $scenario->Nload_Existing - $removed;
		// if ($current > 0) {
		// 	$progress = round($scenario->Nload_Total_Target/$current * 100);
		// }
		// else
		// {
		// 	$progress = 100;
		// }

		$removed = 0;
		$n_load_orig = 0;
		$subembayments = DB::select('exec CapeCodMA.Calc_ScenarioNitrogen_Subembayments ' . $scenarioid);
		// $subembayments = DB::select('exec CapeCodMA.GET_SubembaymentNitrogen ' . $id);
		$total_goal = 0;
		foreach ($subembayments as $key) 
		{
			$n_load_orig += $key->n_load_att;
			$removed += $key->n_load_att_removed;
			$total_goal += $key->n_load_target;
		}
		$current = $n_load_orig - $removed;
		if ($current > 0) {
			$progress = round($total_goal/$current * 100);
		}
		else
		{
			$progress = 100;
		}


		$nitrogen = DB::select('exec CapeCodMA.GET_AreaNitrogen_Unattenuated ' . $id);
		$nitrogen_att = DB::select('exec CapeCodMA.GET_AreaNitrogen_attenuated ' . $id);
		// dd($nitrogen_att);
		JavaScript::put([
				'nitrogen_unatt' => $nitrogen[0],
				'nitrogen_att' => $nitrogen_att[0],
				'center_x'	=> $embayment->longitude,
				'center_y'	=> $embayment->latitude,
				'selectlayer' => $embayment->embay_id,
				'treatments' => $treatments
			]);
		

		return view('layouts/wizard', ['embayment'=>$embayment, 'subembayments'=>$subembayments, 
			// 'nitrogen_att'=>$nitrogen_att[0], 'nitrogen_unatt'=>$nitrogen[0], 
			'goal'=>$total_goal, 'treatments'=>$treatments, 'progress'=>$progress]);

	}

	/**
	 * Test page to show all Nitrogen values for Embayment
	 *
	 * @return void
	 * @author 
	 **/
	

	public function test($id)
	{
		$embayment = Embayment::find($id);
		// $subembayments = DB::table('CapeCodMA.SubEmbayments')
		// 	->select('SUBEM_NAME', 'SUBEM_DISP', 'Nload_Total', 'Total_Tar_Kg', 'MEP_Total_Tar_Kg')
		// 	->where('EMBAY_ID', $embayment->EMBAY_ID)->get();
		$subembayments = DB::select('exec CapeCodMA.GET_SubembaymentNitrogen ' . $id);
		$nitrogen = DB::select('exec CapeCodMA.GET_EmbaymentNitrogen ' . $id);

		 JavaScript::put([
				'nitrogen' => $nitrogen[0]
			]);

		return view('layouts/test', ['embayment'=>$embayment, 'subembayments'=>$subembayments]);
	}


	/**
	 * Get Nitrogen Totals from a polygon string
	 *
	 * @return void
	 * @author 
	 **/
	public function getPolygon($treatment_id, $poly)
	{
		DB::connection('sqlsrv')->statement('SET ANSI_NULLS, QUOTED_IDENTIFIER, CONCAT_NULL_YIELDS_NULL, ANSI_WARNINGS, ANSI_PADDING, NOCOUNT ON');
		$scenarioid = Session::get('scenarioid');
		$embay_id = Session::get('embay_id');

		$parcels = DB::select('exec CapeCodMA.GET_PointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treatment_id . ', \'' . $poly . '\'');

		$poly_nitrogen = $parcels[0]->Septic;

		JavaScript::put([
				'poly_nitrogen' => $parcels
			]);


		/**********************************************
		*	We need to get the total Nitrogen for the custom polygon that this technology will treat 
		*	(fertilizer, stormwater, septic, groundwater, etc.)
		*	and report that back to the technology pop-up. After the user adjusts the treatment settings
		*	we need to save that as "treated_nitrogen" and be able to attenuate it 
		*	If this is a collection & treat (sewer) then we will need to 
		*	create a new treatment record with a parent_treatment_id so we 
		*	can store the N load and the destination point where it will be treated.
		*
		**********************************************/

		// $treatment = Treatment::find($treatment_id);
		// $treatment->POLY_STRING = $poly;
		// $treatment->Custom_POLY = 1;
		// $treatment->save();
		// dd($treatment);
		// $total_septic_nitrogen = $parcels;
		// foreach ($parcels as $parcel) 
		// {
		// 	$total_septic_nitrogen += $parcel->wtp_nload_septic;
		// }

		return $parcels;
		// return view ('layouts/test_septic', ['parcels'=>$parcels, 'poly_nitrogen'=>$poly_nitrogen]);
	}




	
	
}
