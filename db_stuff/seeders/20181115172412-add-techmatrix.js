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
          tm.[Technology ID] as Technology_ID,
          tm.ProjectCost_kg,
          tm.capFTE,
          tm.OMCost_kg,
          tm.omFTE,
          tm.Avg_Life_Cycle_Cost,
          tm.Useful_Life_Yrs,
          tm.NewCompat,
          tm.Resilience,
          t.n_percent_reduction_low,
          t.n_percent_reduction_high
      FROM Tech_Matrix.dbo.Technology_Matrix tm
      inner JOIN Tech_Matrix.dbo.technologies t
          ON tm.TM_ID = t.id
          and tm.Show_In_wMVP != 0
      `)
      .then((result) => {
        return queryInterface.bulkInsert('Tech_Matrix', result.recordset)
      })
    })
  },

  down: (queryInterface, Sequelize) => {

    return queryInterface.bulkDelete('Tech_Matrix', null, {});
  }
};
