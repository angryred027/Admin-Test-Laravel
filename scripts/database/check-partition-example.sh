#!/bin/sh

# CURRENT_DIR=$(cd $(dirname $0); pwd)
DELIMITER_LINE='------------------------------------------------------'
START_MESSAGE='Start Database Partition Check.'

# dateコマンド結果を指定のフォーマットで出力
# TIME_STAMP=$(date "+%Y%m%d_%H%M%S")
# CURRENT_DATE=$(date "+%Y%m%d")
# CURRENT_MONTH_PARTITION=$(date "+%Y%m")
# CURRENT_MONTH=$(date "+%Y-%m-01")

# CHANGE Variable.
DATABASE_CONTAINER_NAME=database_container_name
DATABASE_USER=database_user
DATABASE_PASSWORD=database_password
DATABASE_NAME=database_name
TARGET_TABLE=table_name

# SQL Command
CHECK_PARTITION_COMMAND="
SELECT
TABLE_SCHEMA, TABLE_NAME, PARTITION_NAME, PARTITION_ORDINAL_POSITION, TABLE_ROWS
FROM INFORMATION_SCHEMA.PARTITIONS
WHERE TABLE_NAME='${TARGET_TABLE}'
"
GET_CURRENT_DATE_COMMAND="SELECT DATE_FORMAT(CURRENT_DATE() , '%Y%m%d')"
# GET_1_DAY_LATER_DATE_COMMAND="SELECT DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL 1 DAY) , '%Y%m%d')"
GET_1_DAY_LATER_DATE_COMMAND="SELECT DATE_ADD(CURRENT_DATE(), INTERVAL 1 DAY)"
GET_3_MONTH_LATER_DATE_COMMAND="SELECT DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL 3 MONTH) , '%Y%m%d')"

# @param {string} message
showMessage() {
  echo ${DELIMITER_LINE}
  echo $1
}

execSQL() {
  # 標準入力でSQLを実行
  echo $1 | docker exec -i ${DATABASE_CONTAINER_NAME} mysql -h localhost -u ${DATABASE_USER} -p${DATABASE_PASSWORD} -D ${DATABASE_NAME}
}

addPrtition() {
  execSQL "
ALTER TABLE "${DATABASE_NAME}"."${TARGET_TABLE}"
PARTITION BY RANGE COLUMNS(created_at) (
  PARTITION p"$1" VALUES LESS THAN ('"$2" 00:00:00')
)
"
}

CHECK_PARTITION_RESULT="$(execSQL "${CHECK_PARTITION_COMMAND}")"
GET_CURRENT_DATE_RESULT="$(execSQL "${GET_CURRENT_DATE_COMMAND}")"
GET_1_DAY_LATER_DATE_RESULT="$(execSQL "${GET_1_DAY_LATER_DATE_COMMAND}")"
GET_3_MONTH_LATER_DATE_RESULT="$(execSQL "${GET_3_MONTH_LATER_DATE_COMMAND}")"

getCurrentDateTime() {
  for line in ${GET_CURRENT_DATE_RESULT[@]};
  do
    RESULT+=($line)
    # echo "${line}"
  done
  echo "${RESULT[3]}"
}

get1DayLaterDateTime() {
  for line in ${GET_1_DAY_LATER_DATE_RESULT[@]};
  do
    RESULT+=($line)
    # echo "${line}"
  done
  echo "${RESULT[4]}"
}

get3MonthLaterDateTime() {
  for line in ${GET_3_MONTH_LATER_DATE_RESULT[@]};
  do
    RESULT+=($line)
    # echo "${line}"
  done
  echo "${RESULT[6]}"
}

mainExecuion() {
  # parition check
  for line in ${CHECK_PARTITION_RESULT[@]};
  do
    # １行ずつ配列の末尾に格納する
    PARTITION_RESULT+=($line)
    # echo "${line}"
  done

  # 配列の長さの判定
  if [ "${#PARTITION_RESULT[@]}" -eq 0 ]; then
    echo "Failed."
    exit
  fi

  if [ "${PARTITION_RESULT[7]}" != "NULL" ] && [ "${PARTITION_RESULT[8]}" != "NULL" ]; then
    showMessage "
      Latest Partition Name   is ${PARTITION_RESULT[7]}.
      \nLatest Partition Position is ${PARTITION_RESULT[8]}.
    "
  fi

  # PARTITION_ORDINAL_POSITIONカラムの値(空では無い時は-n, 空の時は-zで判定する時もある)
  if [ "${PARTITION_RESULT[8]}" == "NULL" ]; then
    showMessage "No Partitions. Now Set Partition."
    echo "Set Partition"

    TARGET_DATE="$(getCurrentDateTime)"
    NEXT_DATE="$(get1DayLaterDateTime)"
    echo "${TARGET_DATE}"
    echo "${NEXT_DATE}"
    addPrtition "${TARGET_DATE}" "${NEXT_DATE}"
    # exit
  fi
}

# process start
showMessage ${START_MESSAGE}

# main
mainExecuion

showMessage "End Execution."

# echo "${CHECK_PARTITION_RESULT}"

