<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Embayment;
use DB;

class WizardController extends Controller
{
    //
    public function start($id)
    {
    	$embayment = Embayment::find($id);
    	$subembayments = DB::table('CapeCodMA.SubEmbayments')
    		->select('SUBEM_NAME', 'SUBEM_DISP', 'Nload_Total', 'Total_Tar_Kg', 'MEP_Total_Tar_Kg')
    		->where('EMBAY_ID', $embayment->EMBAY_ID)->get();
    	// dd($subembayments);
    	
    	// Need to create a new scenario or find existing one that the user is editing
    	// Is user logged in?

    	// Need to get list of Technologies (Tech_Matrix)



    	return view('layouts/wizard', ['embayment'=>$embayment, 'subembayments'=>$subembayments]);


    }
}
