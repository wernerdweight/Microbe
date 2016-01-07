<?php

namespace WernerDweight\Microbe\framework\flashmessenger;

use WernerDweight\Microbe\framework\flashmessenger\AbstractFlashMessenger;

class FlashMessenger extends AbstractFlashMessenger
{
	const FLASH_MESSENGER_ID = 'microbe_flashmessenger_session';

	protected $messages;

	protected function __construct(){
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		if (false === isset($_SESSION[self::FLASH_MESSENGER_ID])) {
			$this->refresh();
		}
		else {
			$this->messages = $_SESSION[self::FLASH_MESSENGER_ID];
		}
	}

	protected function refresh(){
		$this->messages = [];

		$_SESSION[self::FLASH_MESSENGER_ID] = $this->messages;
	}

	public function addMessage($type,$message){
		$_SESSION[self::FLASH_MESSENGER_ID][$type][] = $message;
		return $this;
	}

	public function forceMessage($type,$message){
		$this->messages[$type][] = $message;
		return $this;
	}

	public function getMessages($type){
		if(isset($this->messages[$type]) && count($this->messages[$type]) > 0){
			unset($_SESSION[self::FLASH_MESSENGER_ID][$type]);
			return $this->messages[$type];
		}
		return [];
	}

	public function hasMessages($type){
		if(false !== isset($this->messages[$type]) && count($this->messages[$type]) > 0){
			return true;
		}
		return false;
	}

}
