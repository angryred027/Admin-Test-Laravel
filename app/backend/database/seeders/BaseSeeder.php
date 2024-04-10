<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class BaseSeeder extends Seeder
{
    /** @var int SEEDER_DATA_LENGTH 本番環境等でインサートするレコード数 */
    protected const SEEDER_DATA_LENGTH = 10;
    /** @var int SEEDER_DATA_TESTING_LENGTH テスト実行時にインサートするレコード数 */
    protected const SEEDER_DATA_TESTING_LENGTH = 10;
    /** @var int SEEDER_DATA_DEVELOP_LENGTH ローカル環境等でインサートするレコード数 */
    protected const SEEDER_DATA_DEVELOP_LENGTH = 10;
    /** @var int $count インサートするレコード数 */
    protected int $count = 10;
    /** @var int $tableName テーブル名 */
    protected string $tableName = '';

    /**
     * get data length by env.
     *
     * @return int
     */
    protected function getSeederDataLengthByEnv(): int
    {
        $envName = Config::get('app.env');
        if ($envName === 'production') {
            return static::SEEDER_DATA_LENGTH;
        } elseif ($envName === 'testing') {
            // testの時
            return static::SEEDER_DATA_TESTING_LENGTH;
        } else {
            // localやstaging
            return static::SEEDER_DATA_DEVELOP_LENGTH;
        }
    }
}
