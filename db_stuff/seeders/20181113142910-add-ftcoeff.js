var sql = require("mssql");
const Sequelize = require('sequelize')
const config = require('../config/config.json')

var wmvpConnect = new sql.ConnectionPool(config.wmvpConfig)
var sequelize = new Sequelize(config.development);

module.exports = {
  up: (queryInterface, Sequelize) => {
    return Promise.all([
      wmvpConnect.connect(),
      sequelize.authenticate()
    ]).then(([]) => {
      var request = new sql.Request(wmvpConnect)
      return request.query('select * from CapeCodMA.FTCoeff')
      .then((result) => {
        return queryInterface.bulkInsert('FTCoeff', result.recordset)
      })
    })
  },

  down: (queryInterface, Sequelize) => {

    return queryInterface.bulkDelete('FTCoeff', null, {});
  }
};
