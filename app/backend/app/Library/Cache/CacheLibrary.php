<?php

declare(strict_types=1);

namespace App\Library\Cache;

use Illuminate\Redis\RedisManager;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;
use Predis\Response\Status;
use Predis\Client;
use App\Exceptions\MyApplicationHttpException;
use App\Library\Message\StatusCodeMessages;
use App\Library\Time\TimeLibrary;

class CacheLibrary
{
    // キーの接頭辞
    private const KEY_PREFIX = '_database_';

    // database.phpのキー名
    protected const REDIS_CONNECTION = 'cache';
    protected const DEFAULT_CACHE_EXPIRE = 86400; // (1日=86400秒)

    private const SET_CACHE_RESULT_VALUE = 'OK';
    private const SET_CACHE_EXPIRE_RESULT_VALUE = 1;

    private const DELETE_CACHE_RESULT_VALUE_SUCCESS = 1;
    private const DELETE_CACHE_RESULT_VALUE_NO_DATA = 0;

    /**
     * is redis cluster mode.
     *
     * @return bool
     */
    protected static function isClusterMode(): bool
    {

        return config('database.redis.cluster');
    }

    /**
     * get predis client.
     * *redis-clusterを使う場合、必須の様な挙動の為通常モードとclusterモード両方で使えるクライアントを用意する
     *
     * @return Client
     */
    protected static function getClient(): Client
    {
        $connection = static::REDIS_CONNECTION;
        $parameters = config("database.redis.$connection");
        /* $options = null;
        if (self::isClusterMode()) {
            $options = config('database.redis.options');
        } */
        $options = config('database.redis.options');
        return (new Client($parameters, $options));
    }

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

        // $cache = Redis::connection(static::REDIS_CONNECTION)->get($key);
        $cache = static::getClient()->get($key);

        if (is_null($cache)) {
            return $cache;
        }

        /* if (is_string($cache)) {
            return $cache;
        } */

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
                $value = json_encode($value);
            }

            /** @var Status $result redisへの設定処理結果 */
            // $result = Redis::connection(static::REDIS_CONNECTION)->set($key, $value);
            $result = self::getClient()->set($key, $value);

            $payload = $result->getPayload();

            if ($payload !== self::SET_CACHE_RESULT_VALUE) {
                throw new MyApplicationHttpException(
                    StatusCodeMessages::STATUS_500,
                    'set cache action is failure.'
                );
            }

            // 現在の時刻から$expire秒後のタイムスタンプを期限に設定
            /** @var int $setExpireResult 期限設定処理結果 */
            // $setExpireResult = Redis::connection(static::REDIS_CONNECTION)
            $setExpireResult = static::getClient()
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
        $cache = static::getByKey($key);

        if (empty($cache)) {
            if ($isIgnore || self::isTesting()) {
                return;
            }

            throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_500,
                'cache is not exist.'
            );
        }

        /** @var int $result 削除結果 */
        // $result = Redis::connection(static::REDIS_CONNECTION)->del($key);
        $result = static::getClient()->del($key);

        if (($result !== self::DELETE_CACHE_RESULT_VALUE_SUCCESS) && !$isIgnore) {
            throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_500,
                'delete cache action is failure.'
            );
        }
    }

    /**
     * set cache to redis.
     *
     * @param string $key
     * @return int
     */
    public static function getTtl(string $key): int
    {
        // test時は実行しない
        if (self::isTesting()) {
            return -1;
        }
        if (!self::isTesting()) {
            return static::getClient()->ttl($key);
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
        // $cache = Redis::connection(static::REDIS_CONNECTION)->get($key);
        $cache = static::getClient()->get($key);

        return $cache ? true : false;
    }

    /**
     * is testing env.
     *
     * @return bool
     */
    protected static function isTesting(): bool
    {
        return Config::get('app.env') === 'testing';
    }

    /**
     * get cache Key prefix.
     *
     * @param string $key
     * @return string
     */
    private static function getKeyPrefix(): string
    {
        // appの名前がつく
        return Config::get('app.name') . self::KEY_PREFIX;
    }

    /**
     * get redis connection.
     *
     * @param string $key
     * @return string
     */
    public static function getConnection(): string
    {
        return static::REDIS_CONNECTION;
    }

    /**
     * get cache value by Key.
     *
     * @param string $key
     * @return array
     */
    public static function getByAllKeys(): array
    {
        if (self::isTesting()) {
            return [];
        }

        // $keys = Redis::connection(static::REDIS_CONNECTION)->command('keys', ['*']);
        $keys = static::getClient()->keys('*');

        if (is_array($keys)) {
            return $keys;
        } else {
            return [];
        }
    }

    /**
     * get cache value by Key.
     *
     * @param string $key
     * @return array
     */
    public static function removeAllKeys(): void
    {
        if (self::isTesting()) {
            return;
        }

        // キャッシュキーのプレフィックス
        $prefix = self::getKeyPrefix();
        $prefixLength = mb_strlen($prefix);

        $keys = self::getByAllKeys();
        foreach ($keys as $key) {
            self::deleteCache(mb_substr($key, $prefixLength), true);
        }
    }
}
