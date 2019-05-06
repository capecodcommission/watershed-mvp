'use strict';
module.exports = {
  up: (queryInterface, Sequelize) => {
    return queryInterface.createTable('parcelMaster', {
      id: {
        allowNull: false,
        autoIncrement: true,
        type: Sequelize.INTEGER
      },
      row_id: {
        type: Sequelize.INTEGER,
        primaryKey: true
      },
      parcel_id: {
        type: Sequelize.TEXT,
        allowNull: true
      },
      town_id: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      subwater_id: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      treatment_id: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      treatment_type_id: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      treatment_class: {
        type: Sequelize.STRING,
        allowNull: true
      },
      treatment_name: {
        type: Sequelize.STRING,
        allowNull: true
      },
      scenario_id: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      ww_class: {
        type: Sequelize.TEXT,
        allowNull: true
      },
      geo_point: {
        type: Sequelize.GEOMETRY,
        allowNull: true
      },
      ww_flow: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      init_nload_septic: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      init_nload_fert: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      init_nload_storm: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      init_nload_atmosphere: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      init_nload_total: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      att_init_nload_total: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      running_nload_septic: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      running_nload_fert: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      running_nload_storm: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      running_nload_atmosphere: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      running_nload_total: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      att_running_nload_total: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      running_nload_treated: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      running_nload_removed: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      final_nload_septic: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      final_nload_fert: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      final_nload_storm: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      final_nload_atmosphere: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      final_nload_total: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      att_final_nload_total: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      final_nload_treated: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      final_nload_removed: {
        type: Sequelize.FLOAT,
        allowNull: true
      }
    });
  },
  down: (queryInterface, Sequelize) => {
    return queryInterface.dropTable('parcelMaster');
  }
};