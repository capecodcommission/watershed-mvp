'use strict';
module.exports = {
  up: (queryInterface, Sequelize) => {
    return queryInterface.createTable('Tech_Matrix', {
      id: {
        allowNull: false,
        autoIncrement: true,
        type: Sequelize.INTEGER
      },
      Technology_ID: {
        type: Sequelize.INTEGER,
        primaryKey: true
      },
      ProjectCost_kg: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      capFTE: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      OMCost_kg: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      omFTE: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Avg_Life_Cycle_Cost: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Useful_Life_Yrs: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      NewCompat: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      Resilience: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      n_percent_reduction_low: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      n_percent_reduction_high: {
        type: Sequelize.INTEGER,
        allowNull: true
      }
    });
  },
  down: (queryInterface, Sequelize) => {
    return queryInterface.dropTable('Tech_Matrix');
  }
};