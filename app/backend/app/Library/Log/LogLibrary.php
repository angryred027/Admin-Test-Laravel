<?php

declare(strict_types=1);

namespace App\Library\Log;

use App\Exceptions\MyApplicationHttpException;
use App\Library\Message\StatusCodeMessages;
use App\Library\Array\ArrayLibrary;
use App\Library\Time\TimeLibrary;
use Exception;

class LogLibrary
{
    public const EXTENTION = 'log';
    public const DIRECTORY = 'logs/';

    public const FILE_NAME_ACCESS = 'access';
    public const FILE_NAME_ERROR = 'error';

    // リクエストパラメーターがログ出力可能なContent-type(ファイルアップロードなど'form'になっている場合はリクエストパラメーターはログに出力しない)
    public const LOG_OUTPUTABLE_CONTENT_TYPE = [null, 'json'];
    public const SECRET_KEYS = [
        'email' => 'email',
        'password' => 'password',
        'password_confirmation' => 'password_confirmation',
        'token' => 'token',
        'tokenPayload' => 'tokenPayload',
        'tokenHeader' => 'tokenHeader',
        'body' => 'body',
        'detail' => 'detail',
        'name' => 'name',
    ];

    /**
     * get logfile contents.
     *
     * @param string|null $date
     * @param string $name
     * @return array
     */
    public static function getLogFileContentsList(?string $date = null, string $name = self::FILE_NAME_ACCESS): array
    {
        if (is_null($date)) {
            $date = TimeLibrary::getCurrentDateTime(TimeLibrary::DEFAULT_DATE_TIME_FORMAT_DATE_ONLY);
        }

        $path = self::DIRECTORY . "$name-$date." . self::EXTENTION;

        try {
            // storage/app直下に無い為file_get_contents()で取得
            $file = file_get_contents(storage_path($path));
            if (is_null($file)) {
                throw new MyApplicationHttpException(
                    StatusCodeMessages::STATUS_404,
                    'File Not Exist.'
                );
            }
        } catch (Exception $e) {
            throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_404,
                'File Not Exist.',
                ['message' => $e->getMessage()],
                true,
                previous: $e
            );
        }

        $fileContents = explode("\n", $file);

        return $fileContents;
    }

    /**
     * get logfile contents as Associative array(連想配列).
     *
     * @param string|null $date
     * @param string|null $name
     * @param int $sort
     * @param int $page
     * @param int $limit
     * @return array
     */
    public static function getLogFileContentAsAssociative(
        ?string $date = null,
        ?string $name = self::FILE_NAME_ACCESS,
        int $sort = SORT_ASC,
        ?int $page = 0,
        ?int $limit = null,
    ): array {
        if (is_null($name)) {
            $name = self::FILE_NAME_ACCESS;
        }
        $response = [];
        $logFileContetsList = self::getLogFileContentsList($date ?? null, $name ?? null);

        // 降順にソートする場合
        if ($sort === SORT_DESC) {
            $logFileContetsList = array_reverse($logFileContetsList);
        }

        foreach ($logFileContetsList as $logRow) {
            $tmp = explode(' ', $logRow);
            if (!empty($tmp) && (count($tmp) >= 6)) {
                // 日時をkeyとして設定
                $head = $tmp[0] . ' ' . $tmp[1];
                $mainRowLog = '';
                for ($i = 4; $i < count($tmp); $i++) {
                    $mainRowLog .= $tmp[$i];
                }

                $rowDictionary = json_decode($mainRowLog, true);
                $response[$head] = $rowDictionary;
            }
        }

        // return $response;
        return ArrayLibrary::paging($response, $page, $limit);
    }

    /**
     * check content type is able to output to log.
     *
     * @param ?string $contentType
     * @return bool
     */
    public static function isLoggableContentType(?string $contentType): bool
    {
        return in_array($contentType, self::LOG_OUTPUTABLE_CONTENT_TYPE, true);
    }

    /**
     * masking secret value in log.
     *
     * @param array $requestContent
     * @return array
     */
    public static function maskingSecretKeys(array $requestContent): array
    {
        $response = [];

        if (empty($requestContent)) {
            return $response;
        }
        foreach ($requestContent as $key => $value) {
            if (!is_string($value)) {
                $response[$key] = $value;
                continue;
            }

            // if (in_array($value, self::SECRET_KEYS, true)) {
            if (isset(self::SECRET_KEYS[$key])) {
                $response[$key] = 'secretValue:(' . mb_strlen($value) . ')';
            } else {
                $response[$key] = $value;
            }
        }
        return $response;
    }
}
