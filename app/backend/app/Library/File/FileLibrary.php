<?php

declare(strict_types=1);

namespace App\Library\File;

use Illuminate\Support\Facades\Storage;
use App\Exceptions\MyApplicationHttpException;
use App\Library\Message\StatusCodeMessages;
use Exception;

class FileLibrary
{
    // storage disk
    public const STORAGE_DISK_LOCAL = 'local';
    public const STORAGE_DISK_S3 = 's3';

    /**
     * get storage disk by env
     *
     * @return string
     */
    public static function getStorageDiskByEnv(): string
    {
        if ((config('app.env') === 'local') || config('app.env') === 'testing') {
            return config('filesystems.default') ?? self::STORAGE_DISK_LOCAL;
        } else {
            return config('filesystems.default') ?? self::STORAGE_DISK_S3;
        }
    }

    /**
     * get file data in local by file path
     *
     * @param string $path file path
     * @return string|null
     * @throws Exception
     */
    public static function getFileStoream(string $path): string|null
    {
        // ローカルにてstorageの存在確認
        $file = Storage::disk(self::STORAGE_DISK_LOCAL)->get($path);

        // production向けなどS3から取得する時の設定
        if (!((config('app.env') === 'local') || config('app.env') === 'testing') &&
            (config('filesystems.default') === self::STORAGE_DISK_S3)
        ) {
            if (is_null($file)) {
                // productionの時はenvでデフォルトのストレージを変更するのが適切
                $file = Storage::disk(self::STORAGE_DISK_S3)->get($path);

                // 保存されていない場合
                if (is_null($file)) {
                    throw new MyApplicationHttpException(
                        StatusCodeMessages::STATUS_404,
                    );
                }
                // ファイルデータそのものを別途レスポンスに返す時はローカルに保存する
                Storage::disk(self::STORAGE_DISK_LOCAL)->put($path, $file, self::STORAGE_DISK_LOCAL);
            }
        }

        return $file;
    }

    /**
     * save text to new file.
     *
     * @param string $path file path
     * @param string $text text
     * @return bool result
     */
    public static function setTextToFile(string $path, string $text): bool
    {
        return Storage::disk(self::getStorageDiskByEnv())->put($path, $text);
    }

    /**
     * get files in path.
     *
     * @param string $path file path
     * @return array files
     */
    public static function files(string $path): array
    {
        return Storage::disk(self::getStorageDiskByEnv())->files($path);
    }

    /**
     * delete file.
     *
     * @param string|array $paths file path
     * @return bool result
     */
    public static function deleteFile(string|array $paths): bool
    {
        return Storage::disk(self::getStorageDiskByEnv())->delete($paths);
    }

    /**
     * delete Delectory.
     *
     * @param string $directory file path
     * @return bool result
     */
    public static function deleteDeletectory(string $directory): bool
    {
        return Storage::disk(self::getStorageDiskByEnv())->deleteDirectory($directory);
    }
}
