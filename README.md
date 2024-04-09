# Application Name

My Application.

# 構成

## backend

| 名前 | バージョン |
| :--- | :---: |
| PHP | 8.2(php:8.2-fpm-alpine3.17) |
| MySQL | 5.7 |
| Nginx | 1.25(nginx:1.25-alpine) |
| Laravel | 9.* |

[backend/README](./app/backend/README.md)

## frontend

| 名前 | バージョン |
| :--- | :---: |
| npm | 8.1.0 |
| node | 16.13.0 |
| react | 17.0.2 |
| TypeScript | 4.5.2 |

[frontend/README](./frontend/README.md)

---

## volumeとnetworkの作成

networkは`gateway`と`subnet`を必ず指定する。(値は任意。)

```shell
docker volume create ${PROJECT_NAME}-db-store
docker volume create ${PROJECT_NAME}-redis-store
docker volume create ${PROJECT_NAME}-mail-store
docker network create --gateway=172.19.0.1 --subnet=172.19.0.0/16 ${PROJECT_NAME}-net

### volumeの作り直しをする時
docker volume rm ${PROJECT_NAME}-db-store

### コンテナのIPアドレスの確認(jqが入っている場合)
docker network inspect ${NETWORK_NAME} | jq '.[0].Containers | .[].IPv4Address'
```

Docker Containerのデフォルトユーザーがrootで無い場合。
下記でユーザーを指定すればrootユーザーでコンテナに入る事が出来る。

```shell
docker exec -u root -it php-alg_app ash
```


---

## メールサーバーについて

[mailhog](https://github.com/mailhog/MailHog)を利用する。

データの永続化の為に専用のvolumeを新規で作成する。

最低限下記の形でdocker-compose.ymlに記載すれば良い。

コンテナ起動後は`http://localhost:8025/`でブラウザ上からメール情報を確認出来る。

```yaml
  mail:
    image: mailhog/mailhog
    container_name: container_name
    volumes:
      - volumeName:/tmp
    ports:
      - "8025:8025"
    environment:
      MH_MAILDIR_PATH: /tmp
```

`app/backend/.env`のメール設定を下記の通りに設定すること。

`MAIL_HOST`はデフォルトの値が`mailhog`になっているがDockerコンテナ名を設定する必要がある。

* 実際のSMTPでは、port:1025で受け付けている為8025ではなく1025にする必要がある。

```shell
MAIL_MAILER=smtp
MAIL_HOST=mail
MAIL_PORT=1025
```

---

# Redis

### メモリ情報等の確認

```shell
redis-cli info

# Memory
used_memory:1179368 # redisによって割り当てられたバイト数
used_memory_human:1.12M
used_memory_rss:7544832
used_memory_rss_human:7.20M
used_memory_peak:1179368
used_memory_peak_human:1.12M
used_memory_peak_perc:100.11%
used_memory_overhead:916012
used_memory_startup:894848
used_memory_dataset:263356
```

## redisでLua scriptを実行させる場合

`--eval`オプションを指定してファイルを実行させる

キーと引数の間は` , `で区切る必要がある。(前後に半角スペースを入れる必要あり。)



```shell
$ redis-cli --eval /usr/local/share/lua/test1.lua key1 key2, value1 value2 value3

# 引数で渡されたKEYのGETを行うスクリプト
$ redis-cli --eval /usr/local/share/lua/test2.lua key1 key2
1) OK
2) OK

# 引数で渡されたKEYとARGのSETを行うスクリプト
$ redis-cli --eval /usr/local/share/lua/test3.lua key1 key2, value1 value2
1) OK
2) OK
```

### Luaデバッガー

```shell
$ redis-cli --ldb --eval /usr/local/share/lua/example-debug.lua
Lua debugging session started, please use:
quit    -- End the session.
restart -- Restart the script in debug mode again.
help    -- Show Lua script debugging commands.

* Stopped at 1, stop reason = step over
-> 1   local key = 'test'
```

#### オプションについて

1. --ldb:スクリプトの実行結果はロールバックされる為サーバーに影響されない
2. --ldb-sync-mode:スクリプトの実行結果はサーバーに実際に反映される

### デバッグ方法について

ファイルを展開し、ブレイクポイントを貼ることが出来る

```shell
lua debugger> l
-> 1   local key = 'test'
   2
   3   redis.call('SET', key, 10)
   4
   5   local result = redis.call('INCR', key)
   6
lua debugger>
lua debugger> b 5
  4
  #5   local result = redis.call('INCR', key)
   6
lua debugger> c
* Stopped at 5, stop reason = break point
->#5   local result = redis.call('INCR', key)
lua debugger> s
<redis> INCR test
<reply> 11
* Stopped at 7, stop reason = step over
-> 7   return result
lua debugger> p result
<value> 11
lua debugger> c

(integer) 11

(Lua debugging session ended -- dataset changes rolled back)

127.0.0.1:6379> 
```


---

# Swaggerの設定

 ### ローカル環境にswagger-codegenのインストール(mockサーバーのコード出力)

```shell-session
$ brew install swagger-codegen
```

### API仕様から出力するmockサーバーについて

API仕様からmockサーバーの出力

```shell-session
 $ swagger-codegen generate -i api/api.yaml -l nodejs-server -o api/nodejs
```

node.jsのサーバーなので、`node_modules`のインストールが必要

`npm run install`と`npm run prestart`を実行後に起動出来る。

```shell-session
 $ npm run prestart
```

mockサーバーの起動

```shell-session
 $ npm run start
```

### Dockerコンテナの利用について

Dockerコンテナを用意した為もうnpmコマンドを利用する必要が無い。

下記でSwaggerエディターとSwaggerUIのコンテナを立ち上げる。(UI,Mockコンテナはやや立ち上がりに時間がかかる(1分ほど？))

```shell
docker-compose -f ./docker-compose.swagger.yml up -d

# editor
http://localhost:8100
# ui
http://localhost:8200
# mock server
http://localhost:3200

### example
http://localhost:3200/user/coins
http://localhost:3200/user/events
http://localhost:3200/user/informations
```

---

# AWSの設定

## オプションの指定無しでプロファイルの確認

```shell
$ aws configure list
      Name                    Value             Type    Location
      ----                    -----             ----    --------
   profile          　　profile_name           manual    --profile
access_key     ****************XXXX shared-credentials-file
secret_key     ****************XXXX shared-credentials-file
    region           xx-xxxxxxxxx-1      config-file    ~/.aws/config
```

## IAMユーザーやグループの確認

```shell
$ aws iam list-users
$ aws iam list-groups
```

## EC2の確認

```shell
$ aws ec2 describe-vpcs --region ap-northeast-1
```

## S3の設定

### S3の確認

```shell
$ aws s3 ls
```

### バケットの作成

```shell
$ aws s3 mb s3://"$BUCKET_NAME"


$ aws s3 mb s3://test-bucket-yyyymmdd-xxxx-ap-northeast-1
make_bucket: test-bucket-yyyymmdd-xxxx-ap-northeast-1
```

### バケットのアクセスブロックの設定

バケットのアクセスブロックを設定し、公開できる状態にする。

```shell
$ aws s3api put-public-access-block --bucket "$BUCKET_NAME" --public-access-block-configuration "BlockPublicAcls=false,IgnorePublicAcls=false,BlockPublicPolicy=false,RestrictPublicBuckets=false"
```

アクセスブロックの確認

```shell
$ aws s3api get-public-access-block --bucket "$BUCKET_NAME"
{
    "PublicAccessBlockConfiguration": {
        "BlockPublicAcls": false,
        "IgnorePublicAcls": false,
        "BlockPublicPolicy": false,
        "RestrictPublicBuckets": false
    }
}
```

### バケットポリシーを作成、S3バケットにアタッチする

bucketPolicy.jsonの作成

パブリックにアクセス可能になる為利用には注意が必要！

```json
{
    "Version": "2022-12-03",
    "Statement": [
        {
            "Effect": "Allow",
            "Principal": "*",
            "Action": "s3:GetObject",
            "Resource": "arn:aws:s3:::for-test-page/*"
        }
    ]
}
```

S3バケット作成したポリシーをアタッチする

```shell
$ aws s3api put-bucket-policy --bucket "$BUCKET_NAME" --policy file://bucketPolicy.json
```

### S3バケットにファイルをアップロードする

サンプルのファイル作成

```html
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <title>Sample Page</title>
    </head>
    <body>
        This is Sample Page:) Tada-!
    </body>
</html>

```

ファイルをS3にアップロード

```shell
$ aws s3 cp index.html s3://"$BUCKET_NAME"/index.html
upload: ./index.html to s3://"$BUCKET_NAME"/index.html
```


### 静的ウェブサイトのホスティングの場合

下記のいずれかで設定する。

1. 指定したS3バケットにwebsiteの設定を行う

```shell
$ aws s3 website s3://"$BUCKET_NAME" --index-document index.html
```

2. 公開するウェブサイトの設定を記載し、バケットに設定する

Jsonファイルで公開するウェブサイトの設定を記載します。

```json
{
    "IndexDocument": {
        "Suffix": "index.html"
    }
}
```

作成したファイルを元にインデックスドキュメントを設定。

```shell
$ aws s3api put-bucket-website --bucket "$BUCKET_NAME" --website-configuration file://webSite.json
```

### ブラウザで確認

```shell
http://"$BUCKET_NAME".s3-website-"$REGION_NAME".amazonaws.com
```

### ローカルとバケットの同期

```shell
$ aws s3 sync ./ s3://"$BUCKET_NAME"
```

### アップロードされているかの確認

```shell
$ aws s3 ls s3://"$BUCKET_NAME"
$ aws s3 ls s3://"$BUCKET_NAME"/"$OBJECT_NAME"
```

### ディレクトリ内の全てのコピー

```shell
$ aws s3 cp . s3://"$BUCKET_NAME" --recursive
```

### ファイルの削除

```shell
$ aws s3 rm s3://"$BUCKET_NAME"/"$FILE_NAME"
```

### バケット内を空にする

```shell
$ aws s3 rm s3://"$BUCKET_NAME" --recursive
```

### バケットの削除

```shell
$ aws s3 rb s3://"$BUCKET_NAME"
```

---

# 構成



---

