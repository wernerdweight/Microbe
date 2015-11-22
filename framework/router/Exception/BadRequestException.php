<?php

namespace WernerDweight\Microbe\framework\router\Exception;

use WernerDweight\Microbe\framework\router\Exception\AbstractException;

class BadRequestException extends AbstractException {
	protected $errorCode = 400;
	protected $message = '400: Bad Request';
}
