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

class WizardController extends Controller
{

	public function getPolygon3(Request $data)
	{
		$user = Auth::user();
		$data = $data->all();
		$poly = $data['polystring'];

		$parcels = DB::select('exec CapeCodMA.GET_PointsFromPolygon2 ' . '\'' . $poly . '\'');

		return $parcels;
	}
	
}
