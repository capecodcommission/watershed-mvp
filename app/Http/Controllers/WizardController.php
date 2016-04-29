<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Embayment;
use DB;
use JavaScript;
use App\Treatment;

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
		// $subembayments = DB::table('CapeCodMA.SubEmbayments')
		// 	->select('SUBEM_NAME', 'SUBEM_DISP', 'Nload_Total', 'Total_Tar_Kg', 'MEP_Total_Tar_Kg')
		// 	->where('EMBAY_ID', $embayment->EMBAY_ID)->get();
			$subembayments = DB::select('exec CapeCodMA.GET_SubembaymentNitrogen ' . $id);
		$nitrogen = DB::select('exec CapeCodMA.GET_EmbaymentNitrogen ' . $id);

		 JavaScript::put([
				'nitrogen' => $nitrogen[0]
			]);

		return view('layouts/test', ['embayment'=>$embayment, 'subembayments'=>$subembayments]);
	}


	/**
	 * Get Nitrogen Totals from a polygon string
	 *
	 * @return void
	 * @author 
	 **/
	public function getNitrogen($poly)
	{
		$nitrogen_totals = DB::select('exec CapeCodMA.GET_NitrogenFromPolygon \'' . $poly . '\'');
// dd($nitrogen_totals[0]);

		$parcels = DB::select('exec CapeCodMA.GET_PointsFromPolygon \'' . $poly . '\'');
		// dd($parcels);
		// JavaScript::put([
		// 		'nitrogen_totals' => $nitrogen_totals[0]
		// 	]);

		return $parcels;
	}
	
}
