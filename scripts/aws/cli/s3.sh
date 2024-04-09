#!/bin/bash

# CHANGE Variable.
REGION_NAME=ap-northeast-1
REGION_NAME_FOR_SUBNET=ap-northeast-1a

# see,
# https://awscli.amazonaws.com/v2/documentation/api/latest/reference/s3/index.html

##### S3 Bucket

function s3List() {
  aws ls
}

# {string} $bucket-name
function createBucket() {
  # バケットの作成
  BUCKET_NAME="s3://$1"
  aws s3 mb "$BUCKET_NAME"
}

# {string} $bucket-name
function pubBucketPublicAccessBlock() {
  # バケットのアクセスブロックの設定
  aws s3api put-public-access-block --bucket $1 --public-access-block-configuration "BlockPublicAcls=false,IgnorePublicAcls=false,BlockPublicPolicy=false,RestrictPublicBuckets=false"
}

# {string} $bucket-name
# {string} $policy file path ex: file://bucketPolicy.json
function pubBucketPolicy() {
  # 作成したポリシーをS3バケットにアタッチ
  aws s3api put-bucket-policy --bucket $1 --policy $2
}

# {string} $bucket-name
function removeBucketRecursive() {
  # バケットの中身の削除
  BUCKET_NAME="s3://$1"
  aws s3 rm "$BUCKET_NAME" --recursive
}

# {string} $bucket-name
function removeBucket() {
  # バケットの削除
  BUCKET_NAME="s3://$1"
  aws s3 rm "$BUCKET_NAME"
}

##### etc
