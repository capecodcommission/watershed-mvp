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

	// Handle activation of either fert or storm management stored procedure
	public function handleManagementApply ($treat_id, $rate, $techId)
	{
		switch($techId)
		{
			case 400:
				session(['fert_applied' => 1]);
				$updated = DB::select('exec dbo.CALCapplyTreatmentPercent ' . $treat_id . ', ' . $rate . ', ' . 'fert');
				break;

			case 401:
				session(['storm_applied' => 1]);
				$updated = DB::select('exec dbo.CALCapplyTreatmentPercent ' . $treat_id . ', ' . $rate . ', ' . 'storm');
				break;
		}
		$this->updateNitrogenRemoved();
		return $treat_id;
	}

	// Apply Fertilizer and Stormwater management technologies based on type
	public function ApplyTreatment_Management($rate, $techId, $treat_id=null)
	{
		// Retrieve scenario id and removed nitrogen global variables, passed rate from user input percent slider on popdown
		$scenarioid = session('scenarioid');
		$scenario = Scenario::find($scenarioid);
		$embay_id = $scenario->AreaID;
		$rate = round($rate, 2);
		$n_removed = session('n_removed');

		if (!$treat_id)
		{
			// Retrieve / associate all parcels within embayment with user's scenario 
			$tech = Technology::find($techId);
			$treatment = $this->createTreatment($scenarioid, $tech);
			$parcels = DB::select('exec dbo.GETpointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treatment->TreatmentID . ', \'embayment\'');
			return $this->handleManagementApply($treatment->TreatmentID, $rate, $techId);
		}
		else
		{
			return $this->handleManagementApply($treat_id, $rate, $techId);
		}
	}

	// Handle activation of storm non-management stored procedure
	public function handleStormApply($treat_id, $rate)
	{
		$updated = DB::select('exec dbo.CALCapplyTreatmentStorm ' . $treat_id . ', ' . $rate);
		$this->updateNitrogenRemoved();
		$this->deleteSessionGeometry($treat_id);

		return $treat_id;
	}


	// Apply non-management Stormwater technology
	public function ApplyTreatment_Storm($rate, $techId, $treat_id=null)
	{
		// Retrieve scenario id, removed nitrogen, and point XY coordinates from session
		$scenarioid = session('scenarioid');
		$tech = Technology::find($techId);
		$n_removed = session('n_removed');

		// Handle new treatment
		if (!$treat_id) 
		{
			if ( session()->has('pointX') && session()->has('pointY') )
			{
				$x = session()->get('pointX');
				$y = session()->get('pointY');

				// Create new treatment
				$treatment = $this->createTreatment($scenarioid, $tech);

				// Associate parcel with treatment aand scenario
				$point = DB::select("exec dbo.UPDcreditSubembayment @x='$x', @y='$y', @treatment=$treatment->TreatmentID");

				return $this->handleStormApply($treatment->TreatmentID, $rate);
			}
		}
		// Update treatment with new rate and/or geometry
		else
		{
			if ( session()->has('pointX_' . $treat_id) && session()->has('pointY_' . $treat_id) )
			{
				$x = session()->get('pointX_' . $treat_id);
				$y = session()->get('pointY_' . $treat_id);
				$del = DB::select('exec dbo.DELparcels '. $treat_id);
				$point = DB::select("exec dbo.UPDcreditSubembayment @x='$x', @y='$y', @treatment=$treat_id");
				return $this->handleStormApply($treat_id, $rate);
			}
			else
			{
				return $this->handleStormApply($treat_id, $rate);
			}
		}
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


	// Handle activation of either septic or groundwater stored procedure based on technology type
	public function handleCollectStayApply($treat_id, $rate, $techType) 
	{
		$septicTypes = ['Waste Reduction Toilets','On-Site Treatment Systems'];
		$groundwaterTypes = ['Green Infrastructure', 'Innovative and Resource-Management Technologies'];

		if ( in_array($techType, $septicTypes) )
		{
			$updated = DB::select('exec dbo.CALCapplyTreatmentSeptic ' . $treat_id . ', ' . $rate );
		}
		
		if ( in_array($techType, $groundwaterTypes) )
		{
			$updated = DB::select('exec dbo.CALCapplyTreatmentGroundwater ' . $treat_id . ', ' . $rate);
		}

		$this->updateNitrogenRemoved();
		$this->deleteSessionGeometry($treat_id);
		return $treat_id;
	}


	// Apply CollectStay technologies based on its system type and technology type
	// Update nitrogen load and delete session geometry post-treatment
	public function ApplyTreatment_CollectStay($rate, $techId, $treat_id=null)
	{
		// Retrieve Scenario and technology, create treatment
		// Parse technology properties to conditionally activate septic or groundwater treatment application
		$scenarioid = session()->get('scenarioid');
		$scenario = Scenario::find($scenarioid);
		$tech = Technology::find($techId);
		$techType = $tech->technology_type;
		$embay_id = $scenario->AreaID;

		// New treatment
		if ( !$treat_id )
		{
			if ( session()->has('polyString') ) 
			{
				$polyString = session()->get('polyString');
				$treatment = $this->createTreatment($scenarioid, $tech);
				$parcels = DB::select('exec dbo.GETpointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treatment->TreatmentID . ', \'' . $polyString . '\'');
				return $this->handleCollectStayApply($treatment->TreatmentID, $rate, $techType);
			}
		}

		// Update treatment with new rate and/or new geometry
		else
		{
			if ( session()->has('polyString_' . $treat_id) ) 
			{
				$polyString = session()->get('polyString_' . $treat_id);
				$del = DB::select('exec dbo.DELparcels '. $treat_id);
				$parcels = DB::select('exec dbo.GETpointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treat_id . ', \'' . $polyString . '\'');
				return $this->handleCollectStayApply($treat_id, $rate, $techType);
			}
			else
			{
				return $this->handleCollectStayApply($treat_id, $rate, $techType);
			}
		}
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

	// Return html of relevant tech-edit blade based on treatment id
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
				return view('common/technology-collect-stay-edit', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
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
	public function update($treat_id, $treatmentValue, $units=null, $subemid=null)
	{
		$treatment = Treatment::find($treat_id);
		$tech = Technology::find($treatment->TreatmentType_ID);
		$type = $tech->Technology_Sys_Type;

		// Trigger parameterized stored proc based on passed type
		switch ($type) 
		{
			case 'Management':
				$this->ApplyTreatment_Management($treatmentValue, $treatment->TreatmentType_ID, $treat_id);
				break;
			case 'Stormwater':
				$this->ApplyTreatment_Storm($treatmentValue, $treatment->TreatmentType_ID, $treat_id);
				break;
			case 'CollectMove':
				$updated = DB::select('exec dbo.CALCapplyTreatmentSeptic '. $treat_id . ', '. $treatmentValue);
				return $this->updateNitrogenRemoved();
				break;
			case 'CollectStay':
				$this->ApplyTreatment_CollectStay($treatmentValue, $treatment->TreatmentType_ID, $treat_id);
				break;
			case 'PRB':
				$updated = DB::select('exec dbo.CALC_ApplyTreatment_Groundwater1 '. $treat_id . ', '. $treatmentValue . ', ' . $units);
				return $this->updateNitrogenRemoved();
				break;	
			case 'In-Embayment':
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