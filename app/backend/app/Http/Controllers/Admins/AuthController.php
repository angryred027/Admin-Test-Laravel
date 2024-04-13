<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admins;

use App\Exceptions\MyApplicationHttpException;
use App\Http\Controllers\Controller;
use App\Models\Masters\Admins;
use App\Library\Session\SessionLibrary;
use App\Library\Message\StatusCodeMessages;
use App\Trait\CheckHeaderTrait;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Config;

class AuthController extends Controller
{
    use CheckHeaderTrait;

    // login response
    private const LOGIN_RESEPONSE_KEY_ACCESS_TOKEN = 'access_token';
    private const LOGIN_RESEPONSE_KEY_TOKEN_TYPE = 'token_type';
    private const LOGIN_RESEPONSE_KEY_EXPIRES_IN = 'expires_in';
    private const LOGIN_RESEPONSE_KEY_USER = 'user';

    // admin resource key
    private const ADMIN_RESOURCE_KEY_ID = 'id';
    private const ADMIN_RESOURCE_KEY_NAME = 'name';
    private const ADMIN_RESOURCE_KEY_AUTHORITY = 'authority';

    // token prefix
    private const TOKEN_PREFIX = 'bearer';

    private const SESSION_TTL = 60; // 60秒

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Illuminate\Routing\Controller
        // $this->middleware('customAuth:api-admins', ['except' => ['login']]);
        $this->middleware('auth:api-admins', ['except' => ['login']]);
    }

    /**
     * ログイン
     *
     * @return \Illuminate\Http\JsonResponse|Redirector|RedirectResponse
     */
    public function login(): JsonResponse|Redirector|RedirectResponse
    {
        // $credentials = request(['email', 'password']);

        $credentials = [];
        if (Config::get('app.env') === 'production' || Config::get('app.env') === 'testing') {
            $credentials = request(['email', 'password']);
        } else {
            // ローカル開発時はnameだけでログインする。
            $credentials = [
                // 'name'     => request()->email,
                'email'     => request()->email,
                'password' => Config::get('myappSeeder.seeder.password.testadmin')
            ];
        }

        // auth()がreturnするguard: /tymon/jwt-auth/src/JWTGuard
        if (!$token = auth('api-admins')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // json形式のレスポンスを返す場合
        // return $this->respondWithToken($token);
        // ホーム画面のviewにリダイレクトする場合
        return redirect(route('admin.home'));
    }

    /**
     * ログインユーザーの情報を取得
     * @header Accept application/json
     * @header Authorization Bearer
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthUser(Request $request)
    {
        // ユーザーIDの照会、レスポンスヘッダーに設定
        $sessionId = self::getSessionId($request);
        $userId = self::getUserId($request);

        $token = SessionLibrary::getSssionTokenByUserIdAndSessionId($userId, $sessionId, 'api-admins');

        if (empty($token)) {
            throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_401,
                'Unauthorized. Session Failure Error.'
            );
        }

        $user = (new Admins())->getRecordById($userId);
        if (is_null($user)) {
            throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_404,
                'Unauthorized. No User Exist.'
            );
        }
        // $user = auth('api-admins')->user();
        return response()->json($this->getAdminResource($user));
    }

    /**
     * ログアウト
     * @header Accept application/json
     * @header Authorization Bearer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api-admins')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * トークンのリフレッシュ
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        // Tymon\JWTAuth\JWT
        return $this->respondWithToken(auth('api-admins')->refresh());
    }

    /**
     * レスポンスデータの作成
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        /** @var Admins $user authenticated admin model */
        $user = auth('api-admins')->user();

        // $token = SessionLibrary::generateSessionByUserId($user->{Admins::ID}, SessionLibrary::SESSION_GUARD_ADMIN);
        $token = 'token';

        $ttl = self::SESSION_TTL * 60;

        // Tymon\JWTAuth\factory
        // Tymon\JWTAuth\Claims\Factory
        // ユーザー情報を返す。
        return response()->json([
            self::LOGIN_RESEPONSE_KEY_ACCESS_TOKEN => $token,
            self::LOGIN_RESEPONSE_KEY_TOKEN_TYPE => self::TOKEN_PREFIX,
            self::LOGIN_RESEPONSE_KEY_EXPIRES_IN => $ttl,
            self::LOGIN_RESEPONSE_KEY_USER => $this->getAdminResource($user->toArray())
        ]);
    }

    /**
     * 管理者情報のリソースを取得
     *
     * @param array $user
     * @return array
     */
    protected function getAdminResource(array $user): array
    {
        return [
            self::ADMIN_RESOURCE_KEY_ID        => $user[Admins::ID],
            self::ADMIN_RESOURCE_KEY_NAME      => $user[Admins::NAME],
            self::ADMIN_RESOURCE_KEY_AUTHORITY => []
        ];
    }
}
