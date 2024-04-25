<?php

declare(strict_types=1);

namespace App\Library\Database;

use Illuminate\Support\Facades\DB;
use App\Library\Array\ArrayLibrary;
use App\Library\Database\DatabaseLibrary;

class PartitionLibrary
{
    // keys
    public const KEY_TABLE_SCHEMA = 'TABLE_SCHEMA';
    public const KEY_TABLE_NAME = 'TABLE_NAME';
    public const KEY_PARTITION_NAME = 'PARTITION_NAME';
    public const KEY_PARTITION_ORDINAL_POSITION = 'PARTITION_ORDINAL_POSITION';
    public const KEY_TABLE_ROWS = 'TABLE_ROWS';
    public const KEY_CREATE_TIME = 'CREATE_TIME';
    public const KEY_PARTITION_DESCRIPTION = 'PARTITION_DESCRIPTION';
    public const KEY_PARTITION_EXPRESSION = 'PARTITION_EXPRESSION';

    /**
     * create partitions by range
     *
     * @param string $databaseName database name
     * @param string $tableName table name
     * @param string $columnName column name
     * @param string $$partitions partition setting statemetns
     * @return void
     */
    public static function createPartitionsByRange(string $databaseName, string $tableName, string $columnName, string $partitions): void
    {
        DB::statement(
            "
                ALTER TABLE $databaseName.$tableName
                PARTITION BY RANGE COLUMNS($columnName) (
                    $partitions
                )
            "
        );
    }

    /**
     * create partitions by hash
     * (指定カラムをパーティション化キーとして使用して HASH によって$countつのパーティションにパーティション化)
     *
     * @param string $databaseName database name
     * @param string $tableName table name
     * @param string $columnName column name
     * @param string $divCount div count
     * @param int $count partition count
     * @return void
     */
    public static function createPartitionsByHashDiv(
        string $databaseName,
        string $tableName,
        string $columnName,
        string $divCount,
        int $count
    ): void {
        DB::statement(
            "
                ALTER TABLE $databaseName.$tableName
                PARTITION BY HASH($columnName div $divCount)
                PARTITIONS $count;
            "
        );
    }

    /**
     * add partitions
     *
     * @param string $databaseName database name
     * @param string $tableName table name
     * @param string $$partitions partition setting statemetns
     * @return void
     */
    public static function addPartitions(string $databaseName, string $tableName, string $partitions): void
    {
        DB::statement(
            "
                ALTER TABLE $databaseName.$tableName
                ADD PARTITION (
                    $partitions
                )
            "
        );
    }

    /**
     * drop partition.
     *
     * @param string $databaseName database name
     * @param string $tableName table name
     * @param string $partitionName partition name
     * @return void
     */
    public static function dropPartition(string $databaseName, string $tableName, string $partitionName): void
    {
        DB::statement(
            "
                ALTER TABLE $databaseName.$tableName DROP PARTITION $partitionName;
            "
        );
    }

    /**
     * remove partition setting from table.
     *
     * @param string $databaseName database name
     * @param string $tableName table name
     * @return void
     */
    public static function removePartition(string $databaseName, string $tableName): void
    {
        DB::statement(
            "
                ALTER TABLE $databaseName.$tableName REMOVE PARTITIONING;
            "
        );
    }

    /**
     * get partiion by table name
     *
     * @param string $connection connection name
     * @param string $tableName table name
     * @param string $sort sort setting 'ASC' or 'DESC'
     * @return array
     */
    public static function getPartitionsByTableName(
        string $connection,
        string $tableName,
        string $sort = 'ASC'
    ): array {
        $schema = DatabaseLibrary::getDatabaseNameByConnection($connection);

        // パーティションの情報の取得(指定された日付より以前のパーティション)
        // `PARTITION_NAME`では正しくソートされないので`PARTITION_ORDINAL_POSITION`でソートをかける
        // CREATE_TIMEはpartitionを追加する度に更新されている？っぽいのでwhereに不向き。
        // PARTITION_DESCRIPTIONの方が良さそう
        $collection = DB::connection($connection)
            ->table('INFORMATION_SCHEMA.PARTITIONS')
            ->select(DB::raw("
                TABLE_SCHEMA,
                TABLE_NAME,
                PARTITION_NAME,
                PARTITION_ORDINAL_POSITION,
                TABLE_ROWS,
                CREATE_TIME,
                PARTITION_DESCRIPTION,
                PARTITION_EXPRESSION
            "))
            ->where('TABLE_SCHEMA', '=', $schema)
            ->where('TABLE_NAME', '=', $tableName)
            ->orderBy('PARTITION_ORDINAL_POSITION', $sort)
            ->get()
            ->toArray();

        if (empty($collection)) {
            return [];
        }

        // return json_decode(json_encode($collection), true);
        return ArrayLibrary::toArray($collection);
    }

    /**
     * get latest partition of table.
     *
     * @param string $connection connection name
     * @param string $tableName table name
     * @return array
     */
    public static function checkLatestPartition(
        string $connection,
        string $tableName
    ): array {
        return current(self::getPartitionsByTableName($connection, $tableName, 'DESC'));
    }
}
