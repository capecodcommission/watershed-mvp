<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use JavaScript;
use App\Treatment;

class MapController extends Controller
{
	// Associate closest parcel to scenario using coordinates from map-click
	// Return name of subemebayment where parcel was selected
	public function point($x, $y, $treatment)
	{
		$point = DB::select("exec dbo.UPDcreditSubembayment @x='$x', @y='$y', @treatment=$treatment");
		return json_encode($point[0]);
	}

	// Find selected subembayment using coordinates from map-click
	// Dump nitrogen load from parcels within custom polygon to a single selected parcel
	public function moveNitrogen($x, $y, $treatment)
	{
		$subembayment = DB::select("exec dbo.GETsubembaymentFromPoint @x='$x', @y='$y'");
		$scenarioid = session('scenarioid');
		$move = DB::select("exec dbo.CALCmoveNitrogen '$x', '$y', $treatment, $scenarioid");
		return json_encode($subembayment[0]);
	}
}
