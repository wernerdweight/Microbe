<?php 

namespace WernerDweight\Microbe\framework\validator\Constraints;

use WernerDweight\Microbe\framework\validator\constraints\ConstraintInterface;

class Min implements ConstraintInterface{

	public static function getMin($options){
		return (floatval($options) ? floatval($options) : 0);
	}

	public static function validate($value,$options = null){
		$min = self::getMin($options);
		
		if($value < $min){
			return false;
		}
		return true;
	}

	public static function error($value,$options = null){
		$min = self::getMin($options);

		return 'This value must be greater or equal to '.$min.'!';
	}

}
