<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Embayment extends Model
{
		//
	// protected $connection = 'sqlsrv';
	protected $table = 'dbo.Embayments';
	protected $primaryKey = 'EMBAY_ID';

	/**
	* Get the polygon the the selected embayment/area
	*
	* @return void
	* @author 
	**/
	public function getArea()
	{
		return DB::select('select polygon.ToString() as polygon from dbo.embayment_area where embayment_id = ' . $this->EMBAY_ID);
	}



	/**
	 * Return the subembayments for this embayment
	 *
	 * @return void
	 * @author 
	 **/
	public function subembayments()
	{
		return $this->hasMany('App\Subembayment', 'EMBAY_ID', 'EMBAY_ID');
	}

}
