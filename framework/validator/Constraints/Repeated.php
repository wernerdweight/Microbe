<?php

namespace WernerDweight\Microbe\framework\validator\Constraints;

use WernerDweight\Microbe\framework\validator\constraints\ConstraintInterface;

class Repeated implements ConstraintInterface{

	public static function validate($value,$options = null){
		if($value['password'] !== $value['repeated']){
			return false;
		}
		return true;
	}

	public static function error($value,$options = null){
		return 'Passwords must match!';
	}

}
