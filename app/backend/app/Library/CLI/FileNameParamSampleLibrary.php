<?php

declare(strict_types=1);

namespace App\Library\CLI;

/**
 * get file name parameter execution.
 *
 * @param string $fileName
 * @return void
 *
 * @example {string} $ php this.php fileName.csv
 */
function main(string $fileName): void
{
    $records = [];
    $file = fopen($fileName, "r");

    // header行を読み飛ばす
    fgetcsv($file);
    while (($data = fgetcsv($file)) !== false) {
        $records[] = $data;
    }
    fclose($file);

    echo 'result: ' . var_dump($records) . "\n";
}

// phpcs:disable -- PHPCS設定の無効化
$fileName = $argv[1];
main($fileName);
