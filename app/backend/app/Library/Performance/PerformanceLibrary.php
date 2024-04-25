<?php

declare(strict_types=1);

namespace App\Library\Performance;

class PerformanceLibrary
{
    public const ONE_DAY_HOURS = 24;
    public const ONE_HOUR_SECONDS = 3600;

    /**
     * get daily active user
     *
     * @param int $activeUserCount
     * @param int $everyDayActiveUserRate (x%)
     * @return float
     */
    public static function getDailyActiveUser(int $activeUserCount, int $everyDayActiveUserRate): float
    {
        return floor($activeUserCount * ($everyDayActiveUserRate / 100));
    }

    /**
     * get query count per second
     *
     * @param int $dau daily active user
     * @param int $qpu query per user
     * @return float
     */
    public static function getQueryPerSecond(int $dau, int $qpu): float
    {
        return floor(($dau * $qpu) / self::ONE_DAY_HOURS / self::ONE_HOUR_SECONDS);
    }

    /**
     * get query count per second
     *
     * @param int $dau daily active user
     * @param int $qpu query per user
     * @param int $queryRate rate of query
     * @param int $size object size set to storage
     * @return float
     */
    public static function getStorageSize(int $dau, int $qpu, int $queryRate, int $size): float
    {
        return floor(($dau * $qpu) * ($queryRate / 100) * $size);
    }
}
