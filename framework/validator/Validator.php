<?php

namespace WernerDweight\Microbe\framework\validator;

class Validator{
	public static function validate($value,$constraint,$options = null){
		$constraintClass = '\\WernerDweight\\Microbe\\framework\\validator\\Constraints\\'.ucfirst($constraint);
		if($constraintClass::validate($value,$options) === false){
			return $constraintClass::error($value,$options);
		}
		return null;
	}
}
