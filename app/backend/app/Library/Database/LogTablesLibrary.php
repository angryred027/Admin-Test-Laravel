<?php

declare(strict_types=1);

namespace App\Library\Database;

use Illuminate\Support\Facades\Config;

class LogTablesLibrary
{
    /**
     * get log databse connection name.
     *
     * @return string
     */
    public static function getLogDatabaseConnection(): string
    {
        return Config::get('myapp.database.logs.baseConnectionName');
    }
}
