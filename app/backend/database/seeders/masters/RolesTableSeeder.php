<?php

declare(strict_types=1);

namespace Database\Seeders\Masters;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Library\Time\TimeLibrary;
use App\Models\Masters\Roles;
use Database\Seeders\BaseSeeder;

class RolesTableSeeder extends BaseSeeder
{
    protected const SEEDER_DATA_LENGTH = 5;
    protected const SEEDER_DATA_TESTING_LENGTH = 5;
    protected const SEEDER_DATA_DEVELOP_LENGTH = 5;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->tableName = (new Roles())->getTable();

        $now = TimeLibrary::getCurrentDateTime();

        $template = [
            'name'       => '',
            'code'       => '',
            'detail'     => '',
            'created_at' => $now,
            'updated_at' => $now
        ];

        $nameList = Config::get('myappSeeder.seeder.authority.rolesNameList');
        $codeList = Config::get('myappSeeder.seeder.authority.rolesCodeList');
        $detailList = Config::get('myappSeeder.seeder.authority.rolesDetailList');

        // insert用データ
        $data = [];

        // データ数
        $this->count = $this->getSeederDataLengthByEnv();

        // 1~$this->countの数字の配列でforを回す
        foreach (range(1, $this->count) as $i) {
            $row = $template;

            $row['name']   = $nameList[$i - 1];
            $row['code']   = $codeList[$i - 1];
            $row['detail'] = $detailList[$i - 1];

            $data[] = $row;
        }

        // テーブルへの格納
        DB::table($this->tableName)->insert($data);
    }
}
