#!/bin/bash

# CHANGE Variable.
REGION_NAME=ap-northeast-1
REGION_NAME_FOR_SUBNET=ap-northeast-1a

# see,
# https://awscli.amazonaws.com/v2/documentation/api/latest/reference/rds/index.html

##### RDS

function describeDBInstatances() {
  aws rds describe-db-instances
}

##### etc
