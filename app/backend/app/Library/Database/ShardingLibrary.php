<?php

declare(strict_types=1);

namespace App\Library\Database;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class ShardingLibrary
{
    /** @var string CONNECTION_NAME_FOR_CI CIなどで使う場合のコネクション名。単一のコネクションに接続させる。 */
    private const CONNECTION_NAME_FOR_CI = 'sqlite';
    /** @var string CONNECTION_NAME_FOR_TESTING UnitTestで使う場合のコネクション名。単一のコネクションに接続させる。 */
    private const CONNECTION_NAME_FOR_TESTING = 'mysql_testing';

    /**
     * get database node number & shard ids setting.
     *
     * @param int $nodeNumber node number
     * @return array<int, array<int, int>> ユーザー用DBのノード数(番号)とシャードid
     */
    public static function getShardingSetting(): array
    {
        return [
            Config::get('myapp.database.users.nodeNumber1') => Config::get('myapp.database.users.node1ShardIds'),
            Config::get('myapp.database.users.nodeNumber2') => Config::get('myapp.database.users.node2ShardIds'),
            Config::get('myapp.database.users.nodeNumber3') => Config::get('myapp.database.users.node3ShardIds'),
        ];
    }

    /**
     * get connection name by node number.
     *
     * @param int $nodeNumber node number
     * @return string
     */
    public static function getConnectionByNodeNumber(int $nodeNumber): string
    {
        $baseConnectionName = Config::get('myapp.database.users.baseConnectionName');

        if (in_array($baseConnectionName, self::getSingleDatabaseConnections(), true)) {
            return $baseConnectionName;
        }

        return $baseConnectionName . (string)$nodeNumber;
    }

    /**
     * get shard id by user id.
     *
     * @param int $userId user id.
     * @return int shard id
     */
    public static function getShardIdByUserId(int $userId): int
    {
        // 除算の余りを求める
        $shardCount = Config::get('myapp.database.users.shardCount');
        $shardId = $userId % $shardCount;
        // 割り切れる場合は$shardCount自体がshardIDとなる
        return $shardId !== 0 ? $shardId : $shardCount;
    }

    /**
     * get shard id by number key.
     *
     * @param int $value shard key.
     * @return int shard id
     */
    public static function getShardIdByNumber(int $value): int
    {
        // 除算の余りを求める
        $shardCount = Config::get('myapp.database.users.shardCount');
        $shardId = $value % $shardCount;
        // 割り切れる場合は$shardCount自体がshardIDとなる
        return $shardId !== 0 ? $shardId : $shardCount;
    }

    /**
     * get shard id by string.
     *
     * @param int string $value shard key.
     * @return int shard id
     */
    public static function getShardIdByString(string $value): int
    {
        $hex = bin2hex($value);
        // 0～9以外は空白に変換して文字列だけを取得する。
        $tmpIntValue = preg_replace('/[^0-9]/', '', $hex);
        // 終端から8文字取得 するとkeyが重複する
        // $shardKey = mb_substr($tmpIntValue, -8);
        $shardKey = mb_substr($tmpIntValue, 16);

        // 除算の余りを求める
        $shardCount = Config::get('myapp.database.users.shardCount');
        $shardId = $shardKey % $shardCount;
        // 割り切れる場合は$shardCount自体がshardIDとなる
        return $shardId !== 0 ? $shardId : $shardCount;
    }

    /**
     * get user database connection name by shard id.
     *
     * @param int $shardId shard id.
     * @return string node name
     */
    public static function getUserDataBaseConnection(int $shardId): string
    {
        $baseConnectionName = Config::get('myapp.database.users.baseConnectionName');

        if (in_array($baseConnectionName, self::getSingleDatabaseConnections(), true)) {
            return $baseConnectionName;
        }

        // 3で割り切れる場合はnode3
        if (($shardId % Config::get('myapp.database.users.modBaseNumber')) === 0) {
            // user database3
            return $baseConnectionName .(string)Config::get('myapp.database.users.nodeNumber3');
        } elseif (in_array($shardId, Config::get('myapp.database.users.node1ShardIds'), true)) {
            // user database1
            return $baseConnectionName .(string)Config::get('myapp.database.users.nodeNumber1');
        } else {
            // user database2
            return $baseConnectionName .(string)Config::get('myapp.database.users.nodeNumber2');
        }
    }

    /**
     * get database connections for using single database.
     *
     * @return array<int, string> 単一DBで運用する用のDBコネクション名の配列
     */
    public static function getSingleDatabaseConnections(): array
    {
        return [
            Config::get('myapp.unitTest.database.baseConnectionName'),
            Config::get('myapp.ci.database.baseConnectionName'),
        ];
    }

    /**
     * get single database connection name from config.
     *
     * @return string
     */
    public static function getSingleConnectionByConfig(): string
    {
        $logsConnectionName = Config::get('myapp.database.logs.baseConnectionName');
        $userConnectionName = Config::get('myapp.database.users.baseConnectionName');

        // CI用のコネクション
        if (($logsConnectionName === self::CONNECTION_NAME_FOR_CI) && ($userConnectionName === self::CONNECTION_NAME_FOR_CI)) {
            return self::CONNECTION_NAME_FOR_CI;
        } else {
            // テスト用DB内のテーブルのコネクション
            return self::CONNECTION_NAME_FOR_TESTING;
        }
    }

    /**
     * get connection by user id.
     *
     * @param int $userId user id
     * @return string
     */
    public static function getConnectionByUserId(int $userId): string
    {
        return self::getUserDataBaseConnection(self::getShardIdByNumber($userId));
    }

    /**
     * get database connections by some user ids.
     *
     * @param array $userIds user ids
     * @return array
     */
    public static function getConnectionsByUserIds(array $userIds): array
    {
        $connections = [];
        foreach ($userIds as $userId) {
            $connections[] = self::getConnectionByUserId($userId);
        }
        return array_unique($connections);
    }
}
