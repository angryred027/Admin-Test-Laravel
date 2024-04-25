<?php

declare(strict_types=1);

namespace Database\Seeders\Masters;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Library\Time\TimeLibrary;
use App\Models\Masters\Events;
use Database\Seeders\BaseSeeder;

class EventsTableSeeder extends BaseSeeder
{
    protected const SEEDER_DATA_LENGTH = 5;
    protected const SEEDER_DATA_TESTING_LENGTH = 5;
    protected const SEEDER_DATA_DEVELOP_LENGTH = 30;

    // 終了日時として設定する加算年数
    private const END_DATE_ADDITIONAL_YEARS = 5;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->tableName = (new Events())->getTable();

        $now = TimeLibrary::getCurrentDateTime();
        $endDate = TimeLibrary::addYears($now, self::END_DATE_ADDITIONAL_YEARS);

        $template = [
            Events::NAME       => '',
            Events::TYPE       => 1,
            Events::DETAIL     => '',
            Events::START_AT   => $now,
            Events::END_AT     => $endDate,
            Events::CREATED_AT => $now,
            Events::UPDATED_AT => $now
        ];

        // insert用データ
        $data = [];

        // データ数
        $this->count = $this->getSeederDataLengthByEnv();

        // 1~$this->countの数字の配列でforを回す
        foreach (range(1, $this->count) as $i) {
            $row = $template;

            $row[Events::NAME]   = 'イベント_' . (string)($i);
            $row[Events::TYPE]   = (($i - 1) % 3) + 1;
            $row[Events::DETAIL] = 'イベント詳細xxxxx_' . (string)($i) . '';

            $data[] = $row;
        }

        // テーブルへの格納
        DB::table($this->tableName)->insert($data);
    }
}
