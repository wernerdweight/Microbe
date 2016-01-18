<?php

namespace WernerDweight\Microbe\framework\router;

use Symfony\Component\Yaml\Yaml;

use WernerDweight\Microbe\framework\router\Path;
use WernerDweight\Microbe\framework\router\Exception\PageNotFoundException;
use WernerDweight\Microbe\framework\router\Exception\BadRequestException;
use WernerDweight\Microbe\framework\router\Exception\UnauthorizedException;
use WernerDweight\Microbe\framework\kernel\Kernel;

class Router
{

    const CONFIGURATION_FILE_PATH = 'src/app/config/routing.yml';

    protected $routes;
    protected $kernel;
    protected $currentRoute;
    protected $currentRouteName;
    protected $firewallRules;
    protected $roles;

    public function __construct($path = null,$firewallRules = [],$roles = []){
        $this->loadConfiguration($path);
        $this->firewallRules = $firewallRules;
        $this->roles = $roles;
        $this->currentRoute = $this->currentRouteName = null;
    }

    public function registerKernel(Kernel $kernel){
        $this->kernel = $kernel;
        return $this;
    }

    protected function loadConfiguration($path){
        if(is_null($path)){
            $path = self::CONFIGURATION_FILE_PATH;
        }

        if(!is_file($path)){
            throw new \Exception("No routing available", 1);
        }
        /// load contents of the configuration file
        $configurationFileContents = file_get_contents($path);

        try {
            /// parse configuration
            $this->routes = Yaml::parse($configurationFileContents);
        } catch (Exception $e) {
            throw new \Exception("Yaml configuration is invalid: ".$e->getMessage(), 1);
        }
    }

    protected function findRouteByName($routeName){
        if(is_array($this->routes) && isset($this->routes[$routeName])){
            return $this->routes[$routeName];
        }
        return null;
    }

    protected function findRouteByPath($path){
        if(($questionMarkPosition = strpos($path,'?')) !== false){
            $path = substr($path,0,$questionMarkPosition);
        }
        if(is_array($this->routes) && count($this->routes)){
            foreach ($this->routes as $routeName => $route) {
                $regExp = preg_replace('/\{[^\}]+\}/','[a-zA-Z0-9_\-\.%&\+;=]+',$route['path']);
                $regExp = preg_replace('/\//','\/',$regExp);
                if(preg_match('/^'.$regExp.'$/',$path)){
                    $this->currentRouteName = $routeName;
                    $this->currentRoute = $route;
                    return $route;
                }
            }
        }
        return null;
    }

    protected function getParameterValue($parameter,$route,$path){
        $regExp = str_replace($parameter,'([a-zA-Z0-9_\-\.&%\+;=]+)',$route['path']);
        $regExp = preg_replace('/\{[^\}]+\}/','[a-zA-Z0-9_\-\.&%\+;=]+',$regExp);
        $regExp = preg_replace('/\//','\/',$regExp);
        $match = preg_replace('/'.$regExp.'/','$1',$path);
        /// check that preg_replace actually replaced something
        if($match == $path){
            return null;
        }
        return $match;
    }

    protected function getControllerResponse($controller,$action,$options = array()){
        $class = '\\Microbe\\src\app\controllers\\'.str_replace(':','\\',$controller).'Controller';
        $controller = new $class($this->kernel);
        return $controller->$action($options);
    }

    protected function getOptionsForRoute($path){
        $options = array();
        preg_match_all('/\{[^\}]+\}/',$path,$options);
        return $options[0];
    }

    protected function getOptionsFromPath($route,$path){
        $options = array();

        if(count($parameters = $this->getOptionsForRoute($route['path']))){
            foreach ($parameters as $key => $parameter) {
                if(is_null($parameterValue = $this->getParameterValue($parameter,$route,$path))){
                    throw new \Exception("Wrong parameter format for parameter '".$parameter."'", 1);
                }
                $options[trim($parameter,"{}")] = $parameterValue;
            }
        }

        return $options;
    }

    protected function checkFirewallRules($path){
        if(true === is_array($this->firewallRules) && count($this->firewallRules) > 0){
            foreach ($this->firewallRules as $role => $rule) {
                if(preg_match($rule,$path)){
                    if(true !== is_array($this->roles) || count($this->roles) <= 0 || false === in_array($role,$this->roles)){
                        return false;
                    }
                }
            }
        }
        return true;
    }

    public function render($controller,$action,$options = array()){

        echo $this->getControllerResponse($controller,$action,$options);
    }

    public function route($path){
        if(!($route = $this->findRouteByPath($path))){
            throw new PageNotFoundException('Route for path "'.$path.'" not found');
        }

        /// check firewall rules
        if(true !== $this->checkFirewallRules($path)){
            if($loginRoute = $this->findRouteByName('login')){
                $this->redirect($this->path('login'));
            }
            else{
                throw new UnauthorizedException('Access denied for "'.$path.'"!');
            }
        }

        if(isset($route['redirect'])){
            $this->redirect($this->path($route['redirect']),301);
        }

        $options = $this->getOptionsFromPath($route,$path);
        
        echo $this->getControllerResponse($route['controller'],$route['action'],$options);
    }

    public function path($routeName,$options = array(),$absolute = false){
        if(!($route = $this->findRouteByName($routeName))){
            throw new PageNotFoundException('Route named "'.$routeName.'" not found');
        }
        
        /// prepare options
        if(isset($route['defaults']) && is_array($route['defaults'])){
            $ops = array_replace($route['defaults'],$options);
        }
        else{
            $ops = $options;
        }

        /// create path
        $path = new Path($route);
        $path->setOptions($ops);

        return ($absolute === true ? rtrim($this->getRoot(),'/') : $this->getBase()).$path->buildUri();
    }

    public function redirect($url,$statusCode = 303){
        header('Location: '.$url,true,$statusCode);
        exit();
    }

    public function getRoot(){
        return $this->kernel->getRoot();
    }

    public function getBase(){
        return $this->kernel->getBase();
    }

    public function getCurrentRoute(){
        return $this->currentRoute;
    }

    public function getCurrentRouteName(){
        return $this->currentRouteName;
    }

}
