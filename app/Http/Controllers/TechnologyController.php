<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Treatment;
use App\Scenario;
use Log;
use View;

class TechnologyController extends Controller
{
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
				'TreatmentType_ID'=>$tech->Technology_ID, 
				'TreatmentType_Name'=>substr($tech->Technology_Strategy, 0, 50), 
				'Treatment_UnitMetric'=>$tech->Unit_Metric, 
				'Treatment_Class'=>$tech->Technology_Sys_Type, 
				'treatment_icon'=>$tech->Icon
			]
		);

		return $treatment;
	}

	// Retrieve technolgy-related data from tech matrix
	public function getTech($id)
	{
		$tech = DB::table('dbo.v_Technology_Matrix')
			->select(
				'Technology_ID',
				'Unit_Metric',
				'Technology_Sys_Type',
				'Show_In_wMVP',
				'Technology_Strategy',
				'id',
				'Icon',
				'Nutri_Reduc_N_High_ppm',
				'Nutri_Reduc_N_Low_ppm',
				'Nutri_Reduc_N_Low',
				'Nutri_Reduc_N_High',
				'Absolu_Reduc_perMetric_Low',
				'Absolu_Reduc_perMetric_High'
			)
			->where('TM_ID', $id)
			->first();

		return $tech;
	}

	// Retrieve and initialize selected technology data
	// Pass technology data to respective blade based on type
	public function associateTech($type, $id)
	{

		// Retrieve Scenario ID from Laravel session
		$scenarioid = session('scenarioid');
		
		// Retrieve technology data from tech_matrix based on passed TM_ID
		$tech = $this->getTech($id);

		// Technologies to be bypassed during treatment creation
		$idFilter = ['25' ];

		// Create and query Treatment through ORM
		if (!in_array($id, $idFilter))
		{
			$treatment = $this->createTreatment($scenarioid, $tech);
		}
		
		// If selected technology is management-based (embayment-wide)
		if ($tech->Show_In_wMVP == 4)
		{
			// Set fert/storm clickability
			if ($type == 'fert') {
				session(['fert_applied' => 1]);
			}
			if ($type == 'storm') {
				session(['storm_applied' => 1]);
			}
		}

		// return View::make('common/modal', ['tech'=>$tech, 'type'=>$type]);

		// Show relevant technology blade, pass retrieved technology data to blade
		switch ($type) {
			case 'fert':
				return view('common/technology-fertilizer', ['tech'=>$tech, 'type'=>$type]);
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


	// Apply Fertilizer and Stormwater management technologies based on type
	public function ApplyTreatment_Percent($treatment_id = NULL, $rate, $type)
	{
		// Retrieve scenario id and removed nitrogen global variables, passed rate from user input percent slider on popdown
		$scenarioid = session('scenarioid');
		$rate = round($rate, 2);
		$n_removed = session('n_removed');

		// Retrieve Scenario data from SQL, parse embayment id
		$scenario = Scenario::find($scenarioid);
		$embay_id = $scenario->AreaID;
		$techid = 0;

		// Set fert or storm tech id and applied
		if ($type == 'fert')
		{
			$techid = 25;	
			session(['fert_applied' => 1]);

			$tech = $this->getTech($techid);	
			$treatment = $this->createTreatment($scenarioid, $tech);	

			// Retrieve / associate all parcels within embayment with user's scenario 
			$parcels = DB::select('exec dbo.GETpointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treatment->TreatmentID . ', \'embayment\'');

			// Trigger stored proc with function parameters
			// Run the updateNitrogenRemoved public function to update the n_removed session variable
			$updated = DB::select('exec dbo.CALCapplyTreatmentPercent ' . $treatment->TreatmentID . ', ' . $rate . ', ' . $type);

			$this->updateNitrogenRemoved();

			return $treatment->TreatmentID;
		}
		if ($type == 'storm')
		{
			$techid = 26;	
			session(['storm_applied' => 1]);

			// Retrieve / associate all parcels within embayment with user's scenario 
			$parcels = DB::select('exec dbo.GETpointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treatment_id . ', \'embayment\'');

			// Trigger stored proc with function parameters
			// Run the updateNitrogenRemoved public function to update the n_removed session variable
			$updated = DB::select('exec dbo.CALCapplyTreatmentPercent ' . $treatment_id . ', ' . $rate . ', ' . $type);

			$this->updateNitrogenRemoved();

			return $treatment_id;
		}
	}



	// Apply non-management Stormwater technology
	public function ApplyTreatment_Storm($treat_id, $rate, $units = null, $location = null)
	{
		// Retrieve scenario id, removed nitrogen, and point XY coordinates from session
		$scenarioid = session('scenarioid');
		$n_removed = session('n_removed');
		$x = session()->get('pointX');
		$y = session()->get('pointY');

		// Associate parcel with treatment aand scenario
		$point = DB::select("exec dbo.UPDcreditSubembayment @x='$x', @y='$y', @treatment=$treat_id");

		// Treat parcel using parameterized stored proc, update n_remove session variable
		$updated = DB::select('exec dbo.CALCapplyTreatmentStorm ' . $treat_id . ', ' . $rate . ', ' . $units . ', ' . $point[0]->SUBEM_ID);
		return $this->updateNitrogenRemoved();
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
		$updated = DB::select('exec [dbo].[CALC_ApplyTreatment_Groundwater1] ' . $treat_id . ', ' . $rate . ', ' . $units);
	}


	// Apply Septic technology passing the treatment id and selected reduction rate
	// Update nitrogen load post-treatment
	public function ApplyTreatment_Septic($treat_id, $rate)
	{
		$updated = DB::select('exec dbo.CALCapplyTreatmentSeptic ' . $treat_id . ', ' . $rate );
		return $this->updateNitrogenRemoved();
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


	/**
	 * User has edited the polygon for a treatment
	 *
	 * @return void
	 * @author 
	 **/
	// TODO: Apply proper 'apply_treatment' stored proc and pass in type, eg. 'septic' or 'groundwater'
	public function updatePolygon(Request $data)
	{
		$data = $data->all();

		// TODO: Create switch to use proper stored proc based on $type
		
		// DB::connection('sqlsrv')->statement('SET ANSI_NULLS, QUOTED_IDENTIFIER, CONCAT_NULL_YIELDS_NULL, ANSI_WARNINGS, ANSI_PADDING ON');
		// stored procedure needs to update the parcels in wiz_treatment_parcel to match the new polygon
		// then update the polygon and parcel data/N total for this treatment in Treatment_Wiz

		// TODO: Modify update_treatment stored proc to delete/fill parcelmaster instead of wiz_treatment_parcels
		// TODO: Once update_treatment stored proc working, modify get_pointsfrompolygon to incorporate delete portion from update_treatment
		$query = 'exec dbo.UPD_TreatmentPolygon ' . $data['treatment'] . ', \'' . $data['polystring'] . '\'';
		Log::info($query);
		$upd = DB::select('exec dbo.UPD_TreatmentPolygon ' . $data['treatment'] . ', \'' . $data['polystring'] . '\'');
		return $upd;
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

	/**
	 * User wants to edit a treatment for this scenario
	 *
	 * @return void
	 * @author 
	 **/
	public function edit($treat_id)
	{
		$treatment = Treatment::find($treat_id);
		$tech = DB::table('dbo.v_Technology_Matrix')->select('*')->where('Technology_ID', $treatment->TreatmentType_ID)->first();
		$type = $tech->Technology_Sys_Type;
		$toilets = [21, 22, 23, 24];
		if ( in_array($treatment->TreatmentType_ID, $toilets) ) 
		{
			return view('common/technology-septic-edit', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
			break;
		}
		else 
		{
			switch ($type) 
			{
				case 'Fertilization':
					return view('common/technology-fertilizer-edit', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
					break;
				case 'Stormwater':
					return view('common/technology-stormwater-edit', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
					break;
				case 'Septic/Sewer':
					return view('common/technology-collection-edit', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
					break;
				case 'Groundwater':
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
		return view('common/technology-septic-edit', ['treatment'=>$treatment, 'tech'=>$tech]);
	}

	// Handle treatment reapplication by type
	public function update($type, $treat_id, $rate, $units=null, $subemid=null)
	{
		// Trigger parameterized stored proc based on passed type
		switch ($type) 
		{
			// Fertilizer Management
			case 'fert':
				$updated = DB::select('exec dbo.CALCapplyTreatmentPercent ' . $treat_id . ', ' . $rate . ', ' . $type);
				return $this->updateNitrogenRemoved();
				break;
			// Stormwater Management
			case 'storm-percent':
				$updated = DB::select('exec dbo.CALCapplyTreatmentPercent ' . $treat_id . ', ' . $rate . ', storm' );
				return $this->updateNitrogenRemoved();
				break;
			// Stormwater non-management
			case 'storm':
				$updated = DB::select('exec dbo.CALCupdateTreatmentStorm ' . $treat_id . ', ' . $rate . ', ' . $units );
				return $this->updateNitrogenRemoved();
				break;
			// Septic (first row)
			case 'collect':
				$updated = DB::select('exec dbo.CALCapplyTreatmentSeptic '. $treat_id . ', '. $rate);
				return $this->updateNitrogenRemoved();
				break;
			// Septic (second row)
			case 'toilets':
				$updated = DB::select('exec dbo.CALCapplyTreatmentSeptic '. $treat_id . ', '. $rate);
				return $this->updateNitrogenRemoved();
				break;
			// Groundwater
			case 'groundwater':
				$updated = DB::select('exec dbo.CALC_ApplyTreatment_Groundwater1 '. $treat_id . ', '. $rate . ', ' . $units);
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
				$updated = DB::select('exec dbo.CALC_ApplyTreatment_Embayment1 ' . $treat_id . ', ' . $rate . ', ' . $units . ', ' . $n_parcels);
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