<?php

function cleanFolder($path){
	$files = glob($path.DIRECTORY_SEPARATOR.'{,.}*',GLOB_BRACE);
	if(is_array($files)){
		foreach($files as $file) {
			if(in_array(substr($file,strrpos($file,DIRECTORY_SEPARATOR)+1),array('.','..'))){
				continue;
			}

			if(is_dir($file)) {
				cleanFolder($file);
				@rmdir($file);
			}
			else {
				@unlink($file);
			}
		}
	}
}

?>
