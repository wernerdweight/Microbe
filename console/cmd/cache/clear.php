<?php

$root = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;

require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'filesystem'.DIRECTORY_SEPARATOR.'cleanFolder.php');


$dest = $root.'cache';

if(is_dir($dest)){
	echo "Moving old cache to 'cache_old'...\n";
	rename($dest,$dest.'_old');
}

echo "Creating new cache directory...\n";
mkdir($dest);
chmod($dest,0777);

if(is_dir($dest.'_old')){
	echo "Cleaning old cache...\n";
	cleanFolder($dest.'_old');
	@rmdir($dest.'_old');	
}

echo "Done!\n";
