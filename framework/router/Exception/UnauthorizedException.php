<?php

namespace WernerDweight\Microbe\framework\router\Exception;

use WernerDweight\Microbe\framework\router\Exception\AbstractException;

class UnauthorizedException extends AbstractException {
	protected $errorCode = 401;
	protected $message = '401: Unauthorized';
}
