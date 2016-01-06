<?php

namespace WernerDweight\Microbe\framework\validator\Constraints;

use WernerDweight\Microbe\framework\validator\Constraints\ConstraintInterface;

class DivisibleBy implements ConstraintInterface{

	public static function getDivider($options){
		return (intval($options) !== 0 ? intval($options) : 1);
	}

	public static function validate($value,$options = null){
		$divider = self::getDivider($options);
		
		if($value % $divider !== 0){
			return false;
		}
		return true;
	}

	public static function error($value,$options = null){
		$divider = self::getDivider($options);

		return 'This value must be divisible by '.$divider.'!';
	}

}
