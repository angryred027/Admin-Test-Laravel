<?php

declare(strict_types=1);

namespace Database\Seeders\Masters;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Library\Time\TimeLibrary;
use App\Models\Masters\Permissions;
use Database\Seeders\BaseSeeder;

class PermissionsTableSeeder extends BaseSeeder
{
    protected const SEEDER_DATA_LENGTH = 4;
    protected const SEEDER_DATA_TESTING_LENGTH = 4;
    protected const SEEDER_DATA_DEVELOP_LENGTH = 4;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->tableName = (new Permissions())->getTable();

        $now = TimeLibrary::getCurrentDateTime();

        $template = [
            'name'       => '',
            'created_at' => $now,
            'updated_at' => $now
        ];

        $dataList = Config::get('myappSeeder.seeder.authority.permissionsNameList');

        // データ数
        $this->count = $this->getSeederDataLengthByEnv();

        // insert用データ
        $data = [];

        // 1~$this->countの数字の配列でforを回す
        foreach (range(1, $this->count) as $i) {
            $row = $template;

            $row['name'] = $dataList[$i - 1];

            $data[] = $row;
        }

        // テーブルへの格納
        DB::table($this->tableName)->insert($data);
    }
}
