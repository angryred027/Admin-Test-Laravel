<?php

declare(strict_types=1);

namespace App\Library\Database;

use Illuminate\Support\Facades\DB;
use App\Library\Database\DatabaseLibrary;
use App\Library\Database\ShardingLibrary;

class TransactionLibrary
{
    /**
     * begin transaction in sharding table.
     *
     * @param string $connection connection name
     * @return void
     */
    public static function beginTransaction(string $connection): void
    {
        DB::connection($connection)->beginTransaction();
    }

    /**
     * commit active database transaction.
     *
     * @param string $connection connection name
     * @return void
     */
    public static function commit(string $connection): void
    {
        DB::connection($connection)->commit();
    }

    /**
     * rollback active database transaction.
     *
     * @param string $connection connection name
     * @return void
     */
    public static function rollback(string $connection): void
    {
        DB::connection($connection)->rollback();
    }

    /**
     * begin transaction in sharding table.
     *
     * @param int $userId user id
     * @return void
     */
    public static function beginTransactionByUserId(int $userId): void
    {
        $userDBConnection = ShardingLibrary::getConnectionByUserId($userId);
        DB::connection($userDBConnection)->beginTransaction();

        $masterDBConnection = DatabaseLibrary::geMasterDatabaseConnection();
        // テスト時は単一のDBを利用している為不要
        if ($userDBConnection !== $masterDBConnection) {
            // usersテーブルがあるDBに対してtransactionを設定する。(DBが一緒なら不要)
            DB::connection($masterDBConnection)->beginTransaction();
        }
    }

    /**
     * commit active database transaction.
     *
     * @param int $userId user id
     * @return void
     */
    public static function commitByUserId(int $userId): void
    {
        $userDBConnection = ShardingLibrary::getConnectionByUserId($userId);
        DB::connection($userDBConnection)->commit();

        $masterDBConnection = DatabaseLibrary::geMasterDatabaseConnection();
        // テスト時は単一のDBを利用している為不要
        if ($userDBConnection !== $masterDBConnection) {
            // usersテーブルがあるDBに対してtransaction commitする。(DBが一緒なら不要)
            DB::connection($masterDBConnection)->commit();
        }
    }

    /**
     * rollback active database transaction.
     *
     * @param int $userId user id
     * @return void
     */
    public static function rollbackByUserId(int $userId): void
    {
        $userDBConnection = ShardingLibrary::getConnectionByUserId($userId);
        DB::connection($userDBConnection)->rollback();

        $masterDBConnection = DatabaseLibrary::geMasterDatabaseConnection();
        // テスト時は単一のDBを利用している為不要
        if ($userDBConnection !== $masterDBConnection) {
            // usersテーブルがあるDBに対してtransaction rollbackする。(DBが一緒なら不要)
            DB::connection($masterDBConnection)->rollback();
        }
    }

    /**
     * begin transaction in sharding table by some user ids.
     *
     * @param array $userIds user ids
     * @return void
     */
    public static function beginTransactionByUserIds(array $userIds): void
    {
        $connections = ShardingLibrary::getConnectionsByUserIds($userIds);
        foreach ($connections as $connection) {
            DB::connection($connection)->beginTransaction();
        }
    }

    /**
     * commit active database transaction by some user ids.
     *
     * @param array $userIds user ids
     * @return void
     */
    public static function commitByUserIds(array $userIds): void
    {
        $connections = ShardingLibrary::getConnectionsByUserIds($userIds);
        foreach ($connections as $connection) {
            DB::connection($connection)->commit();
        }
    }

    /**
     * rollback active database transaction by some user ids.
     *
     * @param array $userIds user ids
     * @return void
     */
    public static function rollbackByUserIds(array $userIds): void
    {
        $connections = ShardingLibrary::getConnectionsByUserIds($userIds);
        foreach ($connections as $connection) {
            DB::connection($connection)->rollback();
        }
    }
}
