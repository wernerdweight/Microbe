<?php

namespace WernerDweight\Microbe\framework\formbuilder\Form;

use WernerDweight\Microbe\framework\formbuilder\Exception\InvalidConfigurationException;
use WernerDweight\Microbe\framework\formbuilder\Factory\FormFactory;
use WernerDweight\Microbe\framework\formbuilder\Form\FormInterface;
use WernerDweight\Microbe\framework\validator\Validator;

abstract class AbstractForm implements FormInterface{

	protected $data;
	protected $fields;
	protected $entity;
	protected $validator;
	protected $parents;
	protected $options;
	protected $embededForms;

	protected function setupFields($fields){
		if(!is_array($fields) || count($fields) <= 0){
			throw new InvalidConfigurationException('Form is empty! You must specify at least one form field!');
		}
		return $fields;
	}

	protected function setupEmbededForms(){
		foreach ($this->fields as $field => $attributes) {
			if($attributes['type'] === 'entity'){
				$this->embededForms[$field] = FormFactory::createForm($this->entity->{'get'.ucfirst($field)}(),$attributes['form'],array_merge($this->parents,[$field]));
			}
			else if($attributes['type'] === 'collection'){
				$collection = $this->entity->{'get'.ucfirst($field)}();
				if(null !== $collection && count($collection) > 0){
					foreach ($collection as $key => $item) {
						$this->embededForms[$field.'_'.intval($key)] = FormFactory::createForm($item,$attributes['form'],array_merge($this->parents,[$field.'_'.intval($key)]));
					}
				}
			}
		}
	}

	protected function setupChoiceOptions(){
		foreach ($this->fields as $field => $attributes) {
			if($attributes['type'] === 'choice' && true === isset($attributes['optionsCallback'])){
				$this->fields[$field]['options'] = $attributes['optionsCallback']['class']::loadOptions($field,$this->options);
			}
		}
	}

	protected function loadDataFromEntity(){
		foreach ($this->fields as $field => $attributes) {
			if(false === in_array($attributes['type'],['separator','void','button'])){
				$this->data[$field] = $this->entity->{'get'.ucfirst($field)}();
			}
		}
	}

	public function __construct($fields,$entity,$parents = [],$options = []){
		$this->validator = new Validator();
		
		$this->entity = $entity;
		$this->parents = $parents;
		$this->options = $options;

		$this->fields = $this->setupFields($fields);
		$this->loadDataFromEntity();
		$this->setupEmbededForms();
		$this->setupChoiceOptions();
	}

	public function bindData(){
		$basePostData = $_POST['form'];
		foreach ($this->parents as $parent) {
			$basePostData = $basePostData[$parent];
		}
		foreach ($this->fields as $field => $attributes) {
			if(false === in_array($attributes['type'],['separator','void'])){
				if(true === in_array($attributes['type'],['checkbox','button'])){
					$this->data[$field] = isset($basePostData[$field]);
				}
				else if(false === in_array($attributes['type'],['entity','collection'])){
					$this->data[$field] = $basePostData[$field];
				}

				if($attributes['type'] !== 'button'){
					if($attributes['type'] === 'entity'){
						$embededEntity = $this->embededForms[$field]->bindData()->getEntity();
						$this->entity->{'set'.ucfirst($field)}($embededEntity);
						$this->data[$field] = $embededEntity;
					}
					else if($attributes['type'] === 'collection'){
						$collection = $this->entity->{'get'.ucfirst($field)}();
						if(null !== $collection && count($collection) > 0){
							$embededCollection = [];
							foreach ($collection as $key => $item) {
								$embededCollection[$key] = $this->embededForms[$field.'_'.intval($key)]->bindData()->getEntity();
							}
							$this->entity->{'set'.ucfirst($field)}($embededCollection);
							$this->data[$field] = $embededCollection;
						}
					}
					else if($attributes['type'] === 'choice' && true === isset($attributes['optionsCallback'])){
						$this->entity->{'set'.ucfirst($field)}(isset($attributes['options'][$this->data[$field]]) ? $attributes['options'][$this->data[$field]] : null);
					}
					else if($attributes['type'] === 'repeatedPassword'){
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
					if($attributes['type'] === 'entity'){
						if($this->embededForms[$field]->isValid() !== true){
							$errorCount++;
						}
					}
					else if($attributes['type'] === 'collection'){
						$collection = $this->entity->{'get'.ucfirst($field)}();
						if(null !== $collection && count($collection) > 0){
							foreach ($collection as $key => $item) {
								if($this->embededForms[$field.'_'.intval($key)]->isValid() !== true){
									$errorCount++;
								}
							}
						}
					}
					else{
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

	public function getParents(){
		return $this->parents;
	}

	public function getEntity(){
		return $this->entity;
	}

	public function getEmbededForms(){
		return $this->embededForms;
	}

}
