<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;
use App\Treatment;

class TechnologyController extends Controller
{

	/**
	 * Get the technology requested
	 *
	 * @return void
	 * @author 
	 **/
	public function get($id)
	{
		$tech = DB::table('dbo.Technology_Matrix')->select('*')->where('TM_ID', $id)->get();
		// dd($tech);
		// create a new record in the treatment_wiz table for this scenario & technology
		// get the treatmentID back and use that for the treatment_parcels table
		// for now we are using 9999 as the scenario id
		// $treatment = new Treatment;
		// $treatment->ScenarioID = 9999;
		// $treatment->TreatmentType_ID = $tech[0]->TM_ID;
		// // $treatment->CreateDate
		// $treatment->save();

		$treatment = Treatment::create(['ScenarioID' => 9999, 'TreatmentType_ID'=>$tech[0]->TM_ID]);
		// dd($treatment);
		return view('common/technology', ['tech'=>$tech[0], 'treatment'=>$treatment]);
	}

}