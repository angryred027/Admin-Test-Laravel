#!/bin/sh

DELIMITER_LINE='------------------------------------------------------'
START_MESSAGE='check container status.'
echo ${DELIMITER_LINE}
echo ${START_MESSAGE}

# プロセスチェック結果を変数に格納
CHECK_PROCESS_MESSAGE=`docker-compose ps`
# docker-compose ps | head -5

# 配列の用意
LINES=()

# プロセスチェック結果を1行ごと配列に格納
for line in ${CHECK_PROCESS_MESSAGE[@]};
do
  # １行ずつ配列の末尾に格納する
  LINES+=($line)
done

# 配列の長さを取得
LINES_LENGTH=`echo ${#LINES[@]}`

echo ${DELIMITER_LINE}

# チェック結果の長さごとに実行するコマンドの制御を行う
if [ ${LINES_LENGTH} -eq 5 ]; then
  # コンテナが立ち上がっていない状態の時
  echo 'Up Docker Container!'
  echo ${DELIMITER_LINE}
  docker-compose up -d
elif [ ${LINES_LENGTH} -eq 22 ]; then
  # コンテナが立ち上がっている状態の時
  # Exitなどのエラー状態の判別が出来ればより便利
  echo 'Down Docker Container!'
  echo ${DELIMITER_LINE}
  docker-compose down
else
  # コンテナが立ち上がっている状態の時
  echo 'Down Docker Container!'
  echo ${DELIMITER_LINE}
  docker-compose down
fi

# 現在のDocker コンテナの状態を出力
echo ${DELIMITER_LINE}
echo 'Current Docker Status.'
echo ${DELIMITER_LINE}
docker-compose ps

