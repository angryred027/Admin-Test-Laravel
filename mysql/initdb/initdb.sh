#!/bin/sh

# CURRENT_DIR=$(cd $(dirname $0); pwd)
DELIMITER_LINE='------------------------------------------------------'
START_MESSAGE='start database initialization.'

# CHANGE Variable.

# @param {string} message
showMessage() {
  echo ${DELIMITER_LINE}
  echo $1
}

# process start
showMessage ${START_MESSAGE}

# mysql -u ${DB_USER} -p${MYSQL_ROOT_PASSWORD} ${DB_DATABASE}

databaseList=(
cochlea
cochlea_logs
cochlea_user1
cochlea_user2
cochlea_user3
cochlea_testing
)

mysql -u ${DB_USER} -p${MYSQL_ROOT_PASSWORD} -e "CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY '${MYSQL_ROOT_PASSWORD}';"

for d in ${databaseList[@]}; do
  # database setting
  mysql -u ${DB_USER} -p${MYSQL_ROOT_PASSWORD} -e "CREATE DATABASE IF NOT EXISTS ${d} CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"

  # user setting
  mysql -u ${DB_USER} -p${MYSQL_ROOT_PASSWORD} -D $d -e "GRANT ALL PRIVILEGES ON ${d}.* TO 'root'@'%';"
  mysql -u ${DB_USER} -p${MYSQL_ROOT_PASSWORD} -D $d -e "FLUSH PRIVILEGES;"
done

## slaveアカウント
mysql -u ${DB_USER} -p${MYSQL_ROOT_PASSWORD} -e "CREATE USER IF NOT EXISTS 'repl'@'%' IDENTIFIED BY '${MYSQL_ROOT_PASSWORD}';"
mysql -u ${DB_USER} -p${MYSQL_ROOT_PASSWORD} -e "GRANT REPLICATION SLAVE ON *.* TO 'repl'@'%';"

showMessage 'initialize database.'

