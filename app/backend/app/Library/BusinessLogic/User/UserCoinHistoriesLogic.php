<?php

declare(strict_types=1);

namespace App\Library\BusinessLogic\User;

use App\Exceptions\MyApplicationHttpException;
use App\Library\Message\StatusCodeMessages;
use App\Models\Users\UserCoinHistories;

class UserCoinHistoriesLogic
{
    /**
     * get value by coin type
     *
     * @param int $coinType coin type
     * @return int test value
     */
    public static function getUserCoinType(int $coinType): int
    {
        switch ($coinType) {
            case UserCoinHistories::USER_COINS_HISTORY_TYPE_PURCHASED: // 購入
                break;
            case UserCoinHistories::USER_COINS_HISTORY_TYPE_GAIN: // 獲得
                break;
            case UserCoinHistories::USER_COINS_HISTORY_TYPE_CONSUME: // 消費
                break;
            case UserCoinHistories::USER_COINS_HISTORY_TYPE_EXPIRED: // 期限切れ
                break;
            case UserCoinHistories::USER_COINS_HISTORY_TYPE_COMPENSATION: // 補填
                break;
            default:
                throw new MyApplicationHttpException(
                    StatusCodeMessages::STATUS_500,
                    'Received Invalid Coin Type.: ' . $coinType,
                    [
                        UserCoinHistories::TYPE => $coinType,
                    ]
                );
                break;
        }

        return 0;
    }
}
