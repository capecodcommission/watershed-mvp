'use strict';
module.exports = (sequelize, DataTypes) => {
  const rpt_wiz_subembayments = sequelize.define('rpt_wiz_subembayments', {
    subem_id: DataTypes.INTEGER,
    subem_disp: DataTypes.STRING,
    embay_id: DataTypes.INTEGER,
    n_load_att: DataTypes.FLOAT,
    n_load_unatt: DataTypes.FLOAT,
    shape: DataTypes.GEOMETRY,
    n_load_target: DataTypes.FLOAT
  }, {
    freezeTableName: true
  });
  rpt_wiz_subembayments.associate = function(models) {
    // associations can be defined here
  };
  return rpt_wiz_subembayments;
};