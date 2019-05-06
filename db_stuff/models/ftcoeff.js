'use strict';
module.exports = (sequelize, DataTypes) => {
  const ftcoeff = sequelize.define('FTCoeff', {
    FTC_ID: DataTypes.INTEGER,
    EMBAY_ID: DataTypes.INTEGER,
    EMBAY_NAME: DataTypes.STRING,
    EMBAY_DISP: DataTypes.STRING,
    SUBEM_ID: DataTypes.INTEGER,
    SUBEM_NAME: DataTypes.STRING,
    SUBEM_DISP: DataTypes.STRING,
    SUBWATER_ID: DataTypes.INTEGER,
    SUBWATER_NAME: DataTypes.STRING,
    SUBWATER_DISP: DataTypes.STRING,
    FLOWTHRUCOEF: DataTypes.FLOAT,
    AVERAGED: DataTypes.INTEGER,
    SUBWATER_TOTAL: DataTypes.FLOAT,
    SUBEMBAY_PCT: DataTypes.FLOAT
  }, {
    freezeTableName: true
  });
  ftcoeff.associate = function(models) {
    // models.ftcoeff.hasMany(models.whatever)
  };
  return ftcoeff;
};