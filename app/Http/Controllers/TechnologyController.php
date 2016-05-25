<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;
use App\Treatment;
use Session;

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
		// create a new record in the treatment_wiz table for this scenario & technology
		// get the treatmentID back and use that for the treatment_parcels table
		// for now we are using 9999 as the scenario id
		$scenarioid = session('scenarioid');
		$treatment = Treatment::create(['ScenarioID' => $scenarioid, 'TreatmentType_ID'=>$tech[0]->TM_ID]);

		return view('common/technology', ['tech'=>$tech[0], 'treatment'=>$treatment]);
	}

	public function getCollection($id)
	{
		$scenarioid = session('scenarioid');
		// dd($scenarioid);
		$tech = DB::table('dbo.Technology_Matrix')->select('*')->where('TM_ID', $id)->get();
		
		$treatment = Treatment::create(['ScenarioID' => $scenarioid, 'TreatmentType_ID'=>$tech[0]->TM_ID]);

		return view('common/technology-collection', ['tech'=>$tech[0], 'treatment'=>$treatment]);
	}

}