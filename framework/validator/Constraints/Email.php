<?php

namespace WernerDweight\Microbe\framework\validator\Constraints;

use WernerDweight\Microbe\framework\validator\Constraints\ConstraintInterface;

class Email implements ConstraintInterface{

	public static function validate($value,$options = null){
		$isValid = true;
		$atIndex = strrpos($value, "@");
		if(is_bool($atIndex) and !$atIndex){
			$isValid = false;
		}
		else{
			$domain = substr($value, $atIndex+1);
			$local = substr($value, 0, $atIndex);
			$localLen = strlen($local);
			$domainLen = strlen($domain);
			if($localLen < 1 or $localLen > 64){
				// Local part length exceeded
				$isValid = false;
			}
			else if($domainLen < 1 or $domainLen > 255){
				// Domain part length exceeded
				$isValid = false;
			}
			else if($local[0] == '.' or $local[$localLen-1] == '.'){
				// Local part starts or ends with '.'
				$isValid = false;
			}
			else if(preg_match('/\\.\\./', $local)){
				// Local part has two consecutive dots
				$isValid = false;
			}
			else if(!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)){
				// Character not valid in domain part
				$isValid = false;
			}
			else if(preg_match('/\\.\\./', $domain)){
				// Domain part has two consecutive dots
				$isValid = false;
			}
			else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',str_replace("\\\\","",$local))){
				// Character not valid in local part unless local part is quoted
				if(!preg_match('/^"(\\\\"|[^"])+"$/',str_replace("\\\\","",$local))){
					$isValid = false;
				}
			}
			if($isValid && !(checkdnsrr($domain,"MX") or checkdnsrr($domain,"A"))){
				// Domain not found in DNS
				$isValid = false;
			}
		}
		return $isValid;
	}

	public static function error($value,$options = null){
		return $value.' is not a valid e-mail address!';
	}

}
