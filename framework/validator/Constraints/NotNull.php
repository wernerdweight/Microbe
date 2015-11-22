<?php 

namespace WernerDweight\Microbe\framework\validator\Constraints;

use WernerDweight\Microbe\framework\validator\constraints\ConstraintInterface;

class NotNull implements ConstraintInterface{

	public static function validate($value,$options = null){
		if(null === $value){
			return false;
		}
		return true;
	}

	public static function error($value,$options = null){
		return 'This value must not be null!';
	}

}
