<?php

declare(strict_types=1);

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeContents extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const HOME_CONTENTS_KEY_VISUAL = 1;
    public const HOME_CONTENTS_INFORMATION = 2;
    public const HOME_CONTENTS_BANNER = 3;
    public const HOME_CONTENTS_OTHER = 99;

    // カラム一覧
    public const ID          = 'id';
    public const TYPE        = 'type';
    public const GROUP_ID    = 'group_id';
    public const CONTENTS_ID = 'contents_id';
    public const START_AT    = 'start_at';
    public const END_AT      = 'end_at';
    public const CREATED_AT  = 'created_at';
    public const UPDATED_AT  = 'updated_at';
    public const DELETED_AT  = 'deleted_at';

    //テーブル名指定
    protected $table = 'home_contents';

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
        self::TYPE,
        self::GROUP_ID,
        self::CONTENTS_ID,
        self::START_AT,
        self::END_AT,
        self::CREATED_AT,
        self::UPDATED_AT
    ];

    public function __construct()
    {
    }
}
