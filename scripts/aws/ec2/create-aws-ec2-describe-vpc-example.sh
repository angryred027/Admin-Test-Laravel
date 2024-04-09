#!/bin/sh

# CURRENT_DIR=$(cd $(dirname $0); pwd)
DELIMITER_LINE='------------------------------------------------------'
START_MESSAGE='start AWS EC2 Describe VPC.'

# CHANGE Variable.
REGION_NAME=ap-northeast-1
AWS_CLI_PATH=/usr/local/bin/aws


# @param {string} message
showMessage() {
  echo ${DELIMITER_LINE}
  echo $1
}

# process start
showMessage "$START_MESSAGE"

if [ "$1" != '' ]; then
  $AWS_CLI_PATH ec2 describe-vpcs --region "$REGION_NAME" --profile $1
else
  $AWS_CLI_PATH ec2 describe-vpcs --region "$REGION_NAME"
fi

showMessage "Get AWS EC2 Describe VPC. $REGION_NAME"

