var sql = require("mssql");
const Sequelize = require('sequelize')
const config = require('../config/config.js')

var wmvpConnect = new sql.ConnectionPool(config.wmvpConfig)
var sequelize = new Sequelize(config.development);

module.exports = {
  up: (queryInterface, Sequelize) => {
    return Promise.all([
      wmvpConnect.connect(),
      sequelize.authenticate()
    ]).then(([]) => {
      var request = new sql.Request(wmvpConnect)
      return request.query(`
        select 
          [TreatmentID]
          ,[ScenarioID]
          ,[TreatmentType_Name]
          ,[TreatmentType_ID]
          ,[Treatment_Class]
          ,[Treatment_Value]
          ,[Treatment_PerReduce]
          ,[Treatment_UnitMetric]
          ,[Treatment_MetricValue]
          ,[Cost_TC_Input]
          ,[Cost_OM_Input]
          ,[Treatment_Acreage]
          ,[Treatment_Parcels]
          ,[CreateDate]
          ,[UpdateDate]
          , CASE
              WHEN LEFT(Treatment_Wiz.POLY_STRING, 5) = 'POINT' AND
                (RIGHT(Treatment_Wiz.POLY_STRING, 5) = '4326)' OR
                RIGHT(Treatment_Wiz.POLY_STRING, 5) = '3857)') THEN STUFF(REPLACE(REPLACE(Treatment_Wiz.POLY_STRING, ', 4326', ''), ', 3857', ''), CHARINDEX(',', REPLACE(REPLACE(Treatment_Wiz.POLY_STRING, ', 4326', ''), ', 3857', '')), LEN(','), '')
              ELSE Treatment_Wiz.POLY_STRING
            END as POLY_STRING
          ,[Custom_POLY]
          ,[Cost_Capital]
          ,[Cost_OM]
          ,[Cost_Collection]
          ,[Cost_TransportDisposal]
          ,[Cost_NonConstruction]
          ,[Cost_Monitor]
          ,[Cost_Total]
          ,[Nload_Reduction]
          ,[Cost20yr_OM]
          ,[Cost20yr_Cap]
          ,[Cost_Replacement]
          ,[Treatment_Wastewater_Flow]
          ,[Clipped_Rds_LinFeet]
          ,[Treatment_WU_Parcels]
          ,[Parent_TreatmentId]
          ,[treatment_icon]
          ,[Treatment_WaterUse]
        from CapeCodMA.Treatment_Wiz
        where Treatment_Wiz.POLY_STRING NOT LIKE '%POLYGON((POLYGON %'
      `)
      .then((result) => {
        return queryInterface.bulkInsert('Treatment_Wiz', result.recordset)
      })
      // .then(() => {
      //   return queryInterface.changeColumn('Treatment_Wiz', 'POLY_STRING', {
      //     type: Sequelize.GEOMETRY
      //   })
      // })
    })
  },

  down: (queryInterface, Sequelize) => {

    return queryInterface.bulkDelete('Treatment_Wiz', null, {});
  }
};
