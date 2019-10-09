<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Treatment;
use App\Technology;
use App\Scenario;
use Log;
use View;

class TechnologyController extends Controller
{

	// Obtain current treatment object using treatment id
	public function getTreatment($id)
	{
		$treatment = Treatment::find($id);

		if ($treatment)
		{
			return [$treatment];
		}
		else
		{
			return 0;
		}
	}

	// Obtain current treatments array using scenario
	public function getTreatments()
	{
		$scenarioid = session()->get('scenarioid');
		$scenario = Scenario::find($scenarioid);
		$treatments = $scenario->treatments;

		if ($treatments)
		{
			return $treatments;
		}
		else
		{
			return 0;
		}
	}

	// Retrieve scenario id from the session, find the scenario, use it to get the treatments, initialize n_removed_some
	// integer, loop through treatments and take the sum of the Nload_Reduction from dbo.Treatment_Wiz & update the session
	// n_removed with the value
	// This function is to be used anywhere n_removed is updated (applying, updating, deleting technologies)
	public function updateNitrogenRemoved() 
	{
		$scenarioid = session()->get('scenarioid');
		$scenario = Scenario::find($scenarioid);
		$treatments = $scenario->treatments;
		$n_removed_sum = 0;
		
		foreach ($treatments as $treatment) {
			$n_removed_sum += $treatment->Nload_Reduction;
		}
		session(['n_removed' => $n_removed_sum]);
	}

	// Create / associate selected technology with scenario
	public function createTreatment($scenarioid, $tech)
	{
		$treatment = Treatment::create(
			[
				'ScenarioID' => $scenarioid, 
				'TreatmentType_ID'=>$tech->technology_id, 
				'TreatmentType_Name'=>substr($tech->technology_strategy, 0, 50), 
				'Treatment_UnitMetric'=>$tech->Unit_Metric, 
				'Treatment_Class'=>$tech->Technology_Sys_Type, 
				'treatment_icon'=>$tech->icon
			]
		);

		return $treatment;
	}

	// Remove geometry from session post-application of treatment
	public function deleteSessionGeometry ($treatmentId = null)
	{
		if ( session()->has('pointX') && session()->has('pointY') ) 
		{
			session()->forget('pointX');
			session()->forget('pointY');
		}

		if ( session()->has('polyString') )
		{
			session()->forget('polyString');
		}

		if ( session()->has('pointX_' . $treatmentId) && session()->has('pointY_' . $treatmentId) ) 
		{
			session()->forget('pointX_' . $treatmentId);
			session()->forget('pointY_' . $treatmentId);
		}

		if ( session()->has('polyString_' . $treatmentId) )
		{
			session()->forget('polyString_' . $treatmentId);
		}

		return 1;
	}

	// Retrieve technolgy-related data from tech matrix
	public function getTech($id)
	{
		$tech = DB::table('dbo.v_Technology_Matrix')
			->select(
				'technology_id',
				'Unit_Metric',
				'Technology_Sys_Type',
				'Technology_Strategy',
				'TM_ID as id',
				'icon',
				'Nutri_Reduc_N_High_ppm',
				'Nutri_Reduc_N_Low_ppm',
				'Nutri_Reduc_N_Low',
				'Nutri_Reduc_N_High'
			)
			->where('TM_ID', $id)
			->first();

		return $tech;
	}

	// Retrieve and initialize selected technology data
	// Pass technology data to respective blade based on type
	public function associateTech($id)
	{
		// Obtain technology object, create mock Treatment object for legacy blades through the new modal
		$tech = Technology::find($id);
		$type = $tech->Technology_Sys_Type;
		$treatment = new \stdClass();
		$treatment->TreatmentID = 0;
		

		// Show relevant technology blade, pass retrieved technology data to blade
		switch ($type) {
			case 'Management':
				return View('common/technology-management', ['tech'=>$tech, 'type'=>$type]);
				break;
			case 'Stormwater':
				return view('common/technology-stormwater-non-management', ['tech'=>$tech, 'type'=>$type]);
				break;
			case 'CollectStay':
				return view('common/technology-collect-stay', ['tech'=>$tech, 'type'=>$type]);
				break;
			case 'CollectMove':
				return view('common/technology-collection', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;	
			case 'PRB':
				return view('common/technology-groundwater', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;
			case 'In-Embayment':
				return view('common/technology-embayment', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;
			default:
				return view('common/technology', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;
		}
	}

	// Apply Fertilizer and Stormwater management technologies based on type
	public function ApplyTreatment_Percent($rate, $type, $techId)
	{
		// Retrieve scenario id and removed nitrogen global variables, passed rate from user input percent slider on popdown
		$scenarioid = session('scenarioid');
		$rate = round($rate, 2);
		$n_removed = session('n_removed');

		// Retrieve Scenario data from SQL, parse embayment id
		$scenario = Scenario::find($scenarioid);
		$embay_id = $scenario->AreaID;

		// Set applied session variable to disable icon clickability
		session([$type . '_applied' => 1]);

		// Create treatment based on selected Technology ID
		$tech = Technology::find($techId);
		$treatment = $this->createTreatment($scenarioid, $tech);

		// Retrieve / associate all parcels within embayment with user's scenario 
		$parcels = DB::select('exec dbo.GETpointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treatment->TreatmentID . ', \'embayment\'');

		// Apply treatment with passed function parameters
		// Run the updateNitrogenRemoved public function to update the n_removed session variable
		$updated = DB::select('exec dbo.CALCapplyTreatmentPercent ' . $treatment->TreatmentID . ', ' . $rate . ', ' . $type);
		$this->updateNitrogenRemoved();

		return $treatment->TreatmentID;
	}



	// Apply non-management Stormwater technology
	public function ApplyTreatment_Storm($rate, $techId)
	{
		// Retrieve scenario id, removed nitrogen, and point XY coordinates from session
		$scenarioid = session('scenarioid');
		$n_removed = session('n_removed');
		$x = session()->get('pointX');
		$y = session()->get('pointY');
		$tech = Technology::find($techId);

		// Create new treatment
		$treatment = $this->createTreatment($scenarioid, $tech);

		// Associate parcel with treatment aand scenario
		$point = DB::select("exec dbo.UPDcreditSubembayment @x='$x', @y='$y', @treatment=$treatment->TreatmentID");

		// Treat parcel using parameterized stored proc
		$updated = DB::select('exec dbo.CALCapplyTreatmentStorm ' . $treatment->TreatmentID . ', ' . $rate);
		$this->updateNitrogenRemoved();
		$this->deleteSessionGeometry($treatment->TreatmentID);

		return $treatment->TreatmentID;
	}

	/**
	 * Apply Embayment Treatment
	 *
	 * @return void
	 * @author 
	 **/
	// TODO: Rename to ApplyTreatment_Subembayment
	public function ApplyTreatment_Embayment($treat_id, $rate, $units, $subemid = null)
	{
		$scenarioid = session('scenarioid');
		$n_parcels = 0;

		if ($subemid) 
		{
			$parcels = DB::select('exec dbo.GETpointsFromPolygon ' . $subemid . ', ' . $scenarioid . ', ' . $treat_id . ', \'subembayment\'');
			session(['subemid' => $subemid]);
		} 

		foreach ($parcels as $parcel) 
		{
			$n_parcels += $parcel->NumParcels;
		}

		$updated = DB::select('exec [dbo].[CALC_ApplyTreatment_Embayment1] ' . $treat_id . ', ' . $rate . ', ' . $units . ', ' . $n_parcels);
	}


	/**
	 * Apply Groundwater Treatment
	 *
	 * @return void
	 * @author 
	 **/
	public function ApplyTreatment_Groundwater($treat_id, $rate, $units)
	{
		$updated = DB::select('exec [dbo].[CALCapplyTreatmentGroundwater] ' . $treat_id . ', ' . $rate);
	}


	// Apply CollectStay technologies passing the technology id and selected reduction rate
	// Update nitrogen load post-treatment
	public function ApplyTreatment_CollectStay($rate, $techId)
	{
		// Retrieve Scenario and technology, create treatment
		// Parse technology properties to conditionally activate septic or groundwater treatment application
		$scenarioid = session()->get('scenarioid');
		$scenario = Scenario::find($scenarioid);
		$tech = Technology::find($techId);
		$techType = $tech->technology_type;
		$treatment = $this->createTreatment($scenarioid, $tech);
		$embay_id = $scenario->AreaID;
		$polyString = session()->get('polyString');
		$septicTypes = ['Waste Reduction Toilets','On-Site Treatment Systems'];
		$groundwaterTypes = ['Green Infrastructure', 'Innovative and Resource-Management Technologies'];

		// Retrieve / associate all parcels within custom polygon with user's scenario 
		// Apply treatment to parcels with associated polygon
		$parcels = DB::select('exec dbo.GETpointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treatment->TreatmentID . ', \'' . $polyString . '\'');

		if ( in_array($techType, $septicTypes) )
		{
			$updated = DB::select('exec dbo.CALCapplyTreatmentSeptic ' . $treatment->TreatmentID . ', ' . $rate );
		}
		
		if ( in_array($techType, $groundwaterTypes) )
		{
			$updated = DB::select('exec dbo.CALCapplyTreatmentGroundwater ' . $treatment->TreatmentID . ', ' . $rate);
		}

		$this->updateNitrogenRemoved();
		$this->deleteSessionGeometry($treatment->TreatmentID);

		return $treatment->TreatmentID;
	}

	/**
	 * Based on the type of treatment, use the polygon to determine the Nitrogen being treated
	 *
	 * @return void
	 * @author 
	 **/
	public function getPolygon($type, $treatment_id, $poly)
	{
		$scenarioid = session('scenarioid');
		dd($scenarioid);
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

		$poly_nitrogen = $parcels[0]->Septic;

		JavaScript::put(
			[ 'poly_nitrogen' => $parcels ]
		);


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
	}


	// Save edited point or polygon geometry to session
	public function updateGeometry(Request $data)
	{
		// Retrieve POST data
		$data = $data->all();
		$treatmentId = $data['treatment'];
		$polyType = $data['geoType'];
		$geoObj = $data['geoObj'];

		// Save geometry to session based on type
		switch ($polyType)
		{
			case 'polygon':
				$isInEmbay = app('App\Http\Controllers\MapController')->checkGeometryInEmbay('polygon', $geoObj);
				if ($isInEmbay)
				{
					session(['polyString_' . $treatmentId => $geoObj]);
					return 1;
				}
				else
				{
					return 0;
				}
				break;

			case 'point':
				$x = $geoObj[0];
				$y = $geoObj[1];
				$polyString = $x . ' ' . $y;
				$isInEmbay = app('App\Http\Controllers\MapController')->checkGeometryInEmbay('point', $polyString);
				if ($isInEmbay)
				{
					session(['pointX_' . $treatmentId => $x]);
					session(['pointY_' . $treatmentId => $y]);
					return 1;
				}
				else
				{
					return 0;
				}
				break;
		} 
	}

	// Disassociate and delete selected technology data from user's scenario
	public function cancel($treat_id, $type = NULL)
	{
		$del = DB::select('exec dbo.DELtreatment '. $treat_id);
		
		// Reset global variables to handle fert/storm clickability
		if ($type == 'fert') {
			session(['fert_applied' => 0]);
		}
		if ($type == 'storm') {
			session(['storm_applied' => 0]);
		}
		return 1;
	}

	// Return html of relevant tech-edit blade based on various tech-matrix id's
	public function edit($treat_id)
	{
		// Obtain treatment and tech objects using relevant id's
		$treatment = Treatment::find($treat_id);
		$tech = Technology::find($treatment->TreatmentType_ID);
		$type = $tech->Technology_Sys_Type;
		$subType = $tech->technology_type;
		$septicTypes = ['Waste Reduction Toilets','On-Site Treatment Systems'];
		$groundwaterTypes = ['Green Infrastructure', 'Innovative and Resource-Management Technologies'];
		
		// Switch and load edit blade based on Technology System Type
		switch ($type) 
		{
			case 'Management':
				return view('common/technology-management-edit', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;
			case 'Stormwater':
				return view('common/technology-stormwater-non-management-edit', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;
			case 'CollectStay':
				if ( in_array($subType,$septicTypes) )
				{
					return view('common/technology-septic-edit', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				}
				if ( in_array($subType,$groundwaterTypes) )
				{
					return view('common/technology-groundwater-edit', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				}
				break;
			case 'CollectMove':
				return view('common/technology-collection-edit', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;
			case 'PRB':
				return view('common/technology-groundwater-edit', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;
			case 'In-Embayment':
				return view('common/technology-embayment-edit', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;
			default:
				return view('common/technology', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;
		}
	}

	// Handle treatment reapplication by type
	public function update($type, $treat_id, $treatmentValue, $units=null, $subemid=null)
	{
		// Trigger parameterized stored proc based on passed type
		switch ($type) 
		{
			// Management
			case 'management':
				// Trigger fert or storm by technology id from treatment object
				$treatment = Treatment::find($treat_id);
				switch($treatment->TreatmentType_ID)
				{
					case 400:
						$updated = DB::select('exec dbo.CALCapplyTreatmentPercent ' . $treat_id . ', ' . $treatmentValue . ', ' . 'fert');
						break;

					case 401:
						$updated = DB::select('exec dbo.CALCapplyTreatmentPercent ' . $treat_id . ', ' . $treatmentValue . ', ' . 'storm');
						break;
				}
				return $this->updateNitrogenRemoved();
				break;

			// Stormwater non-management
			case 'storm':
				// Check for geometry edit
				// Reassociate and reapply treatment to parcels
				if ( session()->has('pointX_' . $treat_id) && session()->has('pointY_' . $treat_id) ) 
				{
					$del = DB::select('exec dbo.DELparcels '. $treat_id);
					$x = session()->get('pointX_' . $treat_id);
					$y = session()->get('pointY_' . $treat_id);
					$point = DB::select("exec dbo.UPDcreditSubembayment @x='$x', @y='$y', @treatment=$treat_id");
					$updated = DB::select('exec dbo.CALCapplyTreatmentStorm ' . $treat_id . ', ' . $treatmentValue);
					$this->deleteSessionGeometry($treat_id);
				}
				// Continue as normal
				else
				{
					$updated = DB::select('exec dbo.CALCapplyTreatmentStorm ' . $treat_id . ', ' . $treatmentValue );
				}
				$this->updateNitrogenRemoved();
				return $treat_id;
				break;
			// CollectMove (first row)
			case 'collect':
				$updated = DB::select('exec dbo.CALCapplyTreatmentSeptic '. $treat_id . ', '. $treatmentValue);
				return $this->updateNitrogenRemoved();
				break;
			// CollectStay (second row)
			case 'collectStay':
				$treatment = Treatment::find($treat_id);
				$tech = Technology::find($treatment->TreatmentType_ID);
				$subType = $tech->technology_type;
				$septicTypes = ['Waste Reduction Toilets','On-Site Treatment Systems'];
				$groundwaterTypes = ['Green Infrastructure', 'Innovative and Resource-Management Technologies'];
				// Check for geometry edit
				if ( session()->has('polyString_' . $treat_id) )
				{
					// Reassociate and reapply treatment to parcels
					$del = DB::select('exec dbo.DELparcels '. $treat_id);
					$scenarioId = session()->get('scenarioid');
					$scenario = Scenario::find($scenarioId);
					$embay_id = $scenario->AreaID;
					$polyString = session()->get('polyString_' . $treat_id);
					$parcels = DB::select('exec dbo.GETpointsFromPolygon ' . $embay_id . ', ' . $scenarioId . ', ' . $treat_id . ', \'' . $polyString . '\'');
					if ( in_array($subType, $septicTypes) )
					{
						$updated = DB::select('exec dbo.CALCapplyTreatmentSeptic ' . $treat_id . ', ' . $treatmentValue );
					}
					if ( in_array($subType, $groundwaterTypes) )
					{
						$updated = DB::select('exec dbo.CALCapplyTreatmentGroundwater ' . $treat_id . ', ' . $treatmentValue);
					}
					
					$this->deleteSessionGeometry($treat_id);
				}
				else
				{
					if ( in_array($subType, $septicTypes) )
					{
						$updated = DB::select('exec dbo.CALCapplyTreatmentSeptic ' . $treat_id . ', ' . $treatmentValue );
					}
					if ( in_array($subType, $groundwaterTypes) )
					{
						$updated = DB::select('exec dbo.CALCapplyTreatmentGroundwater ' . $treat_id . ', ' . $treatmentValue);
					}
				}
				$this->updateNitrogenRemoved();
				return $treat_id;
				break;
			// Groundwater
			case 'groundwater':
				$updated = DB::select('exec dbo.CALC_ApplyTreatment_Groundwater1 '. $treat_id . ', '. $treatmentValue . ', ' . $units);
				return $this->updateNitrogenRemoved();
				break;	
			// Embayment
			case 'embay':
				// Retrieve scenario id and subembayment id from session, initialize nitrogen load and parcel totals
				// TODO: return $this->updateNitrogenRemoved();
				$scenarioid = session('scenarioid');
				$subemid = session('subemid');
				$n_total = 0;
				$n_parcels = 0;

				// Query and total number of parcels treated from wiz_treatment_towns
				// Trigger parameterized stored proc using function params and number of parcels
				$parcels = DB::table("dbo.wiz_treatment_towns")->select("wtt_tot_parcels")->where("wtt_treatment_id", "=", $treat_id)->get();
				foreach ($parcels as $parcel) 
				{
					$n_parcels += $parcel->wtt_tot_parcels;
				}
				$updated = DB::select('exec dbo.CALC_ApplyTreatment_Embayment1 ' . $treat_id . ', ' . $treatmentValue . ', ' . $units . ', ' . $n_parcels);
				$n_removed += $updated[0]->removed;
				session(['n_removed' => $n_removed]);
				break;
			default:
				break;
		}
	}

	/**
	 * User wants to delete a treatment for this scenario
	 *
	 * @return void
	 * @author 
	 **/
	public function delete($treat_id, $type = NULL)
	{
		$del = DB::select('exec dbo.DELtreatment '. $treat_id);

		$this->deleteSessionGeometry($treat_id);

		// Reset global variables to handle fert/storm clickability
		if ($type == 'fert') {
			session(['fert_applied' => 0]);
		}
		if ($type == 'storm') {
			session(['storm_applied' => 0]);
		}
		
		return $this->updateNitrogenRemoved();
	}
}