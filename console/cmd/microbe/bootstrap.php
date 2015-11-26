<?php

/// unix systems
$root = preg_replace('/^(.*)\/[^\/]+\/[^\/]+\/[^\/]+\/[^\/]+\/[^\/]+\/[^\/]+\/[^\/]+\/[^\/]+$/','$1',__DIR__);
/// windows
$root = preg_replace('/^(.*)(\\\)[^(\\\)]+(\\\)[^(\\\)]+(\\\)[^(\\\)]+(\\\)[^(\\\)]+(\\\)[^(\\\)]+(\\\)[^(\\\)]+(\\\)[^(\\\)]+(\\\)[^(\\\)]+$/','$1',$root);
/// make path a directory
$root .= DIRECTORY_SEPARATOR;

$options = array_slice($argv,2);

echo "Bootstrapping...\n";

if(!is_dir($root.'cache')){
	echo "Creating 'cache' directory...\n";
	mkdir($root.'cache');
	chmod($root.'cache',0777);
}
if(!is_dir($root.'public')){
	echo "Creating 'public' directory...\n";
	mkdir($root.'public');
}
if(!is_dir($root.'src'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'entity')){
	echo "Creating 'src/app/entity' directory...\n";
	mkdir($root.'src'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'entity');
}
if(!is_dir($root.'src'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'forms')){
	echo "Creating 'src/app/forms' directory...\n";
	mkdir($root.'src'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'forms');
}
if(!is_dir($root.'src'.DIRECTORY_SEPARATOR.'themes')){
	echo "Creating 'src/themes' directory...\n";
	mkdir($root.'src'.DIRECTORY_SEPARATOR.'themes');
}

echo "Done!\n";
