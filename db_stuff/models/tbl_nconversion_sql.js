'use strict';
module.exports = (sequelize, DataTypes) => {
  const TBL_NConversion_SQL = sequelize.define('TBL_NConversion_SQL', {
    EMBAY_ID: DataTypes.STRING,
    EMBAY_DISP: DataTypes.STRING,
    EMBAY_NAME: DataTypes.STRING,
    MEP: DataTypes.STRING,
    Intercept: DataTypes.STRING,
    Slope: DataTypes.STRING,
    R_squared: DataTypes.STRING,
    Had_val: DataTypes.STRING
  }, {
    freezeTableName: true
  });
  TBL_NConversion_SQL.associate = function(models) {
    // associations can be defined here
  };
  return TBL_NConversion_SQL;
};