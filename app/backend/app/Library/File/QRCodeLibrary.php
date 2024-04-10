<?php

declare(strict_types=1);

namespace App\Library\File;

use Exception;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QRCodeLibrary
{
    // font-family
    public const FONT_FAMILY_KOZGOPROMEDIUM = 'kozgopromedium';
    // font-size
    public const FONT_SIZE_10 = 10;

    /**
     * サンプルQRコードの出力(ファイルリソース)
     *
     * @return string
     * @throws Exception
     */
    public static function getSampleQrCode(): string
    {
        $options = new QROptions(
            [
              'eccLevel' => QRCode::ECC_L,
              'outputType' => QRCode::OUTPUT_MARKUP_SVG,
              'version' => 5,
            ]
        );

        $qrcode = (new QRCode($options))->render('http://localhost');

        $html = <<< EOF
        <style>
        body {
            color: #212121;
        }
        </style>
        <img src='$qrcode' alt='QR Code' width='800' height='800'>
        EOF;

        return $html;
    }

    /**
     * URLを基にQRコードを出力
     *
     * @param string $url
     * @param string $alt
     * @param int $width
     * @param int $height
     * @return string
     * @throws Exception
     */
    public static function getQrCodeByUrl(string $url, string $alt = 'alt value', int $width = 300, int $height = 300): string
    {
        $options = new QROptions(
            [
              'eccLevel' => QRCode::ECC_L,
              'outputType' => QRCode::OUTPUT_MARKUP_SVG,
              'version' => 5,
            ]
        );

        $qrcode = (new QRCode($options))->render($url);

        $html = <<< EOF
        <style>
        body {
            color: #212121;
        }
        </style>
        <img src='$qrcode' alt='$alt' width='$width' height='$height'>
        EOF;

        return $html;
    }
}
