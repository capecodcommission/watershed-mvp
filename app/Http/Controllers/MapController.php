<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use JavaScript;
use App\Treatment;

class MapController extends Controller
{
	// Save map click geometry to session
	public function setPointCoords($x, $y)
	{
		session(['pointX' => $x]);
		session(['pointY' => $y]);
		return 1;
	}

	// Save custom polygon coordinate array to session
	public function setCoordArray(Request $data)
	{
		// Retrieve coordinate string from post data
		$data = $data->all();
		$stringCoordArray = $data['coordString'];

		// Save to session
		session(['polyString' => $stringCoordArray]);

		return 1;
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
