#!/bin/sh

# CURRENT_DIR=$(cd $(dirname $0); pwd)
DELIMITER_LINE='------------------------------------------------------'
START_MESSAGE='check container status.'
DOCKER_COMPOSE_FILE='./docker-compose.swagger.yml'

BASE_HOST=http://localhost
SWAGGER_EDIOTER_PORT=8100
SWAGGER_UI_PORT=8200
SWAGGER_MOCK_PORT=3200

LAST_MESSAGE=''

# @param {string} message
showMessage() {
  echo ${DELIMITER_LINE}
  echo $1
}

showMessage ${START_MESSAGE}

# -qオプション container idのみを表示
# /dev/null: 出力が破棄され、なにも表示されない。
# 2(標準エラー出力) を/dev/nullに破棄することで、1(標準出力)のみを出力する。
if [[ "$(docker-compose -f ${DOCKER_COMPOSE_FILE} ps -q 2>/dev/null)" == "" ]]; then
  # コンテナが立ち上がっていない状態の時
  showMessage 'Up Docker Container!'
  docker-compose -f ${DOCKER_COMPOSE_FILE} up -d

  LAST_MESSAGE="Swagger Local Host is Here EDIOTER ${BASE_HOST}:${SWAGGER_EDIOTER_PORT} & UI ${BASE_HOST}:${SWAGGER_UI_PORT} & MOCK ${BASE_HOST}:${SWAGGER_MOCK_PORT}"
else
  # コンテナが立ち上がっている状態の時
  showMessage 'Down Docker Container!'
  docker-compose -f ${DOCKER_COMPOSE_FILE} down
fi

# 現在のDocker コンテナの状態を出力
showMessage 'Current Docker Status.'
docker-compose -f ${DOCKER_COMPOSE_FILE} ps

if [[ ${LAST_MESSAGE} != "" ]]; then
  showMessage "${LAST_MESSAGE}"
fi

