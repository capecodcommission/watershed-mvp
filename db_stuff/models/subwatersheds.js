'use strict';
module.exports = (sequelize, DataTypes) => {
  const Subwatersheds = sequelize.define('Subwatersheds', {
    OBJECTID: DataTypes.INTEGER,
    SUBWATER_ID: DataTypes.INTEGER,
    SUBWATER_NAME: DataTypes.STRING,
    SUBWATER_DISP: DataTypes.STRING,
    EMBAY_ID: DataTypes.INTEGER,
    EMBAY_NAME: DataTypes.STRING,
    EMBAY_DISP: DataTypes.STRING,
    X_Centroid: DataTypes.FLOAT,
    Y_Centroid: DataTypes.FLOAT,
    Acreage: DataTypes.FLOAT,
    Shape: DataTypes.GEOMETRY,
    GeoString: DataTypes.TEXT
  }, {
    freezeTableName: true
  });
  Subwatersheds.associate = function(models) {
    // associations can be defined here
  };
  return Subwatersheds;
};