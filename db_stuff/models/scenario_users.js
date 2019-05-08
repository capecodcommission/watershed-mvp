'use strict';
module.exports = (sequelize, DataTypes) => {
  const Scenario_Users = sequelize.define('Scenario_Users', {
    user_id: DataTypes.INTEGER,
    name: DataTypes.TEXT,
    email: DataTypes.TEXT,
    password: DataTypes.TEXT,
    created_at: DataTypes.DATE,
    updated_at: DataTypes.DATE,
    deleted_at: DataTypes.DATE,
    remember_token: DataTypes.TEXT
  }, {
    freezeTableName: true
  });
  Scenario_Users.associate = function(models) {
    // associations can be defined here
  };
  return Scenario_Users;
};