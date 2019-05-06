'use strict';
module.exports = {
  up: (queryInterface, Sequelize) => {
    return queryInterface.createTable('Scenario_Wiz', {
      id: {
        allowNull: false,
        autoIncrement: true,
        type: Sequelize.INTEGER
      },
      ScenarioID: {
        type: Sequelize.INTEGER,
        primaryKey: true
      },
      CreateDate: {
        type: Sequelize.STRING,
        allowNull: true
      },
      UpdateDate: {
        type: Sequelize.STRING,
        allowNull: true
      },
      Deleted: {
        type: Sequelize.BOOLEAN,
        allowNull: true
      },
      CreatedBy: {
        type: Sequelize.STRING,
        allowNull: true
      },
      ScenarioName: {
        type: Sequelize.STRING,
        allowNull: true
      },
      ScenarioDescription: {
        type: Sequelize.STRING,
        allowNull: true
      },
      ScenarioNotes: {
        type: Sequelize.TEXT,
        allowNull: true
      },
      AreaType: {
        type: Sequelize.STRING,
        allowNull: true
      },
      AreaID: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      AreaName: {
        type: Sequelize.STRING,
        allowNull: true
      },
      Nload_Existing: {
        type: Sequelize.FLOAT,
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
      Total_Parcels: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      Total_WaterUse: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Total_WaterFlow: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Sept_Target: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Total_Target: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Calculated_Total: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Cost_Total: {
        type: Sequelize.FLOAT,
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
      ScenarioPeriod: {
        type: Sequelize.STRING,
        allowNull: true
      },
      POLY_STRING: {
        type: Sequelize.TEXT,
        allowNull: true
      },
      ScenarioAcreage: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Calculated_Fert: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Calculated_SW: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Calculated_Septic: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Calculated_GW: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Calculated_InEmbay: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Calculated_Attenuation: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Reduction_Fert: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Reduction_SW: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Reduction_Septic: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Reduction_GW: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Reduction_Attenuation: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      Nload_Reduction_InEmbay: {
        type: Sequelize.FLOAT,
        allowNull: true
      },
      ScenarioProgress: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      ScenarioComplete: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      user_id: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      deleted_at: {
        type: Sequelize.DATE,
        allowNull: true
      },
      parcels_septic: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      parcels_sewer: {
        type: Sequelize.INTEGER,
        allowNull: true
      },
      parcels_gwdp: {
        type: Sequelize.INTEGER,
        allowNull: true
      }
    });
  },
  down: (queryInterface, Sequelize) => {
    return queryInterface.dropTable('Scenario_Wiz');
  }
};