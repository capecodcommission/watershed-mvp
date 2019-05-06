'use strict';
module.exports = {
  up: (queryInterface, Sequelize) => {
    return queryInterface.createTable('sam_vote', {
      id: {
        allowNull: false,
        autoIncrement: true,
        type: Sequelize.INTEGER
      },
      scenario_id: {
        type: Sequelize.INTEGER,
        primaryKey: true
      },
      meeting_id: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      cap_cost: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      om_cost: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      lc_cost: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      growth_comp: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      jobs: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      var_perf: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      flood_ratio: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      pvla: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      years: {
        type: Sequelize.FLOAT,
        allowNull: true
      }
    });
  },
  down: (queryInterface, Sequelize) => {
    return queryInterface.dropTable('sam_vote');
  }
};