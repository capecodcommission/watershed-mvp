'use strict';
module.exports = (sequelize, DataTypes) => {
  const Treatment_Wiz = sequelize.define('Treatment_Wiz', {
    TreatmentID: DataTypes.INTEGER,
    ScenarioID: DataTypes.INTEGER,
    TreatmentType_Name: DataTypes.STRING,
    TreatmentType_ID: DataTypes.INTEGER,
    Treatment_Class: DataTypes.STRING,
    Treatment_Value: DataTypes.FLOAT,
    Treatment_PerReduce: DataTypes.FLOAT,
    Treatment_UnitMetric: DataTypes.STRING,
    Treatment_MetricValue: DataTypes.FLOAT,
    Cost_TC_Input: DataTypes.FLOAT,
    Cost_OM_Input: DataTypes.FLOAT,
    Treatment_Acreage: DataTypes.FLOAT,
    Treatment_Parcels: DataTypes.INTEGER,
    CreateDate: DataTypes.STRING,
    UpdateDate: DataTypes.STRING,
    POLY_STRING: DataTypes.GEOMETRY,
    Custom_POLY: DataTypes.INTEGER,
    Cost_Capital: DataTypes.FLOAT,
    Cost_OM: DataTypes.FLOAT,
    Cost_Collection: DataTypes.FLOAT,
    Cost_TransportDisposal: DataTypes.FLOAT,
    Cost_NonConstruction: DataTypes.FLOAT,
    Cost_Monitor: DataTypes.FLOAT,
    Cost_Total: DataTypes.FLOAT,
    Nload_Reduction: DataTypes.FLOAT,
    Cost20yr_OM: DataTypes.FLOAT,
    Cost20yr_Cap: DataTypes.FLOAT,
    Cost_Replacement: DataTypes.FLOAT,
    Treatment_Wastewater_Flow: DataTypes.FLOAT,
    Clipped_Rds_LinFeet: DataTypes.FLOAT,
    Treatment_WU_Parcels: DataTypes.INTEGER,
    Parent_TreatmentId: DataTypes.INTEGER,
    treatment_icon: DataTypes.STRING,
    Treatment_WaterUse: DataTypes.FLOAT
  }, {
    freezeTableName: true
  });
  Treatment_Wiz.associate = function(models) {
    models.Treatment_Wiz.belongsTo(models.Scenario_Wiz, {targetKey: 'ScenarioID', foreignKey: 'ScenarioID'})
  };
  return Treatment_Wiz;
};