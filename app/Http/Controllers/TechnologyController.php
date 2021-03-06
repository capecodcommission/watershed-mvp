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
				'Treatment_UnitMetric'=>$tech->unit_metric, 
				'Treatment_Class'=>$tech->Technology_Sys_Type, 
				'treatment_icon'=>$tech->icon
			]
		);

		return $treatment;
	}

	// Remove geometry from session
	public function deleteSessionGeometry()
	{
		$sessionKeys = array_keys(session()->all());
		$terms = ['point','polyString'];
		
		// Loop through terms
		foreach ($terms as $term)
		{
			// Filter to goemetry session keys containing term
			$results = array_filter($sessionKeys, function ($key) use ($term) 
			{
				return strpos($key, $term) !== false;
			});
	
			// If geometry session keys exist, delete from session by key
			if ( !empty($results) )
			{
				foreach ($results as $result)
				{
					session()->forget($result);
				}
			}
		}

		return 1;
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
				return view('common/technology-collect-move', ['tech'=>$tech, 'type'=>$type]);
				break;	
			case 'PRB':
				return view('common/technology-collect-stay', ['tech'=>$tech, 'type'=>$type]);
				break;
			case 'In-Embayment':
				return view('common/technology-in-embayment', ['tech'=>$tech, 'type'=>$type]);
				break;
			default:
				return view('common/technology', ['tech'=>$tech, 'type'=>$type]);
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

		// New treatment
		if (!$treat_id)
		{
			// Retrieve / associate all parcels within embayment with user's scenario 
			$tech = Technology::find($techId);
			$treatment = $this->createTreatment($scenarioid, $tech);
			$parcels = DB::select('exec dbo.GETpointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treatment->TreatmentID . ', \'embayment\'');
			return $this->handleManagementApply($treatment->TreatmentID, $rate, $techId);
		}
		// Existing treatment
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
		$this->deleteSessionGeometry();

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
		// Update existing treatment with new rate and/or geometry
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

	// Handle activation of In-Embayment stored procedure
	public function handleInEmbayApply($treat_id, $rate, $units, $total_parcels, $pointCoords)
	{
		$updated = DB::select("exec dbo.CALCapplyTreatmentEmbayment $treat_id, $rate, $units, $total_parcels, '$pointCoords'");
		$this->updateNitrogenRemoved();
		$this->deleteSessionGeometry();

		return $treat_id;
	}

	// Associate parcels within selected subembayment
	public function handleInEmbayParcelAssoc($embay_id, $scenarioid, $treat_id, $pointCoords)
	{
		$total_parcels = 0;

		$subembayment = DB::select("exec dbo.GETsubembaymentFromPoint @pointCoords='$pointCoords', @embay_id=$embay_id");
		$parcelsByTown = DB::select('exec dbo.GETpointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treat_id . ', \'subembayment\'' . ', ' . $subembayment[0]->SUBEM_ID);
		foreach ($parcelsByTown as $town) 
		{
			$total_parcels += $town->NumParcels;
		}

		return $total_parcels;
	}

	// Get subembayment name and ID for modal display
	public function getSubembayment($pointCoords)
	{
		$scenarioid = session('scenarioid');
		$scenario = Scenario::find($scenarioid);
		$embay_id = $scenario->AreaID;
		$subembayment = DB::select("exec dbo.GETsubembaymentFromPoint @pointCoords='$pointCoords', @embay_id=$embay_id");
		return $subembayment;
	}

	// Apply In-Embayment technology using passed rate. units, and technology id
	public function ApplyTreatment_Embayment($rate, $units, $techId, $treat_id=null)
	{
		// Retrieve Scenario and Technology objects from ORM
		$scenarioid = session('scenarioid');
		$scenario = Scenario::find($scenarioid);
		$embay_id = $scenario->AreaID;
		$tech = Technology::find($techId);

		// Handle new treatment
		if (!$treat_id) 
		{
			if ( session()->has('pointX') && session()->has('pointY') )
			{
				// Retrieve XY coordinates from session
				$x = session()->get('pointX');
				$y = session()->get('pointY');
				$pointCoords = $x . ' ' . $y;

				// Create new treatment
				$treatment = $this->createTreatment($scenarioid, $tech);

				// Associate parcels within selected subembayment with treatment and scenario
				$total_parcels = $this->handleInEmbayParcelAssoc($embay_id, $scenarioid, $treatment->TreatmentID, $pointCoords);
				return $this->handleInEmbayApply($treatment->TreatmentID, $rate, $units, $total_parcels, $pointCoords);
			}
		}
		// Reassociate parcels within existing or newly selected subembayment
		// Update existing treatment with new rate and/or geometry
		else
		{
			if ( session()->has('pointX') && session()->has('pointY') )
			{
				$x = session()->get('pointX');
				$y = session()->get('pointY');
				$pointCoords = $x . ' ' . $y;
				$del = DB::select('exec dbo.DELparcels '. $treat_id);
				$total_parcels = $this->handleInEmbayParcelAssoc($embay_id, $scenarioid, $treat_id, $pointCoords);
				return $this->handleInEmbayApply($treat_id, $rate, $units, $total_parcels, $pointCoords);
			}
			else
			{
				$treatment = Treatment::find($treat_id);
				$pointCoords = $treatment->POLY_STRING;
				$del = DB::select('exec dbo.DELparcels '. $treat_id);
				$total_parcels = $this->handleInEmbayParcelAssoc($embay_id, $scenarioid, $treat_id, $pointCoords);
				return $this->handleInEmbayApply($treat_id, $rate, $units, $total_parcels, $pointCoords);
			}
		}
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
	public function handleCollectStayApply($treat_id, $rate, $techType, $units) 
	{
		$septicTypes = ['Waste Reduction Toilets', 'On-Site Treatment Systems', 'Treatment Systems'];
		$groundwaterTypes = ['Green Infrastructure', 'Innovative and Resource-Management Technologies', 'System Alterations'];

		if ( in_array($techType, $septicTypes) )
		{
			$updated = DB::select("exec dbo.CALCapplyTreatmentSeptic $treat_id, $rate");
		}
		
		if ( in_array($techType, $groundwaterTypes) )
		{
			
			$updated = DB::select("exec dbo.CALCapplyTreatmentGroundwater $treat_id, $rate, $units");	
		}

		$this->updateNitrogenRemoved();
		$this->deleteSessionGeometry();
		return $treat_id;
	}

	// Handle nitrogen move using either new or existing point coordinates for either new or existing treatment
	public function handleCollectMoveDump($scenarioid, $treat_id)
	{
		// New treatment with new move site
		if ( session()->has('pointX') && session()->has('pointY') )
		{
			$x = session()->get('pointX');
			$y = session()->get('pointY');
			return $move = DB::select("exec dbo.CALCmoveNitrogen '$x $y', $treat_id, $scenarioid");
		}

		// Existing treatment with either updated or existing move site
		else
		{
			$dumpTreatment = Treatment::where('Parent_TreatmentId', $treat_id)->first();
			if ($dumpTreatment)
			{
				$dumpTreatmentID = $dumpTreatment->TreatmentID;
				if ( session()->has('pointX_' . $dumpTreatmentID) && session()->has('pointY_' . $dumpTreatmentID) )
				{
					$x = session()->get('pointX_' . $dumpTreatmentID);
					$y = session()->get('pointY_' . $dumpTreatmentID);
					$dumpTreatment->delete();
					$move = DB::select("exec dbo.CALCmoveNitrogen '$x $y', $treat_id, $scenarioid");
					return $this->deleteSessionGeometry();
				}
				else
				{
					$originalXY = $dumpTreatment->POLY_STRING;
					$dumpTreatment->delete();
					$move = DB::select("exec dbo.CALCmoveNitrogen '$originalXY', $treat_id, $scenarioid");
					return $this->deleteSessionGeometry();
				}
			}
		}
	}


	// Apply CollectStay technologies based on its system type and technology type
	// Update nitrogen load and delete session geometry post-treatment
	public function ApplyTreatment_CollectStay($rate, $techId, $units=null, $treat_id=null)
	{
		// Retrieve and parse Scenario and Technology properties 
		$scenarioid = session()->get('scenarioid');
		$scenario = Scenario::find($scenarioid);
		$tech = Technology::find($techId);
		$techType = $tech->technology_type;
		$embay_id = $scenario->AreaID;

		// Apply new Treatment with new geometry
		if ( !$treat_id )
		{
			if ( session()->has('polyString') ) 
			{
				$polyString = session()->get('polyString');
				$treatment = $this->createTreatment($scenarioid, $tech);
				$parcels = DB::select('exec dbo.GETpointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treatment->TreatmentID . ', \'' . $polyString . '\'');
				$this->handleCollectMoveDump($scenarioid, $treatment->TreatmentID);
				return $this->handleCollectStayApply($treatment->TreatmentID, $rate, $techType, $units);
			}
		}

		// Update existing treatment with new rate and/or new geometry
		else
		{
			// New geometry
			if ( session()->has('polyString_' . $treat_id) ) 
			{
				$polyString = session()->get('polyString_' . $treat_id);
				$del = DB::select('exec dbo.DELparcels '. $treat_id);
				$parcels = DB::select('exec dbo.GETpointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treat_id . ', \'' . $polyString . '\'');
				$this->handleCollectMoveDump($scenarioid, $treat_id);
				return $this->handleCollectStayApply($treat_id, $rate, $techType, $units);
			}

			// Existing geometry
			else
			{
				$treatment = Treatment::find($treat_id);
				$originalPoly = $treatment->POLY_STRING;
				$del = DB::select('exec dbo.DELparcels '. $treat_id);
				$parcels = DB::select('exec dbo.GETpointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treat_id . ', \'' . $originalPoly . '\'');
				$this->handleCollectMoveDump($scenarioid, $treat_id);
				return $this->handleCollectStayApply($treat_id, $rate, $techType, $units);
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
		$treatment = Treatment::find($treatmentId);
		$parentTreatmentId = $treatment->Parent_TreatmentId;

		if ($parentTreatmentId)
		{
			$parentTreatment = Treatment::find($parentTreatmentId);
			$techId = $parentTreatment->TreatmentType_ID;
		} 
		else 
		{
			$techId = $treatment->TreatmentType_ID;
		}

		// Save geometry to session based on type
		switch ($polyType)
		{
			case 'polygon':
				$isInEmbay = app('App\Http\Controllers\MapController')->checkGeometryInEmbay('polygon', $geoObj, $techId, $treatmentId);
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
				$isInEmbay = app('App\Http\Controllers\MapController')->checkGeometryInEmbay('point', $polyString, $techId);
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
				$dumpTreatment = Treatment::where('Parent_TreatmentId', $treat_id)->first();
				if (!$dumpTreatment)
				{
					$dumpTreatment = new \stdClass();
					$dumpTreatment->TreatmentID = 0;	
				}
				return view('common/technology-collect-move-edit', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type, 'dumpTreatment'=>$dumpTreatment]);
				break;
			case 'PRB':
				return view('common/technology-collect-stay-edit', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;
			case 'In-Embayment':
				return view('common/technology-in-embayment-edit', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;
			default:
				return view('common/technology', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				break;
		}
	}

	// Handle treatment reapplication by type
	public function update($treat_id, $treatmentValue, $units=null)
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
				$this->ApplyTreatment_CollectStay($treatmentValue, $treatment->TreatmentType_ID, $units, $treat_id);
				break;
			case 'CollectStay':
				$this->ApplyTreatment_CollectStay($treatmentValue, $treatment->TreatmentType_ID, $units, $treat_id);
				break;
			case 'PRB':
				$this->ApplyTreatment_CollectStay($treatmentValue, $treatment->TreatmentType_ID, $units, $treat_id);
				break;	
			case 'In-Embayment':
				$this->ApplyTreatment_Embayment($treatmentValue, $units, $treatment->TreatmentType_ID, $treat_id);
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

		$this->deleteSessionGeometry();

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