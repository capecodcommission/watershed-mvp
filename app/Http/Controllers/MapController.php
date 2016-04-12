<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;

class MapController extends Controller
{
    //

    /**
     * Return the subembayment & subwatershed for the point sent (x,y)
     *
     * @return void
     * @author 
     **/
    public function point($x, $y)
    {
		$subembayment = DB::select("exec [CapeCodMA].[GET_Subembayment_from_Point] @x='$x', @y='$y'");
		// dd($subembayment);
		return json_encode($subembayment[0]);

    }
}
