#!/usr/bin/env bash
set -ex
source .env

until PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -U $DB_USERNAME -d $DB_DATABASE -c '\q'; do
  >&2 echo "Postgres is unavailable - sleeping"
  sleep 15
done

>&2 echo "Postgres is up - executing command"
npx sequelize db:migrate 
npx sequelize db:seed:all