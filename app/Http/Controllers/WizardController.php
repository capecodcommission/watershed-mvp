<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Embayment;
use DB;
use JavaScript;

class WizardController extends Controller
{
	//
	public function start($id)
	{
		$embayment = Embayment::find($id);
		// $subembayments = DB::table('CapeCodMA.SubEmbayments')
		// 	->select('SUBEM_NAME', 'SUBEM_DISP', 'Nload_Total', 'Total_Tar_Kg', 'MEP_Total_Tar_Kg')
		// 	->where('EMBAY_ID', $embayment->EMBAY_ID)->get();
		$subembayments = DB::select('exec CapeCodMA.GET_SubembaymentNitrogen ' . $id);

		$nitrogen = DB::select('exec CapeCodMA.GET_EmbaymentNitrogen ' . $id);

		JavaScript::put([
				'nitrogen' => $nitrogen[0]
			]);
		// dd($nitrogen);
		// dd($subembayments);
		
		// Need to create a new scenario or find existing one that the user is editing
		// Is user logged in?

		// Need to get list of Technologies (Tech_Matrix)



		return view('layouts/wizard', ['embayment'=>$embayment, 'subembayments'=>$subembayments]);


	}

	/**
	 * Test page to show all Nitrogen values for Embayment
	 *
	 * @return void
	 * @author 
	 **/
	

	public function test($id)
	{
		$embayment = Embayment::find($id);
		$subembayments = DB::table('CapeCodMA.SubEmbayments')
			->select('SUBEM_NAME', 'SUBEM_DISP', 'Nload_Total', 'Total_Tar_Kg', 'MEP_Total_Tar_Kg')
			->where('EMBAY_ID', $embayment->EMBAY_ID)->get();

		$nitrogen = DB::select('exec CapeCodMA.GET_EmbaymentNitrogen ' . $id);

		 JavaScript::put([
				'nitrogen' => $nitrogen[0]
			]);
		// dd($nitrogen);
		// dd($subembayments);
		
		// Need to create a new scenario or find existing one that the user is editing
		// Is user logged in?

		// Need to get list of Technologies (Tech_Matrix)



		return view('layouts/test', ['embayment'=>$embayment, 'subembayments'=>$subembayments]);


	}
}
