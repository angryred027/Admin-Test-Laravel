<?php

declare(strict_types=1);

namespace App\Library\String;

class UuidLibrary
{
    // UUIDには数種類のバリアント(変種)がある。
    // 16進表記をした場合に「xxxxxxxx-xxxx-xxxx-Nxxx-xxxxxxxxxxxx」のNの桁の上位ビットがバリアントを示す。
    // 16進表記をした場合に「xxxxxxxx-xxxx-Mxxx-Nxxx-xxxxxxxxxxxx」のMの桁がバージョンを示す。
    // 16進表記でNの桁が小文字になっているのはバリアントの情報が含まれることを示す。

    // version1は時刻とMACアドレスを利用したUUID。
    // version3と5は、ドメイン名など何かしら一意な文字（バイト列）を用いたUUID。
    // uuid3はMD5ハッシュから、uuid5はSHA-1ハッシュからUUIDを生成
    // version4は乱数により生成されるUUID

    // version4のパターン
    public const PATTERN_V4 = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx';

    public const RANDOM_INT_MIN_0 = 0;
    public const RANDOM_INT_MIN_8 = 8;
    public const RANDOM_INT_MAX_11 = 11;
    public const RANDOM_INT_MAX_15 = 15;

    public const CHAR_BIT = 'x';
    public const CHAR_VARIANT = 'y';

    /**
     * generate uui version4
     *
     * @return string uuid
     */
    public static function uuidVersion4(): string
    {
        // パターンの配列化
        $chars = mb_str_split(self::PATTERN_V4);

        foreach ($chars as $i => $char) {
            if ($char === self::CHAR_BIT) {
                $chars[$i] = dechex(random_int(self::RANDOM_INT_MIN_0, self::RANDOM_INT_MAX_15));
            } elseif ($char === self::CHAR_VARIANT) {
                // バリアントに設定する値(16進数で8,9,A,B)
                $chars[$i] = dechex(random_int(self::RANDOM_INT_MIN_8, self::RANDOM_INT_MAX_11));
            }
        }

        return implode('', $chars);
    }
}
