<?php

declare(strict_types=1);

namespace Database\Seeders\Masters;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Library\Time\TimeLibrary;
use App\Models\Masters\HomeContents;
use Database\Seeders\BaseSeeder;

class HomeContentsTableSeeder extends BaseSeeder
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
        $this->tableName = (new HomeContents())->getTable();

        $now = TimeLibrary::getCurrentDateTime();
        $endDate = TimeLibrary::addYears($now, self::END_DATE_ADDITIONAL_YEARS);

        $template = [
            HomeContents::TYPE        => HomeContents::HOME_CONTENTS_OTHER,
            HomeContents::GROUP_ID => 1,
            HomeContents::CONTENTS_ID => 1,
            HomeContents::START_AT    => $now,
            HomeContents::END_AT      => $endDate,
            HomeContents::CREATED_AT  => $now,
            HomeContents::UPDATED_AT  => $now
        ];

        // insert用データ
        $data = [];

        // データ数
        $this->count = $this->getSeederDataLengthByEnv();

        // 1~$this->countの数字の配列でforを回す
        foreach (range(1, $this->count) as $i) {
            $row = $template;

            // $row[HomeContents::TYPE]   = (($i - 1) % 3) + 1;
            // $row[HomeContents::GROUP_ID]   = (($i - 1) % 5) + 1;
            $row[HomeContents::CONTENTS_ID]   = (($i - 1) % 5) + 1;

            $data[] = $row;
        }

        // テーブルへの格納
        DB::table($this->tableName)->insert($data);
    }
}
