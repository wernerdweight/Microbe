<?php

namespace WernerDweight\Microbe\framework\twig;

class TextFunctionsExtension extends \Twig_Extension
{

    public function getFilters(){
        return [
            new \Twig_SimpleFilter(
                'ucfirst',
                [
                    $this,
                    'utfUcfirst'
                ]
            )
        ];
    }

    public function utfUcfirst($string){
        $first = mb_substr($string,0,1);
        $rest = mb_substr($string,1,mb_strlen($string)-1);
        return mb_strtoupper($first).$rest;
    }

    public function getName(){
        return 'TextFunctionsExtension';
    }
}
