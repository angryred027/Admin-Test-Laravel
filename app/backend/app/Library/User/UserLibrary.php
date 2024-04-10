<?php

declare(strict_types=1);

namespace App\Library\User;

use Illuminate\Support\Facades\Hash;
use App\Exceptions\MyApplicationHttpException;
use App\Library\Message\StatusCodeMessages;
use App\Library\Hash\HashLibrary;
use App\Models\User;

class UserLibrary
{
    /**
     * validate user password by record.
     *
     * @param string $value
     * @param array $user
     * @return bool
     */
    public static function validateUserPassword(string $value, array $user): bool
    {
        $pepper = HashLibrary::getPepper();
        $taget = $value. $user[User::SALT] . $pepper;
        // 現在のパスワードのチェック
        if (!Hash::check($taget, $user[User::PASSWORD])) {
            throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_404,
                'hash check failed.'
            );
        }

        return true;
    }

    /**
     * get user record & lock for update user record
     *
     * @param int $userId
     * @return array
     */
    public static function lockUser(int $userId): array
    {
        $user = (new User())->getRecordByUserId($userId, true);

        if (is_null($user)) {
            throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_404,
                'not exist user',
                ['userId' => $userId]
            );
        }

        return $user;
    }
}
