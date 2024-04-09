#!/bin/sh

# CURRENT_DIR=$(cd $(dirname $0); pwd)
DELIMITER_LINE='------------------------------------------------------'
START_MESSAGE='start getting database dump.'

# dateコマンド結果を指定のフォーマットで出力
TIME_STAMP=$(date "+%Y%m%d_%H%M%S")

# CHANGE Variable.
DATABASE_CONTAINER_NAME=database_container_name
DATABASE_USER=database_user
DATABASE_PASSWORD=database_password
DATABASE_NAME=database_name
OUTPUT_FILE=scripts/database/dump_${TIME_STAMP}.sql # 存在するディレクトリである必要がある(scripts/databaseなど)
SECURE_FILE_PRIV_DIR=/var/lib/mysql-files
OUTPUT_CSV_FILE=scripts/database/dump_${TIME_STAMP}.csv
CSV_FILES_DIR=mysql/files

# @param {string} message
showMessage() {
  echo ${DELIMITER_LINE}
  echo $1
}

# process start
showMessage ${START_MESSAGE}

# parameter check
if [ "$1" != '' ]; then
  if [ "$1" == 'gz' ]; then
    docker exec -it ${DATABASE_CONTAINER_NAME} mysqldump -u ${DATABASE_USER} -p${DATABASE_PASSWORD} ${DATABASE_NAME} | gzip > ${OUTPUT_FILE}.gz
  elif [ "$1" == 'csv' ]; then
    # only ouput in docker container
    docker exec -it ${DATABASE_CONTAINER_NAME} mysqldump -u ${DATABASE_USER} -p${DATABASE_PASSWORD} --tab=${SECURE_FILE_PRIV_DIR} --fields-terminated-by=, ${DATABASE_NAME}
    # txtファイルで出力される為、csvに変換
    ### txt形式のファイル一覧を取得(docker経由だとワイルドカード指定が出来ない)
    ### コンテナ内での実行結果を渡すと正しく文字列を整形出来ない為host側で実行する
    TARGET_FILES=`find ${CSV_FILES_DIR} -maxdepth 1 -name "*.txt" | sort`
    for TARGET_FILE in ${TARGET_FILES[@]}
      do
        # 文字列の最後に改行コードが含まれている為`echo -n`で削除する
        TMP=`echo -n ${TARGET_FILE}`
        # `-nの文字列の削除`
        TEXT_FILE_PATH="${TMP//-n /}"
        # 拡張子の置き換え
        CSV_FILE_PATH="${TEXT_FILE_PATH//.txt/.csv}"

        # mvで変更
        mv $TEXT_FILE_PATH $CSV_FILE_PATH

        # \Nをnullに変換
        # macOSの場合、バックアップファイル名を設定する必要がある。-eを指定するか、空文字列指定で回避出来る。
        # バックスラッシュを文字列として置き換えするにはバックスラッシュを4文字記載する
        sed -i '' "s/\\\\N/null/g" $CSV_FILE_PATH
    done
  elif [ "$1" == 'tsv' ]; then
    # no --fields-terminated-by option
    docker exec -it ${DATABASE_CONTAINER_NAME} mysqldump -u ${DATABASE_USER} -p${DATABASE_PASSWORD} --tab=${SECURE_FILE_PRIV_DIR} ${DATABASE_NAME}
  elif [ "$1" == 'ddlOnly' ]; then
    docker exec -it ${DATABASE_CONTAINER_NAME} mysqldump -u ${DATABASE_USER} -p${DATABASE_PASSWORD} ${DATABASE_NAME} -d -n --single-transaction > ${OUTPUT_FILE}
  elif [ "$1" == 'dataOnly' ]; then
    docker exec -it ${DATABASE_CONTAINER_NAME} mysqldump -u ${DATABASE_USER} -p${DATABASE_PASSWORD} -t ${DATABASE_NAME} --single-transaction > ${OUTPUT_FILE}
  else
    # parameter is table name.
    docker exec -it ${DATABASE_CONTAINER_NAME} mysqldump -u ${DATABASE_USER} -p${DATABASE_PASSWORD} ${DATABASE_NAME} $1 > ${OUTPUT_FILE}
  fi
else
  # dump command.
  docker exec -it ${DATABASE_CONTAINER_NAME} mysqldump -u ${DATABASE_USER} -p${DATABASE_PASSWORD} ${DATABASE_NAME} > ${OUTPUT_FILE}
fi

# docker exec -it ${DATABASE_CONTAINER_NAME} mysqldump -u ${DATABASE_USER} -p${DATABASE_PASSWORD} ${DATABASE_NAME} > ${OUTPUT_FILE}

# 現在のDocker コンテナの状態を出力
showMessage 'get data base dump.'

