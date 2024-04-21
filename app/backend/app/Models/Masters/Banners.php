<?php

declare(strict_types=1);

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banners extends Model
{
    use HasFactory;
    use SoftDeletes;

    // バナー設置場所
    public const LOCATION_TYPE_HOME = 'home'; // ホーム
    public const LOCATION_TYPE_BLOCK = 'block'; // ブロック
    public const LOCATION_TYPE_KEY_VISUAL = 'keyVisual'; // キービジュアル

    public const LOCATION_VALUE_HOME = 1; // ホーム
    public const LOCATION_VALUE_BLOCK = 2; // ブロック
    public const LOCATION_VALUE_KEY_VISUAL = 3; // キービジュアル

    public const LOCATION_VALUE_LIST = [
        self::LOCATION_VALUE_HOME => self::LOCATION_TYPE_HOME,
        self::LOCATION_VALUE_BLOCK => self::LOCATION_TYPE_BLOCK,
        self::LOCATION_VALUE_KEY_VISUAL => self::LOCATION_TYPE_KEY_VISUAL,
    ];

    // カラム一覧
    public const ID         = 'id';
    public const UUID       = 'uuid';
    public const NAME       = 'name';
    public const DETAIL     = 'detail';
    public const LOCATION   = 'location';
    public const PC_HEIGHT  = 'pc_height';
    public const PC_WIDTH   = 'pc_width';
    public const SP_HEIGHT  = 'sp_height';
    public const SP_WIDTH   = 'sp_width';
    public const START_AT   = 'start_at';
    public const END_AT     = 'end_at';
    public const URL        = 'url';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DELETED_AT = 'deleted_at';

    //テーブル名指定
    protected $table = 'banners';

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
        self::UUID,
        self::NAME,
        self::DETAIL,
        self::LOCATION,
        self::PC_HEIGHT,
        self::PC_WIDTH,
        self::SP_HEIGHT,
        self::SP_WIDTH,
        self::START_AT,
        self::END_AT,
        self::CREATED_AT,
        self::UPDATED_AT
    ];

    public function __construct()
    {
    }
}
