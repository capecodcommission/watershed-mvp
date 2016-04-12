<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;

class TechnologyController extends Controller
{
    //

    /**
     * List all technologies available
     *
     * @return void
     * @author 
     **/
    // public function list()
    // {
    // 	$technologies = DB::table('Technology_Matrix')->select('*')->get();
    // }


    /**
     * Get the technology requested
     *
     * @return void
     * @author 
     **/
    public function get($id)
    {
    	$tech = DB::table('CapeCodMA.Technology_Matrix')->select('*')->where('TM_ID', $id)->get();
    	// dd($tech);
    	return view('common/technology', ['tech'=>$tech[0]]);
    }


}
