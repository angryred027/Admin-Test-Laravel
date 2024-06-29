<?php

declare(strict_types=1);

namespace App\Library\Log;

use Closure;
use App\Library\Log\LogLibrary;
use App\Library\Time\TimeLibrary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminActionLogLibrary
{
    private const LOG_CAHNNEL_NAME = 'adminActionLog';

    private const AUTHORIZATION_HEADER_KEY = 'authorization';
    private const AUTHORIZATION_HEADER_VALUE_SUFFIX = '*****';
    private const AUTHORIZATION_HEADER_VALUE_START_POSITION = 0;
    private const AUTHORIZATION_HEADER_VALUE_END_POSITION = 10;

    private const TARGET_REQUEST_METHOD_LIST = ['POST', 'PATCH', 'DELETE'];

    // ログキー(log出力項目)
    private const LOG_KEY_REQUEST_DATETIME = 'request_datetime';
    private const LOG_KEY_REQUEST_URI = 'uri';
    private const LOG_KEY_REQUEST_METHOD = 'method';
    private const LOG_KEY_REQUEST_STATUS_CODE = 'status_code';
    private const LOG_KEY_REQUEST_RESPONSE_TIME = 'response_time';
    private const LOG_KEY_REQUEST_HOST = 'host';
    private const LOG_KEY_REQUEST_IP = 'ip';
    private const LOG_KEY_REQUEST_CONTENT_TYPE = 'content_type';
    private const LOG_KEY_REQUEST_HEADERS = 'headers';
    private const LOG_KEY_REQUEST_REQUEST_CONTENT = 'request_content';
    private const LOG_KEY_REQUEST_PLATHOME = 'plathome';
    private const LOG_KEY_REQUEST_DESCRIPTION = 'description';
    private const LOG_KEY_REQUEST_PROCESS_ID = 'process_id';
    private const LOG_KEY_REQUEST_MEMORY_BYTE = 'memory_byte';
    private const LOG_KEY_REQUEST_PEAK_MEMORY_BYTE = 'peak_memory_byte';

    private const ECLUDE_PATH_LIST = [
        '_debugbar',
    ];

    public const ROUTE_NAME_LIST = [
        'sampleImageUploader1',
    ];

    /**
     * check current path is log exclude path.
     *
     * @param string $path
     * @return bool
     */
    public static function isExcludePath(string $path): bool
    {
        return in_array($path, self::ECLUDE_PATH_LIST, true);
    }

    /**
     * get log parameter from request.
     *
     * @param Request $request
     * @return array
     */
    public static function getLogParameterByRequest(Request $request): array
    {
        return [
            $request->getRequestUri(),
            $request->getMethod(),
            $request->getHost(),
            $request->getClientIp(),
            $request->getContentTypeFormat(),
            $request->userAgent() ?? '',
            self::getRequestHeader($request->header()),
            LogLibrary::maskingSecretKeys($request->all())
        ];
    }

    /**
     * get log parameter from response.
     *
     * @param RedirectResponse|Response|JsonResponse|BinaryFileResponse $response
     * @return array
     */
    public static function getLogParameterByResponse(
        RedirectResponse | Response | JsonResponse | BinaryFileResponse $response
    ): array {
        return [
            $response->getStatusCode(),
        ];
    }

    /**
     * get request header.
     *
     * @param string|array|null $headers header contents. (\Illuminate\Http\Request->header())
     * @return string|array|null
     */
    public static function getRequestHeader(string|array|null $headers): string|array|null
    {
        if (is_array($headers)) {
            $response = [];
            foreach ($headers as $key => $value) {
                if ($key === self::AUTHORIZATION_HEADER_KEY) {
                    // $valueは配列になる想定
                    $response[$key] = mb_substr(
                        $value[0],
                        self::AUTHORIZATION_HEADER_VALUE_START_POSITION,
                        self::AUTHORIZATION_HEADER_VALUE_END_POSITION
                    ) . self::AUTHORIZATION_HEADER_VALUE_SUFFIX;
                } else {
                    $response[$key] = $value;
                }
            }

            return $response;
        } else {
            return $headers;
        }
    }

    /**
     * output access log in log file.
     *
     * @param string $requestDateTime
     * @param string $uri
     * @param string $method
     * @param string $statusCode
     * @param string $responseTime
     * @param string $host
     * @param string $ip
     * @param ?string $contentType
     * @param string|array|null $headers
     * @param mixed $requestContent
     * @param string $plathome
     * @param string $description
     * @param int|bool $pid
     * @param int $memory
     * @param int $peakMemory
     * @return void
     */
    public static function outputLog(
        string $requestDateTime,
        string $uri,
        string $method,
        int $statusCode,
        string $responseTime,
        string $host,
        string $ip,
        ?string $contentType,
        string|array|null $headers,
        mixed $requestContent,
        string $plathome,
        string $description,
        int|bool $pid,
        int $memory,
        int $peakMemory
    ): void {
        $context = [
            self::LOG_KEY_REQUEST_DATETIME         => $requestDateTime,
            self::LOG_KEY_REQUEST_URI              => $uri,
            self::LOG_KEY_REQUEST_METHOD           => $method,
            self::LOG_KEY_REQUEST_STATUS_CODE      => $statusCode,
            self::LOG_KEY_REQUEST_RESPONSE_TIME    => $responseTime,
            self::LOG_KEY_REQUEST_HOST             => $host,
            self::LOG_KEY_REQUEST_IP               => $ip,
            self::LOG_KEY_REQUEST_CONTENT_TYPE     => $contentType,
            self::LOG_KEY_REQUEST_HEADERS          => $headers,
            self::LOG_KEY_REQUEST_REQUEST_CONTENT  => $requestContent,
            self::LOG_KEY_REQUEST_PLATHOME         => $plathome,
            self::LOG_KEY_REQUEST_DESCRIPTION      => $description,
            self::LOG_KEY_REQUEST_PROCESS_ID       => $pid,
            self::LOG_KEY_REQUEST_MEMORY_BYTE      => $memory,
            self::LOG_KEY_REQUEST_PEAK_MEMORY_BYTE => $peakMemory,
        ];

        Log::channel(self::LOG_CAHNNEL_NAME)->info('Action:', $context);
    }
}
