<?php

declare(strict_types=1);

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
// use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Library\Array\ArrayLibrary;
use App\Library\Random\RandomStringLibrary;
use App\Notifications\Admins\ResetPasswordNotification;

class Admins extends Authenticatable // implements JWTSubject
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    // カラム一覧
    public const ID = 'id';
    public const NAME = 'name';
    public const EMAIL = 'email';
    public const EMAIL_VERIFIED_AT = 'email_verified_at';
    public const PASSWORD = 'password';
    public const REMEMBER_TOKEN = 'remember_token';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DELETED_AT = 'deleted_at';

    //テーブル名指定
    protected $table = 'admins';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = self::ID;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::NAME,
        self::EMAIL,
        self::PASSWORD,
        self::UPDATED_AT
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        self::DELETED_AT => 'datetime',
    ];

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
     * sent password reset mail.
     *
     * @return void
     */
    public function sentPasswordResetMail(): void
    {
        $token = RandomStringLibrary::getRandomStringValue();
        $this->getEmailForPasswordReset();
        $this->sendPasswordResetNotification($token);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * get single Record record by user id.
     *
     * @param int $userId user id
     * @param bool $isLock exec lock For Update
     * @return array|null
     */
    public function getRecordById(int $id, bool $isLock = false): array|null
    {
        $query = DB::table($this->getTable())
            ->where(static::ID, '=', $id);


        if ($isLock) {
            $query->lockForUpdate();
        }

        $record = $query->get()->toArray();

        if (empty($record)) {
            return null;
        }

        return ArrayLibrary::getFirst(ArrayLibrary::toArray($record));
    }

    /**
     * get single Record record by user id.
     *
     * @param string $credential user email or name
     * @param string $password password
     * @param bool $isDevelopment either local development mode
     * @return array|null
     */
    public function getRecordByCredential(string $credential, string $password, bool $isDevelopment): array|null
    {
        $query = DB::table($this->getTable());

        // 開発時はnameで検索
        if ($isDevelopment) {
            $query->where(self::NAME, '=', $credential);
        } else {
            $query->where(self::EMAIL, '=', $credential);
        }

        $record = $query->get()->toArray();

        if (empty($record)) {
            return null;
        } else {
            $record = ArrayLibrary::getFirst(ArrayLibrary::toArray($record));
            if (Hash::check($password, $record[self::PASSWORD])) {
                return $record;
            }
        }

        return null;
    }
}
