<?php

$root = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;

require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'filesystem'.DIRECTORY_SEPARATOR.'cleanFolder.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'filesystem'.DIRECTORY_SEPARATOR.'copyFolder.php');



$clean = true;
$options = array_slice($argv,2);

foreach ($options as $option) {
	if(preg_match('/^--theme=/',$option)){
		$theme = substr($option,8);
	}
	else if($option === '--no-clean'){
		$clean = false;
	}
}

if(!isset($theme) || strlen($theme) <= 0){
	die("ERROR: No theme selected, select with --theme=theme_name\n");
}

$src = $root.'src'.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.'prod';
$dest = $root.'public'.DIRECTORY_SEPARATOR.'theme';

echo "Cleaning current assets...\n";
if($clean === true){
	cleanFolder($dest);
}

if(!is_dir($dest)){
	mkdir($dest);
}

echo "Installing new assets...\n";
copyFolder($src,$dest);
echo "Done!\n";
