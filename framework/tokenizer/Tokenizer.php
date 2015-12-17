<?php

namespace WernerDweight\Microbe\framework\tokenizer;

class Tokenizer {
	
	const ALPHABET = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	public function __construct(){}

	protected function cryptoRandSecure($min,$max) {
		$range = $max - $min;
		
		/// not so random, just for sure
		if ($range < 0){
			return $min;
		}
		
		$log = log($range,2);
		/// length in bytes
		$bytes = (int) ($log / 8) + 1;
		/// length in bits
		$bits = (int) $log + 1;
		/// set all lower bits to 1
		$filter = (int) (1 << $bits) - 1;
		
		do {
			$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
			/// discard irrelevant bits
			$rnd = $rnd & $filter;
		} while ($rnd >= $range);
		
		return $min + $rnd;
	}

	public function tokenize($length = 128){
		$token = "";
		
		for($i = 0; $i < intval($length); $i++){
			$token .= self::ALPHABET[$this->cryptoRandSecure(0,strlen(self::ALPHABET))];
		}

		return $token;
	}
}
