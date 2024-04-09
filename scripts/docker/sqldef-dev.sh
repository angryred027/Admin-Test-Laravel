#!/bin/sh

# CURRENT_DIR=$(cd $(dirname $0); pwd)
DELIMITER_LINE='------------------------------------------------------'
START_MESSAGE='check container status.'
DOCKER_COMPOSE_FILE='./docker-compose.sqldef.yml'

# @param {string} message
showMessage() {
  echo ${DELIMITER_LINE}
  echo $1
}

showMessage ${START_MESSAGE}

# parameter count ($#) check。
# if [ $# -ne 1 ]; then
#   echo "You had set $# parameters." 1>&2
#   echo "You need 1 parameters to exectute this batch." 1>&2
#   exit 1
# fi

# -qオプション container idのみを表示
# /dev/null: 出力が破棄され、なにも表示されない。
# 2(標準エラー出力) を/dev/nullに破棄することで、1(標準出力)のみを出力する。
if [[ "$(docker-compose -f ${DOCKER_COMPOSE_FILE} ps -q 2>/dev/null)" == "" ]]; then
  # コンテナが立ち上がっていない状態の時
  showMessage 'Up Docker Container!'
  docker-compose -f ${DOCKER_COMPOSE_FILE} up -d
else
  # コンテナが立ち上がっている状態の時
  showMessage 'Down Docker Container!'
  docker-compose -f ${DOCKER_COMPOSE_FILE} down
fi

# 現在のDocker コンテナの状態を出力
showMessage 'Current Docker Status.'
docker-compose -f ${DOCKER_COMPOSE_FILE} ps

