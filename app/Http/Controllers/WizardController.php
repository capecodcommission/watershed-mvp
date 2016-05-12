<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Embayment;
use DB;
use JavaScript;
use App\Treatment;

class WizardController extends Controller
{
	//
	public function start($id)
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
		
		// Need to create a new scenario or find existing one that the user is editing
		// Is user logged in?

		// Need to get list of Technologies (Tech_Matrix)

		return view('layouts/wizard', ['embayment'=>$embayment, 'subembayments'=>$subembayments]);

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
	public function getNitrogen($treatment_id, $poly)
	{
		// $nitrogen_totals = DB::select('exec CapeCodMA.GET_NitrogenFromPolygon \'' . $poly . '\'');
		// dd($nitrogen_totals[0]);

		$parcels = DB::select('exec CapeCodMA.GET_PointsFromPolygon ' . $treatment_id . ', \'' . $poly . '\'');
		// dd($parcels);
		// JavaScript::put([
		// 		'nitrogen' => $nitrogen_totals[0]
		// 	]);


		/**********************************************
		*	We need to get the total Nitrogen for the custom polygon that this technology will treat 
		*	(fertilizer, stormwater, septic, groundwater, etc.)
		*	and report that back to the technology pop-up. After the user adjusts the treatment settings
		*	we need to save that as "treated_nitrogen" and be able to attenuate it 
		*	If this is a collection & treat (sewer) then we will need to create a new treatment record with a parent_treatment_id so we 
		*	can store the N load and the destination point where it will be treated.
		*
		**********************************************/

		$treatment = Treatment::find($treatment_id);
		$treatment->POLY_STRING = $poly;
		$treatment->Custom_POLY = 1;
		$treatment->save();
		dd($treatment);
		$total_septic_nitrogen = 0;
		foreach ($parcels as $parcel) 
		{
			$total_septic_nitrogen += $parcel->wtp_nload_septic;
		}

		// return $parcels;
		return view ('layouts/test_septic', ['parcels'=>$parcels, 'total_septic_nitrogen'=>$total_septic_nitrogen]);
	}
	
}
