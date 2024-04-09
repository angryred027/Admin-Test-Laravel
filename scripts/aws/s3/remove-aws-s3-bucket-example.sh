#!/bin/sh

# CURRENT_DIR=$(cd $(dirname $0); pwd)
DELIMITER_LINE='------------------------------------------------------'
START_MESSAGE='remove AWS S3 bucket.'

# CHANGE Variable.
BUCKET_NAME=bucket_name
AWS_CLI_PATH=$HOME/path/to


# @param {string} message
showMessage() {
  echo ${DELIMITER_LINE}
  echo $1
}

# process start
showMessage "$START_MESSAGE"

# parameter check
if [ "$1" != '' ]; then
  # バケットの中身の削除
  $AWS_CLI_PATH/aws s3 rm s3://"$BUCKET_NAME" --recursive --profile $1

  # バケットの削除
  $AWS_CLI_PATH/aws s3 rb s3://"$BUCKET_NAME" --profile $1
else
  # バケットの中身の削除
  $AWS_CLI_PATH/aws s3 rm s3://"$BUCKET_NAME" --recursive

  # バケットの削除
  $AWS_CLI_PATH/aws s3 rb s3://"$BUCKET_NAME"
fi

showMessage "Remove AWS S3 Bucket. $BUCKET_NAME"

