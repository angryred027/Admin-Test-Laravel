<?php

declare(strict_types=1);

namespace Database\Seeders\Masters;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Library\Time\TimeLibrary;
use App\Models\Masters\BannerBlockContents;
use Database\Seeders\BaseSeeder;

class BannerBlockContentsTableSeeder extends BaseSeeder
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
        $this->tableName = (new BannerBlockContents())->getTable();

        $now = TimeLibrary::getCurrentDateTime();
        $endDate = TimeLibrary::addYears($now, self::END_DATE_ADDITIONAL_YEARS);

        $template = [
            BannerBlockContents::BANNER_BLOCK_ID => 1,
            BannerBlockContents::BANNER_ID => 1,
            BannerBlockContents::TYPE        => 1,
            BannerBlockContents::ORDER        => 10,
            BannerBlockContents::START_AT    => $now,
            BannerBlockContents::END_AT      => $endDate,
            BannerBlockContents::CREATED_AT  => $now,
            BannerBlockContents::UPDATED_AT  => $now
        ];

        // insert用データ
        $data = [];

        // データ数
        $this->count = $this->getSeederDataLengthByEnv();

        // 1~$this->countの数字の配列でforを回す
        foreach (range(1, $this->count) as $i) {
            $row = $template;

            $row[BannerBlockContents::BANNER_BLOCK_ID]   = (($i - 1) % 5) + 1;
            $row[BannerBlockContents::BANNER_ID]   = $i;
            $row[BannerBlockContents::TYPE]   = (($i - 1) % 3) + 1;
            $row[BannerBlockContents::ORDER] = ($i * 10);

            $data[] = $row;
        }

        // テーブルへの格納
        DB::table($this->tableName)->insert($data);
    }
}
