<?php

declare(strict_types=1);

namespace App\Library\CLI;

/**
 * get echo parameter execution.
 *
 * @param string
 * @return void
 * @example {string} $ echo 10 20 30 | php this.php
 */
function main(): void
{
    $list = [];
    // 入力値はspace区切りで入力される事を想定
    // ex 10 20 30 etc...
    while ($line = fgets(STDIN)) {
        if ($line === '') {
            echo 'invalid values';
            exit;
        }

        $list = explode(' ', trim($line));
    }
    foreach ($list as $key => $value) {
        printf('Value is %d!, index is %s' . "\n", (int)$value, $key);
    }
}

// phpcs:disable -- PHPCS設定の無効化
main();
