<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
	//
	protected $table = 'CapeCodMa.Treatment_Wiz';
	protected $primaryKey = 'TreatmentId';

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
		'CreateDate',
		'UpdateDate',
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
		'COst20yr_Cap'
	];
}
