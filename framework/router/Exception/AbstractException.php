<?php

namespace WernerDweight\Microbe\framework\router\Exception;

class AbstractException extends \Exception {

	protected $errorCode;
	protected $message = 'Unknown routing exception occured';

	public function getErrorCode(){
		return $this->errorCode;
	}

}
