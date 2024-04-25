<?php

declare(strict_types=1);

namespace Database\Seeders\Masters;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Library\Time\TimeLibrary;
use App\Models\Masters\Informations;
use App\Models\Masters\ServiceTerms;
use Database\Seeders\BaseSeeder;

class ServiceTermsTableSeeder extends BaseSeeder
{
    protected const SEEDER_DATA_LENGTH = 5;
    protected const SEEDER_DATA_TESTING_LENGTH = 5;
    protected const SEEDER_DATA_DEVELOP_LENGTH = 30;

    // 終了日時として設定する加算年数
    private const END_DATE_ADDITIONAL_YEARS = 5;

    public const TEMPALTE = [
        ServiceTerms::VERSION        => 1,
        ServiceTerms::TERMS          => '',
        ServiceTerms::PRIVACY_POLICY => '',
        ServiceTerms::MEMO           => '',
        ServiceTerms::START_AT       => '',
        ServiceTerms::END_AT         => '',
        ServiceTerms::CREATED_AT     => '',
        ServiceTerms::UPDATED_AT     => '',
        ServiceTerms::DELETED_AT     => null
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->tableName = (new ServiceTerms())->getTable();

        $now = TimeLibrary::getCurrentDateTime();
        $endDate = TimeLibrary::addYears($now, self::END_DATE_ADDITIONAL_YEARS);

        $template = self::TEMPALTE;
        $template[ServiceTerms::START_AT] = $now;
        $template[ServiceTerms::END_AT] = $endDate;
        $template[ServiceTerms::CREATED_AT] = $now;
        $template[ServiceTerms::UPDATED_AT] = $now;

        // insert用データ
        $data = [];

        // データ数
        $this->count = $this->getSeederDataLengthByEnv();

        // 1~$this->countの数字の配列でforを回す
        foreach (range(1, $this->count) as $i) {
            $row = $template;

            $row[ServiceTerms::VERSION] =  $i;
            $row[ServiceTerms::TERMS]   = '利用規約_' . (string)($i);
            $row[ServiceTerms::PRIVACY_POLICY]   = 'プライバシーポリシー' . (string)($i);
            $row[ServiceTerms::MEMO] = '利用規約メモ';

            $data[] = $row;
        }

        // テーブルへの格納
        DB::table($this->tableName)->truncate();
        DB::table($this->tableName)->insert($data);
    }
}
