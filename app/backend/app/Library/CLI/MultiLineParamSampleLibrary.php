<?php

declare(strict_types=1);

namespace App\Library\CLI;

/**
 * get mulit line parameter execution.
 *
 * @param int
 * @return void
 *
 * @example {string} $ php this.php
 * @example {string} 3
 * @example {string} 2
 * @example {string} 1
 * @example {string} 2
 */
function main(): void
{
    // 初回のinput
    $count = trim(fgets(STDIN));
    $result = 0;
    for ($i = 0; $i < $count; $i++) {
        // 2回目以降のinput(数字前提)
        $input = ((int)trim(fgets(STDIN)));
        $result = $result + $input;
    }
    echo 'result: ' . $result . "\n";
}

// phpcs:disable -- PHPCS設定の無効化
main();
