<?php

namespace WernerDweight\Microbe\framework\formbuilder\Twig\Extension;

use WernerDweight\Microbe\framework\formbuilder\Factory\FormFactory;

class FormExtension extends \Twig_Extension
{

    protected $twig;

    public function __construct(\Twig_Environment $twig){
        $this->twig = $twig;
    }

    public function getFunctions(){
        $twig = $this->twig;
        $twig->getLoader()->addPath(__DIR__.'/../Template');
        
        return [
            new \Twig_SimpleFunction('form',function($form,$path,$attributes = [],$options = []) use ($twig){
                return $twig->render('form.html.twig',[
                    'form' => $form,
                    'path' => $path,
                    'attributes' => $attributes,
                    'options' => $options
                ]);
            },['is_safe' => ['html']]),
            new \Twig_SimpleFunction('formAttributes',function($attributes = null) use ($twig){
                $attributesString = '';
                if($attributes !== null){
                    foreach ($attributes as $key => $value) {
                        $attributesString .= ' '.$key.'="'.$value.'"';
                    }
                }
                return $attributesString;
            },['is_safe' => ['html']]),
            new \Twig_SimpleFunction('formParents',function($formParents,$type = 'name') use ($twig){
                $parentString = '';
                if(count($formParents)){
                    if($type === 'name'){
                        $parentString = '['.implode('][',$formParents).']';
                    }
                    else if($type === 'id'){
                        $parentString = implode('_',$formParents).'_';
                    }
                }
                return $parentString;
            },['is_safe' => ['html']]),
            new \Twig_SimpleFunction('formField',function($form,$name,$field,$value,$formParents = []) use ($twig){
                return $twig->render('Theme/'.$field['type'].'.html.twig',[
                    'form' => $form,
                    'name' => $name,
                    'field' => $field,
                    'value' => $value,
                    'formParents' => $formParents,
                ]);
            },['is_safe' => ['html']]),
        ];
    }

    public function getName(){
        return 'FormExtension';
    }
}
