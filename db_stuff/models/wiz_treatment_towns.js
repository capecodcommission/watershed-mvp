'use strict';
module.exports = (sequelize, DataTypes) => {
  const wiz_treatment_towns = sequelize.define('wiz_treatment_towns', {
    wtt_id: DataTypes.INTEGER,
    wtt_scenario_id: DataTypes.INTEGER,
    wtt_treatment_id: DataTypes.INTEGER,
    wtt_town_id: DataTypes.INTEGER,
    wtt_tot_parcels: DataTypes.INTEGER,
    wtt_wu_parcels: DataTypes.INTEGER,
    wtt_att_n_removed: DataTypes.FLOAT,
    wtt_unatt_n_removed: DataTypes.FLOAT
  }, {});
  wiz_treatment_towns.associate = function(models) {
    // associations can be defined here
  };
  return wiz_treatment_towns;
};