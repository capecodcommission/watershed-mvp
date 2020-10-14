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
        SELECT
          [OBJECTID]
          ,[SUBWATER_ID]
          ,[SUBWATER_NAME]
          ,[SUBWATER_DISP]
          ,[EMBAY_ID]
          ,[EMBAY_NAME]
          ,[EMBAY_DISP]
          ,[X_Centroid]
          ,[Y_Centroid]
          ,[Acreage]
          ,convert(nvarchar(max),[Shape]) as Shape
          ,[GeoString]
        FROM [wMVP4].[CapeCodMA].[Subwatersheds]
      `)
      .then((result) => {
        return queryInterface.bulkInsert('Subwatersheds', result.recordset)
      })
    })
  },

  down: (queryInterface, Sequelize) => {

    return queryInterface.bulkDelete('Subwatersheds', null, {});
  }
};
