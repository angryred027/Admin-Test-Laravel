#!/bin/sh

# CURRENT_DIR=$(cd $(dirname $0); pwd)
DELIMITER_LINE='------------------------------------------------------'
START_MESSAGE='check container status.'
DOCKER_COMPOSE_FILE='./docker-compose.redis-cluster.yml'
DOCKER_NETWORK_NAME='cochlea-net'
DOCKER_SERVICE_NAME='redis-cluster'
DOCKER_REDIS_PORT=6379
REDIS_CLUSTER_REPLICA_COUNT=1

# @param {string} message
showMessage() {
  echo ${DELIMITER_LINE}
  echo $1
}

showMessage ${START_MESSAGE}

# parameter count ($#) check。
if [ $# -ne 1 ]; then
  echo "You had set $# parameters." 1>&2
  echo "You need 1 parameters to exectute this batch." 1>&2
  exit 1
fi

# -qオプション container idのみを表示
# /dev/null: 出力が破棄され、なにも表示されない。
# 2(標準エラー出力) を/dev/nullに破棄することで、1(標準出力)のみを出力する。
if [[ "$(docker-compose -f ${DOCKER_COMPOSE_FILE} ps -q 2>/dev/null)" == "" ]]; then
  # コンテナが立ち上がっていない状態の時
  showMessage 'Up Docker Container!'
  docker-compose -f ${DOCKER_COMPOSE_FILE} up -d --scale ${DOCKER_SERVICE_NAME}=$1

  # dockerに割り当てられたIPの取得(jqコマンドを利用)
  TARGET_IPS=`docker network inspect ${DOCKER_NETWORK_NAME} | jq '.[0].Containers | .[].IPv4Address'`;
  # cluster設定を行うIPとportの組み合わせ
  CLUSTER_IPS_STRING=''
  for TARGET_IP in ${TARGET_IPS[@]}
    do
      # 各IPアドレスの取得(「"」とは不要の為削除)
      TMP_IP="${TARGET_IP//\"/}"
      # ネットワーク部指定の為のCIDR表記のbit指定も削除
      echo "Target IP is: ${TMP_IP//\/16/}:${DOCKER_REDIS_PORT}"
      # 文字列連結
      CLUSTER_IPS_STRING="${CLUSTER_IPS_STRING} ${TMP_IP//\/16/}:${DOCKER_REDIS_PORT}"
  done
  echo ${CLUSTER_IPS_STRING}

  # redis clusterの作成(対話形式の箇所がある為パラメーターを渡す)
  # docker compose exec redis-cluster redis-cli --cluster create ${CLUSTER_IPS_STRING} --cluster-replicas ${REDIS_CLUSTER_REPLICA_COUNT}
  docker compose exec ${DOCKER_SERVICE_NAME} ash -c "echo yes | redis-cli --cluster create ${CLUSTER_IPS_STRING} --cluster-replicas ${REDIS_CLUSTER_REPLICA_COUNT}"

  # clusterの設定確認
  echo ${DELIMITER_LINE}
  docker-compose exec ${DOCKER_SERVICE_NAME} redis-cli cluster nodes
  # networkの設定確認
  echo ${DELIMITER_LINE}
  docker network inspect ${DOCKER_NETWORK_NAME} | jq '.[0].Containers | .[] | {Name, IPv4Address}'
else
  # コンテナが立ち上がっている状態の時
  showMessage 'Down Docker Container!'
  # 全redisコンテナのvolumeも削除する
  docker-compose -f ${DOCKER_COMPOSE_FILE} down -v
fi

# 現在のDocker コンテナの状態を出力
showMessage 'Current Docker Status.'
docker-compose -f ${DOCKER_COMPOSE_FILE} ps

