<?php

declare(strict_types=1);

namespace App\Library\File;

use Exception;
use TCPDF;

class PdfLibrary
{
    // font-family
    public const FONT_FAMILY_KOZGOPROMEDIUM = 'kozgopromedium';
    // font-size
    public const FONT_SIZE_10 = 10;

    /**
     * サンプルPDFの出力(ファイルリソース)
     *
     * @return string
     * @throws Exception
     */
    public static function getSamplePDF(): string
    {
        $tcpdf = new TCPDF();
        $tcpdf->AddPage();
        $tcpdf->SetFont(self::FONT_FAMILY_KOZGOPROMEDIUM, "", self::FONT_SIZE_10);
        $html = <<< EOF
        <style>
        body {
            color: #212121;
        }
        </style>
        <body>
        <h1>header</h1>
        <p>
        sample text.
        </p>
        <p>
        contents.
        </p>
        </body>

        EOF;

        $tcpdf->writeHTML($html);
        return $tcpdf->Output('sample.pdf', 'I');
    }

    /**
     * HTMLを文字列として指定してPDFの出力
     *
     * @param string $name file name
     * @param string $html file contents
     * @return string
     * @throws Exception
     */
    public static function getPdfByHtmlString(string $name, string $html): string
    {
        $tcpdf = new TCPDF();
        $tcpdf->AddPage();
        $tcpdf->SetFont(self::FONT_FAMILY_KOZGOPROMEDIUM, "", self::FONT_SIZE_10);
        $tcpdf->writeHTML($html);
        return $tcpdf->Output($name, 'I');
    }
}
