#!/bin/sh

CURRENT_DIR=$(cd $(dirname $0); pwd)
DELIMITER_LINE='------------------------------------------------------'
START_MESSAGE='check database host.'
FILE_NAME='.env'

# $1 : current database name
# $2 : target database name

# @param {string} message
showMessage() {
  echo ${DELIMITER_LINE}
  echo $1
}

changeDBHost() {
  PROJECT_NAME='backend'

  # ^: 行頭
  # $: 行末
  cd ${CURRENT_DIR}/../app/${PROJECT_NAME} && \
  sed -i -e "s/^DB_MASTER_HOST=$1$/DB_MASTER_HOST=$2/g" ${FILE_NAME} && \
  rm -rf ${FILE_NAME}-e
}

# -nオプション: 文字列の長さが1以上の場合はtrue
# -zオプション: 文字列の長さが0の場合はtrue
if [ -z $1 ]; then
  showMessage 'first parameter is empty, exit script.'
elif [ -z $2 ]; then
  showMessage 'secand parameter is empty, exit script.'
else
  showMessage "${START_MESSAGE}"

  changeDBHost $1 $2


  showMessage "Change Database Host From $1 to $2"
fi



