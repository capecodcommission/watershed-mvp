var sql = require("mssql");
const Sequelize = require('sequelize')
const config = require('../config/config.json')

var wmvpConnect = new sql.ConnectionPool(config.wmvpConfig)
var sequelize = new Sequelize(config.development);

var request = new sql.Request(wmvpConnect)
request.stream = true

module.exports = {
  up: (queryInterface, Sequelize) => {
    return Promise.all([
      wmvpConnect.connect(),
      sequelize.authenticate()
    ])
    .then(([]) => {
      var batchRows = []
      request.on('row', row => {
        batchRows.push(row)
        if (batchRows.length === 30000 ) {
          queryInterface.bulkInsert('WIN',batchRows).catch((err) => {console.log('bulkInsert error: ',err)})
          batchRows = []
        }
        return
      })
      request.on('done', result => {
        queryInterface.bulkInsert('WIN',batchRows).catch((err) => {console.log('bulkInsert error: ',err)})
        batchRows = []
        console.log('done', result)
        return
      })
      return request.query(`
        SELECT
          [OBJECTID_1]
          ,[Muni_ID]
          ,[Other_ID]
          ,[POINT_X]
          ,[POINT_Y]
          ,[Embayment]
          ,[MEPSubwate]
          ,[WaterUseExisting]
          ,[NLoadExisting]
          ,[Waterfront]
          ,[TotalAssessedValue]
          ,[NewSLIRM]
          ,[GCScore]
          ,[GCabs]
          ,[WWTreatmentExisting]
          ,convert(nvarchar(max),[SHAPE]) as SHAPE
          ,[SUBWATER_ID]
          ,[EconDevType]
          ,[DensityCat]
          ,[BioMap2]
          ,[CWMP]
          ,[NaturalAttenuation]
        FROM [TBL_Dev].[dbo].[WIN]
      `)
      .catch((err) => {
        console.log('query error: ',err)
      })
    })
    .catch((err) => {
      console.log('connection pool error: ',err)
    })
  },

  down: (queryInterface, Sequelize) => {

    return queryInterface.bulkDelete('WIN', null, {});
  }
};
