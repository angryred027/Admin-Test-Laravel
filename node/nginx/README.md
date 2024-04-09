# Nginx Memo

---

## 環境


---

## alpのインストール

随時はバージョンは調節すること。

```shell
curl -L -O https://github.com/tkuchiki/alp/releases/download/v1.0.17/alp_linux_amd64.zip
unzip alp_linux_amd64.zip
mv alp /usr/local/bin/alp

# バージョン確認
alp -v
1.0.17
```

### nginxのconfファイルの設定

`default.conf`などで個別にログ設定をしている場合は各々のconfファイルに記載する事。

```conf
# $upstream_response_time: リバースプロキシとして動いている場合はWEBアプリケーションが処理行いnginxにレスポンスを返すまでの時間
# json
log_format json escape=json '{"time":"$time_iso8601",'
                            '"host":"$remote_addr",'
                            '"forwardedfor":"$http_x_forwarded_for",'
                            '"req":"$request",'
                            '"status":"$status",'
                            '"method":"$request_method",'
                            '"uri":"$request_uri",'
                            '"body_bytes":$body_bytes_sent,'
                            '"referer":"$http_referer",'
                            '"ua":"$http_user_agent",'
                            '"request_time":"$request_time",'
                            '"cache":"$upstream_http_x_cache",'
                            '"runtime":"$upstream_http_x_runtime",'
                            '"rseponse_time":"$upstream_response_time",'
                            '"vhost":"$host"}';

access_log  /var/log/nginx/access.log  json; # server {}内に記載すること。

# ltsv
log_format ltsv "time:$time_local"
    "\thost:$remote_addr"
    "\tforwardedfor:$http_x_forwarded_for"
    "\treq:$request"
    "\tmethod:$request_method"
    "\turi:$request_uri"
    "\tstatus:$status"
    "\tsize:$body_bytes_sent"
    "\treferer:$http_referer"
    "\tua:$http_user_agent"
    "\treqtime:$request_time"
    "\truntime:$upstream_http_x_runtime"
    "\tapptime:$upstream_response_time"
    "\tcache:$upstream_http_x_cache"
    "\tvhost:$host";

access_log  /var/log/nginx/access.log ltsv;
```


### jsonファイルで出力

フォーマットを変えたら一度ログファイル内を空にするのが良い。

```json
/ # cat /var/log/nginx/access.log | alp json
+-------+-----+-----+-----+-----+-----+--------+----------------------------+-------+-------+-------+-------+-------+-------+-------+--------+------------+------------+------------+------------+
| COUNT | 1XX | 2XX | 3XX | 4XX | 5XX | METHOD |            URI             |  MIN  |  MAX  |  SUM  |  AVG  |  P90  |  P95  |  P99  | STDDEV | MIN(BODY)  | MAX(BODY)  | SUM(BODY)  | AVG(BODY)  |
+-------+-----+-----+-----+-----+-----+--------+----------------------------+-------+-------+-------+-------+-------+-------+-------+--------+------------+------------+------------+------------+
| 1     | 0   | 1   | 0   | 0   | 0   | GET    | /api/v1/debug/phpinfo      | 0.402 | 0.402 | 0.402 | 0.402 | 0.402 | 0.402 | 0.402 | 0.000  | 159705.000 | 159705.000 | 159705.000 | 159705.000 |
| 1     | 0   | 1   | 0   | 0   | 0   | GET    | /api/v1/debug/status       | 0.479 | 0.479 | 0.479 | 0.479 | 0.479 | 0.479 | 0.479 | 0.000  | 392.000    | 392.000    | 392.000    | 392.000    |
| 3     | 0   | 3   | 0   | 0   | 0   | GET    | /api/v1/debug/test         | 0.378 | 0.540 | 1.302 | 0.434 | 0.540 | 0.540 | 0.540 | 0.075  | 44.000     | 44.000     | 132.000    | 44.000     |
+-------+-----+-----+-----+-----+-----+--------+----------------------------+-------+-------+-------+-------+-------+-------+-------+--------+------------+------------+------------+------------+

```

---

### jqコマンドのインストール

`Dockerfile`にて下記のコマンドでインストールする。

```dockerfile
# その他、brew,yum,aptなどの各パッケージマネージャーでインストール出来る
RUN apk update && \
  apk add --update --no-cache linux-headers --virtual=.build-dependencies \
  jq
```

コマンド実行例

```shell
echo '{"a": 123, "b": "b123", "c": {"d": 1}, "e": [1,2,3]}' | jq .

cat test.json | jq

### json内の各keyを指定する場合
echo '{"a": 123, "b": "b123", "c": {"d": 1}, "e": [1,2,3]}' | jq '.a'
echo '{"a": 123, "b": "b123", "c": {"d": 1}, "e": [1,2,3]}' | jq '.c'
echo '{"a": 123, "b": "b123", "c": {"d": 1}, "e": [1,2,3]}' | jq '.c.d'
echo '{"a": 123, "b": "b123", "c": {"d": 1}, "e": [1,2,3]}' | jq '.e'
### 配列の要素にアクセス
echo '{"test": [{"a": 1, "b": 1}, {"a": 2, "b": 2}]}' | jq '.test[1]'
echo '{"test": [{"a": 1, "b": 1}, {"a": 2, "b": 2}]}' | jq '.test[1].b'
```

---

