<?php

declare(strict_types=1);

namespace App\Library\Hash;

class SHA256HasherLibrary
{
    /**
     * create hash of param
     * equal password_hash()
     *
     * @param string $value value
     * @return string hash value
     */
    public static function make(string $value): string
    {
        return hash('sha256', $value);
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
        return self::make($value) === $hashedValue;
    }

    /**
     * check value is rehash.
     * equal password_needs_rehash()
     *
     * @param string $hash hashed value
     * @param array $options options
     * @return bool is need rehash
     */
    public static function isNeedsRehash(string $hash, array $options = []): bool
    {
        return password_needs_rehash($hash, 'sha256', $options);
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
}
