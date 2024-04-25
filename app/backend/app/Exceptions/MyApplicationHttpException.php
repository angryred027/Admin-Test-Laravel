<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Library\Log\ErrorLogLibrary;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

// class MyApplicationHttpException extends RuntimeException implements HttpExceptionInterface
class MyApplicationHttpException extends HttpException
{
    // ステータスコード
    private int $statusCode;

    // ヘッダー情報
    private array $headers;

    /**
     * Application Http Exception class.
     *
     * @param int $statusCode status code
     * @param string $message message
     * @param array $parameter error data exmple: request parameter
     * @param bool $isResponseMessage if true, $message is output to response, false, output to log.
     * @param Throwable|null previous throwable
     * @param array $headers headers
     * @param int $code code
     * @return void
     */
    public function __construct(
        int $statusCode,
        string $message = '',
        array $parameter = [],
        bool $isResponseMessage = false,
        Throwable $previous = null,
        array $headers = [],
        int $code = 0
    ) {
        $this->statusCode = $statusCode;
        $this->headers = $headers;

        parent::__construct($statusCode, $message, $previous, $headers, $code);

        // メッセージをレスポンスとして返さない場合
        if (!$isResponseMessage) {
            // ログに出力
            $this->setErrorLog($statusCode, $parameter);
            // ログ出力後にメッセージを初期化(Handlerクラスでエラーメッセージを設定する)
            $this->message = '';
        }
    }

    /**
     * get status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * get headers.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * set headers.
     *
     * @param array $headers header data.
     * @return void
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * set message to error log.
     *
     * @param int $statusCode status code
     * @param array $parameter error data exmple: request parameter
     * @return void
     */
    private function setErrorLog(
        int $statusCode,
        array $parameter
    ): void {
        if (config('app.env') !== 'testing') {
            // エラーログの出力
            ErrorLogLibrary::exec($this, $statusCode, $parameter);
        }
    }

    /**
     * Determine if the given exception is an HTTP exception (Custom Exception).
     *
     * @param  Throwable|Symfony\Component\HttpKernel\Exception\HttpExceptionInterface  $e
     * @return bool
     */
    public static function isThisException(Throwable|HttpExceptionInterface $e)
    {
        return $e instanceof self;
    }
}
