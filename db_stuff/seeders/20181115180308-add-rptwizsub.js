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
        [subem_id]
        ,[subem_disp]
        ,[embay_id]
        ,[n_load_att]
        ,[n_load_unatt]
        ,convert(nvarchar(max),[shape]) as shape
        ,[n_load_target]
      FROM [wMVP4].[CapeCodMA].[rpt_wiz_subembayments]`)
      .then((result) => {
        return queryInterface.bulkInsert('rpt_wiz_subembayments', result.recordset)
      })
    })
  },

  down: (queryInterface, Sequelize) => {

    return queryInterface.bulkDelete('rpt_wiz_subembayments', null, {});
  }
};
