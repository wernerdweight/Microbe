<?php

namespace WernerDweight\Microbe\framework\controller;

use WernerDweight\Microbe\framework\kernel\Kernel;
use WernerDweight\Microbe\framework\router\exception\PageNotFoundException;
use WernerDweight\Microbe\framework\router\exception\BadRequestException;
use WernerDweight\Microbe\framework\router\exception\UnauthorizedException;

abstract class AbstractController{
	
	protected $context;
	protected $kernel;

	public function __construct(Kernel $kernel, $contentType = 'html'){
		$this->kernel = $kernel;
		$this->context = array(
			'vars' => array(),
		);
		$this->initialize();
	}

	protected function setHeaders($string,$replace = true,$responseCode = 200){
		header($string,$replace,$responseCode);
	}

	protected function validateRequest(){}

	protected function initialize(){}

	protected function finalize(){}

	/// twig

	protected function setTwigVar($key,$value){
		$this->context['vars'][$key] = $value;
	}

	/// dobee

	protected function getDobeeProvider(){
		return $this->kernel->service('dobee')->getProvider();
	}

	/// router

	protected function renderWithVars($template){
		$this->finalize();
		return $this->kernel->service('twig')->render($template,$this->context['vars']);
	}

	protected function redirect($url,$statusCode = 303){
		return $this->kernel->service('router')->redirect($url,$statusCode);
	}

	protected function redirectToRoute($routeName,$options = [],$statusCode = 303){
		return $this->redirect(
			$this->kernel->service('router')->path(
				$routeName,
				$options,
				true
			),
			$statusCode
		);
	}

	protected function redirectBack(){
		return $this->redirect($this->kernel->getReferer());
	}

	protected function pageNotFound($message = null){
		throw new PageNotFoundException($message);
	}

	protected function badRequest($message = null){
		throw new BadRequestException($message);
	}

	protected function unauthorized($message = null){
		throw new UnauthorizedException($message);
	}

	/// canonicalizer

	protected function canonicalize($string){
		return $this->kernel->service('canonicalizer')->canonicalize($string);
	}

	/// gatekeeper

	protected function isLogged(){
		return $this->kernel->service('gatekeeper')->isLogged();
	}

	protected function getUser(){
		return $this->kernel->service('gatekeeper')->getUser();
	}

	protected function loginUser($user){
		$this->kernel->service('gatekeeper')->setLogged(true)->setUser($user);
	}

	protected function logOut(){
		$this->kernel->service('gatekeeper')->logOut();
	}

}

?>
