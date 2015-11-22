<?php

namespace WernerDweight\Microbe\framework\canonicalizer;

class Canonicalizer
{
    public static function canonicalize($value){
        /// transliterate cyrilic and other special chars
        $value = str_replace(
            array('Щ','щ','Ё','Ж','Х','Ц','Ч','Ш','Ю','я','ё','ж','х','ц','ч','ш','ю','я','А','Б','В','Г','Д','Е','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Ь','Ы','Ъ','Э','а','б','в','г','д','е','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','ь','ы','ъ','э'),
            array('Sc','sc','Jo','Z','Ch','C','C','S','Ju','ja','jo','z','ch','c','c','s','ju','ja','A','B','V','G','D','E','Z','I','Y','K','L','M','N','O','P','R','S','T','U','F','','Y','','E','a','b','v','g','d','e','z','i','j','k','l','m','n','o','p','r','s','t','u','f','','y','','e'),
            $value
        );
        /// get rid of some special chars like tabs etc.
        $value = preg_replace(
            '#[^\x09\x0A\x0D\x20-\x7E\xA0-\x{2FF}\x{370}-\x{10FFFF}]#u',
            '',
            $value
        );
        /// mask some special chars
        $value = strtr(
            $value,
            '`\'"^~',
            "\x01\x02\x03\x04\x05"
        );
        /// transliterate to ASCII/Win-1250
        if (ICONV_IMPL === 'glibc') {
            $value = iconv('UTF-8', 'WINDOWS-1250//TRANSLIT', $value);
            $value = strtr(
                $value,
                "\xa5\xa3\xbc\x8c\xa7\x8a\xaa\x8d\x8f\x8e\xaf\xb9\xb3\xbe\x9c\x9a\xba\x9d\x9f\x9e\xbf\xc0\xc1\xc2\xc3\xc4\xc5\xc6\xc7\xc8\xc9\xca\xcb\xcc\xcd\xce\xcf\xd0\xd1\xd2\xd3\xd4\xd5\xd6\xd7\xd8\xd9\xda\xdb\xdc\xdd\xde\xdf\xe0\xe1\xe2\xe3\xe4\xe5\xe6\xe7\xe8\xe9\xea\xeb\xec\xed\xee\xef\xf0\xf1\xf2\xf3\xf4\xf5\xf6\xf8\xf9\xfa\xfb\xfc\xfd\xfe\x96",
                "ALLSSSSTZZZallssstzzzRAAAALCCCEEEEIIDDNNOOOOxRUUUUYTsraaaalccceeeeiiddnnooooruuuuyt-"
            );
        }
        else {
            $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
        }
        /// get rid of previously masked special chars
        $value = str_replace(
            array('`', "'", '"', '^', '~'),
            '',
            $value
        );
        $value = strtr(
            $value,
            "\x01\x02\x03\x04\x05",
            '`\'"^~'
        );

        $value = strtolower($value);
        $value = preg_replace(
            '#[^a-z0-9]+#i',
            '-',
            $value
        );
        $value = trim($value,'-');

        return $value;
    }

}
