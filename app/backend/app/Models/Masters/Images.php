<?php

declare(strict_types=1);

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Images extends Model
{
    use HasFactory;
    use SoftDeletes;

    // カラム一覧
    public const ID          = 'id';
    public const UUID        = 'uuid';
    public const NAME        = 'name';
    public const EXTENTION   = 'extention';
    public const MIME_TYPE   = 'mime_type';
    public const S3_KEY      = 's3_key';
    public const VERSION     = 'version';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT  = 'updated_at';
    public const DELETED_AT  = 'deleted_at';

    //テーブル名指定
    protected $table = 'images';

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
     * Indicates if the IDs are auto-incrementing.
     * (primary keyが複数(配列)の場合はfalseを指定する。)
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable(複数代入可能な属性(カラム)).
     *
     * @var array
     */
    protected $fillable = [
        self::UUID,
        self::NAME,
        self::EXTENTION,
        self::MIME_TYPE,
        self::S3_KEY,
        self::VERSION,
        self::CREATED_AT,
        self::UPDATED_AT
    ];

    public function __construct()
    {
    }
}
