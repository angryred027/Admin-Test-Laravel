<?php

declare(strict_types=1);

namespace App\Library\String;

class Unicode
{
    /**
     * convert unicode string to japanese
     *
     * @param string $value
     * @return string
     */
    public static function convertUnicodeToJapanese(string $value): string
    {
        return json_decode('"' . $value . '"');
    }
}
