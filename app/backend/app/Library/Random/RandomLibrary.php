<?php

declare(strict_types=1);

namespace App\Library\Random;

use Exception;
use App\Library\Message\StatusCodeMessages;

class RandomLibrary
{
    private const RANDOM_MIN_VALUE = 1;

    /**
     * 重み付き抽選関数
     *
     * @param array<string|int, int> $entries ($key => $weight)
     * @return int random value
     * @throws Exception
     */
    public static function getWeightedRandomValue(array $entries): int
    {
        // 配列の値($weight)を全て足す。
        // objectやcollectionから算出する場合はarray_map()などで別配列化する必要がある。
        $sum  = array_sum($entries);
        $rand = rand(self::RANDOM_MIN_VALUE, $sum);

        foreach ($entries as $key => $weight) {
            // ($a = $a - $b)と同様(減算)
            if (($sum -= $weight) < $rand) {
                return $key;
            }
        }

        throw new Exception('failed getting random value,', StatusCodeMessages::STATUS_500);
    }
}
