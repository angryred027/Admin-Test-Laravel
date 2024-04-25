<?php

declare(strict_types=1);

namespace Database\Seeders\Masters;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Library\Time\TimeLibrary;
use App\Models\Masters\Products;
use Database\Seeders\BaseSeeder;

class ProductsTableSeeder extends BaseSeeder
{
    protected const SEEDER_DATA_LENGTH = 5;
    protected const SEEDER_DATA_TESTING_LENGTH = 5;
    protected const SEEDER_DATA_DEVELOP_LENGTH = 50;

    // 終了日時として設定する加算月数
    private const END_DATE_ADDITIONAL_MOUNTHS = 1;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->tableName = (new Products())->getTable();

        $now = TimeLibrary::getCurrentDateTime();
        $endDate = TimeLibrary::addMonths($now, self::END_DATE_ADDITIONAL_MOUNTHS);

        $template = [
            Products::NAME              => '',
            Products::DETAIL            => '',
            Products::TYPE              => 1,
            Products::PRICE             => 600,
            Products::UNIT              => '本',
            Products::MANUFACTURE       => 'テストメーカー',
            Products::NOTICE_START_AT   => $now,
            Products::NOTICE_END_AT     => $endDate,
            Products::PURCHASE_START_AT => $now,
            Products::PURCHASE_END_AT   => $endDate,
            Products::IMAGE             => '',
            Products::CREATED_AT        => $now,
            Products::UPDATED_AT        => $now
        ];

        // insert用データ
        $data = [];

        // データ数
        $this->count = $this->getSeederDataLengthByEnv();

        // 1~$this->countの数字の配列でforを回す
        foreach (range(1, $this->count) as $i) {
            $row = $template;

            $row[Products::NAME]          = 'product' . (string)($i);
            $row[Products::DETAIL]        = 'testProduct' . (string)($i) . '@example.com';
            $row[Products::MANUFACTURE]  .= ' product' . (string)($i);
            $row[Products::IMAGE]         = '/product/image/' . (string)($i);

            $data[] = $row;
        }

        // テーブルへの格納
        DB::table($this->tableName)->insert($data);
    }
}
