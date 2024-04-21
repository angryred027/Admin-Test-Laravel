<?php

declare(strict_types=1);

namespace Database\Seeders\Masters;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Library\Time\TimeLibrary;
use App\Models\Masters\Questionnaires;
use Database\Seeders\BaseSeeder;

class QuestionnairesTableSeeder extends BaseSeeder
{
    protected const SEEDER_DATA_LENGTH = 5;
    protected const SEEDER_DATA_TESTING_LENGTH = 5;
    protected const SEEDER_DATA_DEVELOP_LENGTH = 30;

    // 終了日時として設定する加算年数
    private const END_DATE_ADDITIONAL_YEARS = 5;

    public const TEMPALTE = [
        Questionnaires::NAME       => 1,
        Questionnaires::DETAIL     => '',
        Questionnaires::QUESTIONS  => [],
        Questionnaires::START_AT   => '',
        Questionnaires::END_AT     => '',
        Questionnaires::EXPIRED_AT => '',
        Questionnaires::CREATED_AT => '',
        Questionnaires::UPDATED_AT => '',
        Questionnaires::DELETED_AT => null
    ];

    public const QUESCTION_TEMPALTE = [
        Questionnaires::QUESTION_KEY_KEY => 1,
        Questionnaires::QUESTION_KEY_TEXT => '',
        Questionnaires::QUESTION_KEY_TYPE => 1,
        Questionnaires::QUESTION_KEY_CHOICES => [],
        Questionnaires::QUESTION_KEY_DEFAULT_TEXT => '',
    ];

    public const QUESCTION_CHOICE_TEMPALTE = [
        Questionnaires::CHOICE_KEY_KEY => 1,
        Questionnaires::CHOICE_KEY_NAME => '',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->tableName = (new Questionnaires())->getTable();

        $now = TimeLibrary::getCurrentDateTime();
        $endDate = TimeLibrary::addYears($now, self::END_DATE_ADDITIONAL_YEARS);

        $template = self::TEMPALTE;
        $template[Questionnaires::START_AT] = $now;
        $template[Questionnaires::END_AT] = $endDate;
        $template[Questionnaires::EXPIRED_AT] = $endDate;
        $template[Questionnaires::CREATED_AT] = $now;
        $template[Questionnaires::UPDATED_AT] = $now;

        // insert用データ
        $data = [];

        // データ数
        $this->count = $this->getSeederDataLengthByEnv();

        // 1~$this->countの数字の配列でforを回す
        foreach (range(1, $this->count) as $i) {
            $row = $template;

            $row[Questionnaires::NAME]   = 'アンケート_' . (string)($i);
            $row[Questionnaires::DETAIL]   = 'アンケート詳細_' . (string)($i);

            // 質問事項設定
            foreach (range(1, $this->count) as $j) {
                $question = self::QUESCTION_TEMPALTE;
                $question[Questionnaires::QUESTION_KEY_KEY] = $j;
                $question[Questionnaires::QUESTION_KEY_TEXT] = '質問内容_' . (string)$j;

                $type = ($j % 4) + 1;
                $isSelectType = in_array($type, Questionnaires::SELECT_QUESTION_TYPE_LIST, true);
                $question[Questionnaires::QUESTION_KEY_TYPE] = $type;

                if ($isSelectType) {
                    // 選択肢設定
                    foreach (range(1, 3) as $c) {
                        $choice = self::QUESCTION_CHOICE_TEMPALTE;
                        $choice[Questionnaires::CHOICE_KEY_KEY] = $c;
                        $choice[Questionnaires::CHOICE_KEY_NAME] = '選択肢内容_' . (string)$c;
                        $question[Questionnaires::QUESTION_KEY_CHOICES][] = $choice;
                    }
                } else {
                    $question[Questionnaires::QUESTION_KEY_DEFAULT_TEXT] = 'デフォルトテキスト_' . (string)$j;
                }

                $row[Questionnaires::QUESTIONS][] = $question;
            }
            // 連想配列の箇所をJSON化
            $row[Questionnaires::QUESTIONS] = json_encode($row[Questionnaires::QUESTIONS]);

            $data[] = $row;
        }

        // テーブルへの格納
        DB::table($this->tableName)->truncate();
        DB::table($this->tableName)->insert($data);
    }
}
