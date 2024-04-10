<?php

declare(strict_types=1);

namespace App\Library\Banner;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\MyApplicationHttpException;
use App\Library\Message\StatusCodeMessages;
use App\Library\Time\TimeLibrary;
use App\Models\Masters\Banners;

class BannerLibrary
{
    public const EXTENTION = 'png';
    public const DIRECTORY = 'images/';
    public const DIRECTORY_DEFAULT = 'default/';
    public const ADMIN_BANNER_PATH = '/api/v1/admin/banners/banner/image/';
    public const USER_BANNER_PATH = '/api/v1/banners/banner/image/';
    public const DEFAULT_FILE_IMAGE_NAME_200X600_1 = '200x600px_default1';
    public const DEFAULT_FILE_IMAGE_NAME_200X600_2 = '200x600px_default2';
    public const DEFAULT_FILE_IMAGE_NAME_200X600_3 = '200x600px_default3';
    public const DEFAULT_FILE_IMAGE_NAME_240X1200_1 = '240x1200px_default1';
    public const DEFAULT_FILE_IMAGE_NAME_240X1200_2 = '240x1200px_default2';
    public const DEFAULT_FILE_IMAGE_NAME_240X1200_3 = '240x1200px_default3';

    // テスト用UUID
    public const BASE_TEST_UUID = 'eac5c89b-9688-4d5a-a377-53586c46cffX';

    /**
     * get banner uuid for testing.
     *
     * @param int $value
     * @return string
     */
    public static function getTestBannerUuidByNumber(int $value): string
    {
        // 10以上は16進数に変換
        if ($value > 9) {
            $target = dechex($value);
        } else {
            $target = (string)$value;
        }
        // 末尾を置き換えて返す
        return str_replace('X', $target, self::BASE_TEST_UUID);
    }

    /**
     * get banner default image.
     *
     * @return string
     */
    public static function getDefaultBanner(): string
    {
        $path = self::DIRECTORY . self::DIRECTORY_DEFAULT . self::DEFAULT_FILE_IMAGE_NAME_200X600_1 . '.' . self::EXTENTION;

        // storage/app直下に無い為file_get_contents()で取得
        $file = file_get_contents(storage_path($path));

        if (is_null($file) || !$file) {
            throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_404,
                'File Not Exist.'
            );
        }

        return $file;
    }

    /**
     * get banner default image storage path.
     *
     * @param bool $isRand is random default image
     * @return string
     */
    public static function getDefaultBannerStoragePath(bool $isRand = false): string
    {
        if ($isRand) {
            $value = rand(1, 3);
            $path = self::DIRECTORY . self::DIRECTORY_DEFAULT . "200x600px_default$value" . '.' . self::EXTENTION;
        } else {
            $path = self::DIRECTORY . self::DIRECTORY_DEFAULT . self::DEFAULT_FILE_IMAGE_NAME_200X600_1 . '.' . self::EXTENTION;
        }
        return storage_path($path);
    }

    /**
     * get banner storage directory.
     *
     * @return string
     */
    public static function getBannerStorageDirctory(): string
    {
        return Config::get('myappFile.upload.storage.local.images.banner');
    }

    /**
     * get banner storage path by banner id & uuid.
     *
     * @param int $bannerId
     * @param string $uuid
     * @param string $extention
     * @return string
     */
    public static function getBannerStoragePathByBannerIdAndUuid(int $bannerId, string $uuid, string $extention = 'png'): string
    {
        return self::getBannerStorageDirctory() . "$bannerId/$uuid.$extention";
    }

    /**
     * get banner path at admin service.
     *
     * @param array $banner banner record
     * @return string
     */
    public static function getAdminServiceBannerPath(array $banner): string
    {
        return config('app.url') . self::ADMIN_BANNER_PATH . $banner[Banners::UUID] . '?ver=' . TimeLibrary::strToTimeStamp($banner[Banners::UPDATED_AT]);
    }

    /**
     * get banner path at user service.
     *
     * @param array $banner banner record
     * @return string
     */
    public static function getUserServiceBannerPath(array $banner): string
    {
        return config('app.url') . self::USER_BANNER_PATH . $banner[Banners::UUID] . '?ver=' . TimeLibrary::strToTimeStamp($banner[Banners::UPDATED_AT]);
    }
}
