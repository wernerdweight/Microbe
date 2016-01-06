<?php

namespace WernerDweight\Microbe\framework\validator\Constraints;

use WernerDweight\Microbe\framework\validator\Constraints\ConstraintInterface;

class Max implements ConstraintInterface{

	public static function getMax($options){
		return (floatval($options) ? floatval($options) : 0);
	}

	public static function validate($value,$options = null){
		$max = self::getMax($options);
		
		if($value > $max){
			return false;
		}
		return true;
	}

	public static function error($value,$options = null){
		$max = self::getMax($options);

		return 'This value must be lower or equal to '.$max.'!';
	}

}
