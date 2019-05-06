'use strict';
module.exports = {
  up: (queryInterface, Sequelize) => {
    return queryInterface.createTable('wiz_treatment_towns', {
      id: {
        allowNull: false,
        autoIncrement: true,
        type: Sequelize.INTEGER
      },
      wtt_id: {
        type: Sequelize.INTEGER,
        primaryKey: true
      },
      wtt_scenario_id: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      wtt_treatment_id: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      wtt_town_id: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      wtt_tot_parcels: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      wtt_wu_parcels: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      wtt_att_n_removed: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      wtt_unatt_n_removed: {
        type: Sequelize.FLOAT,
        allowNull: true
      }
    });
  },
  down: (queryInterface, Sequelize) => {
    return queryInterface.dropTable('wiz_treatment_towns');
  }
};