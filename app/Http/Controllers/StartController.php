<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use App\Embayment;
use Auth;
use DB;

class StartController extends Controller
{
	//
	public function index()
	{
		// this is the start page
		// get a list of all the embayments to populate the drop-down list
		$embayments = Embayment::all();

		return view('welcome', ['embayments'=>$embayments]);
	}
}
