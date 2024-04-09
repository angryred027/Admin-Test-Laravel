#!/bin/sh

# MacOSのtimeコマンドはfオプションが使えない為必ずLinux上で実行すること

# CURRENT_DIR=$(cd $(dirname $0); pwd)
DELIMITER_LINE='------------------------------------------------------'
START_MESSAGE='start check performance.'

# dateコマンド結果を指定のフォーマットで出力
TIME_STAMP=$(date "+%Y%m%d_%H%M%S")

# CHANGE Variable.
CONTAINER_NAME=app
DOCKER_COMPOSE_PATH="/path/to/docker-compose"
COMMAND_OPTION='/usr/bin/time -f "\n real:%e[sec]\n user:%U[sec]\n sys:%S[sec]\n Memory:%M[KB]" php ./path/to/example.php'

# @param {string} message
showMessage() {
  echo ${DELIMITER_LINE}
  echo $1
}

# process start
showMessage "$START_MESSAGE $TIME_STAMP"

"$DOCKER_COMPOSE_PATH" exec "$CONTAINER_NAME" ash -c "$COMMAND_OPTION"

showMessage 'finish check performance.'

