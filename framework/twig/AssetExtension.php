<?php

namespace WernerDweight\Microbe\framework\twig;

use WernerDweight\Microbe\framework\router\Router;

class AssetExtension extends \Twig_Extension
{

    protected $router;
    protected $env;
    protected $theme;

    public function __construct(Router $router,$env = 'prod',$theme = 'default'){
        $this->router = $router;
        $this->env = $env;
        $this->theme = $theme;
    }

    public function getFunctions(){
        return [
            new \Twig_SimpleFunction('asset',function($assetPath,$absolute = false){
                $path = '';
                if($absolute === true){
                    $path .= $this->router->getRoot();
                }
                else{
                    $path .= $this->router->getBase();
                }

                if($this->env === 'prod' || !preg_match('/^theme\//i',$assetPath)){
                    $path .= '/public/'.$assetPath;
                }
                else{
                    $path .= '/src/themes/'.$this->theme.'/prod/'.preg_replace('/^theme\//i','',$assetPath);
                }

                return $path;
            }),
        ];
    }

    public function getName(){
        return 'AssetExtension';
    }
}
