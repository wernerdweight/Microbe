<?php

namespace WernerDweight\Microbe\framework\formbuilder\Twig\Extension;

class FormExtension extends \Twig_Extension
{

    protected $twig;

    public function __construct(\Twig_Environment $twig){
        $this->twig = $twig;
    }

    public function getFunctions(){
        $twig = $this->twig;
        $twig->getLoader()->addPath('src/app/utils/formbuilder/Twig/Template');
        
        return [
            new \Twig_SimpleFunction('form',function($form,$path,$attributes = []) use ($twig){
                return $twig->render('form.html.twig',[
                    'form' => $form,
                    'path' => $path,
                    'attributes' => $attributes
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
            new \Twig_SimpleFunction('formField',function($name,$field,$value) use ($twig){
                return $twig->render('Theme/'.$field['type'].'.html.twig',[
                    'name' => $name,
                    'field' => $field,
                    'value' => $value
                ]);
            },['is_safe' => ['html']]),
        ];
    }

    public function getName(){
        return 'FormExtension';
    }
}
