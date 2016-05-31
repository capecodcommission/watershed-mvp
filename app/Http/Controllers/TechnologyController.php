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
		$tech = DB::table('dbo.Technology_Matrix')->select('*')->where('TM_ID', $id)->first();
		$scenarioid = session('scenarioid');
		// $tech = $tech[0];
		$treatment = Treatment::create(['ScenarioID' => $scenarioid, 'TreatmentType_ID'=>$tech->TM_ID]);
		// dd($treatment->TreatmentId);
		// dd($tech->Show_In_wMVP);
		if ($tech->Show_In_wMVP == 4) 
		{
			// this is embayment-wide, need to get the embayment_area and use that as the custom polygon for the Get_PointsfromPolygon
			$embay_id = session('embay_id');
			$embayment = Embayment::find($embay_id);
			$poly = $embayment->getArea();
			// dd($poly[0]->polygon);
			$parcels = DB::select('exec CapeCodMA.GET_PointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treatment->TreatmentId . ', \'embayment\'');
			// dd($parcels);
			// print_r(DB::getQueryLog());
			// $poly_nitrogen = $parcels[0]->Septic;

		// dd($parcels);
			// JavaScript::put([
			// 		'poly_nitrogen' => $parcels
			// 	]);

		}
		// create a new record in the treatment_wiz table for this scenario & technology
		// get the treatmentID back and use that for the treatment_parcels table

		switch ($type) {
			case 'fert':
				return view('common/technology-fertilizer', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;
			case 'storm':
				return view('common/technology-stormwater', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
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
		
		return $updated;

	}



	

}