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
          ,[TOWN_ID]
          ,[TOWN]
          ,convert(nvarchar(max),[SHAPE]) as SHAPE
          ,[SHAPE_AREA]
          ,[SHAPE_LEN] 
          ,[GEOSTRING]
          ,[TOTAL_WU_PAR]
          ,[TOTAL_PAR]
          ,[MEAN_BLDG_VAL_PRI]
          ,[MEAN_BLDG_VAL_SEC]
          ,[TOT_ASSESSED_VAL]
        FROM [wMVP4].[CapeCodMA].[MATowns]
      `)
      .then((result) => {
        return queryInterface.bulkInsert('MATowns', result.recordset)
        // .then(() => {
        //   return queryInterface.changeColumn('MATowns','GEOSTRING', {
        //     type: Sequelize.GEOMETRY
        //   })
        // })
      })
    })
  },

  down: (queryInterface, Sequelize) => {

    return queryInterface.bulkDelete('MATowns', null, {});
  }
};
