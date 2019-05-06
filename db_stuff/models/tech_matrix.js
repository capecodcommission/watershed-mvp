'use strict';
module.exports = (sequelize, DataTypes) => {
  const Tech_Matrix = sequelize.define('Tech_Matrix', {
    Technology_ID: DataTypes.INTEGER,
    ProjectCost_kg: DataTypes.FLOAT,
    capFTE: DataTypes.FLOAT,
    OMCost_kg: DataTypes.FLOAT,
    omFTE: DataTypes.FLOAT,
    Avg_Life_Cycle_Cost: DataTypes.FLOAT,
    Useful_Life_Yrs: DataTypes.FLOAT,
    NewCompat: DataTypes.INTEGER,
    Resilience: DataTypes.INTEGER,
    n_percent_reduction_low: DataTypes.INTEGER,
    n_percent_reduction_high: DataTypes.INTEGER
  }, {
    freezeTableName: true
  });
  Tech_Matrix.associate = function(models) {
    // associations can be defined here
  };
  return Tech_Matrix;
};