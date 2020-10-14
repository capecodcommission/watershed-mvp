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
          ,[SUBEM_ID]
          ,[SUBEM_NAME]
          ,[SUBEM_DISP]
          ,[EMBAY_ID]
          ,[EMBAY_NAME]
          ,[EMBAY_DISP]
          ,[Nload_Sept]
          ,[Nload_Fert]
          ,[Nload_Storm]
          ,[Nload_Total]
          ,[Nload_Parcels]
          ,[Sept_Tar_Kg]
          ,[Total_Tar_Kg]
          ,[MEP_Sept_Tar_Kg]
          ,[MEP_Total_Tar_Kg]
          ,[MEP_Source]
          ,[MEP_Sept_Tar_p]
          ,[MEP_Total_Tar_p]
          ,[X_Centroid]
          ,[Y_Centroid]
          ,[Acreage]
          ,convert(nvarchar(max),[Shape]) as Shape
          ,[GeoString]
          ,[ParcSEPTIC]
          ,[ParcGWDP]
          ,[ParcSEWERED]
        FROM [wMVP4].[CapeCodMA].[SubEmbayments]
      `)
      .then((result) => {
        return queryInterface.bulkInsert('SubEmbayments', result.recordset)
      })
    })
  },

  down: (queryInterface, Sequelize) => {

    return queryInterface.bulkDelete('SubEmbayments', null, {});
  }
};
