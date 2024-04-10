<?php

declare(strict_types=1);

namespace Database\Seeders\Masters;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Library\Time\TimeLibrary;
use App\Models\Masters\Admins;
use Database\Seeders\BaseSeeder;

class AdminsTableSeeder extends BaseSeeder
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
        $this->tableName = (new Admins())->getTable();

        $now = TimeLibrary::getCurrentDateTime();

        $template = [
            Admins::NAME       => '',
            Admins::EMAIL      => '',
            Admins::PASSWORD   => bcrypt(Config::get('myappSeeder.seeder.password.testadmin')),
            Admins::CREATED_AT => $now,
            Admins::UPDATED_AT => $now
        ];

        // insert用データ
        $data = [];

        // データ数
        $this->count = $this->getSeederDataLengthByEnv();

        // 1~$this->countの数字の配列でforを回す
        foreach (range(1, $this->count) as $i) {
            $row = $template;

            $row[Admins::NAME]  = 'admin' . (string)($i);
            $row[Admins::EMAIL] = 'testadmin' . (string)($i) . '@example.com';

            $data[] = $row;
        }

        // テーブルへの格納
        DB::table($this->tableName)->insert($data);
    }
}
