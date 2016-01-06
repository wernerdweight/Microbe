<?php

namespace WernerDweight\Microbe\framework\validator\Constraints;

use WernerDweight\Microbe\framework\validator\Constraints\ConstraintInterface;

class PhoneNumber implements ConstraintInterface{

	public static function validate($value,$options = null){
		if(!preg_match('/^[\d\s\-\+]+$/i',$value)){
			return false;
		}
		return true;
	}

	public static function error($value,$options = null){
		return $value.' is not a valid phone nuber!';
	}

}
