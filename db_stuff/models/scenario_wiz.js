'use strict';
module.exports = (sequelize, DataTypes) => {
  const Scenario_Wiz = sequelize.define('Scenario_Wiz', {
    ScenarioID: DataTypes.INTEGER,
    CreateDate: DataTypes.STRING,
    UpdateDate: DataTypes.STRING,
    Deleted: DataTypes.BOOLEAN,
    CreatedBy: DataTypes.STRING,
    ScenarioName: DataTypes.STRING,
    ScenarioDescription: DataTypes.STRING,
    ScenarioNotes: DataTypes.TEXT,
    AreaType: DataTypes.STRING,
    AreaID: DataTypes.INTEGER,
    AreaName: DataTypes.STRING,
    Nload_Existing: DataTypes.FLOAT,
    Nload_Sept: DataTypes.FLOAT,
    Nload_Fert: DataTypes.FLOAT,
    Nload_Storm: DataTypes.FLOAT,
    Total_Parcels: DataTypes.INTEGER,
    Total_WaterUse: DataTypes.FLOAT,
    Total_WaterFlow: DataTypes.FLOAT,
    Nload_Sept_Target: DataTypes.FLOAT,
    Nload_Total_Target: DataTypes.FLOAT,
    Nload_Calculated_Total: DataTypes.FLOAT,
    Cost_Total: DataTypes.FLOAT,
    Cost_Capital: DataTypes.FLOAT,
    Cost_OM: DataTypes.FLOAT,
    Cost_Collection: DataTypes.FLOAT,
    Cost_TransportDisposal: DataTypes.FLOAT,
    Cost_NonConstruction: DataTypes.FLOAT,
    Cost_Monitor: DataTypes.FLOAT,
    ScenarioPeriod: DataTypes.STRING,
    POLY_STRING: DataTypes.TEXT,
    ScenarioAcreage: DataTypes.FLOAT,
    Nload_Calculated_Fert: DataTypes.FLOAT,
    Nload_Calculated_SW: DataTypes.FLOAT,
    Nload_Calculated_Septic: DataTypes.FLOAT,
    Nload_Calculated_GW: DataTypes.FLOAT,
    Nload_Calculated_InEmbay: DataTypes.FLOAT,
    Nload_Calculated_Attenuation: DataTypes.FLOAT,
    Nload_Reduction_Fert: DataTypes.FLOAT,
    Nload_Reduction_SW: DataTypes.FLOAT,
    Nload_Reduction_Septic: DataTypes.FLOAT,
    Nload_Reduction_GW: DataTypes.FLOAT,
    Nload_Reduction_Attenuation: DataTypes.FLOAT,
    Nload_Reduction_InEmbay: DataTypes.FLOAT,
    ScenarioProgress: DataTypes.INTEGER,
    ScenarioComplete: DataTypes.INTEGER,
    user_id: DataTypes.INTEGER,
    deleted_at: DataTypes.DATE,
    parcels_septic: DataTypes.INTEGER,
    parcels_sewer: DataTypes.INTEGER,
    parcels_gwdp: DataTypes.INTEGER
  }, {
    freezeTableName: true,
    timestamps: false
  });
  Scenario_Wiz.associate = function(models) {
    models.Scenario_Wiz.hasMany(models.Treatment_Wiz, {foreignKey: 'ScenarioID', sourceKey: 'ScenarioID'})
    // models.Scenario_wiz.hasMany(models.parcelMaster, {sourceKey: 'scenario_id'})
  };
  return Scenario_Wiz;
};