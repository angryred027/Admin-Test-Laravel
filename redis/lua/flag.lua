#!lua flags=no-writes,allow-stale

``` no-writes: 書き込み無し
``` allow-oom: サーバーがOut of Memoryでも実行出来るスクリプトである事を指定
``` allow-stale: レプリカがマスターからコネクションが切断される等でデータが古い状態であってもスクリプトが実行可能である事を指定
``` no-cluster: Redisクラスター内で実行出来ないスクリプトであることを指定
``` allow-cross-slot-keys: Redisクラスターで複数のスロットからキーにアクセス出来るスクリプトである事を指定

local result = redis.call('get', 'x')

return result
