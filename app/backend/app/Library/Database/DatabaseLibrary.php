<?php

declare(strict_types=1);

namespace App\Library\Database;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Library\Array\ArrayLibrary;
use App\Library\Database\ShardingLibrary;

class DatabaseLibrary
{
    private const DEFAULT_CONNECTION_NAME = 'mysql';

    /**
     * get default database connection by config.
     *
     * @param string $connection connection name
     * @return string database name
     */
    public static function getDefaultDatabaseConnection(): string
    {
        return config('database.default');
    }

    /**
     * get master database connection.
     *
     * @param string $connection connection name
     * @return string database name
     */
    public static function geMasterDatabaseConnection(): string
    {
        // test実行時は専用のconnection設定を利用
        if (config('app.env') === 'testing') {
            return ShardingLibrary::getSingleConnectionByConfig();
        } else {
            return self::getDefaultDatabaseConnection();
        }
    }

    /**
     * get single database connection name from config.
     *
     * @param string $connection connection name
     * @return string database name
     */
    public static function getDatabaseNameByConnection($connection): string
    {
        return Config::get("database.connections.$connection.database");
    }

    /**
     * get single database connection name from replication database config.
     *
     * @param string $connection connection name
     * @return string database name
     */
    public static function getDatabaseNameByConnectionForReplication($connection): string
    {
        return Config::get("database.connections.$connection.read.database");
    }

    /**
     * get scema name list in single connection.
     *
     * @param ?string $connection connection name
     * @return array
     */
    public static function getSchemaListByConnection(?string $connection = null): array
    {
        // デフォルトのコネクション設定
        if (is_null($connection) || ($connection === self::DEFAULT_CONNECTION_NAME)) {
            $connection = self::DEFAULT_CONNECTION_NAME;
            $database = self::getDatabaseNameByConnectionForReplication($connection);
        } else {
            $database = self::getDatabaseNameByConnection($connection);
        }

        // objectの配列を変換
        $tmpSchemaList = DB::connection($connection)->select("SHOW TABLES");
        $schemaList = array_column(ArrayLibrary::toArray($tmpSchemaList), "Tables_in_$database");

        return array_values($schemaList);
    }

    /**
     * get table status.
     *
     * @param string $table table name
     * @param ?string $connection connection name
     * @return array
     */
    public static function getTableStatusByConnection(string $table, ?string $connection = null): array
    {
        // デフォルトのコネクション設定
        if (is_null($connection)) {
            $connection = self::DEFAULT_CONNECTION_NAME;
        }

        // objectの配列を変換
        $tmpSchemaList = DB::connection($connection)->select("SHOW FULL COLUMNS FROM $table");
        $tableColumnList = ArrayLibrary::toArray($tmpSchemaList);

        return array_values($tableColumnList);
    }

    /**
     * disconnect from database.
     *
     * @param string $connection connection name
     * @return void
     */
    public static function disconnect(string $connection): void
    {
        DB::connection($connection)->disconnect();
    }

    /**
     * trunacte table.
     *
     * @param string $connection connection name
     * @param string $table table name
     * @return void
     */
    public static function truncate(string $connection, string $table): void
    {
        DB::connection($connection)->table($table)->truncate();
    }
}
