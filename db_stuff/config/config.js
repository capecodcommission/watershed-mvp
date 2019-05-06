require('dotenv').config()
module.exports = 
{
  "development": {
    "username": process.env.DB_USERNAME,
    "password": process.env.DB_PASSWORD,
    "database": process.env.DB_DATABASE,
    "host": process.env.DB_HOST,
    "dialect": "postgresql"
  },
  "test": {
    "username": "root",
    "password": null,
    "database": "database_test",
    "host": "127.0.0.1",
    "dialect": "mysql"
  },
  "production": {
    "username": "root",
    "password": null,
    "database": "database_production",
    "host": "127.0.0.1",
    "dialect": "mysql"
  },
  "wmvpConfig": {
    "user": process.env.LEGACY_USER,
    "password": process.env.LEGACY_PW,
    "server": process.env.LEGACY_HOST,
    "port": process.env.LEGACY_PORT,
    "database": process.env.DB_DATABASE,
    "stream": true,
    "requestTimeout": 3000000,
    "connectionTimeout": 3000000,
    "pool": {
      "max": 1000000,
      "min": 1,
      "idleTimeoutMillis": 3000000,
      "evictionRunIntervalMillis": 5,
      "softIdleTimeoutMillis": 5
    }
  }
}
