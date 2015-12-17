#!/bin/bash

# check a DB instance is linked
if [ -z "$DB_PORT_3306_TCP_ADDR" ]; then
  echo "You have to link a mysql container with name db"
fi

# rename environemnt variables from linked DB
export DB_NAME=$DB_ENV_MYSQL_DATABASE
export DB_USER=root
export DB_PWD=$DB_ENV_MYSQL_ROOT_PASSWORD
export DB_HOST=$DB_PORT_3306_TCP_ADDR
export DB_PORT=$DB_PORT_3306_TCP_PORT

exec "$@"
