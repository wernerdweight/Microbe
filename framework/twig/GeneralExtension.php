<?php

namespace WernerDweight\Microbe\framework\twig;

class GeneralExtension extends \Twig_Extension
{

    public function getFilters(){
        return [
            new \Twig_SimpleFilter('intval','intval'),
        ];
    }

    public function getName(){
        return 'GeneralExtension';
    }
}
