<?php

declare(strict_types=1);

namespace App\Library\Cache;

use Illuminate\Redis\RedisManager;
use Illuminate\Support\Facades\Redis;
use Predis\Response\Status;
use App\Exceptions\MyApplicationHttpException;
use App\Library\Cache\CacheLibrary;
use App\Library\Message\StatusCodeMessages;
use App\Library\Time\TimeLibrary;

class SortedSetLibrary extends CacheLibrary
{
    // database.phpのキー名
    protected const REDIS_CONNECTION = 'cache';
    protected const DEFAULT_CACHE_EXPIRE = 86400; // (1日=86400秒)
    protected const HASH_RECORD_KEY = 'record'; // hash内のキー名
    protected const SORTED_SET_RECORD_KEY = 'record'; // hash内のキー名

    private const SET_CACHE_RESULT_VALUE = 'OK';
    private const SET_CACHE_EXPIRE_RESULT_VALUE = 1;

    private const ZREVRANGE_OPTION_WITH_SCORE = 'WITHSCORES';
    private const ZREVRANGE_OPTION_REV = 'rev';

    /**
     * zet add for increment.
     *
     * @param string $key
     * @param int $value
     * @param int $expire
     * @return void
     */
    public static function zIncBy(string $key, int $value, int $expire = self::DEFAULT_CACHE_EXPIRE): void
    {
        // test時は実行しない
        if (!self::isTesting()) {
            // floatに変換
            $floatValue = (float)$value;

            // $result = Redis::connection(static::REDIS_CONNECTION)->command('ZADD', [static::SORTED_SET_RECORD_KEY, [$key => $floatValue]]);
            $result = Redis::connection(static::REDIS_CONNECTION)
                ->command('ZINCRBY', [static::SORTED_SET_RECORD_KEY, $floatValue, $key]);
            // 登録済みはresult = 0。これもエラーとする。
            if ($result <= 0) {
                throw new MyApplicationHttpException(
                    StatusCodeMessages::STATUS_500,
                    'zincr cache action is failure.'
                );
            }

            // 期限が設定済みでは無い場合
            if (!(0 < self::getTtl(static::SORTED_SET_RECORD_KEY))) {
                // 現在の時刻から$expire秒後のタイムスタンプを期限に設定
                /** @var int $setExpireResult 期限設定処理結果 */
                $setExpireResult = Redis::connection(static::REDIS_CONNECTION)
                    ->expireAt(static::SORTED_SET_RECORD_KEY, TimeLibrary::getCurrentDateTimeTimeStamp() + $expire);

                if ($setExpireResult !== self::SET_CACHE_EXPIRE_RESULT_VALUE) {
                    throw new MyApplicationHttpException(
                        StatusCodeMessages::STATUS_500,
                        'set cache expire action is failure.'
                    );
                }
            }
        }
    }

    /**
     * remove zincre or zdd cache.
     *
     * @param string $key
     * @param bool $igoreResult
     * @return void
     */
    public static function zRem(string $key, bool $igoreResult = false): void
    {
        // test時は実行しない
        if (!self::isTesting()) {
            $result = Redis::connection(static::REDIS_CONNECTION)
                ->command('ZREM', [static::SORTED_SET_RECORD_KEY, $key]);
            // 登録済みはresult = 0。これもエラーとする。
            if ($igoreResult && $result <= 0) {
                throw new MyApplicationHttpException(
                    StatusCodeMessages::STATUS_500,
                    'zrem cache action is failure.'
                );
            }
        }
    }

    /**
     * zet reverse range.
     *
     * @param string $key
     * @param int $top
     * @param int $end
     * @param bool $isWithScore
     * @return array
     */
    public static function zRevRange(
        string $key,
        int $top,
        int $end,
        bool $isWithScore = false,
    ): array {
        // test時は実行しない
        if (self::isTesting()) {
            return [];
        }

        $option = [];
        if ($isWithScore) {
            $option = [self::ZREVRANGE_OPTION_WITH_SCORE => true];
        }

        $result = Redis::connection(static::REDIS_CONNECTION)
            ->command('ZREVRANGE', [static::SORTED_SET_RECORD_KEY, $top, $end, $option]);

        return $result;
    }

    /**
     * zet range.
     *
     * @param string $key
     * @param int $top
     * @param int $end
     * @param bool $isWithScore
     * @param bool $isRev
     * @return array
     */
    public static function zRange(
        string $key,
        int $top,
        int $end,
        bool $isWithScore = false,
        bool $isRev = false
    ): array {
        // test時は実行しない
        if (self::isTesting()) {
            return [];
        }

        $option = [];
        if ($isWithScore) {
            $option = [self::ZREVRANGE_OPTION_WITH_SCORE => true];
        }

        if ($isRev) {
            $option = array_merge(
                $option,
                [self::ZREVRANGE_OPTION_REV => true]
            );
        }

        $result = Redis::connection(static::REDIS_CONNECTION)
            ->command('ZRANGE', [static::SORTED_SET_RECORD_KEY, $top, $end, $option]);

        return $result;
    }
}
