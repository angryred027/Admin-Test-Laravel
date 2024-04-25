<?php

declare(strict_types=1);

namespace Database\Seeders\Masters;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Library\Time\TimeLibrary;
use App\Models\Masters\Manufacturers;
use Database\Seeders\BaseSeeder;

class ManufacturersTableSeeder extends BaseSeeder
{
    protected const SEEDER_DATA_LENGTH = 5;
    protected const SEEDER_DATA_TESTING_LENGTH = 5;
    protected const SEEDER_DATA_DEVELOP_LENGTH = 50;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->tableName = (new Manufacturers())->getTable();

        $now = TimeLibrary::getCurrentDateTime();

        $template = [
            Manufacturers::NAME       => '',
            Manufacturers::DETAIL     => '',
            Manufacturers::ADDRESS    => 'test県test市test町',
            Manufacturers::TEL        => '000-0000-0000',
            Manufacturers::CREATED_AT => $now,
            Manufacturers::UPDATED_AT => $now
        ];

        // insert用データ
        $data = [];

        // データ数
        $this->count = $this->getSeederDataLengthByEnv();

        // 1~$this->countの数字の配列でforを回す
        foreach (range(1, $this->count) as $i) {
            $row = $template;

            $row[Manufacturers::NAME]    = 'manufacturer' . (string)($i);
            $row[Manufacturers::DETAIL]  = 'testManufacturer' . (string)($i) . 'Detail';
            $row[Manufacturers::ADDRESS] = 'test県test市test' . (string)($i) . '町';
            $row[Manufacturers::TEL]     = '000-0000-000' . (string)($i);

            $data[] = $row;
        }

        // テーブルへの格納
        DB::table($this->tableName)->insert($data);
    }
}
