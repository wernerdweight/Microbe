<?php

namespace WernerDweight\Microbe\framework\translator;

use Symfony\Component\Yaml\Yaml;
use WernerDweight\Microbe\framework\traverser\Traverser;

class Translator{

    protected $defaultLocale;
    protected $translations;

    public function __construct(array $paths){
        $this->translations = [];

        if(count($paths) > 0){
            foreach ($paths as $path) {
                $locale = preg_replace('/^.*\.(..)\.yml$/i','$1',$path);
                $this->loadConfiguration($locale,$path);
            }
        }
    }

    public function setLocale($locale){
        $this->defaultLocale = $locale;
    }

    protected function loadConfiguration($locale,$path){
        if(is_file($path)){
            /// load contents of the translations file
            $translationFileContents = file_get_contents($path);

            try {
                /// parse translations
                $this->translations[$locale] = Yaml::parse($translationFileContents);
            } catch (Exception $e) {
                throw new \Exception("Yaml configuration is invalid: ".$e->getMessage(), 1);
            }
        }
    }

    protected function findTranslation($translation,$locale){
        return Traverser::getFromArray($this->translations[$locale],$translation,'.');
    }

    public function translate($translation,$parameters = [],$locale = null){
        /// find translation
        $translated = $this->findTranslation($translation,$locale !== null ? $locale : $this->defaultLocale);

        /// enhance parameters (if any)
        foreach ($parameters as $parameter => $value) {
            $translated = str_replace('%'.$parameter.'%',$value,$translated);
        }

        return $translated;
    }

}
