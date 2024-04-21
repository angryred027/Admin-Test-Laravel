<?php

declare(strict_types=1);

namespace Database\Seeders\Masters;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Library\Banner\BannerLibrary;
use App\Library\String\UuidLibrary;
use App\Library\Time\TimeLibrary;
use App\Models\Masters\Banners;
use Database\Seeders\BaseSeeder;

class BannersTableSeeder extends BaseSeeder
{
    protected const SEEDER_DATA_LENGTH = 5;
    protected const SEEDER_DATA_TESTING_LENGTH = 5;
    protected const SEEDER_DATA_DEVELOP_LENGTH = 10;

    // 終了日時として設定する加算年数
    private const END_DATE_ADDITIONAL_YEARS = 5;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->tableName = (new Banners())->getTable();

        $now = TimeLibrary::getCurrentDateTime();
        $endDate = TimeLibrary::addYears($now, self::END_DATE_ADDITIONAL_YEARS);

        $template = [
            Banners::UUID       => '',
            Banners::NAME       => '',
            Banners::DETAIL     => '',
            Banners::LOCATION   => '',
            Banners::PC_HEIGHT  => 240,
            Banners::PC_WIDTH   => 1200,
            Banners::SP_HEIGHT  => 60,
            Banners::SP_WIDTH   => 300,
            Banners::START_AT   => $now,
            Banners::END_AT     => $endDate,
            Banners::URL        => '',
            Banners::CREATED_AT => $now,
            Banners::UPDATED_AT => $now
        ];

        // insert用データ
        $data = [];

        // データ数
        $this->count = $this->getSeederDataLengthByEnv();

        // 1~$this->countの数字の配列でforを回す
        foreach (range(1, $this->count) as $i) {
            $row = $template;

            $row[Banners::UUID]   = BannerLibrary::getTestBannerUuidByNumber($i);
            $row[Banners::NAME]   = 'banner_' . (string)($i);
            $row[Banners::DETAIL] = 'testBanner' . (string)($i) . '';
            $row[Banners::LOCATION]   = Banners::LOCATION_VALUE_LIST[(($i % 3) + 1)];
            $row[Banners::URL] = config('app.url') . '/image/banner/' . $i;

            $data[] = $row;
        }

        // テーブルへの格納
        DB::table($this->tableName)->insert($data);
    }
}
