<?php

declare(strict_types=1);

namespace App\Library\Performance;

class MemoryLibrary
{
    // memory_get_usage(TRUE) = システム上から割り当てられている容量=あらかじめ確保されているメモリ容量=php.iniやini_setで設定した「memory_limit」のメモリ上限
    // memory_get_usage(FALSE) = スクリプトが実際に使用しているメモリ容量

    // メモリ使用量の例
    // App\Library\Performance\MemoryLibrary::getIntValueListUsage(100); // "8.05 KB"
    // App\Library\Performance\MemoryLibrary::getIntValueListUsage(1000); // "36.05 KB"
    // App\Library\Performance\MemoryLibrary::getIntValueListUsage(10000); // "516.05 KB"
    // App\Library\Performance\MemoryLibrary::getIntValueListUsage(100000); // "4 MB"
    // App\Library\Performance\MemoryLibrary::getIntValueListUsage(1000000); // "32 MB"
    // App\Library\Performance\MemoryLibrary::getIntValueListUsage(10000000); // "512 MB"

    public const MEMORY_UNIT = ['B','KB','MB','GB','TB','PB'];
    public const BASE_BIT = 1024;

    /**
     * convert memory usage unit.
     *
     * @param int $size
     * @return string
     */
    public static function convert(int $size): string
    {
        // この場合のlogは自然対数のこと。log(base)numを返す
        return round($size/pow(self::BASE_BIT, ($i=floor(log($size, self::BASE_BIT)))), 2) . ' ' . self::MEMORY_UNIT[$i];
    }

    /**
     * set int values count of param & get memory of this value.
     *
     * @param int $value
     * @return string
     */
    public static function getIntValueListUsage(int $value): string
    {
        $list = [];
        $start = memory_get_usage();

        for ($i = 0; $i < $value; $i++) {
            $list[] = $i;
        }

        // 現在の総メモリ使用量を確認する場合
        // echo self::convert(memory_get_usage()) . "\n";

        return self::convert(memory_get_usage() - $start);
    }

    /**
     * echo memory usage by start memory size
     *
     * @param int $startMemorySize return of memory_get_usage()
     * @return void
     */
    public static function echoMemoryUsageInScript(int $startMemorySize): void
    {
        echo 'Used Memory: ' .  self::convert(memory_get_usage() - $startMemorySize);
    }
}
