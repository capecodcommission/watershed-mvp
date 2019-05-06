'use strict';
module.exports = {
  up: (queryInterface, Sequelize) => {
    return queryInterface.createTable('MATowns', {
      id: {
        allowNull: false,
        autoIncrement: true,
        type: Sequelize.INTEGER
      },
      OBJECTID: {
        type: Sequelize.INTEGER,
        primaryKey: true
      },
      TOWN_ID: {
        type: Sequelize.BIGINT
      },
      TOWN: {
        type: Sequelize.STRING
      },
      SHAPE: {
        type: Sequelize.GEOMETRY
      },
      SHAPE_AREA: {
        type: Sequelize.REAL
      },
      SHAPE_LEN: {
        type: Sequelize.REAL
      },
      GEOSTRING: {
        type: Sequelize.TEXT
      },
      TOTAL_WU_PAR: {
        type: Sequelize.INTEGER
      },
      TOTAL_PAR: {
        type: Sequelize.INTEGER
      },
      MEAN_BLDG_VAL_PRI: {
        type: Sequelize.FLOAT
      },
      MEAN_BLDG_VAL_SEC: {
        type: Sequelize.FLOAT
      },
      TOT_ASSESSED_VAL: {
        type: Sequelize.FLOAT
      }
    });
  },
  down: (queryInterface, Sequelize) => {
    return queryInterface.dropTable('MATowns');
  }
};