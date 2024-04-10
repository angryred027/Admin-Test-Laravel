<?php

declare(strict_types=1);

namespace App\Library\Hash;

class HashLibrary
{
    public const ALGORITHM_MD5 = 'md5';
    public const ALGORITHM_SHA1 = 'sha1';
    public const ALGORITHM_SHA256 = 'sha256';
    public const ALGORITHM_CRC32 = 'crc32';

    /**
     * get hash algo list
     *
     * @return array hash algos
     */
    public static function getHashRlgos(): array
    {
        return hash_algos();
    }

    /**
     * create hash of param
     * equal password_hash()
     *
     * @param string $value value
     * @param string $algorithm algorithm
     * @return string hash value
     */
    public static function hash(string $value, string $algorithm = self::ALGORITHM_SHA256): string
    {
        return hash($algorithm, $value);
    }

    /**
     * check value is equal hashed value.
     * equal password_verify()
     *
     * @param string $value value
     * @param string $hashedValue hashed value
     * @return bool is same value
     */
    public static function check(string $value, string $hashedValue): bool
    {
        return self::hash($value) === $hashedValue;
    }

    /**
     * check value is rehash.
     * equal password_needs_rehash()
     *
     * @param string $hash hashed value
     * @param string $algorithm algorithm
     * @param array $options options
     * @return bool is need rehash
     */
    public static function isNeedsRehash(
        string $hash,
        string $algorithm = self::ALGORITHM_SHA256,
        array $options = []
    ): bool {
        return password_needs_rehash($hash, $algorithm, $options);
    }

    /**
     * get about hash.
     * equal password_get_info()
     *
     * @param string $hashedValue hashed value
     * @return ?array
     */
    public static function info(string $hash): ?array
    {
        return password_get_info($hash);
    }

    /**
     * get password_algos.
     * equal password_algos()
     *
     * @return array
     */
    public static function passwordAlgos(): array
    {
        return password_algos();
    }

    /**
     * get service password pepper.
     *
     * @return string
     */
    public static function getPepper(): string
    {
        return config('myApp.hash.pepper');
    }
}
