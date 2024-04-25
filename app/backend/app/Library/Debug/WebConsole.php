<?php

declare(strict_types=1);

namespace App\Library\Debug;

use App\Exceptions\MyApplicationHttpException;
use App\Library\Message\StatusCodeMessages;

class WebConsole
{
    // オプションは1つのみを有効にする想定
    private const ENABLE_COMMAND_OPTION_LIST = [
        'ls' => ['-a', '-l', '-al'],
        'cd' => null,
        'cat' => null,
        'echo' => null,
        'date' => null,
        'time' => null,
        'arch' => null,
        'head' => ['-n'],
        'tail' => ['-n'],
        'less' => null,
        'free' => null,
        'df' => ['-h', '-i', '-hT'],
        'mount' => null,
        'ps' => ['axufww'],
        'hostname' => null,
        'uname' => ['-a', '-r'],
        'ifconfig' => null,
        'route' => null,
        'netstat' => ['-a', '-C', '-e','-g', '-i', '-l', '-p', '-r', '-s', '-t', '-u'],
        'ipcalc' => null,
        'iproute' => null,
        // 'ping' => null,
        'iostat' => ['-c', '-d', '-g', '-h', '-k', '-m', '-N', '-t', '-T', '-x'],
        'getopt' => null,
        'pidof' => null,
        'printenv' => null,
        'mpstat' => ['-A', '-I', '-u'],
    ];

    // オプション特定不可のコマンドはパイプ処理をさせない様にする。
    private const FORBIDDEN_LIST = [
        '|',
        '||',
        '&&',
        '>',
        '>>',
    ];

    /**
     * exec server command.
     *
     * @param string $value command
     * @return array
     */
    public static function exec(string $value): array
    {
        $output = [];
        exec($value, $output);
        return $output;
        // $tmp = explode(' ', $logRow);
    }

    /**
     * validate command string.
     *
     * @param string $value command
     * @return void
     * @throws MyApplicationHttpException
     */
    public static function validateCommand(string $value): void
    {
        // スペース区切りで配列化
        $list = explode(' ', $value);
        $command = current($list);

        if (!isset(self::ENABLE_COMMAND_OPTION_LIST[$command])) {
            throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_401,
                '許可されていないコマンド又はオプションです。' . $command,
                ['value' => $value],
                false
            );
        }
        $enableOptions = self::ENABLE_COMMAND_OPTION_LIST[$command];

        if (is_null($enableOptions)) {
            // オプションが許可されていないコマンドの場合はパイプライン有無のチェックのみ行う
            foreach (self::FORBIDDEN_LIST as $v) {
                $pattern = '/' . $v . '/';
                if (preg_match($pattern, $value)) {
                    throw new MyApplicationHttpException(
                        StatusCodeMessages::STATUS_401,
                        '許可されていないコマンド又はオプションです。' . $command,
                        ['value' => $value],
                        false
                    );
                }
            }
        } else {
            // オプションの検証
            $option = $list[1];
            // 許可されていないオプションの場合
            if (!in_array($option, $enableOptions, true)) {
                throw new MyApplicationHttpException(
                    StatusCodeMessages::STATUS_401,
                    '許可されていないコマンド又はオプションです。' . $command,
                    ['value' => $value],
                    false
                );
            }
        }
        return;
    }
}
