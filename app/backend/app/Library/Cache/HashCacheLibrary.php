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
use App\Trait\CheckHeaderTrait;

class HashCacheLibrary extends CacheLibrary
{
    use CheckHeaderTrait;

    // database.phpのキー名
    protected const REDIS_CONNECTION = 'cache';
    protected const DEFAULT_CACHE_EXPIRE = 86400; // (1日=86400秒)
    protected const HASH_RECORD_KEY = 'record'; // hash内のキー名

    private const SET_CACHE_RESULT_VALUE = 'OK';
    private const SET_CACHE_EXPIRE_RESULT_VALUE = 1;

    private const DELETE_CACHE_RESULT_VALUE_SUCCESS = 1;
    private const DELETE_CACHE_RESULT_VALUE_NO_DATA = 0;

    /**
     * get cache value by Key.
     *
     * @param string $key
     * @return mixed
     */
    public static function getByKey(string $key): mixed
    {
        if (self::isTesting()) {
            return null;
        }

        // hashキーとhash内のキーの両方を配列で指定
        $cache = Redis::connection(self::REDIS_CONNECTION)->command('HGET', [$key, self::HASH_RECORD_KEY]);

        if (empty($cache)) {
            return null;
        }

        if (is_array($cache)) {
            return $cache;
        }

        return json_decode($cache, true);
    }

    /**
     * set cache to redis.
     *
     * @param string $key
     * @param mixed $value
     * @param int $expire
     * @return void
     */
    public static function setCache(string $key, mixed $value, int $expire = self::DEFAULT_CACHE_EXPIRE): void
    {
        // test時は実行しない
        if (!self::isTesting()) {
            if (is_array($value)) {
                $jsonValue = json_encode($value);
            }

            // 設定済みの場合は削除が必要
            if (self::hasCache($key)) {
                self::deleteCache($key);
            }

            $result = Redis::connection(self::REDIS_CONNECTION)->command('HSET', [$key, self::HASH_RECORD_KEY, $jsonValue]);
            // 登録済みはresult = 0。これもエラーとする。
            if ($result !== 1) {
                throw new MyApplicationHttpException(
                    StatusCodeMessages::STATUS_500,
                    'set hash cache action is failure.'
                );
            }

            // 現在の時刻から$expire秒後のタイムスタンプを期限に設定
            /** @var int $setExpireResult 期限設定処理結果 */
            $setExpireResult = Redis::connection(self::REDIS_CONNECTION)
                ->expireAt($key, TimeLibrary::getCurrentDateTimeTimeStamp() + $expire);

            if ($setExpireResult !== self::SET_CACHE_EXPIRE_RESULT_VALUE) {
                throw new MyApplicationHttpException(
                    StatusCodeMessages::STATUS_500,
                    'set cache expire action is failure.'
                );
            }
        }
    }

    /**
     * remove cache by request header data.
     *
     * @param string $key
     * @param bool $isIgnore ignore data check result.
     * @return bool
     */
    public static function deleteCache(string $key, bool $isIgnore = false): void
    {
        $cache = self::getByKey($key);

        if (empty($cache)) {
            if ($isIgnore || self::isTesting()) {
                return;
            }

            throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_500,
                'cache is not exist.'
            );
        }

        /** @var int $result 削除結果 *hashキーとhash内のキーの両方を配列で指定 */
        $result = Redis::connection(self::REDIS_CONNECTION)->command('HDEL', [$key, self::HASH_RECORD_KEY]);

        if (($result !== self::DELETE_CACHE_RESULT_VALUE_SUCCESS) && !$isIgnore) {
            throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_500,
                'delete cache action is failure.'
            );
        }
    }

    /**
     * check has cache by key.
     *
     * @param string $key
     * @return bool
     */
    public static function hasCache(string $key): bool
    {
        // hashキーとhash内のキーの両方を配列で指定
        $cache = Redis::connection(self::REDIS_CONNECTION)->command('HGET', [$key, self::HASH_RECORD_KEY]);

        return !empty($cache) ? true : false;
    }
}
