'use strict';
module.exports = (sequelize, DataTypes) => {
  const MATowns = sequelize.define('MATowns', {
    OBJECTID: DataTypes.INTEGER,
    TOWN_ID: DataTypes.BIGINT,
    TOWN: DataTypes.STRING,
    SHAPE: DataTypes.GEOMETRY,
    SHAPE_AREA: DataTypes.REAL,
    SHAPE_LEN: DataTypes.REAL,
    GEOSTRING: DataTypes.TEXT,
    TOTAL_WU_PAR: DataTypes.INTEGER,
    TOTAL_PAR: DataTypes.INTEGER,
    MEAN_BLDG_VAL_PRI: DataTypes.FLOAT,
    MEAN_BLDG_VAL_SEC: DataTypes.FLOAT,
    TOT_ASSESSED_VAL: DataTypes.FLOAT
  }, {});
  MATowns.associate = function(models) {
    // associations can be defined here
  };
  return MATowns;
};