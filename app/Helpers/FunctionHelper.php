<?php

namespace App\Helpers;

class FunctionHelper {

    public const IMAGES_EXT = ['jpg', 'JPG', 'jpeg', 'JPEG', 'gif', 'GIF', 'png', 'PNG'];
    public const WORDS_EXT = ['doc', 'DOC', 'docx', 'DOCX', 'odt', 'ODT'];
    public const EXCELS_EXT = ['xls', 'XLS', 'xlsx', 'XLSX', 'ods', 'ODS'];
    public const PDF_EXT = ['pdf', 'PDF'];
    public const COMPRESSES_EXT = ['rar', 'RAR', 'zip', 'ZIP', '7z', '7Z'];

    /**
     * Truncate string in the middle
     *
     * @param string $text
     * @param integer $length
     * @param string $separator
     * @return string
     **/
    public static function substrMiddle($text, $length = 15, $separator = '...')
    {
        $maxlength = $length - strlen($separator);
        $start = $maxlength / 2 ;
        $trunc =  strlen($text) - $maxlength;
        return substr_replace($text, $separator, $start, $trunc);
    }
}
