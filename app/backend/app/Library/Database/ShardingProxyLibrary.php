<?php

declare(strict_types=1);

namespace App\Library\Database;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Library\Array\ArrayLibrary;
use App\Library\Database\ShardingLibrary;

class ShardingProxyLibrary
{
    /**
     * get connection name by shard key.
     *
     * @param int $shardKey shard key.
     * @return string
     */
    public static function getConnectionNameByShardKey(int $shardKey): string
    {
        return ShardingLibrary::getUserDataBaseConnection(ShardingLibrary::getShardIdByNumber($shardKey));
    }

    /**
     * get connections & group connectio by shard keys.
     *
     * @param array $shardKeys example: user ids
     * @return array
     */
    public static function groupShardKeysByConnection(array $shardKeys): array
    {
        return array_reduce($shardKeys, function (array $groups, int $shardKey) {
            $groups[self::getConnectionNameByShardKey($shardKey)][] = $shardKey;
            return $groups;
        }, []);
    }

    /**
     * get connectio and shard id group by shard keys.
     *
     * @param array $shardKeys shard keys
     * @return array
     */
    public static function getConnectionAndShardIdGroupByShardKeys(array $shardKeys): array
    {
        $result = [];
        $shardKeysGroupByConnection = self::groupShardKeysByConnection($shardKeys);
        foreach ($shardKeysGroupByConnection as $connection => $tmpShardKeys) {
            foreach ($tmpShardKeys as $shardKey) {
                $result[$connection][ShardingLibrary::getShardIdByNumber($shardKey)][] = $shardKey;
            }
        }
        return $result;
    }

    /**
     * get database node number & shard ids setting.
     *
     * @param string $table table name
     * @param array $columns columns
     * @param ?array $equals condition values of where
     * @param ?array $ins condition values of whereIn
     * @param ?array $betweens condition values of between
     * @param ?int $limit limit conditions.
     * @param ?int $offset offset conditions.
     * @return array
     * @example App\Library\Database\ShardingProxyLibrary::select('user_coins', ['user_id']);
     * @example App\Library\Database\ShardingProxyLibrary::select('user_coins', ins: ['user_id' => [1,2,3]])
     */
    public static function select(
        string $table,
        array $columns = ['*'],
        ?array $equals = null,
        ?array $ins = null,
        ?array $betweens = null,
        ?int $limit = null,
        ?int $offset = null
    ): array {
        $connections = self::getConnectionAndShardIdGroupByShardKeys(range(1, 16));
        $result = [];
        foreach ($connections as $connection => $shardIds) {
            foreach ($shardIds as $shardId => $_) {
                $query = DB::connection($connection)
                    ->table($table . $shardId)
                    ->select($columns);

                if (!is_null($equals)) {
                    foreach ($equals as $column => $condition) {
                        $query = $query->where($column, '=', $condition);
                    }
                }

                if (!is_null($ins)) {
                    foreach ($ins as $column => $conditions) {
                        $query = $query->whereIn($column, $conditions);
                    }
                }

                if (!is_null($betweens)) {
                    foreach ($betweens as $column => $condition) {
                        $query = $query->whereBetween($column, $condition);
                    }
                }

                if (!is_null($limit)) {
                    $query = $query->limit($limit);
                }

                if (!is_null($offset)) {
                    $query = $query->offset($offset);
                }

                $records = $query->get()->toArray();
                $result = array_merge($result, $records);
            }
        }

        return ArrayLibrary::toArray($result);
    }
}
