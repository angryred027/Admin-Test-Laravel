<?php

declare(strict_types=1);

namespace App\Library\Database;

use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\MySqlSchemaState;

class DumpLibrary
{
    // WARNING
    // database.phpのconnection設定に下記の設定を追加する必要がある。
    // mysqldumpコマンドを叩く事になる為mysqlコマンドだけ実行出来ても利用出来ない
    // 'dump' => [
    //     'dump_binary_path' => 'mysql-clientのPATH'
    // ]

    /**
     * dump database.
     *
     * @param string $connection database connection
     * @param string $path file path
     * @return void
     */
    public static function dump(string $connection, string $path): void
    {
        // \Illuminate\Contracts\Foundation\Application をパラメーターとして渡す必要がある
        $app = app();
        $connectionInstance = (new DatabaseManager($app, new ConnectionFactory($app)))->connection($connection);
        // sqliteを使う場合の設定は現状考慮外
        (new MySqlSchemaState($connectionInstance))->dump($connectionInstance, $path);
    }
}
