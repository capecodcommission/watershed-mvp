<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scenario extends Model
{
    //

    protected $table = 'CapeCodMa.Scenario_Wiz';
	protected $primaryKey = 'ScenarioId';

	protected $fillable = [
		'CreatedBy', // this should be the FK to users table
		'ScenarioName', 
		'ScenarioDescription',
		'ScenarioNotes',
		'AreaType',
		'AreaID',
		'AreaName',
		'Nload_Existing',
		'Nload_Sept',
		'Nload_Fert',
		'Nload_Storm',
		'Total_Parcels',
		'Total_WaterUse',
		'Total_WaterFlow',
		'Nload_Sept_Target',
		'Nload_Total_Target',
		'Nload_Calculated_Total',
		'Cost_Total',	
		'Cost_Capital',
		'Cost_OM',
		'Cost_Collection',	
		'Cost_TransportDisposal',
		'Cost_NonConstruction',
		'Cost_Monitor',
		'ScenarioPeriod',
		'POLY_STRING',
		'ScenarioAcreage',
		'Nload_Calculated_Fert',
		'Nload_Calculated_SW',
		'Nload_Calculated_Septic',
		'Nload_Calculated_GW',
		'Nload_Calculated_InEmbay',
		'Nload_Calculated_Attenuation',
		'Nload_Reduction_Fert',
		'Nload_Reduction_SW',
		'Nload_Reduction_Septic',
		'Nload_Reduction_GW',
		'Nload_Reduction_Attenuation',
		'Nload_Reduction_InEmbay',
		'ScenarioProgress',
		'ScenarioComplete'

	];
	
	

    /**
     * The name of the "created at" and "updated at" columns.
     * These are different from the default created_at and updated_at because we are using an existing table that already had these fields
     *
     * @var string
     */
    const CREATED_AT = 'CreateDate';
    const UPDATED_AT = 'UpdateDate';

}
