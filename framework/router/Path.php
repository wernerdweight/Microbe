<?php

namespace WernerDweight\Microbe\framework\router;

class Path
{

    protected $uri;

    public function __construct($route){
        $this->uri = $route['path'];
    }

    public function setOptions($options){
        if(is_array($options) && count($options)){
            foreach ($options as $option => $value) {
                $this->uri = str_replace('{'.$option.'}',$value,$this->uri);
            }
        }
    }

    public function buildUri(){
        $missingOptions = array();
        /// check for missing options
        if(preg_match_all('/\{[^\}]+\}/',$this->uri,$missingOptions)){
            //echo $this->uri;
            //die();
            throw new \Exception("Mandatory parameter(s) missing", 1);
        }

        return $this->uri;
    }

}
