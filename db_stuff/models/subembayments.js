'use strict';
module.exports = (sequelize, DataTypes) => {
  const SubEmbayments = sequelize.define('SubEmbayments', {
    OBJECTID: DataTypes.INTEGER,
    SUBEM_ID: DataTypes.INTEGER,
    SUBEM_NAME: DataTypes.STRING,
    SUBEM_DISP: DataTypes.STRING,
    EMBAY_ID: DataTypes.INTEGER,
    EMBAY_NAME: DataTypes.STRING,
    EMBAY_DISP: DataTypes.STRING,
    Nload_Sept: DataTypes.FLOAT,
    Nload_Fert: DataTypes.FLOAT,
    Nload_Storm: DataTypes.FLOAT,
    Nload_Total: DataTypes.FLOAT,
    Nload_Parcels: DataTypes.INTEGER,
    Sept_Tar_Kg: DataTypes.FLOAT,
    Total_Tar_Kg: DataTypes.FLOAT,
    MEP_Sept_Tar_Kg: DataTypes.FLOAT,
    MEP_Total_Tar_Kg: DataTypes.FLOAT,
    MEP_Source: DataTypes.INTEGER,
    MEP_Sept_Tar_p: DataTypes.FLOAT,
    MEP_Total_Tar_p: DataTypes.FLOAT,
    X_Centroid: DataTypes.FLOAT,
    Y_Centroid: DataTypes.FLOAT,
    Acreage: DataTypes.FLOAT,
    Shape: DataTypes.GEOMETRY,
    GeoString: DataTypes.TEXT,
    ParcSEPTIC: DataTypes.INTEGER,
    ParcGWDP: DataTypes.INTEGER,
    ParcSEWERED: DataTypes.INTEGER
  }, {
    freezeTableName: true
  });
  SubEmbayments.associate = function(models) {
    // associations can be defined here
  };
  return SubEmbayments;
};