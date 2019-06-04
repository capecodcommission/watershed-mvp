<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
	//
	protected $table = 'dbo.Treatment_Wiz';
	protected $primaryKey = 'TreatmentID';

	protected $fillable = [
		'ScenarioID', 
		'TreatmentType_Name', 
		'TreatmentType_ID',
		'Treatment_Class',
		'Treatment_Value',
		'Treatment_PerReduce',
		'Treatment_UnitMetric',
		'Treatment_MetricValue',
		'Cost_TC_Input',
		'Cost_OM_Input',
		'Treatment_Acreage',
		'Treatment_Parcels',
		'POLY_STRING',
		'Custom_POLY',
		'Cost_Capital',
		'Cost_OM',
		'Cost_Collection',
		'Cost_TransportDisposal',
		'Cost_NonConstruction',
		'Cost_Monitor',
		'Cost_Total',
		'Nload_Reduction',
		'Cost20yr_OM',
		'Cost20yr_Cap'
	];
	
	

    /**
     * The name of the "created at" and "updated at" columns.
     * These are different from the default created_at and updated_at because we are using an existing table that already had these fields
     *
     * @var string
     */
    const CREATED_AT = 'CreateDate';
    const UPDATED_AT = 'UpdateDate';

    public function scenario()
    {
    	return $this->belongsTo('App\Scenario', 'ScenarioID', 'ScenarioID');
    }

    public function technology()
    {
    	return $this->hasOne('App\Technology', 'Technology_ID', 'TreatmentType_ID');
		}
 
}
