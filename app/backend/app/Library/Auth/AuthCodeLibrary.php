<?php

declare(strict_types=1);

namespace App\Library\Auth;

use stdClass;
use App\Exceptions\MyApplicationHttpException;
use App\Library\Time\TimeLibrary;
use App\Library\Message\StatusCodeMessages;
use App\Models\Users\UserAuthCodes;

class AuthCodeLibrary
{
    public const MAX_CODE_TRIAL_COUNT = 5; // 認証コードの最大確認回数

    /**
     * validate auth code.
     *
     * @param int $userId
     * @param int $authCode
     * @param array $record record
     * @return bool
     * @throws MyApplicationHttpException
     */
    public static function validateAuthCode(int $userId, int $authCode, array $record): bool
    {
        if (empty($record)) {
            throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_401,
                '認証コード情報がありません。',
                ['userId' => $userId, 'record' => $record],
                false
            );
        }

        if ($userId !== $record[UserAuthCodes::USER_ID]) {
            throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_401,
                'ユーザーIDが一致しません。',
                ['userId' => $userId, 'record_user_id' => $record[UserAuthCodes::USER_ID]],
                false
            );
        }

        if (self::isUsed($record)) {
            throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_401,
                '認証コードが使用済みです。',
                ['userId' => $userId, 'is_used' => $record[UserAuthCodes::IS_USED]],
                false
            );
        }

        if (self::isExpired($record)) {
            throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_401,
                '認証コードが期限切れです。',
                ['userId' => $userId, 'expired_at' => $record[UserAuthCodes::EXPIRED_AT]],
                false
            );
        }

        if (self::isGreaterThanMaxTrialCount($record)) {
            throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_401,
                '認証コードの利用回数の上限を超えています。',
                ['userId' => $userId, 'count' => $record[UserAuthCodes::COUNT]],
                false
            );
        }

        // no match is return false, not error.
        return self::isMatchAuthCode($record, $authCode);
    }

    /**
     * check is used.
     *
     * @param array $record record
     * @return bool
     */
    public static function isUsed(array $record): bool
    {
        return $record[UserAuthCodes::IS_USED] === 1;
    }

    /**
     * check is expired.
     *
     * @param array $record record
     * @param ?string $dateTime dateTime
     * @return bool
     */
    public static function isExpired(array $record, ?string $dateTime = null): bool
    {
        if (is_null($dateTime)) {
            $dateTime = TimeLibrary::getCurrentDateTime();
        }
        return TimeLibrary::greater($dateTime, $record[UserAuthCodes::EXPIRED_AT]);
    }

    /**
     * check is over max trial count.
     *
     * @param array $record record
     * @return bool
     */
    public static function isGreaterThanMaxTrialCount(array $record): bool
    {
        return self::MAX_CODE_TRIAL_COUNT <= $record[UserAuthCodes::COUNT];
    }

    /**
     * check is match auth code of paramter & in record.
     *
     * @param array $record record
     * @param int $code code
     * @return bool
     */
    public static function isMatchAuthCode(array $record, int $code): bool
    {
        return $record[UserAuthCodes::CODE] === $code;
    }
}
