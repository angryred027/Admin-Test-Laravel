#!/bin/bash

# CHANGE Variable.
REGION_NAME=ap-northeast-1
REGION_NAME_FOR_SUBNET=ap-northeast-1a
AWS_CHECK_IP_END_POINT='https://checkip.amazonaws.com'

# see,
# https://awscli.amazonaws.com/v2/documentation/api/latest/reference/ec2/index.html

##### VPC

function describeVPC() {
  aws ec2 describe-vpcs --region "$REGION_NAME"
}

# {string} vpc-id
function describeVPCById() {
  aws ec2 describe-vpcs --vpc-id $1
}

# {string} cidr-block ex: 172.16.0.0/16
function createVPC() {
  # aws ec2 create-vpc --cidr-block $1
  aws ec2 create-vpc --cidr-block $1 | jq '.Vpc.VpcId'
}

# {string} vpc-id
function deleteVPC() {
  aws ec2 delete-vpc --vpc-id $1
}

##### Subnet

# {string} subnet-id
function describeSubnet() {
  aws ec2 describe-subnets --subnet-id $1
}

# {string} vpc-id
# {string} cidr-block ex: 172.16.0.0/24
function createSubnet() {
  # aws ec2 create-subnet --vpc-id $1 --cidr-block $2 --availability-zone $REGION_NAME_FOR_SUBNET
  aws ec2 create-subnet --vpc-id $1 --cidr-block $2 --availability-zone $REGION_NAME_FOR_SUBNET | jq '.Subnet.SubnetId'
}

# {string} subnet-id
function deleteSubnet() {
  aws ec2 delete-subnets --subnet-id $1
}

# {string} subnet-cidr-reservation-id
function deleteSubnetCIDRReservertion() {
  aws ec2 delete-subnet-cidr-reservation --subnet-cidr-reservation-id $1
}

##### Internet Gateway

# {string} internet-gateway-id
function describeInternetGateway() {
  # no option
  # aws ec2 describe-internet-gateways
  aws ec2 describe-internet-gateways --internet-gateway-id $1
}

function createInternetGateway() {
  # aws ec2 create-internet-gateway
  aws ec2 create-internet-gateway | jq '.InternetGateway.InternetGatewayId'
}

# {string} igw-id
# {string} vpc-id
function attachInternetGateway() {
  aws ec2 attach-internet-gateway --internet-gateway-id $1 --vpc-id $2
}

# {string} internet-gateway-id
# {string} --dry-run | --no-dry-run
function deleteInternetGateway() {
  aws ec2 delete-internet-gateway $2 --internet-gateway-id $1
}

##### Route Table

# {string} route-table-id
function describeRouteTable() {
  aws ec2 describe-route-tables --route-table-id $1
}

# {string} vpc-id
function createRouteTable() {
  # aws ec2 create-route-table --vpc-id $1
  aws ec2 create-route-table --vpc-id $1 | jq '.RouteTable.RouteTableId'
}

# {string} rtb-id
# {string} igw-id
function createRoute() {
  aws ec2 create-route --route-table-id $1 --destination-cidr-block 0.0.0.0/0 --gateway-id $2
}

# {string} rtb-id
# {string} subnet-id
function associateRouteTable() {
  aws ec2 associate-route-table --route-table-id $1 --subnet-id $2
}

# {string} subnet-id
function modifySubnetAttribute() {
  # パブリックIPアドレスが自動的に割り当てる為の設定
  aws ec2 modify-subnet-attribute --subnet-id $1 --map-public-ip-on-launch
}

# {string} rtb-id
# {string} --dry-run | --no-dry-run
function deleteRoute() {
  aws ec2 delete-route $2 --route-table-id $1
}

# {string} rtb-id
# {string} --dry-run | --no-dry-run
function createRouteTable() {
  aws ec2 create-route-table $2 --route-table-id $1
}

##### EC2

# {string} instace-id
function startInstance() {
  aws ec2 start-instances --instance-ids $1
}

# {string} instace-id
function stopInstance() {
  aws ec2 stop-instances --instance-ids $1
}

function describeInstances() {
  # aws ec2 describe-instances
  aws ec2 describe-instances
}

# {string} instace-id
function describeInstance() {
  # aws ec2 describe-instances
  aws ec2 describe-instances --instance-id $1
}

# {string} instace-id
function describeInstanceAndGetStatus() {
  aws ec2 describe-instances --instance-id $1 | jq '.Reservations[0].Instances[0].State'
}

function describeInstanceStatus() {
  aws ec2 describe-instance-status | jq '.InstanceStatuses'
}

# {string} instace-id
function describeInstanceAndGetPublicIp() {
  aws ec2 describe-instances --instance-id $1 | jq '.Reservations[0].Instances[0].NetworkInterfaces[0].Association.PublicIp'
}

# {string} image-id ex: ami-xxx
# {string} key-name
# {string} security-group-id
# {string} subnet-id
function runInstance() {
  aws ec2 run-instances \
    --image-id $1 \
    --count 1 \
    --instance-type t2.micro \
    --key-name $2 \
    --security-group-ids $3 \
    --subnet-id $4 # \
    # --tag-specific 'ResourceType=instance,Tags=[{Key=Name,Value=TestEC2_20231126}]'
}

# {string} instace-id
# {string} --dry-run | --no-dry-run
function terminateEC2Instance() {
  aws ec2 terminate-instances $2 --instance-ids $1
}

# {string} instace-id
# {string} --dry-run | --no-dry-run
function moniterEC2Instance() {
  aws ec2 monitor-instances $2 --instance-ids $1
}

# {string} instace-id
# {string} --dry-run | --no-dry-run
function unmoniterEC2Instance() {
  aws ec2 unmonitor-instances $2 --instance-ids $1
}

##### Security Group

function describeSecurityGroups() {
  aws ec2 describe-security-groups
}

# {string} security-group-id
function getCurrentSecurityGroupAllowedIp() {
  aws ec2 describe-security-groups --group-id $1 | jq '.SecurityGroups[0].IpPermissions[0].IpRanges[0].CidrIp'
}

# {string} security-group-id
function getCurrentSecurityGroup() {
  aws ec2 describe-security-groups --group-id $1
}

# {string} security-group-id
function getCurrentSecurityGroupPermissions() {
  aws ec2 describe-security-groups --group-id $1 | jq '.SecurityGroups[0].IpPermissions'
}

# {string} group-name
# {string} description
# {string} vpc-id
function createSecurityGroup() {
  # aws ec2 create-security-group --group-name $1 --description $2 --vpc-id $3
  aws ec2 create-security-group --group-name $1 --description $2 --vpc-id $3 | jq '.GroupId'
}

# {string} security-group-id
# {string} --dry-run | --no-dry-run
function deleteSecurityGroup() {

  aws ec2 delete-security-group $2 --group-id $1
}

# {string} security-group-id
# {string} my ip
function authorizeIp() {
  TMP_CIDR="$2/32"
  aws ec2 authorize-security-group-ingress --group-id $1 --protocol tcp --port 22 --cidr $TMP_CIDR
}

# {string} security-group-id
# {string} target ip
function revokeIp() {
  # 削除実行
  aws ec2 revoke-security-group-ingress --group-id $1 --protocol tcp --port 22 --cidr $2
  # 下記はシングルクォーテーション、ダブルクォーテーションで囲んだ文字列をawc-cli側が上手く読み取ってくれない為実行出来ない。(文字列を直接打てば実行出来るが変数として渡せない。)
  # TMP_CIDR_IP="[{\"CidrIp\": \"$2\"}]"
  # IP_PERMISSIONS="[{\"IpProtocol\": \"tcp\", \"FromPort\": 22, \"ToPort\": 22, \"IpRanges\": $TMP_CIDR_IP}]"
  # echo $IP_PERMISSIONS
  # aws ec2 revoke-security-group-ingress --group-id $1 --ip-permissions $IP_PERMISSIONS
  # aws ec2 revoke-security-group-ingress --group-id $1 --ip-permissions \'$IP_PERMISSIONS\'
}

##### etc

function getMyIp() {
  # -sオプションで取得結果に関する文言をskipする
  curl -s "$AWS_CHECK_IP_END_POINT"
}

# {string} pem path
# {string} instace client ip
function sshToInstance() {
  ssh -i "$1" "ec2-user@$2"
}

# {string} resource-id ex: vpc-id,subnet-id,igw-id
# {string} key,value ex: 'Key=Name,Value=TestVPC20231126'
function createTag() {
  aws ec2 create-tags --resources $1 --tags $2
}

# {string} key name
# {string} file name
function createKeyPair() {
  aws ec2 create-key-pair --key-name $1 --query 'KeyMaterial' --output text > $2
  chmod 400 $2
}

# {string} key name
# {string} --dry-run | --no-dry-run
function deleteKeyPair() {
  aws ec2 delete-key-pair $2 --key-name $1
}

function describeKeyPair() {
  aws ec2 describe-key-pairs
}

function describeHosts() {
  aws ec2 describe-hosts | jq '.Hosts'
}


