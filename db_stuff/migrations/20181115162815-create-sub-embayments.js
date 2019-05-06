'use strict';
module.exports = {
  up: (queryInterface, Sequelize) => {
    return queryInterface.createTable('SubEmbayments', {
      id: {
        allowNull: false,
        autoIncrement: true,
        type: Sequelize.INTEGER
      },
      OBJECTID: {
        type: Sequelize.INTEGER,
        primaryKey: true
      },
      SUBEM_ID: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      SUBEM_NAME: {
        type: Sequelize.STRING,
        allowNull: true
      },
      SUBEM_DISP: {
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
      Nload_Sept: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Fert: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Storm: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Total: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Parcels: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      Sept_Tar_Kg: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Total_Tar_Kg: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      MEP_Sept_Tar_Kg: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      MEP_Total_Tar_Kg: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      MEP_Source: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      MEP_Sept_Tar_p: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      MEP_Total_Tar_p: {
        type: Sequelize.FLOAT,
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
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Shape: {
        type: Sequelize.GEOMETRY,
        allowNull: true
      },
      GeoString: {
        type: Sequelize.TEXT,
        allowNull: true
      },
      ParcSEPTIC: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      ParcGWDP: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      ParcSEWERED: {
        type: Sequelize.INTEGER,
        allowNull: true
      }
    });
  },
  down: (queryInterface, Sequelize) => {
    return queryInterface.dropTable('SubEmbayments');
  }
};