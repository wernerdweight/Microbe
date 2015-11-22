<?php

namespace WernerDweight\Microbe\framework\kernel;

use WernerDweight\Microbe\framework\router\Exception\AbstractException as RouterException;

class Kernel{

	protected $root;
	protected $base;
	protected $rootPath;
	protected $referer;
	protected $currentUri;
	protected $method;
	protected $services;

	public function __construct($services){
		/// store services
		$this->services = $services;

		/// resolve request data
		$this->resolveRequestData();
		
		/// add globals to twig
		$this->service('twig')->addGlobal('g_root',$this->root);
		$this->service('twig')->addGlobal('g_referer',$this->referer);
		$this->service('twig')->addGlobal('g_current_uri',$this->currentUri);
		$this->service('twig')->addGlobal('g_method',$this->method);
		$this->service('twig')->addGlobal('g_user',$this->service('gatekeeper')->getUser());
		$this->service('twig')->addGlobal('g_logged',$this->service('gatekeeper')->isLogged());
		
		/// set up router
		$this->service('router')->registerKernel($this);
		/// do the routing
		try {
			$this->service('router')->route($this->currentUri);
		} catch (\Exception $e) {
			/// development
			if(ENV === 'dev'){
				echo $e->getMessage();
				exit;
			}
			/// production
			else{
				/// routing error (400, 401, 404...)
				if($e instanceof RouterException){
					$this->service('router')->route(
						$this->service('router')->path(
							'error',
							array(
								'locale' => DEFAULT_LOCALE,
								'errorCode' => is_null($e->getErrorCode()) === false ? $e->getErrorCode() : 500,
							)
						)
					);
				}
				/// unexpected server error
				else{
					$this->service('router')->route(
						$this->service('router')->path(
							'error',
							array(
								'locale' => DEFAULT_LOCALE,
								'errorCode' => 500,
							)
						)
					);
				}
			}
		}
	}

	protected function determineCurrentUri(){
		return str_replace(
			'?'.$_SERVER['QUERY_STRING'],
			'',
			(isset($_SERVER['BASE']) ? 
				str_replace($_SERVER['BASE'],'',$_SERVER['REQUEST_URI']) : 
				(isset($_SERVER['REDIRECT_BASE']) ? 
					str_replace($_SERVER['REDIRECT_BASE'],'',$_SERVER['REQUEST_URI']) : 
					$_SERVER['REQUEST_URI']
				)
			)
		);
	}

	protected function determineRootUri(){
		return 'http://'.$_SERVER['HTTP_HOST'].$this->determineBaseUri();
	}

	protected function determineBaseUri(){
		return (isset($_SERVER['BASE']) ? 
			$_SERVER['BASE'] : 
			(isset($_SERVER['REDIRECT_BASE']) ? 
				$_SERVER['REDIRECT_BASE'] : 
				''
			)
		);
	}

	protected function resolveRequestData(){
		/// determine current root url
		$this->root = $this->determineRootUri();
		/// determine current base url
		$this->base = $this->determineBaseUri();
		/// set current root path
		$this->rootPath = __DIR__.'/../../../';
		/// check for referer
		$this->referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
		/// get current uri
		$this->currentUri = $this->determineCurrentUri();
		/// get current request method
		$this->method = $_SERVER['REQUEST_METHOD'];
	}

	public function getRoot(){
		return $this->root;
	}

	public function getBase(){
		return $this->base;
	}

	public function getRootPath(){
		return $this->rootPath;
	}

	public function getCurrentUri(){
		return $this->currentUri;
	}

	public function getMethod(){
		return $this->method;
	}

	public function getReferer(){
		return $this->referer;
	}

	public function service($serviceName){
		if(isset($this->services[$serviceName])){
			return $this->services[$serviceName];
		}
		else{
			return null;
		}
	}

}

?>
