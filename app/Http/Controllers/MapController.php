<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use JavaScript;
use App\Treatment;

class MapController extends Controller
{
	// Find selected subembayment using coordinates from map-click
	// Associate parcel within selected subembayment with user's scenario
	public function point($x, $y, $treatment)
	{
		$subembayment = DB::select("exec [dbo].[UPD_Credit_Subembayment1] @x='$x', @y='$y', @treatment=$treatment");
		return json_encode($subembayment[0]);
	}

	// Find selected subembayment using coordinates from map-click
	// Dump nitrogen load from parcels within custom polygon to a single selected parcel
	public function moveNitrogen($x, $y, $treatment)
	{
		$subembayment = DB::select("exec [dbo].[GET_Subembayment_from_Point] @x='$x', @y='$y'");
		$scenarioid = session('scenarioid');
		$move = DB::select("exec dbo.CALC_MoveNitrogen1 '$x', '$y', $treatment, $scenarioid");
		return json_encode($subembayment[0]);
	}
}
