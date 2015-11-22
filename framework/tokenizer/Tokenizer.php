<?php

namespace WernerDweight\Microbe\framework\tokenizer;

class Tokenizer {
	
	public static function tokenize($string) {
		return self::netteWebalize($string);
	}

	/**
	 * From Nette framework
	 */
	private static function netteToAscii($s) {
	    $s = preg_replace('#[^\x09\x0A\x0D\x20-\x7E\xA0-\x{2FF}\x{370}-\x{10FFFF}]#u', '', $s);
	    $s = strtr($s, '`\'"^~', "\x01\x02\x03\x04\x05");
	    if (ICONV_IMPL === 'glibc') {
	        $s = @iconv('UTF-8', 'WINDOWS-1250//TRANSLIT', $s); // intentionally @
	        $s = strtr($s, "\xa5\xa3\xbc\x8c\xa7\x8a\xaa\x8d\x8f\x8e\xaf\xb9\xb3\xbe\x9c\x9a\xba\x9d\x9f\x9e"
	            . "\xbf\xc0\xc1\xc2\xc3\xc4\xc5\xc6\xc7\xc8\xc9\xca\xcb\xcc\xcd\xce\xcf\xd0\xd1\xd2\xd3"
	            . "\xd4\xd5\xd6\xd7\xd8\xd9\xda\xdb\xdc\xdd\xde\xdf\xe0\xe1\xe2\xe3\xe4\xe5\xe6\xe7\xe8"
	            . "\xe9\xea\xeb\xec\xed\xee\xef\xf0\xf1\xf2\xf3\xf4\xf5\xf6\xf8\xf9\xfa\xfb\xfc\xfd\xfe\x96",
	            "ALLSSSSTZZZallssstzzzRAAAALCCCEEEEIIDDNNOOOOxRUUUUYTsraaaalccceeeeiiddnnooooruuuuyt-");
	    } else {
	        $s = @iconv('UTF-8', 'ASCII//TRANSLIT', $s); // intentionally @
	    }
	    $s = str_replace(array('`', "'", '"', '^', '~'), '', $s);
	    return strtr($s, "\x01\x02\x03\x04\x05", '`\'"^~');
	}

	/**
	 * From Nette framework
	 */
	private static function netteWebalize($s, $charlist = NULL, $lower = TRUE) {
		$s = netteToAscii($s);
		if ($lower) {
			$s = strtolower($s);
		}
		$s = preg_replace('#[^a-z0-9' . preg_quote($charlist, '#') . ']+#i', '-', $s);
		$s = trim($s, '-');
		return $s;
	}
}
