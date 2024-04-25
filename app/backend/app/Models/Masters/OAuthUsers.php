<?php

declare(strict_types=1);

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use App\Library\Array\ArrayLibrary;
use Illuminate\Database\Eloquent\SoftDeletes;

class OAuthUsers extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const PROVIDER_TYPE_GIT_HUB = 1;
    public const PROVIDER_TYPE_TWITTER = 2;
    public const PROVIDER_TYPE_FACEBOOK = 3;

    public const PROVIDER_TYPE_LIST = [
        self::PROVIDER_TYPE_GIT_HUB => self::PROVIDER_TYPE_GIT_HUB,
        self::PROVIDER_TYPE_TWITTER => self::PROVIDER_TYPE_TWITTER,
        self::PROVIDER_TYPE_FACEBOOK => self::PROVIDER_TYPE_FACEBOOK,
    ];

    // カラム一覧
    public const ID = 'id';
    public const USER_ID = 'user_id';
    public const TYPE = 'type';
    public const GIT_HUB_ID = 'github_id';
    public const GIT_HUB_TOKEN = 'github_token';
    public const TWITTER_ID = 'twitter_id';
    public const TWITTER_TOKEN = 'twitter_token';
    public const FACEBOOK_ID = 'facebook_id';
    public const FACEBOOK_TOKEN = 'facebook_token';
    public const CODE = 'code';
    public const STATE = 'state';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DELETED_AT = 'deleted_at';

    // テーブル名指定
    protected $table = 'oauth_users';

    // カラムの自動更新をEloquentに許可
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
    protected $primaryKey = self::USER_ID;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        self::USER_ID,
        self::TYPE,
        self::GIT_HUB_ID,
        self::GIT_HUB_TOKEN,
        self::TWITTER_ID,
        self::TWITTER_TOKEN,
        self::FACEBOOK_ID,
        self::FACEBOOK_TOKEN,
        self::FACEBOOK_ID,
        self::FACEBOOK_TOKEN,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    public function getUserId()
    {
        return  $this->id;
    }

    public function getUserName()
    {
        return $this->name;
    }

    public function getUserEmail()
    {
        return $this->email;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.(JWTSubject)
     *
     * @a return mixed
     */
    public function getJWTIdentifier()
    {
        // primary keyを取得
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.(JWTSubject)
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * get query builder by user id.
     *
     * @return Builder
     */
    public function getQueryBuilder(): Builder
    {
        return DB::table($this->getTable());
    }

    /**
     * get single Record record by user id.
     *
     * @param int $userId user id
     * @param bool $isLock exec lock For Update
     * @return array|null
     */
    public function getRecordByUserId(int $userId, bool $isLock = false): array|null
    {
        $query = DB::table($this->getTable())->where(self::USER_ID, '=', $userId);

        if ($isLock) {
            $query->lockForUpdate();
        }

        $record = $query->get()->toArray();

        if (empty($record)) {
            return null;
        }

        return ArrayLibrary::getFirst(ArrayLibrary::toArray($record));
        // return ArrayLibrary::toArray($record);
    }

    /**
     * get single Record record by user id.
     *
     * @param int $userId user id
     * @param bool $isLock exec lock For Update
     * @return array|null
     */
    public function getRecordByGitHubUserId(int $userId, bool $isLock = false): array|null
    {
        $query = DB::table($this->getTable())->where(self::GIT_HUB_ID, '=', $userId);

        if ($isLock) {
            $query->lockForUpdate();
        }

        $record = $query->first();

        if (empty($record)) {
            return null;
        }

        return ArrayLibrary::toArray($record);
    }

    /**
     * insert record.
     *
     * @param array $resource resource
     * @return bool
     */
    public function insertUser(array $resource): bool
    {
        return DB::table($this->getTable())->insert($resource);
    }

    /**
     * update record by userId & githubId(code & state).
     *
     * @param int $userId user id
     * @param int $gitHubId github id
     * @param array $resource resource
     * @return bool
     */
    public function updateByUserIdAndGitHubId(int $userId, int $gitHubId, array $resource): bool
    {
        return (bool)DB::table($this->getTable())
            ->where(self::USER_ID, '=', $userId)
            ->where(self::GIT_HUB_ID, '=', $gitHubId)
            ->update($resource);
    }
}
