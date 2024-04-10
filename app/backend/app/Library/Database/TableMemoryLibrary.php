<?php

declare(strict_types=1);

namespace App\Library\Database;

use Illuminate\Support\Facades\DB;
use App\Library\Array\ArrayLibrary;

class TableMemoryLibrary
{
    // keys
    public const KEY_TABLE_SCHEMA = 'TABLE_SCHEMA';
    public const KEY_TABLE_NAME = 'TABLE_NAME';
    public const KEY_PARTITION_NAME = 'PARTITION_NAME';
    public const KEY_PARTITION_ORDINAL_POSITION = 'PARTITION_ORDINAL_POSITION';
    public const KEY_TABLE_ROWS = 'TABLE_ROWS';
    public const KEY_CREATE_TIME = 'CREATE_TIME';
    public const KEY_PARTITION_DESCRIPTION = 'PARTITION_DESCRIPTION';

    // 単位ごとのバイト数
    public const BASE_UNIT_VALUE = 1024;

    // label
    public const LABEL_DB_ENGINE = 'DBエンジン';
    public const LABEL_ROWS = '行数';
    public const LABEL_AVG_RECORD_LENGTH = '平均レコード長';
    public const LABEL_ALL_MB = 'ALL_MB';
    public const LABEL_DATA_MB = 'DATA_MB';
    public const LABEL_INDEX_MB = 'INDEX_MB';

    /**
     * get database memories.
     *
     * @param string $connection connection name
     * @param string $sort sort setting 'ASC' or 'DESC'
     * @return array
     */
    public static function getDatabaseMemories(
        string $connection = 'mysql',
        string $sort = 'DESC'
    ): array {
        $baseValue = self::BASE_UNIT_VALUE;
        $allMegaByte = self::LABEL_ALL_MB;
        $dataMegaByte = self::LABEL_DATA_MB;
        $indexMegaByte = self::LABEL_INDEX_MB;

        $collection = DB::connection($connection)
            ->table('INFORMATION_SCHEMA.TABLES')
            ->select(DB::raw("
                TABLE_SCHEMA,
                FLOOR(SUM(DATA_LENGTH + INDEX_LENGTH) / $baseValue / $baseValue) AS $allMegaByte,
                FLOOR(SUM((DATA_LENGTH) / $baseValue / $baseValue)) AS $dataMegaByte,
                FLOOR(SUM((INDEX_LENGTH) / $baseValue / $baseValue)) AS $indexMegaByte
            "))
            ->groupBy('TABLE_SCHEMA')
            ->orderByRaw("SUM(DATA_LENGTH + INDEX_LENGTH) $sort")
            ->get()
            ->toArray();

        if (empty($collection)) {
            return [];
        }

        return ArrayLibrary::toArray($collection);
    }

    /**
     * get table memories.
     *
     * @param string $connection connection name
     * @param string $sort sort setting 'ASC' or 'DESC'
     * @return array
     */
    public static function getTableMemories(
        string $connection = 'mysql',
        string $sort = 'DESC'
    ): array {
        $baseValue = self::BASE_UNIT_VALUE;

        $engine = self::LABEL_DB_ENGINE;
        $rows = self::LABEL_ROWS;
        $avgRecordLength = self::LABEL_AVG_RECORD_LENGTH;
        $allMegaByte = self::LABEL_ALL_MB;
        $dataMegaByte = self::LABEL_DATA_MB;
        $indexMegaByte = self::LABEL_INDEX_MB;

        $collection = DB::connection($connection)
            ->table('INFORMATION_SCHEMA.TABLES')
            ->select(DB::raw("
                TABLE_NAME,
                ENGINE AS $engine,
                TABLE_ROWS AS $rows,
                AVG_ROW_LENGTH AS $avgRecordLength,
                FLOOR((DATA_LENGTH + INDEX_LENGTH) / $baseValue / $baseValue) AS $allMegaByte,
                FLOOR(DATA_LENGTH / $baseValue / $baseValue) AS $dataMegaByte,
                FLOOR(INDEX_LENGTH / $baseValue / $baseValue) AS $indexMegaByte
            "))
            ->whereRaw("TABLE_SCHEMA = database()")
            ->orderByRaw("(DATA_LENGTH + INDEX_LENGTH) $sort")
            ->get()
            ->toArray();

        if (empty($collection)) {
            return [];
        }

        return ArrayLibrary::toArray($collection);
    }
}
