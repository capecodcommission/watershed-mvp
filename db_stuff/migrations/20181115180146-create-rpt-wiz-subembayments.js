'use strict';
module.exports = {
  up: (queryInterface, Sequelize) => {
    return queryInterface.createTable('rpt_wiz_subembayments', {
      id: {
        allowNull: false,
        autoIncrement: true,
        type: Sequelize.INTEGER
      },
      subem_id: {
        type: Sequelize.INTEGER,
        primaryKey: true,
      },
      subem_disp: {
        type: Sequelize.STRING,
        allowNull: true
      },
      embay_id: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      n_load_att: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      n_load_unatt: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      shape: {
        type: Sequelize.GEOMETRY,
        allowNull: true
      },
      n_load_target: {
        type: Sequelize.FLOAT,
        allowNull: true
      }
    });
  },
  down: (queryInterface, Sequelize) => {
    return queryInterface.dropTable('rpt_wiz_subembayments');
  }
};