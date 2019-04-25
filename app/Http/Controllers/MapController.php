<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use JavaScript;
use App\Treatment;

class MapController extends Controller
{
	//

	/**
	 * Return the subembayment & subwatershed for the point sent (x,y)
	 *
	 * @return void
	 * @author 
	 **/
	public function point($x, $y, $treatment)
	{
		// DB::connection('sqlsrv')->statement('SET ANSI_NULLS, QUOTED_IDENTIFIER, CONCAT_NULL_YIELDS_NULL, ANSI_WARNINGS, ANSI_PADDING ON');

		// $subembayment = DB::select("exec [CapeCodMA].[UPD_Credit_Subembayment] @x='$x', @y='$y', @treatment=$treatment");
		$subembayment = DB::select("exec [CapeCodMA].[UPD_Credit_Subembayment1] @x='$x', @y='$y', @treatment=$treatment");
  		
		// need to update the record in the treatment_wiz table with the location of the treatment
		// use point as the polygon value; 
		// add the point to wiz_treatment_parcels so the N removed gets credited to the subembayment

		return json_encode($subembayment[0]);
	}

	/**
	 * Return the subembayment & subwatershed for the point sent (x,y)
	 *	Move Nitrogen being treated to the destination point.
	 *
	 * @return void
	 * @author 
	 **/
	public function moveNitrogen($x, $y, $treatment)
	{
		// DB::connection('sqlsrv')->statement('SET ANSI_NULLS, QUOTED_IDENTIFIER, CONCAT_NULL_YIELDS_NULL, ANSI_WARNINGS, ANSI_PADDING ON');
		$subembayment = DB::select("exec [CapeCodMA].[GET_Subembayment_from_Point] @x='$x', @y='$y'");
  
		// need to create a new record in the treatment_wiz table with the destination of the Nitrogen and the parent_treatment_id
		// use point as the polygon value; use treatment as parent_treatment_id
		// need to add the Nitrogen to the selected destination and have it ADDED to that subembayment's total
		$scenarioid = session('scenarioid');
		// $move = DB::select("exec CapeCodMA.CALC_MoveNitrogen '$x', '$y', $treatment, $scenarioid");
		$move = DB::select("exec CapeCodMA.CALC_MoveNitrogen1 '$x', '$y', $treatment, $scenarioid");

		return json_encode($subembayment[0]);
	}

}
