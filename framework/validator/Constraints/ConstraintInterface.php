<?php

namespace WernerDweight\Microbe\framework\validator\Constraints;

interface ConstraintInterface{
	public static function validate($value,$options = null);
	public static function error($value,$options = null);
}
