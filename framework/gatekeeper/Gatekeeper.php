<?php

namespace WernerDweight\Microbe\framework\gatekeeper;

use WernerDweight\Microbe\framework\gatekeeper\AbstractGatekeeper;

class Gatekeeper extends AbstractGatekeeper
{
	const GATEKEEPER_ID = 'microbe_gatekeeper_session';

	protected $user;
	protected $logged;

	protected function __construct(){
		session_start();

		if (!isset($_SESSION[self::GATEKEEPER_ID])) {
			$this->refresh();
		}
		else {
			$this->user = $_SESSION[self::GATEKEEPER_ID]['user'];
			$this->logged = $_SESSION[self::GATEKEEPER_ID]['logged'];
		}
	}

	protected function refresh(){
		$this->user = null;
		$this->logged = false;

		$_SESSION[self::GATEKEEPER_ID] = [
			'user' => $this->user,
			'logged' => $this->logged
		];
	}

	public function isLogged(){
		return $this->logged;
	}

	public function setLogged($logged){
		$this->logged = $logged;
		$_SESSION[self::GATEKEEPER_ID]['logged'] = $logged;
		return $this;
	}

	public function getUser(){
		return $this->user;
	}

	public function setUser($user){
		$this->user = $user;
		$_SESSION[self::GATEKEEPER_ID]['user'] = $user;
		return $this;
	}

	public function logOut(){
		$this->refresh();
	}
}
