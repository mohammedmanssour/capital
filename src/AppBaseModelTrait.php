<?php

namespace Helilabs\HDH;

use Validator;

trait AppBaseModelTrait{

    /**
     * repo to save attributes for the current model
     * @var array
     */
    protected $attrs;

    /**
     * validation rules errors
     * @var array
     */
    protected $errors;

    /**
     * Wheter to validate input accoding to rules or not
     * default to true
     * @var boolean 
     */
    public $validate = true;

    /**
     * List of Callbacks to be excuted beforeSaving and after the validation
     */
    protected $beforeSaveCallbacks = [];

    /**
     * rules to validate input accordingly
     */
    public function rules(){
		return [];
	}

    /**
     * Enhancing Save Method
     */
    public function save( array $options = array())
	{
		
		$this->attrs = $this->getAttributes();

		if($this->validate && !$this->validate()){
			return false;
		}
		
		$this->beforeSave();
		
		$success = parent::save( $options );
		
		/**
		 * if you want to run function before save user 
		 */

		return $success;
	}

    /**
     * validate input according to rules
     */
    public function validate(){
		$validator = Validator::make($this->attrs,$this->rules());
		if ($validator->fails()) {
			$this->errors = $validator->messages()->toArray();
			return false;
		}
		return true;
	}

    /**
     * getting Errors as Html 
     */
    public function getErrors(){
		if($this->errors){
			$html = '<p>'.trans('messages.fix_errors').'</p>';
			$html .= '<ul>';
			foreach($this->errors as $attrs_errors){
				if(empty($attrs_errors)){
					continue;
				}
				foreach($attrs_errors as $err){
					$html .= '<li> '.$err.' </li>';
				}
			}
			$html .= '</ul>';
			return $html;
		}
		return [];
	}

    /**
     * getteing Errors as Array
     */
    public function getErrorsAsArray(){
		$errors = [];
		foreach($this->errors as $attrs_errors){
			if(empty($attrs_errors)){
				continue;
			}
			foreach($attrs_errors as $err){
				$errors[] = $err;
			}
		}

		return $errors;
	}

    /**
     * function to excute after save
     */
	public function afterSave(){}

    /**
     * function to excute before save
     * if you want to add callbacks that run on every model extends this function
     */
	public function beforeSave(){
		if( !$this->beforeSaveCallbacks ){
			return;
		}

		foreach( $this->beforeSaveCallbacks as $callback ){
			$callback();
		}
	}

	/**
	 * Function used to add callbacks to this specific model
	 */
	public function addBeforeSaveCallback( $callback ){
		$this->beforeSaveCallbacks[] = $callback;
	}

}