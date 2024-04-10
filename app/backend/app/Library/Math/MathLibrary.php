<?php

declare(strict_types=1);

namespace App\Library\Math;

class MathLibrary
{
    // フェルマーの小定理
    // pが素数で，aがpの倍数でない正の整数のとき 下記の式が成り立つ
    // a**(p-1)≡1(modp)

    // フェルマーの小定理
    // pが素数で，aがpの倍数でない正の整数のとき 下記の式が成り立つ
    // a^(p-1)≡1(modp)

    // 合同式=割り算の余りに注目した式
    // 7 ≡ 4 (mod3) 7と4は割った余りが等しい(両方余り1)
    // aとbをnで割った余りが等しいとき，合同式では a≡b(modn),a≡b(modn) と書く

    /**
     * get Fermat's Little Theorem value
     *
     * @param int $value
     * @param int $light light value
     * @return array
     */
    public static function getFermatsLittleTheorem(int $value, int $lightValue): array
    {
        // a**(p-1)≡1(mod p)の検証
        $squared = ($value - 1);
        $mod = $lightValue % $value;

        $result = false;
        $leftValue = 0;
        // $valueより小さい値からループ開始
        for ($i = ($value - 1); $i >= 0; $i--) {
            $checkValue = ($i ** $squared);
            // 余りが一致する場合
            if ($checkValue % $value === $mod) {
                $result = true;
                $leftValue = $i;
                break;
            }
        }

        return [
            'mod' => $mod,
            'leftValue' => $leftValue,
            'result' => $result,
        ];
    }

    // モジュラ逆数の取得
    // ax≡1(mod m)の時,
    // 両辺 aの逆数 a**(-1)=1/a をかけ、x=1/aとするとxが求められる
    // (整数aのモジュラ逆数)=a**(m−2) mod m
    // a = 3, m = 11, mod_inverse = 4
    // a = 4, m = 11, mod_inverse = 3

    // 互いに素な自然数=最大公約数が1の値

    /**
     * get mod inverse of parameter by $mod
     *
     * @param int $value
     * @param int $mod be prime value;
     * @return int
     */
    public static function getModInverse(int $value, int $mod): int
    {
        // ax≡1 mod p が成立する様な数xをモジュラ逆数と言う。(a,pが互いに素な素数であるのが前提)
        // x= a ** -1 と表記される事もある。

        // フェルマーの小定理を使う事で、a**(p-1)≡1(mod p)が成り立つ事が想定出来る(pが素数)
        // pが素数かつp>=3 素数の場合、(a**-1)を導く為に両辺に(a**(p-2))をかけると下記の通りになる。
        // (a**-1) ≡ (a**p-2) mod p
        // 故にモジュラ逆数は(a**-1)となりaの逆数と言える。

        // a **((p-1)(q-1)n + 1) ≡ a mod pq
        return ($value ** ($mod - 2)) % $mod;
    }

    /**
     * get prime factorization values (素因数分解)
     *
     * @param int $value value
     * @return array
     */
    public static function getPrimeFactorization(int $value): array
    {
        $factors = [];
        $divisor = 2;
        $number = $value;

        while ($number > 1) {
            // 割り切れる場合
            if ($number % $divisor === 0) {
                // 素因数を格納
                $factors[] = $divisor;
                $number = $number / $divisor;
            } else {
                $divisor++;
            }
        }

        return $factors;
    }

    /**
     * get max prime factorization has two values (素因数分解)
     *
     * @param int $value value
     * @return int
     */
    public static function getMaxTwoPairPrimeFactorization(int $value): int
    {
        $result = 0;
        // 最大値の為パラメーターから減算して確認
        for ($i = $value; 0 < $i; $i--) {
            if ($i / 2 === 0) {
                continue;
            }
            // 素因数分解の結果2つだけ取得出来る数値を取得
            if (count(self::getPrimeFactorization($i)) === 2) {
                $result = $i;
                break;
            }
        }
        return $result;
    }

    /**
     * get two pair factorization values at $maxCount (素因数分解)
     *
     * @param int $value value
     * @return int
     */
    public static function getMaxCoountTwoPairPrimeFactorization(int $value, int $maxCount): int
    {
        $result = 0;
        $count = 0;
        // 指定された値を最大値として2から$maxCount目の値を取得
        for ($i = 2; 0 < $value; $i++) {
            if ($i / 2 === 0) {
                continue;
            }
            // 素因数分解の結果2つだけ取得出来る数値を取得
            if (count(self::getPrimeFactorization($i)) === 2) {
                $result = $i;
                ++$count;
                if ($count === $maxCount) {
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * get greatest common divisor value (GCD=最大公約数)
     *
     * @param int $value1 value
     * @param int $value2 compair value
     * @return int
     */
    public static function getGreatestCommonDivisor(int $value1, int $value2): int
    {
        // 割り切れたら終了
        while ($value2 != 0) {
            $remainder = $value1 % $value2;
            $value1 = $value2;
            $value2 = $remainder;
        }
        // 絶対値に変換
        return abs($value1);
    }

    /**
     * check is greatest common divisor value is 1
     *
     * @param int $value1 value
     * @param int $value2 compair value
     * @return bool
     */
    public static function isGcdIsOne(int $value1, int $value2): bool
    {
        // 最大公約数が1=互いに素な値
        return self::getGreatestCommonDivisor($value1, $value2) === 1;
    }

    /**
     * get max greatest common divisor number in parameter($value以下の数字で$targetと互いに素の値の最大値を取得)
     *
     * @param int $value
     * @param int $target
     * @return int
     */
    public static function getMaxGreatestCommonDivisor(int $value, int $target): int
    {
        $result = 0;
        // 最大値の為パラメーターから減算して確認
        for ($i = $value; 0 < $i; $i--) {
            if (self::isGcdIsOne($i, $target)) {
                $result = $i;
                break;
            }
        }
        return $result;
    }

    /**
     * get least common multiple value (LCM=最小公倍数)
     *
     * @param int $value1 value
     * @param int $value2 compair value
     * @return int
     */
    public static function getLeastCommonMultiple(int $value1, int $value2): int
    {
        $gcd = self::getGreatestCommonDivisor($value1, $value2);
        return ($value1 * $value2) / $gcd;
    }

    /**
     * get GCD of $value1, $value2, And x,y (拡張ユークリッド互除法)
     * ユークリッドの互除法を用いてex + ly = gcd(e,l)の解となる整数x,yの組を見つける
     *
     * @param int $value1 value
     * @param int $value2 compair value
     * @return array
     */
    public static function getExtendedEuclidean(int $value1, int $value2): array
    {
        if ($value2 === 0) {
            return ['gcd' => $value1, 'x' => 1, 'y' => 0];
        }

        // 商
        $quotient = intval($value1 / $value2);
        // 余り
        $remainder = $value1 % $value2;

        // 再起的に取得
        $result = self::getExtendedEuclidean($value2, $remainder);
        $gcd = $result['gcd'];
        $x = $result['y'];
        $y = $result['x'] - ($quotient * $result['y']);

        return [
            'gcd' => $gcd,
            'x' => $x,
            'y' => $y,
        ];
    }

    /**
     * get masking value base setting list.
     * @param int $log 対数
     * @return array
     */
    public static function getMaskingBaseValueList(int $log = 28): array
    {
        // 時間計測用
        $time = microtime(true);
        $memory = memory_get_usage();

        $base = pow(2, $log);
        $encryptBase = $base - 1;
        $nextBase = $base + 1;
        $rand = rand(1, 10);
        $randNextBase = pow(2, $log + $rand) + 1;
        $primeFactorization = self::getPrimeFactorization($randNextBase);

        $response = [
            'base' => $base,
            'decbin(base)' => decbin($base),
            'encryptBase' => $encryptBase,
            'decbin(encryptBase)' => decbin($encryptBase),
            'nextBase' => $nextBase,
            'decbin(nextBase)' => decbin($nextBase),
            'randNextBase' => $randNextBase,
            'decbin(randNextBase)' => decbin($randNextBase),
            'primeFactorization' => $primeFactorization,
        ];

        // 時間計測用
        $endTime = microtime(true) - $time;
        $usageMemory = memory_get_usage() - $memory;
        $response['time'] = floor($endTime);
        $response['memory'] = $usageMemory;

        return $response;
    }
}
