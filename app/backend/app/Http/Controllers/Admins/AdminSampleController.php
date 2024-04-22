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
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

class AdminSampleController extends Controller
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
     * test page.
     *
     * @return View|Factory
     */
    public function test(): View|Factory
    {
        return view('/admin/test');
    }

    /**
     * sample page.
     *
     * @return View|Factory
     */
    public function sample(): View|Factory
    {
        return view('/admin/sample');
    }

    /**
     * sample page1.
     *
     * @return View|Factory
     */
    public function sample1(): View|Factory
    {
        return view('/admin/sample1');
    }

    /**
     * sample page2.
     *
     * @return View|Factory
     */
    public function sample2(): View|Factory
    {
        return view('/admin/sample2');
    }

    /**
     * sample image uploader index.
     *
     * @return View|Factory
     */
    public function sampleImageUploader1(): View|Factory
    {
        return view(
            '/admin/sample/imageUploader/index',
            []
        );
    }

    /**
     * sample image uploader.
     *
     * @return View|Factory
     */
    public function sampleImageUploader1Edit(): View|Factory
    {
        return view(
            '/admin/sample/imageUploader/edit',
            ['subTitle' => 'testSubTitle1']
        );
    }
}
