<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Embayment;

class Subembayment extends Model
{
    //

    protected $table = 'CapeCodMA.SubEmbayments';
	protected $primaryKey = 'SUBEM_ID';

	protected $fillable = [
		// we aren't updating anything via wmvp so none of the fields should be fillable

	];
	


    // public function getNitrogenTarget()
    // {
    // 	return $this->hasMany('App\Treatment', 'ScenarioID', 'ScenarioID');
    // }

}
