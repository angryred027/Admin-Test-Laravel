<?php

declare(strict_types=1);

namespace App\Library\Encrypt;

use App\Library\Math\MathLibrary;

class RSAEncryptLibrary
{
    // RSA暗号=公開鍵暗号(非対称鍵暗号)方式

    // 基準値(INF考慮の為あまり大きな値は設定出来ない)
    private const MAX_PRIME_BASE_NUMBER = 300000000; // 3億

    // private const BASE_N_VALUE = 2987;
    // private const BASE_E_VALUE = 13;
    // private const BASE_D_VALUE = 2197;

    private const BASE_N_VALUE = 33;
    private const BASE_E_VALUE = 3;
    private const BASE_D_VALUE = 7;

    private const BASE = 268435455;
    private const E_VALUE1 = 3033169;
    private const D_VALUE2 = 177;

    /**
     * get encrypt base values
     *
     * @param int $value
     * @return array
     */
    public static function getEncryptBaseValueList(int $value): array
    {
        $time = microtime(true);
        $memory = memory_get_usage();
        // パラメーター以下でもっとも大きい$p,$qの値を素因数分解結果から取得
        // ケースによって$nの値を柔軟に取得出来る為最大値から＊個目を取得するか指定すると良い
        $n = MathLibrary::getMaxTwoPairPrimeFactorization($value);
        [$p, $q] = MathLibrary::getPrimeFactorization($n);

        // E,Dの取得
        $ed = self::getEAndD($p, $q);
        $endTime = microtime(true) - $time;
        $usageMemory = memory_get_usage() - $memory;

        return [
            'p' => $p,
            'q' => $q,
            'N' => $n,
            'E' => $ed['E'],
            'D' => $ed['D'],
            'L' => $ed['L'],
            'time' => $endTime,
            'memory' => $usageMemory,
        ];
    }

    /**
     * get E & D.
     *
     * @param int $p prime number p
     * @param int $q prime number q
     * @return array
     */
    public static function getEAndD(int $p, int $q): array
    {
        $result['ED'] = ((($p - 1) * ($q - 1)) * 1) + 1;
        $result['L'] = self::getL($p, $q);
        $e = 0;
        $d = 0;

        // 大きい値を基準値にする
        $base = $p >= $q ? $p : $q;

        // // 最大値の為パラメーターから減算して確認
        for ($i = 2; 0 < $base; $i++) {
            // 同じ値は参照しない
            if (($i === ($p - 1)) || ($i === ($q - 1))) {
                continue;
            }
            // (p-1),(q-1)とそれぞれ互いに素
            if (($i < ($p - 1)) &&
                ($i < ($q - 1)) &&
                MathLibrary::isGcdIsOne($i, ($p - 1)) &&
                MathLibrary::isGcdIsOne($i, ($q - 1))
            ) {
                $e = $i;
                $euclidean = MathLibrary::getExtendedEuclidean($e, $result['L']);
                if ($euclidean['x'] < 0) {
                    $d = $result['L'] + $euclidean['x'];
                } else {
                    $d = $result['L'] - $euclidean['x'];
                }

                break;
            }
        }
        $result['E'] = $e;
        $result['D'] = $d;

        return $result;
    }

    /**
     * get L
     *
     * @param int $p prime number p
     * @param int $q prime number q
     * @return int
     */
    public static function getL(int $p, int $q): int
    {
        // L = (p - 1)と(q - 1)の最小公倍数
        return MathLibrary::getLeastCommonMultiple(($p - 1), ($q - 1));
    }

    /**
     * encrypt value
     *
     * @param int $value value
     * @return int encrypt value
     */
    public static function encrypt(int $value): int
    {
        return ($value * self::E_VALUE1) & self::BASE;
    }

    /**
     * decrypt value
     *
     * @param int $value value
     * @return int decrypt value
     */
    public static function decrypt(int $value): int
    {
        return ($value * self::D_VALUE2) & self::BASE;
    }
}
