'use strict';
module.exports = (sequelize, DataTypes) => {
  const WIN = sequelize.define('WIN', {
    OBJECTID_1: DataTypes.INTEGER,
    Muni_ID: DataTypes.INTEGER,
    Other_ID: DataTypes.STRING,
    POINT_X: DataTypes.FLOAT,
    POINT_Y: DataTypes.FLOAT,
    Embayment: DataTypes.STRING,
    MEPSubwate: DataTypes.STRING,
    WaterUseExisting: DataTypes.FLOAT,
    NLoadExisting: DataTypes.FLOAT,
    Waterfront: DataTypes.INTEGER,
    TotalAssessedValue: DataTypes.FLOAT,
    NewSLIRM: DataTypes.INTEGER,
    GCScore: DataTypes.FLOAT,
    GCabs: DataTypes.FLOAT,
    WWTreatmentExisting: DataTypes.STRING,
    SHAPE: DataTypes.GEOMETRY,
    SUBWATER_ID: DataTypes.INTEGER,
    EconDevType: DataTypes.STRING,
    DensityCat: DataTypes.INTEGER,
    BioMap2: DataTypes.INTEGER,
    CWMP: DataTypes.INTEGER,
    NaturalAttenuation: DataTypes.FLOAT
  }, {
    freezeTableName: true
  });
  WIN.associate = function(models) {
    // associations can be defined here
  };
  return WIN;
};