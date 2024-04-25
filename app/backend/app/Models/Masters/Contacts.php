<?php

declare(strict_types=1);

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contacts extends Model
{
    use HasFactory;
    use SoftDeletes;

    // 問合せ
    public const TYPE_QUESTION = 1; // サービスに関する問合せ
    public const TYPE_REQUST = 2; // ご意見・ご要望
    public const TYPE_FAILURE = 3; // 障害報告
    public const TYPE_CHEAT_HARASMENT_REPORT = 4; // 不正・悪質行為報告
    public const TYPE_COPYRIGHT = 5; // 著作物について
    public const TYPE_COPORATION = 6; // 法人のお客様はこちら
    public const TYPE_ETC = 99; // その他

    public const CONTACT_CATEGORIES = [
        self::TYPE_QUESTION,
        self::TYPE_REQUST,
        self::TYPE_FAILURE,
        self::TYPE_CHEAT_HARASMENT_REPORT,
        self::TYPE_COPYRIGHT,
        self::TYPE_COPORATION,
        self::TYPE_ETC,
    ];

    public const CONTACT_CATEGORIE_TEXT_LIST = [
        self::TYPE_QUESTION => 'サービスに関する問合せ',
        self::TYPE_REQUST => 'ご意見・ご要望',
        self::TYPE_FAILURE => '障害報告',
        self::TYPE_CHEAT_HARASMENT_REPORT => '不正・悪質行為報告',
        self::TYPE_COPYRIGHT => '著作物について',
        self::TYPE_COPORATION => '法人のお客様はこちら',
        self::TYPE_ETC => 'その他',
    ];

    // カラム一覧
    public const ID             = 'id';
    public const EMAIL           = 'email';
    public const USER_ID        = 'user_id';
    public const NAME           = 'name';
    public const TYPE           = 'type';
    public const DETAIL         = 'detail';
    public const FAILURE_DETAIL = 'failure_detail';
    public const FAILURE_AT     = 'failure_at';
    public const CREATED_AT     = 'created_at';
    public const UPDATED_AT     = 'updated_at';
    public const DELETED_AT     = 'deleted_at';

    //テーブル名指定
    protected $table = 'contacts';

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
        self::EMAIL,
        self::USER_ID,
        self::NAME,
        self::TYPE,
        self::DETAIL,
        self::FAILURE_DETAIL,
        self::FAILURE_AT,
        self::CREATED_AT,
        self::UPDATED_AT
    ];

    public function __construct()
    {
    }
}
