<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use JavaScript;
use App\Treatment;
use App\Scenario;

class MapController extends Controller
{

	// Check if geometry lies partially or fully within scenario embayment geometry
	public function checkGeometryInEmbay($type, $polyString, $tech_id=null, $treatment_id = null)
	{
		// Obtain embayment id from scenario
		$scenarioid = session('scenarioid');
		$scenario = Scenario::find($scenarioid);
		$embay_id = $scenario->AreaID;
		
		// Check if point falls within, or polygon falls partially within, embayment geometry using embayment id
		$checkGeometry = DB::select("exec dbo.CHKgeoInEmbayment @polyString='$polyString', @embay_id='$embay_id', @type='$type', @scenario_id='$scenarioid', @tech_id='$tech_id', @treatment_id='$treatment_id'");

		return $checkGeometry[0]->inEmbay;
	}

	// Save map click geometry to session
	public function setPointCoords($x, $y, $techId= null)
	{
		$polyString = $x . ' ' . $y;
		$isInEmbay = $this->checkGeometryInEmbay('point', $polyString, $techId);
		$scenarioid = session('scenarioid');
		$scenario = Scenario::find($scenarioid);
		$embay_id = $scenario->AreaID;
		
		// Save coordinates to session
		// Check subembayment
		if ($isInEmbay) 
		{
			session(['pointX' => $x]);
			session(['pointY' => $y]);
			$subembayment = DB::select("exec dbo.GETsubembaymentFromPoint @pointCoords='$x $y', @embay_id=$embay_id");
			return $subembayment;
		}
		else 
		{
			return 0;
		}
	}

	// Save custom polygon coordinate array to session
	public function setCoordArray(Request $data)
	{
		// Obtain request data
		$data = $data->all();
		$polyString = $data['coordString'];
		$tech_id = $data['tech_id'];

		$isInEmbay = $this->checkGeometryInEmbay('polygon', $polyString, $tech_id);

		if ($isInEmbay)
		{
			// Save to session
			session(['polyString' => $polyString]);
			return 1;
		}
		else
		{
			return 0;
		}
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
