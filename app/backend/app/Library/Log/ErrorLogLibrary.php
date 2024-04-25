<?php

declare(strict_types=1);

namespace App\Library\Log;

use App\Library\Log\LogLibrary;
use App\Library\Time\TimeLibrary;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ErrorLogLibrary
{
    private const LOG_CAHNNEL_NAME = 'errorlog';

    // $_SERVERのキー
    private const GLOBAL_VALUE_KEY_REQUEST_URI = 'REQUEST_URI';

    // ログキー
    private const LOG_KEY_REQUEST_DATETIME = 'request_datetime';
    private const LOG_KEY_REQUEST_MESSAGE = 'message';
    private const LOG_KEY_REQUEST_URI = 'uri';
    private const LOG_KEY_REQUEST_STATUS_CODE = 'status_code';
    private const LOG_KEY_REQUEST_PROCESS_ID = 'process_id';
    private const LOG_KEY_REQUEST_MEMORY_BYTE = 'memory_byte';
    private const LOG_KEY_REQUEST_PEAK_MEMORY_BYTE = 'peak_memory_byte';
    private const LOG_KEY_REQUEST_STACK_TRACE = 'stackTrace';
    private const LOG_KEY_REQUEST_REQUEST_PARAMETER = 'request_parameter';

    /**
     * constructer.
     *
     * @param Throwable|HttpExceptionInterface $error error
     * @param int $statusCode status code
     * @param array $parameter error data exmple: request parameter
     * @return void
     */
    public static function exec(
        Throwable|HttpExceptionInterface $error,
        int $statusCode = 0,
        array $parameter = []
    ) {
        $uri = isset($_SERVER[self::GLOBAL_VALUE_KEY_REQUEST_URI])
        ? $_SERVER[self::GLOBAL_VALUE_KEY_REQUEST_URI]
        : null;

        self::outputLog(
            TimeLibrary::getCurrentDateTime(),
            $uri,
            $error->getMessage(),
            $statusCode,
            getmypid(),
            memory_get_usage(),
            memory_get_peak_usage(),
            str_replace("\n", '', $error->getTraceAsString()), // １行で表示させる
            LogLibrary::maskingSecretKeys($parameter) // マスキング処理を挟む
        );
    }

    /**
     * output error log in log file.
     *
     * @param string $requestDateTime
     * @param ?string $uri
     * @param string $message
     * @param int $statusCode
     * @param int|bool $pid
     * @param int $memory
     * @param int $peakMemory
     * @param string $stackTrace
     * @param string $parameter
     * @return void
     */
    private static function outputLog(
        string $requestDateTime,
        ?string $uri,
        string $message,
        int $statusCode,
        int|bool $pid,
        int $memory,
        int $peakMemory,
        string $stackTrace,
        array $parameter
    ): void {
        $context = [
            self::LOG_KEY_REQUEST_DATETIME          => $requestDateTime,
            self::LOG_KEY_REQUEST_URI               => $uri ?? null,
            self::LOG_KEY_REQUEST_MESSAGE           => $message,
            self::LOG_KEY_REQUEST_STATUS_CODE       => $statusCode,
            self::LOG_KEY_REQUEST_PROCESS_ID        => $pid,
            self::LOG_KEY_REQUEST_MEMORY_BYTE       => $memory,
            self::LOG_KEY_REQUEST_PEAK_MEMORY_BYTE  => $peakMemory,
            self::LOG_KEY_REQUEST_STACK_TRACE       => $stackTrace,
            self::LOG_KEY_REQUEST_REQUEST_PARAMETER => $parameter,
        ];

        Log::channel(self::LOG_CAHNNEL_NAME)->error('Error:', $context);
    }
}
