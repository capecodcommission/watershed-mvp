<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use App\Embayment;
use Auth;
use DB;
use Session;

class StartController extends Controller
{
   public function __construct()
   {
	   $this->middleware('auth');
	}
	//
	public function index()
	{
		// this is the start page
		// get a list of all the embayments to populate the drop-down list
		$embayments = Embayment::orderBy('EMBAY_DISP')->get();

        $groupedEmbayments = array();
        foreach($embayments as $embayment) {
            $groupedEmbayments[$embayment['Region']][] = $embayment;
		}

		session()->forget('scenarioid');
		session()->forget('n_removed');
		session()->forget('fert_applied');
		session()->forget('storm_applied');
		return view('welcome', ['groupedEmbayments'=>$groupedEmbayments]);
	}
}