<?php

function copyFolder($src,$dest) {
    if(is_dir($src)) {
        $dir_handle = opendir($src);
        while($file = readdir($dir_handle)) {
            if($file !== '.' && $file !== '..') {
                if(is_dir($src.DIRECTORY_SEPARATOR.$file)){
                    if(!is_dir($dest.DIRECTORY_SEPARATOR.$file)) {
                        mkdir($dest.DIRECTORY_SEPARATOR.$file);
                    }
                    copyFolder($src.DIRECTORY_SEPARATOR.$file,$dest.DIRECTORY_SEPARATOR.$file);
                }
                else {
                    copy($src.DIRECTORY_SEPARATOR.$file,$dest.DIRECTORY_SEPARATOR.$file);
                }
            }
        }
        closedir($dir_handle);
    } else {
        copy($src,$dest);
    }
}

?>
