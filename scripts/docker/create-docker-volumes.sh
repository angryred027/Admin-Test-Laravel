#!/bin/sh

# CURRENT_DIR=$(cd $(dirname $0); pwd)
DELIMITER_LINE='------------------------------------------------------'
START_MESSAGE='create docker-volume, network'
PROJECT_NAME='projectName'
PROJECT_NAME='laravel-admin-test'

# @param {string} message
showMessage() {
  echo ${DELIMITER_LINE}
  echo $1
}

showMessage ${START_MESSAGE}

docker volume create ${PROJECT_NAME}-db-volume
docker volume create ${PROJECT_NAME}-redis-volume
# docker volume create ${PROJECT_NAME}-mail-volume
# docker network create --gateway=172.19.0.1 --subnet=172.19.0.0/16 ${PROJECT_NAME}-net
docker network create --gateway=172.21.0.1 --subnet=172.21.0.0/16 ${PROJECT_NAME}-net

### コンテナのIPアドレスの確認(jqが入っている場合)
docker network inspect ${PROJECT_NAME}-net | jq '.[0].Containers | .[].IPv4Address'


