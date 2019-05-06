var sql = require("mssql");
const Sequelize = require('sequelize')
const config = require('../config/config.js')

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
          queryInterface.bulkInsert('parcelMaster',batchRows).catch((err) => {console.log('bulkInsert error: ',err)})
          batchRows = []
        }
        return
      })
      request.on('done', result => {
        queryInterface.bulkInsert('parcelMaster',batchRows).catch((err) => {console.log('bulkInsert error: ',err)})
        batchRows = []
        console.log('done', result)
        return
      })
      return request.query(`
        select 
          [row_id]
          ,[parcel_id]
          ,[town_id]
          ,[subwater_id]
          ,[treatment_id]
          ,[treatment_type_id]
          ,[treatment_class]
          ,[treatment_name]
          ,[scenario_id]
          ,[ww_class]
          ,convert(nvarchar(150),geo_point) as geo_point
          ,[ww_flow]
          ,[init_nload_septic]
          ,[init_nload_fert]
          ,[init_nload_storm]
          ,[init_nload_atmosphere]
          ,[init_nload_total]
          ,[att_init_nload_total]
          ,[running_nload_septic]
          ,[running_nload_fert]
          ,[running_nload_storm]
          ,[running_nload_atmosphere]
          ,[running_nload_total]
          ,[att_running_nload_total]
          ,[running_nload_treated]
          ,[running_nload_removed]
          ,[final_nload_septic]
          ,[final_nload_fert]
          ,[final_nload_storm]
          ,[final_nload_atmosphere]
          ,[final_nload_total]
          ,[att_final_nload_total]
          ,[final_nload_treated]
          ,[final_nload_removed]
        from CapeCodMA.parcelMaster
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

    return queryInterface.bulkDelete('parcelMaster', null, {});
  }
};
