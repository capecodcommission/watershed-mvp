'use strict';
module.exports = {
  up: (queryInterface, Sequelize) => {
    return queryInterface.createTable('Subwatersheds', {
      id: {
        allowNull: false,
        autoIncrement: true,
        type: Sequelize.INTEGER
      },
      OBJECTID: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      SUBWATER_ID: {
        type: Sequelize.INTEGER,
        primaryKey: true,
        allowNull: true
      },
      SUBWATER_NAME: {
        type: Sequelize.STRING,
        allowNull: true
      },
      SUBWATER_DISP: {
        type: Sequelize.STRING,
        allowNull: true
      },
      EMBAY_ID: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      EMBAY_NAME: {
        type: Sequelize.STRING,
        allowNull: true
      },
      EMBAY_DISP: {
        type: Sequelize.STRING,
        allowNull: true
      },
      X_Centroid: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Y_Centroid: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Acreage: {
        type: Sequelize.FLOAT
      },
      Shape: {
        type: Sequelize.GEOMETRY,
        allowNull: true
      },
      GeoString: {
        type: Sequelize.TEXT,
        allowNull: true
      }
    });
  },
  down: (queryInterface, Sequelize) => {
    return queryInterface.dropTable('Subwatersheds');
  }
};