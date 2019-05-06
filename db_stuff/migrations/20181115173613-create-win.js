'use strict';
module.exports = {
  up: (queryInterface, Sequelize) => {
    return queryInterface.createTable('WIN', {
      id: {
        allowNull: false,
        autoIncrement: true,
        type: Sequelize.INTEGER
      },
      OBJECTID_1: {
        type: Sequelize.INTEGER,
        primaryKey: true,
      },
      Muni_ID: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      Other_ID: {
        type: Sequelize.STRING,
        allowNull: true
      },
      POINT_X: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      POINT_Y: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Embayment: {
        type: Sequelize.STRING,
        allowNull: true
      },
      MEPSubwate: {
        type: Sequelize.STRING,
        allowNull: true
      },
      WaterUseExisting: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      NLoadExisting: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Waterfront: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      TotalAssessedValue: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      NewSLIRM: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      GCScore: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      GCabs: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      WWTreatmentExisting: {
        type: Sequelize.STRING,
        allowNull: true
      },
      SHAPE: {
        type: Sequelize.GEOMETRY,
        allowNull: true
      },
      SUBWATER_ID: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      EconDevType: {
        type: Sequelize.STRING,
        allowNull: true
      },
      DensityCat: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      BioMap2: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      CWMP: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      NaturalAttenuation: {
        type: Sequelize.FLOAT,
        allowNull: true
      }
    });
  },
  down: (queryInterface, Sequelize) => {
    return queryInterface.dropTable('WIN');
  }
};