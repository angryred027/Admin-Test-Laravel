<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use App\Library\Time\TimeLibrary;
use App\Library\Log\AdminActionLogLibrary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AdminActionLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 管理画面以外
        if (!str_contains($request->url(), '/admin')) {
            return $next($request);
        }
        // GETリクエストは対象外
        if ($request->getMethod() === 'GET') {
            return $next($request);
        }

        if (AdminActionLogLibrary::isExcludePath($request->path())) {
            return $next($request);
        }

        // $this->host = getmypid();
        $requestDateTime = TimeLibrary::getCurrentDateTime();
        $pid             = getmypid();

        [
            $uri,
            $method,
            $host,
            $ip,
            $contentType,
            $plathome,
            $headers,
            $requestContent,
        ] = AdminActionLogLibrary::getLogParameterByRequest($request);


        // 処理速度の計測
        $startTime = microtime(true);

        $response = $next($request);

        $routeName= request()->route()?->getName();
        $descriptionPrefix = 'アクション';
        foreach (AdminActionLogLibrary::ROUTE_NAME_LIST as $key => $name) {
            if (str_contains($routeName, $key)) {
                $descriptionPrefix = $name;
                break;
            }
        }
        $admin = Auth::guard('api-admins')->user();
        $adminId = $admin ? $admin->id : 0;

        $responseTime = (string)(microtime(true) - $startTime);
        $memory = memory_get_usage();
        $peakMemory = memory_get_peak_usage();

        [$statusCode] = AdminActionLogLibrary::getLogParameterByResponse($response);

        $description = "管理者ID=$adminId さんが $descriptionPrefix" . 'しました。';

        // log出力
        AdminActionLogLibrary::outputLog(
            $requestDateTime,
            $uri,
            $method,
            $statusCode,
            $responseTime,
            $host,
            $ip,
            $contentType,
            $headers,
            $requestContent,
            $plathome,
            $description,
            $pid,
            $memory,
            $peakMemory
        );

        return $response;
    }
}
