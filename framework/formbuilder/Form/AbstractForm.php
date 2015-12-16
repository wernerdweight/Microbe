<?php

namespace WernerDweight\Microbe\framework\formbuilder\Form;

use WernerDweight\Microbe\framework\formbuilder\Exception\InvalidConfigurationException;
use WernerDweight\Microbe\framework\formbuilder\Form\FormInterface;
use WernerDweight\Microbe\framework\validator\Validator;

abstract class AbstractForm implements FormInterface{

	protected $errors;
	protected $data;
	protected $fields;
	protected $entity;
	protected $validator;

	protected function setupFields($fields){
		if(!is_array($fields) || count($fields) <= 0){
			throw new InvalidConfigurationException('Form is empty! You must specify at least one form field!');
		}
		return $fields;
	}

	protected function loadDataFromEntity(){
		foreach ($this->fields as $field => $attributes) {
			if(false === in_array($attributes['type'],['separator','button'])){
				$this->data[$field] = $this->entity->{'get'.ucfirst($field)}();
			}
		}
	}

	public function __construct($fields,$entity){
		$this->entity = $entity;
		$this->fields = $this->setupFields($fields);
		$this->loadDataFromEntity();
		$this->validator = new Validator();
		$this->errors = [];
	}

	public function bindData(){
		foreach ($this->fields as $field => $attributes) {
			if($attributes['type'] !== 'separator'){
				if(true === in_array($attributes['type'],['checkbox','button'])){
					$this->data[$field] = isset($_POST['form'][$field]);
				}
				else{
					$this->data[$field] = $_POST['form'][$field];
				}

				if($attributes['type'] !== 'button'){
					if($attributes['type'] === 'repeatedPassword'){
						$this->entity->{'set'.ucfirst($field)}($this->data[$field]['password']);
					}
					else{
						$this->entity->{'set'.ucfirst($field)}($this->data[$field]);
					}
				}
			}
		}
		return $this;
	}

	public function isValid(){
		$errorCount = 0;

		foreach ($this->fields as $field => $attributes) {
			if(isset($attributes['constraints']) && count($attributes['constraints']) > 0){
				foreach ($attributes['constraints'] as $constraint => $options) {
					if($attributes['type'] === 'repeatedPassword' && $constraint === 'repeated'){
						$error = Validator::validate($this->data[$field],$constraint,$options);
					}
					else{
						$error = Validator::validate($this->entity->{'get'.ucfirst($field)}(),$constraint,$options);
					}
					if($error !== null){
						$this->fields[$field]['errors'][] = $error;
						$errorCount++;
					}
				}
			}
		}
		
		if($errorCount !== 0){
			return false;
		}
		else{
			return true;
		}
	}

	public function getData(){
		return $this->data;
	}

	public function getFields(){
		return $this->fields;
	}

}
