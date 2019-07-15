<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;
use App\Treatment;
use App\Embayment;
use App\Scenario;
use Session;
use Log;

class TechnologyController extends Controller
{

	// Retrieve and initialize selected technology data
	// Associate with scenario
	public function associateTech($type, $id)
	{

		// Retrieve technology data from tech_matrix based on passed TM_ID
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

		// Retrieve Scenario ID from Laravel session
		$scenarioid = session('scenarioid');

		// Create / associate selected technology with scenario
		$treatment = Treatment::create([
			'ScenarioID' => $scenarioid, 
			'TreatmentType_ID'=>$tech->Technology_ID, 
			'TreatmentType_Name'=>substr($tech->Technology_Strategy, 0, 50), 
			'Treatment_UnitMetric'=>$tech->Unit_Metric, 
			'Treatment_Class'=>$tech->Technology_Sys_Type, 
			'treatment_icon'=>$tech->Icon
		]);

		// If selected technology is management-based (embayment-wide)
		if ($tech->Show_In_wMVP == 4)
		{
			// Retrieve Scenario data from SQL, parse embayment id
			$scenario = Scenario::find($scenarioid);
			$embay_id = $scenario->AreaID;

			// Retrieve / associate all parcels within embayment with user's scenario 
			$parcels = DB::select('exec dbo.GETpointsFromPolygon ' . $embay_id . ', ' . $scenarioid . ', ' . $treatment->TreatmentID . ', \'embayment\'');
		}

		// Show relevant technology blade, pass retrieved technology data to blade
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


	// Apply Fertilizer and Stormwater management technologies based on type
	public function ApplyTreatment_Percent($treat_id, $rate, $type)
	{
		// Retrieve scenario id and removed nitrogen global variables, passed rate from blade
		$scenarioid = session('scenarioid');
		$rate = round($rate, 2);
		$n_removed = session('n_removed');

		// Trigger stored proc with function parameters
		// Set removed nitrogen global variable with new total returned from stored proc
		$updated = DB::select('exec dbo.CALC_ApplyTreatment_Percent ' . $treat_id . ', ' . $rate . ', ' . $type);
		$n_removed += $updated[0]->removed;
		Session::put('n_removed', $n_removed);

		// Set fert or storm applied global variable to disable management from being selected/applied again
		if ($type == 'fert')
		{
			Session::put('fert_applied', 1);
		}
		else if ($type == 'storm')
		{
			Session::put('storm_applied', 1);
		}

		return $n_removed
	}



	// Apply non-management Stormwater technology
	public function ApplyTreatment_Storm($treat_id, $rate, $units = null, $location = null)
	{
		
		$scenarioid = session('scenarioid');
		$updated = DB::select('exec [dbo].[CALC_ApplyTreatment_Storm1] ' . $treat_id . ', ' . $rate . ', ' . $units . ', ' . $location );
		$n_removed = session('n_removed');
		$n_removed += $updated[0]->removed;
		Session::put('n_removed', $n_removed);
		return $n_removed;
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
			// $parcels = DB::select('exec CapeCodMA.GET_PointsFromPolygon ' . $subemid . ', ' . $scenarioid . ', ' . $treat_id . ', \'subembayment\'');
			$parcels = DB::select('exec dbo.GETpointsFromPolygon ' . $subemid . ', ' . $scenarioid . ', ' . $treat_id . ', \'subembayment\'');
			Session::put('subemid', $subemid);
		} 

		foreach ($parcels as $parcel) 
		{
			$n_parcels += $parcel->NumParcels;
		}

		// $updated = DB::select('exec [CapeCodMA].[CALC_ApplyTreatment_Embayment] ' . $treat_id . ', ' . $rate . ', ' . $units . ', ' . $n_parcels);
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


	/**
	 * Apply Septic Treatment
	 *
	 * @return void
	 * @author 
	 **/
	public function ApplyTreatment_Septic($treat_id, $rate)
	{		
		//$scenarioid = session('scenarioid');
		
		// $updated = DB::select('exec [CapeCodMA].[CALC_ApplyTreatment_Septic] ' . $treat_id . ', ' . $rate );
		$updated = DB::select('exec [dbo].[CALC_ApplyTreatment_Septic1] ' . $treat_id . ', ' . $rate );
		
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


	/**
	 * User has edited the polygon for a treatment
	 *
	 * @return void
	 * @author 
	 **/
	// TODO: Apply proper 'apply_treatment' stored proc and pass in type, eg. 'septic' or 'groundwater'
	// public function updatePolygon(Request $data, $type)
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
	public function cancel($treat_id)
	{
		$del = DB::select('exec dbo.DEL_Treatment '. $treat_id);
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
		// dd($treatment);
		$tech = DB::table('dbo.v_Technology_Matrix')->select('*')->where('Technology_ID', $treatment->TreatmentType_ID)->first();
		// dd($treatment, $tech);
		$type = $tech->Technology_Sys_Type;
				$toilets = [21, 22, 23, 24];
		if (in_array($treatment->TreatmentType_ID, $toilets) ) 
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
				// case 'septic':
				// 	return view('common/technology-septic-edit', ['tech'=>$tech, 'treatment'=>$treatment, 'type'=>$type]);
				// 	break;
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

	/**
	 * User updated an existing treatment for this scenario
	 *
	 * @return void
	 * @author 
	 **/
	public function update($type, $treat_id, $rate, $units=null, $subemid=null)
	{
		$treatment = Treatment::find($treat_id);
			switch ($type) 
			{
				case 'fert':
					// TODO: Change reference to CALC_ApplyTreatment_Percent1, modify CALC_ApplyTreatment_Percent1 stored procedure to take in 'fert' param similar to storm-percent switch below
					$updated = DB::select('exec [dbo].[CALC_ApplyTreatment_Fert1] ' . $treat_id . ', ' . $rate );
					return $updated;	
					break;

				// Stormwater Management
				case 'storm-percent':
					// $updated = DB::select('exec CapeCodMA.CALC_ApplyTreatment_Percent ' . $treat_id . ', ' . $rate . ', storm' );
					$updated = DB::select('exec dbo.CALC_ApplyTreatment_Percent1 ' . $treat_id . ', ' . $rate . ', storm' );
					return $updated;
					break;

				// Stormwater treatments
				case 'storm':
					// $updated = DB::select('exec [CapeCodMA].[CALC_UpdateTreatment_Storm] ' . $treat_id . ', ' . $rate . ', ' . $units );
					$updated = DB::select('exec [dbo].[CALC_UpdateTreatment_Storm] ' . $treat_id . ', ' . $rate . ', ' . $units );
					return $updated;
					break;

				case 'toilets':
					// $updated = DB::select('exec [CapeCodMA].[CALC_ApplyTreatment_Septic] '. $treat_id . ', '. $rate);
					$updated = DB::select('exec [dbo].[CALC_ApplyTreatment_Septic1] '. $treat_id . ', '. $rate);
					return $updated;
					break;

				case 'collect':
				// $query = 'exec [CapeCodMA].[CALC_ApplyTreatment_Septic] '. $treat_id . ', '. $rate;
				$query = 'exec [dbo].[CALC_ApplyTreatment_Septic1] '. $treat_id . ', '. $rate;
				Log::info($query);
					// $updated = DB::select('exec [CapeCodMA].[CALC_ApplyTreatment_Septic] '. $treat_id . ', '. $rate);
					$updated = DB::select('exec [dbo].[CALC_ApplyTreatment_Septic1] '. $treat_id . ', '. $rate);
					return $updated;
					break;	
				case 'septic':
					
					break;
				case 'groundwater':
					$updated = DB::select('exec [dbo].[CALC_ApplyTreatment_Groundwater1] '. $treat_id . ', '. $rate . ', ' . $units);
					return $updated;
					break;	
					
				case 'embay':
					$n_total = 0;
					$scenarioid = session('scenarioid');
					$subemid = session('subemid');
					$n_parcels = 0;
				
					// TODO: Can we pass in # of parcels from either session or treatment id?
					$parcels = DB::table("dbo.wiz_treatment_towns")->select("*")->where("wtt_treatment_id", "=", $treat_id)->get();

					foreach ($parcels as $parcel) 
					{
						$n_parcels += $parcel->wtt_tot_parcels;
					}

					// $updated = DB::select('exec [CapeCodMA].[CALC_ApplyTreatment_Embayment] ' . $treat_id . ', ' . $rate . ', ' . $units . ', ' . $n_total . ', ' . $n_parcels);
					$updated = DB::select('exec [dbo].[CALC_ApplyTreatment_Embayment1] ' . $treat_id . ', ' . $rate . ', ' . $units . ', ' . $n_parcels);

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
	
		$del = DB::select('exec dbo.DEL_Treatment '. $treat_id);

		// Reset global variables to handle fert/storm clickability
		if ($type == 'fert') {
			
			Session::put('fert_applied',0);
		} 
		else if ($type == 'storm') {
			
			Session::put('storm_applied',0);
		}

		// TODO: Check if deleted treatment can be removed from global treatments array
		// Can we go Back to Map without page refresh?
		
		return 1;
	}


}