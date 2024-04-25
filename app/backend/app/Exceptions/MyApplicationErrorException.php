<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Library\Log\BatchLogLibrary;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;
use ErrorException;
use RuntimeException;

class MyApplicationErrorException extends ErrorException
{
    /**
     * Application Http Exception class.
     *
     * @param int $code code
     * @param string $message message
     * @param array $parameter error data exmple: request parameter
     * @param string|null $lifilename fine name
     * @param int|null $line line
     * @param Throwable|null $previous throwable
     * @return void
     */
    public function __construct(
        int $code = 0,
        string $message = '',
        array $parameter = [],
        ?string $lifilename = null,
        ?int $line = null,
        Throwable $previous = null,
    ) {
        parent::__construct($message, $code, 1, $lifilename, $line, $previous);

        // ログに出力
        $this->setErrorLog($code, $parameter);
        // ログ出力後にメッセージを初期化(Handlerクラスでエラーメッセージを設定する)
        $this->message = '';
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
            BatchLogLibrary::exec($this, $statusCode, $parameter);
        }
    }

    /**
     * Determine if the given exception is an Error exception (Custom Exception).
     *
     * @param  Throwable|Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e
     * @return bool
     */
    public static function isThisException(Throwable|HttpExceptionInterface $e)
    {
        return $e instanceof self;
    }
}
