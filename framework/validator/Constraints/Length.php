<?php

namespace WernerDweight\Microbe\framework\validator\Constraints;

use WernerDweight\Microbe\framework\validator\Constraints\ConstraintInterface;

class Length implements ConstraintInterface{

	public static function getMax($options){
		return (isset($options['max']) ? intval($options['max']) : PHP_INT_MAX);
	}

	public static function getMin($options){
		return (isset($options['min']) ? intval($options['min']) : 0);
	}

	public static function validate($value,$options = null){
		$min = self::getMin($options);
		$max = self::getMax($options);
		
		if(mb_strlen($value) < $min || mb_strlen($value) > $max){
			return false;
		}
		return true;
	}

	public static function error($value,$options = null){
		$min = self::getMin($options);
		$max = self::getMax($options);

		return 'The length of this value must be between '.$min.' and '.$max.'!';
	}

}
