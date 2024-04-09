#!/bin/sh

# 日時ランジパーティションを指定してダンプを取得するバッチ

# CURRENT_DIR=$(cd $(dirname $0); pwd)
DELIMITER_LINE='------------------------------------------------------'
START_MESSAGE='start getting database range partition dump.'

# dateコマンド結果を指定のフォーマットで出力
TIME_STAMP=$(date "+%Y%m%d_%H%M%S")

# CHANGE Variable.
DATABASE_CONTAINER_NAME=database_container_name
DATABASE_USER=database_user
DATABASE_PASSWORD=database_password
DATABASE_NAME=database_name
TABLE_NAME=table_name
COLUMN_NAME=colume_name
START_DATE=start_datetime # ex: '2023-01-14 00:00:00'
END_DATE=end_datetime # ex: '2023-01-14 23:59:59'
OUTPUT_FILE=scripts/database/dump_${TIME_STAMP}.sql # 存在するディレクトリである必要がある(scripts/databaseなど)

# @param {string} message
showMessage() {
  echo ${DELIMITER_LINE}
  echo $1
}

# process start
showMessage ${START_MESSAGE}

# dump command.

docker exec -it ${DATABASE_CONTAINER_NAME} \
mysqldump -u ${DATABASE_USER} -p${DATABASE_PASSWORD} \
--default-character-set=utf8 --complete-insert --no-create-info \
--where="$COLUMN_NAME BETWEEN '$START_DATE' AND '$END_DATE'" ${DATABASE_NAME} ${TABLE_NAME} \
> ${OUTPUT_FILE}

# docker exec -it ${DATABASE_CONTAINER_NAME} \
# mysqldump -u ${DATABASE_USER} -p${DATABASE_PASSWORD} \
# --default-character-set=utf8 --complete-insert --no-create-info \
# --where="$COLUMN_NAME>='$START_DATE' AND $COLUMN_NAME<'$END_DATE'" ${DATABASE_NAME} ${TABLE_NAME} \
# > ${OUTPUT_FILE}

showMessage 'get database range partition dump.'

