<?php

namespace WernerDweight\Microbe\framework\formbuilder\Exception;

class MissingConfigurationException extends \RuntimeException{

	public function __construct($message = null,$code = null,\Exception $previous = null){
		if(is_null($message)){
			$message = 'The configuration is missing!';
		}
		parent::__construct($message,$code,$previous);
	}

}
