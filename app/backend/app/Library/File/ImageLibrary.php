<?php

declare(strict_types=1);

namespace App\Library\File;

use Exception;
use Illuminate\Http\UploadedFile;
use App\Models\Masters\Images;
use App\Library\Random\RandomStringLibrary;
use App\Library\String\UuidLibrary;

class ImageLibrary
{
    // リソースキー
    public const RESOURCE_KEY_UUID = Images::UUID;
    public const RESOURCE_KEY_NAME = Images::NAME;
    public const RESOURCE_KEY_EXTENTION = Images::EXTENTION;
    public const RESOURCE_KEY_MIME_TYPE = Images::MIME_TYPE;
    public const RESOURCE_KEY_S3_KEY = Images::S3_KEY;

    /**
     * 画像ファイルのアップロード
     *
     * @param UploadedFile $file
     * @return array<string, string>
     * @throws Exception
     */
    public static function getFileResource(UploadedFile $file): array
    {
        $uuid = UuidLibrary::uuidVersion4();

        // オリジナルファイル名
        $originalName = $file->getClientOriginalName();

        // 拡張子
        $extention = $file->getClientOriginalExtension();

        // mimeType
        $mimeType = $file->getMimeType();

        // S3キー
        $s3key = RandomStringLibrary::getByHashRandomString(RandomStringLibrary::RANDOM_STRING_LENGTH_24);


        return [
            Images::UUID      => $uuid,
            Images::NAME      => $originalName,
            Images::EXTENTION => $extention,
            Images::MIME_TYPE => $mimeType,
            Images::S3_KEY    => $s3key,
        ];
    }
}
