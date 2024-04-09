#!/bin/sh

# CURRENT_DIR=$(cd $(dirname $0); pwd)
DELIMITER_LINE='------------------------------------------------------'
START_MESSAGE='start AWS S3 bucket.'

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
  $AWS_CLI_PATH/aws s3 mb s3://"$BUCKET_NAME" --profile $1

  $AWS_CLI_PATH/aws s3api put-public-access-block --bucket "$BUCKET_NAME" --public-access-block-configuration "BlockPublicAcls=false,IgnorePublicAcls=false,BlockPublicPolicy=false,RestrictPublicBuckets=false" --profile $1
else
  # バケットの作成
  $AWS_CLI_PATH/aws s3 mb s3://"$BUCKET_NAME"

  # バケットのアクセスブロックの設定
  $AWS_CLI_PATH/aws s3api put-public-access-block --bucket "$BUCKET_NAME" --public-access-block-configuration "BlockPublicAcls=false,IgnorePublicAcls=false,BlockPublicPolicy=false,RestrictPublicBuckets=false"
fi

# TODO
# バケットポリシーを作成&S3バケットにアタッチ
# git管理しないサンプルのファイルからバケット名を置き換える形を想定
### ポリシーのアタッチ
# $AWS_CLI_PATH/aws s3api put-bucket-policy --bucket "$BUCKET_NAME" --policy file://bucketPolicy.json

showMessage "Create AWS S3 Bucket. $BUCKET_NAME"

