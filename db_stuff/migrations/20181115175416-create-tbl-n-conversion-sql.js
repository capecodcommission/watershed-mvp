'use strict';
module.exports = {
  up: (queryInterface, Sequelize) => {
    return queryInterface.createTable('TBL_NConversion_SQL', {
      id: {
        allowNull: false,
        autoIncrement: true,
        type: Sequelize.INTEGER
      },
      EMBAY_ID: {
        type: Sequelize.STRING,
        primaryKey: true,
        allowNull: true
      },
      EMBAY_DISP: {
        type: Sequelize.STRING,
        allowNull: true
      },
      EMBAY_NAME: {
        type: Sequelize.STRING,
        allowNull: true
      },
      MEP: {
        type: Sequelize.STRING,
        allowNull: true
      },
      Intercept: {
        type: Sequelize.STRING,
        allowNull: true
      },
      Slope: {
        type: Sequelize.STRING,
        allowNull: true
      },
      R_squared: {
        type: Sequelize.STRING,
        allowNull: true
      },
      Had_val: {
        type: Sequelize.STRING,
        allowNull: true
      }
    });
  },
  down: (queryInterface, Sequelize) => {
    return queryInterface.dropTable('TBL_NConversion_SQL');
  }
};