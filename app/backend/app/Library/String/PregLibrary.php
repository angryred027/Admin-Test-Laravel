<?php

declare(strict_types=1);

namespace App\Library\String;

class PregLibrary
{
    /**
     * filtering string value by number & return int value & chage typet to int
     * @param string $value
     * @return ?int
     */
    public static function filteringByNumber(string $value): ?int
    {
        // 0～9以外は空白に変換して文字列だけを取得する。
        $filterdValue = preg_replace('/[^0-9]/', '', $value);
        if (is_null($filterdValue)) {
            return null;
        }
        return (int)$filterdValue;
    }
}
