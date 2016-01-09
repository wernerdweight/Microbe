<?php

namespace WernerDweight\Microbe\framework\traverser;

class Traverser{

	public static function getFromArray(array $array,$key,$separator){
		$keys = explode($separator,$key);
		$currentNode = $array;

		foreach ($keys as $key) {
			if(false === isset($currentNode[$key])){
				return null;
			}
			$currentNode = $currentNode[$key];
		}

		return $currentNode;
	}

}
