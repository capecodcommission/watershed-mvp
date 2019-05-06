'use strict';
module.exports = (sequelize, DataTypes) => {
  const parcelMaster = sequelize.define('parcelMaster', {
    row_id: DataTypes.INTEGER,
    parcel_id: DataTypes.TEXT,
    town_id: DataTypes.INTEGER,
    subwater_id: DataTypes.INTEGER,
    treatment_id: DataTypes.INTEGER,
    treatment_type_id: DataTypes.INTEGER,
    treatment_class: DataTypes.STRING,
    treatment_name: DataTypes.STRING,
    scenario_id: DataTypes.INTEGER,
    ww_class: DataTypes.TEXT,
    geo_point: DataTypes.GEOMETRY,
    ww_flow: DataTypes.FLOAT,
    init_nload_septic: DataTypes.FLOAT,
    init_nload_fert: DataTypes.FLOAT,
    init_nload_storm: DataTypes.FLOAT,
    init_nload_atmosphere: DataTypes.FLOAT,
    init_nload_total: DataTypes.FLOAT,
    att_init_nload_total: DataTypes.FLOAT,
    running_nload_septic: DataTypes.FLOAT,
    running_nload_fert: DataTypes.FLOAT,
    running_nload_storm: DataTypes.FLOAT,
    running_nload_atmosphere: DataTypes.FLOAT,
    running_nload_total: DataTypes.FLOAT,
    att_running_nload_total: DataTypes.FLOAT,
    running_nload_treated: DataTypes.FLOAT,
    running_nload_removed: DataTypes.FLOAT,
    final_nload_septic: DataTypes.FLOAT,
    final_nload_fert: DataTypes.FLOAT,
    final_nload_storm: DataTypes.FLOAT,
    final_nload_atmosphere: DataTypes.FLOAT,
    final_nload_total: DataTypes.FLOAT,
    att_final_nload_total: DataTypes.FLOAT,
    final_nload_treated: DataTypes.FLOAT,
    final_nload_removed: DataTypes.FLOAT
  }, {
    freezeTableName: true
  });
  parcelMaster.associate = function(models) {
    // associations can be defined here
  };
  return parcelMaster;
};