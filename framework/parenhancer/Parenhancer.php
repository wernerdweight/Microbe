<?php

namespace WernerDweight\Microbe\framework\parenhancer;

use Symfony\Component\Yaml\Yaml;

class Parenhancer{

    protected $parameters;

    public function __construct($path){
        $this->loadConfiguration($path);
    }

    protected function loadConfiguration($path){
        if(is_file($path)){
            /// load contents of the configuration file
            $configurationFileContents = file_get_contents($path);

            try {
                /// parse configuration
                $this->parameters = Yaml::parse($configurationFileContents);
            } catch (Exception $e) {
                throw new \Exception("Yaml configuration is invalid: ".$e->getMessage(), 1);
            }
        }
        else{
            $this->parameters = array();
        }
    }

    public function enhance($string){
        foreach ($this->getParametersToEnhance($string) as $parameter) {
            $parameterKey = trim($parameter,'%');
            if(array_key_exists($parameterKey,$this->parameters)){
                $string = str_replace($parameter,$this->parameters[$parameterKey],$string);
            }
        }

        return $string;
    }

    public function enhanceArray($array){
        if(is_array($array)){
            if(count($array)){
                foreach ($array as $key => $value) {
                    $array[$key] = $this->enhanceArray($value);
                }
            }
        }
        else{
            $array = $this->enhance($array);
        }
        return $array;
    }

    protected function getParametersToEnhance($string){
        $parameters = array();
        preg_match_all('/%[^%]+%/',$string,$parameters);
        return $parameters[0];
    }

}
