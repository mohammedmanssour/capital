<?php

namespace Helilabs\Capital;

use Validator;
use Helilabs\Capital\Helpers\ErrorParser;

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

    private $validator;

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
	 * Allows you to validate whatever you want before saving
	 */
	public function setAttrs( $attrs ){
		$this->attrs = $attrs;
		return $this;
	}

    /**
     * Enhancing Save Method
     */
    public function save( array $options = array())
	{
		if( !$this->attrs ){
			$this->attrs = $this->getAttributes();
		}

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
		$this->validator = Validator::make($this->attrs,$this->rules());
		if ($this->validator->fails()) {
			$this->errors = $this->validator->messages();
			return false;
		}
		return true;
	}

	public function getValidator(){
		return $this->validator;
	}

    /**
     * getting Errors as Html 
     */
    public function getErrors(){
		return ErrorParser::parse( $this->errors )->toHtmlUlList();
	}

    /**
     * getteing Errors as Array
     */
    public function getErrorsAsArray(){
		return ErrorParser::parse( $this->errors )->toSimpleArray();
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