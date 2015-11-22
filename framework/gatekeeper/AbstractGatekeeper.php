<?php

namespace WernerDweight\Microbe\framework\gatekeeper;

abstract class AbstractGatekeeper
{
	protected static $instance;

	protected function __construct(){}
	
	private function __wakeup(){}

	private function __clone(){}

	public static function getInstance(){
		if (null === static::$instance) {
			static::$instance = new static();
		}

		return static::$instance;
	}
}
