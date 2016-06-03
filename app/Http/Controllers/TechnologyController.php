<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;
use App\Treatment;
use App\Embayment;
use Session;

class TechnologyController extends Controller
{

	/**
	 * Get the technology requested
	 *
	 * @return void
	 * @author 
	 **/
	public function get($type, $id)
	{
		// DB::enableQueryLog();

		   DB::connection('sqlsrv')->statement('SET ANSI_NULLS, QUOTED_IDENTIFIER, CONCAT_NULL_YIELDS_NULL, ANSI_WARNINGS, ANSI_PADDING ON');
		$tech = DB::table('dbo.Technology_Matrix')->select('*')->where('TM_ID', $id)->first();
		$scenarioid = session('scenarioid');
		// $tech = $tech[0];
		$treatment = Treatment::create(['ScenarioID' => $scenarioid, 'TreatmentType_ID'=>$tech->TM_ID]);

		if ($tech->Show_In_wMVP == 4) 
		{
			// this is embayment-wide, need to get the embayment_area and use that as the custom polygon for the Get_PointsfromPolygon
			$embay_id = session('embay_id');
			$parcels = DB::select('exec CapeCodMA.GET_PointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treatment->TreatmentId . ', \'embayment\'');
		}
		// create a new record in the treatment_wiz table for this scenario & technology
		// get the treatmentID back and use that for the treatment_parcels table

		switch ($type) {
			case 'fert':
				return view('common/technology-fertilizer', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;
			case 'storm':
				return view('common/technology-stormwater', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;
			case 'collect':
				return view('common/technology-collection', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;		
			case 'septic':
				return view('common/technology-septic', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;
			case 'groundwater':
				return view('common/technology-groundwater', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;
			case 'embayment':
				return view('common/technology-embayment', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;
			default:
				return view('common/technology', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;
		}
		

		
	}

	public function getCollection($id)
	{
		$scenarioid = session('scenarioid');
		// dd($scenarioid);
		$tech = DB::table('dbo.Technology_Matrix')->select('*')->where('TM_ID', $id)->get();
		
		$treatment = Treatment::create(['ScenarioID' => $scenarioid, 'TreatmentType_ID'=>$tech[0]->TM_ID]);

		return view('common/technology-collection', ['tech'=>$tech[0], 'treatment'=>$treatment]);
	}


	/**
	 * Apply Treatment
	 *
	 * @return void
	 * @author 
	 **/
	public function ApplyTreatment($treat_id, $rate, $type)
	{
		//$treatment = Treatment::find($treat_id);
		$scenarioid = session('scenarioid');
		// need to update the wiz_treatment_parcel table with the N removed
		if ($type == 'fert') {
			$updated = DB::select('exec [CapeCodMA].[CALC_ApplyTreatment_Fert] ' . $treat_id . ', ' . $rate );
		}
		else if ($type == 'storm') {
			$updated = DB::select('exec [CapeCodMA].[CALC_ApplyTreatment_Storm] ' . $treat_id . ', ' . $rate );
		}
		$n_removed = session('n_removed');
		$n_removed += $updated[0]->removed;
		Session::put('n_removed', $n_removed);
		
		return $n_removed;

	}



	/**
	 * Apply Septic Treatment
	 *
	 * @return void
	 * @author 
	 **/
	public function ApplyTreatment_Septic($treat_id, $rate, $type)
	{
		//$treatment = Treatment::find($treat_id);
		$scenarioid = session('scenarioid');
		// need to update the wiz_treatment_parcel table with the N removed
		if ($type == 'septic') {
			$updated = DB::select('exec [CapeCodMA].[CALC_ApplyTreatment_Septic] ' . $treat_id . ', ' . $rate );
		}
		$n_removed = session('n_removed');
		$n_removed += $updated[0]->removed;
		Session::put('n_removed', $n_removed);
		
		return $n_removed;

	}

	/**
	 * Based on the type of treatment, use the polygon to determine the Nitrogen being treated
	 *
	 * @return void
	 * @author 
	 **/
	public function getPolygon($type, $treatment_id, $poly)
	{
				   DB::connection('sqlsrv')->statement('SET ANSI_NULLS, QUOTED_IDENTIFIER, CONCAT_NULL_YIELDS_NULL, ANSI_WARNINGS, ANSI_PADDING ON');

		$scenarioid = session('scenarioid');
		$embay_id = session('embay_id');
		if ($type == 'septic') 
		{
			// we need to know how many toilets/parcels will be implemented
			$parcels = DB::select('exec CapeCodMA.GET_PointsFromPolygon_Septic ' . $embay_id . ', ' . $scenarioid . ', ' . $treatment_id . ', \'' . $poly . '\'');

			return $parcels[0];
		}
		else if ($type == 'collect') 
		{
			// we need to know how many toilets/parcels will be implemented
			$parcels = DB::select('exec CapeCodMA.GET_PointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treatment_id . ', \'' . $poly . '\'');
			dd($parcels);
			return $parcels[0];
		}

		// dd($embay_id, $scenarioid);
		// dd($parcels);
		$poly_nitrogen = $parcels[0]->Septic;

		// dd($parcels);
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

		return $poly_nitrogen;
		// return view ('layouts/test_septic', ['parcels'=>$parcels, 'poly_nitrogen'=>$poly_nitrogen]);		
	}

}