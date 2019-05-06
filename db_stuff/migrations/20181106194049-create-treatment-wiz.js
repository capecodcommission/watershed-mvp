'use strict';
module.exports = {
  up: (queryInterface, Sequelize) => {
    return queryInterface.createTable('Treatment_Wiz', {
      id: {
        allowNull: false,
        autoIncrement: true,
        type: Sequelize.INTEGER
      },
      TreatmentID: {
        type: Sequelize.INTEGER,
        primaryKey: true
      },
      ScenarioID: {
        type: Sequelize.INTEGER,
        primaryKey: true,
        allowNull: true
      },
      TreatmentType_Name: {
        type: Sequelize.STRING,
        allowNull: true
      },
      TreatmentType_ID: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      Treatment_Class: {
        type: Sequelize.STRING,
        allowNull: true
      },
      Treatment_Value: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Treatment_PerReduce: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Treatment_UnitMetric: {
        type: Sequelize.STRING,
        allowNull: true
      },
      Treatment_MetricValue: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Cost_TC_Input: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Cost_OM_Input: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Treatment_Acreage: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Treatment_Parcels: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      CreateDate: {
        type: Sequelize.STRING,
        allowNull: true
      },
      UpdateDate: {
        type: Sequelize.STRING,
        allowNull: true
      },
      POLY_STRING: {
        type: Sequelize.GEOMETRY,
        allowNull: true
      },
      Custom_POLY: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      Cost_Capital: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Cost_OM: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Cost_Collection: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Cost_TransportDisposal: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Cost_NonConstruction: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Cost_Monitor: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Cost_Total: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Reduction: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Cost20yr_OM: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Cost20yr_Cap: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Cost_Replacement: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Treatment_Wastewater_Flow: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Clipped_Rds_LinFeet: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Treatment_WU_Parcels: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      Parent_TreatmentId: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      treatment_icon: {
        type: Sequelize.STRING,
        allowNull: true
      },
      Treatment_WaterUse: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
    });
  },
  down: (queryInterface, Sequelize) => {
    return queryInterface.dropTable('Treatment_Wiz');
  }
};