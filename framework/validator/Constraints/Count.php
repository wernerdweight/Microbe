<?php

namespace WernerDweight\Microbe\framework\validator\Constraints;

use WernerDweight\Microbe\framework\validator\Constraints\ConstraintInterface;

class Count implements ConstraintInterface{

	public static function getMax($options){
		return (isset($options['max']) ? intval($options['max']) : PHP_INT_MAX);
	}

	public static function getMin($options){
		return (isset($options['min']) ? intval($options['min']) : 0);
	}

	public static function validate($value,$options = null){
		$min = self::getMin($options);
		$max = self::getMax($options);
		
		if(true !== is_array($value) || count($value) < $min || count($value) > $max){
			return false;
		}
		return true;
	}

	public static function error($value,$options = null){
		$min = self::getMin($options);
		$max = self::getMax($options);

		if($min > 0) {
			if($max < PHP_INT_MAX) {
				$ending = 'between '.$min.' and '.$max;
			}
			else{
				$ending = 'greater than '.$min;
			}
		}
		else{
			if($max < PHP_INT_MAX) {
				$ending = 'lower than '.$min;
			}
		}

		return 'The count of items in this value must be '.$ending.'!';
	}

}
