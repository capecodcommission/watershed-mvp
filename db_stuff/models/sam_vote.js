'use strict';
module.exports = (sequelize, DataTypes) => {
  const sam_vote = sequelize.define('sam_vote', {
    scenario_id: DataTypes.INTEGER,
    meeting_id: DataTypes.INTEGER,
    cap_cost: DataTypes.FLOAT,
    om_cost: DataTypes.FLOAT,
    lc_cost: DataTypes.FLOAT,
    growth_comp: DataTypes.FLOAT,
    jobs: DataTypes.FLOAT,
    var_perf: DataTypes.FLOAT,
    flood_ratio: DataTypes.FLOAT,
    pvla: DataTypes.FLOAT,
    years: DataTypes.FLOAT
  }, {
    freezeTableName: true
  });
  sam_vote.associate = function(models) {
    // associations can be defined here
  };
  return sam_vote;
};