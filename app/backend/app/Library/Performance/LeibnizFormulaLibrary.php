<?php

declare(strict_types=1);

namespace App\Library\Performance;

class LeibnizFormulaLibrary
{
    /**
     * ライプニッツ級数を計算する
     * (奇数の逆数を交互に足したり引いたりすることで π/4 に収束すること)
     *
     * @return int value
     */
    public static function leibnizFormula()
    {
        $s = 0;
        // 10の8乗(1億)。**演算子のほか、pow()でも良い
        for ($i = 0; $i < 10**8; $i++) {
            $s += ((-1)**$i)/(2*$i + 1);
        }
        return $s;
    }
}

// ファイル指定で実行する為classの外で呼び出す。
// phpcs:disable -- PHPCS設定の無効化
LeibnizFormulaLibrary::leibnizFormula();
