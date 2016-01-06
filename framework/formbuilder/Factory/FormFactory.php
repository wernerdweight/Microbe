<?php

namespace WernerDweight\Microbe\framework\formbuilder\Factory;

use Symfony\Component\Yaml\Yaml;

use WernerDweight\Microbe\framework\formbuilder\Exception\MissingConfigurationException;
use WernerDweight\Microbe\framework\formbuilder\Exception\InvalidConfigurationException;
use WernerDweight\Microbe\framework\formbuilder\Form\GeneratedForm;

class FormFactory{

	public static function createForm($entity,$formName = null,$formParents = []){
		/// construct form configuration path
		if(null === $formName){
			$className = get_class($entity);
			$formName = strtolower(substr($className,strrpos($className,'\\') + 1));
		}
		$configurationFilePath = 'src/app/forms/'.str_replace(':',DIRECTORY_SEPARATOR,$formName).'.form.yml';

		/// chceck that configuration exists
		if(!is_file($configurationFilePath)){
			throw new MissingConfigurationException('Configuration for form "'.$formName.'" does not exist!');
		}

		/// try to load and parse the configuration
		try {
			$fields = Yaml::parse(file_get_contents($configurationFilePath));
		} catch (\Exception $e) {
			throw new InvalidConfigurationException('Configuration for form "'.$formName.'" is not a valid YML file!');
		}
		
		/// create and return form generated according to the configuration
		return new GeneratedForm($fields,$entity,$formParents);
	}

}
