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
				$scenario = $user->scenarios()->create([
						'AreaID'=>$id, 'ScenarioPeriod'=>'Existing'
					]);

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
			else {
				Session::put('fert_applied', 0);
			}
		}

		$removed = 0;
		$n_load_orig = 0;
		$subembayments = DB::select('exec CapeCodMA.Calc_ScenarioNitrogen_Subembayments ' . $scenarioid);
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
		$remaining = $current - $total_goal;
		if($remaining < 0)
		{
			$remaining = 0;
		}
		$nitrogen = DB::select('exec CapeCodMA.GET_AreaNitrogen_Unattenuated ' . $id);

		$nitrogen_att = DB::select('exec CapeCodMA.GET_AreaNitrogen_attenuated ' . $id);
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
		

		return view('layouts/wizard', ['embayment'=>$embayment, 'subembayments'=>$subembayments, 
			// 'nitrogen_att'=>$nitrogen_att[0], 'nitrogen_unatt'=>$nitrogen[0], 
			'goal'=>$total_goal, 'treatments'=>$treatments, 'progress'=>$progress, 'remaining'=>$remaining]);

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
	public function getPolygon($treatment_id, $poly, $part2 = null)
	{
		if ($part2) 
		{
			// this means the poly string was too long to be sent as a single url parameter so we are going to concatenate the strings	
			$poly = $poly + $part2;
		}
		// DB::connection('sqlsrv')->statement('SET ANSI_NULLS, QUOTED_IDENTIFIER, CONCAT_NULL_YIELDS_NULL, ANSI_WARNINGS, ANSI_PADDING, NOCOUNT ON');
		$scenarioid = Session::get('scenarioid');
		$scenario = Scenario::find($scenarioid);
		$embay_id = $scenario->AreaID;


		$parcels = DB::select('exec CapeCodMA.GET_PointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treatment_id . ', \'' . $poly . '\'');

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


	/**
	 * Get Nitrogen Totals from a polygon string
	 *
	 * @return void
	 * @author 
	 **/
	public function getPolygon2(Request $data)
	{
		// dd($data);
		    // Log::info('Data received '.$data);
		$data = $data->all();
		// return $test;
		$treatment_id = $data['treatment'];
		$poly = $data['polystring'];
		// DB::connection('sqlsrv')->statement('SET ANSI_NULLS, QUOTED_IDENTIFIER, CONCAT_NULL_YIELDS_NULL, ANSI_WARNINGS, ANSI_PADDING, NOCOUNT ON');
		$scenarioid = Session::get('scenarioid');
		$scenario = Scenario::find($scenarioid);
		$embay_id = $scenario->AreaID;

		$query = 'exec CapeCodMA.GET_PointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treatment_id . ', \'' . $poly . '\'';
		Log::info($query);
		$parcels = DB::select('exec CapeCodMA.GET_PointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treatment_id . ', \'' . $poly . '\'');

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
