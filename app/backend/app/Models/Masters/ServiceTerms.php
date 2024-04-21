<?php

declare(strict_types=1);

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceTerms extends Model
{
    use HasFactory;
    use SoftDeletes;

    // カラム一覧
    public const ID             = 'id';
    public const VERSION        = 'version';
    public const TERMS          = 'terms';
    public const PRIVACY_POLICY = 'privacy_policy';
    public const MEMO           = 'memo';
    public const START_AT       = 'start_at';
    public const END_AT         = 'end_at';
    public const CREATED_AT     = 'created_at';
    public const UPDATED_AT     = 'updated_at';
    public const DELETED_AT     = 'deleted_at';

    //テーブル名指定
    protected $table = 'service_terms';

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
        self::VERSION,
        self::TERMS,
        self::PRIVACY_POLICY,
        self::MEMO,
        self::START_AT,
        self::END_AT,
        self::CREATED_AT,
        self::UPDATED_AT
    ];

    public function __construct()
    {
    }

    /**
     * sort by version.
     *
     * @param array $records record list
     * @param int $order order
     * @return array
     */
    public static function sortByVersion(array $records, int $order = SORT_ASC): array
    {
        $versions = array_column($records, self::VERSION);

        array_multisort($versions, $order, $records);
        return $records;
    }
}
