<?php

namespace App\Http\Controllers;
use Log;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;

use App\Embayment;
use App\Scenario;
use App\Treatment;

use DB;
use JavaScript;

use Session;
use Excel;

class MarioController extends Controller
{

	public function sumTotalsWithinPoly(Request $data)
	{
		$user = Auth::user();
		$data = $data->all();
		$poly = $data['polystring'];

		// Obtain parcel count, nitrogen and wastewater loads within user-drawn polygon
		$parcels = DB::select('exec CapeCodMA.sumTotalsWithinPoly ' . '\'' . $poly . '\'');

		return $parcels;
	}
	
}
