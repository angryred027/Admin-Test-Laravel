<?php

declare(strict_types=1);

namespace App\Models\Masters;

use App\Library\Time\TimeLibrary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Questionnaires extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const QUESTION_TYPE_TEXT = 1; // テキストボックス
    public const QUESTION_TYPE_TEXT_AREA = 2; // テキストエリア
    public const QUESTION_TYPE_CHECK_BOX = 3; // チェックボックス
    public const QUESTION_TYPE_RADIO_BUTTON = 4; // ラジオボタン

    public const QUESTION_TYPE_LIST = [
        self::QUESTION_TYPE_TEXT,
        self::QUESTION_TYPE_TEXT_AREA,
        self::QUESTION_TYPE_CHECK_BOX,
        self::QUESTION_TYPE_RADIO_BUTTON,
    ];
    public const SELECT_QUESTION_TYPE_LIST = [
        self::QUESTION_TYPE_CHECK_BOX,
        self::QUESTION_TYPE_RADIO_BUTTON,
    ];

    // questionsカラム内の要素
    public const QUESTION_KEY_KEY = 'key';
    public const QUESTION_KEY_TEXT = 'text';
    public const QUESTION_KEY_TYPE = 'type';
    public const QUESTION_KEY_CHOICES = 'chocies';
    public const QUESTION_KEY_DEFAULT_TEXT = 'defaultText';

    // questionsカラム内の選択肢の要素
    public const CHOICE_KEY_KEY = 'key';
    public const CHOICE_KEY_NAME = 'name';

    public const TEXT_MAX_COUNT = 100;
    public const TEXT_AREA_MAX_COUNT = 1000;

    // カラム一覧
    public const ID         = 'id';
    public const NAME       = 'name';
    public const DETAIL     = 'detail';
    public const QUESTIONS  = 'questions';
    public const START_AT   = 'start_at';
    public const END_AT     = 'end_at';
    public const EXPIRED_AT = 'expired_at';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DELETED_AT = 'deleted_at';

    //テーブル名指定
    protected $table = 'questionnaires';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * used in initializeSoftDeletes()
     *
     * @var array
     */
    protected $casts = [self::DELETED_AT => 'datetime'];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = self::ID;

    /**
     * The attributes that are mass assignable(複数代入可能な属性(カラム)).
     *
     * @var array
     */
    protected $fillable = [
        self::NAME,
        self::DETAIL,
        self::QUESTIONS,
        self::START_AT,
        self::END_AT,
        self::EXPIRED_AT,
        self::CREATED_AT,
        self::UPDATED_AT
    ];

    public function __construct()
    {
    }

    /**
     * sort by start at.
     *
     * @param array $records record list
     * @param int $order order
     * @return array
     */
    public static function sortByStartAt(array $records, int $order = SORT_ASC): array
    {
        $startAts = [];
        foreach ($records as $record) {
            $startAts[] = TimeLibrary::strToTimeStamp($record[self::START_AT]);
        }

        array_multisort($startAts, $order, $records);
        return $records;
    }
}
