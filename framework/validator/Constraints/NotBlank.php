<?php

namespace WernerDweight\Microbe\framework\validator\Constraints;

use WernerDweight\Microbe\framework\validator\constraints\ConstraintInterface;

class NotBlank implements ConstraintInterface{

	public static function validate($value,$options = null){
		if(empty($value)){
			return false;
		}
		return true;
	}

	public static function error($value,$options = null){
		return 'This value must not be empty!';
	}

}
