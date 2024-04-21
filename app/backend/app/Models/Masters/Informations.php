<?php

declare(strict_types=1);

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Informations extends Model
{
    use HasFactory;
    use SoftDeletes;

    // 決済のステータス
    public const INFORMATION_TYPE_DEFAULT = 0b01; // お知らせ
    public const INFORMATION_TYPE_MAINTENANCE = 0b10; // メンテナンス
    public const INFORMATION_TYPE_FAILURE = 0b11; // 障害

    public const INFORMATION_TYPE_LIST = [
        self::INFORMATION_TYPE_DEFAULT => 'お知らせ',
        self::INFORMATION_TYPE_MAINTENANCE => 'メンテナンス',
        self::INFORMATION_TYPE_FAILURE => '障害',
    ];

    // カラム一覧
    public const ID         = 'id';
    public const NAME       = 'name';
    public const TYPE       = 'type';
    public const DETAIL     = 'detail';
    public const START_AT   = 'start_at';
    public const END_AT     = 'end_at';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DELETED_AT = 'deleted_at';

    //テーブル名指定
    protected $table = 'informations';

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
        self::TYPE,
        self::DETAIL,
        self::START_AT,
        self::END_AT,
        self::CREATED_AT,
        self::UPDATED_AT
    ];

    public function __construct()
    {
    }
}
