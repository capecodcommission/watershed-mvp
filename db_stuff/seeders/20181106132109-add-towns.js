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
      return request.query('select * from dbo.wiz_treatment_towns')
      .then((result) => {
        return queryInterface.bulkInsert('wiz_treatment_towns', result.recordset)
      })
    })
  },

  down: (queryInterface, Sequelize) => {

    return queryInterface.bulkDelete('wiz_treatment_towns', null, {});
  }
};
