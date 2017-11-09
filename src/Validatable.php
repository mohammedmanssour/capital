<?php

namespace Helilabs\Capital;

use Helilabs\Capital\Helpers\CallbackHandler;

trait Validatable{

	/**
	 * Validator Instance;
	 * @var  Illuminate\Validation\Validator
	 */
	private $validator;

	/**
	 * whether to enable or disable validation
	 * default to tue
	 * @var boot
	 */
	private $validate;

	/**
	 * Validation Errors Container
	 * @var \Illuminate\Support\MessageBag
	 */
	private $errors;

	/**
	 * Excute things to do before save
	 * @var Helilabs\Capital\Helpers\CallbackHandler | null
	 */
	public $beforeSaveCallbackHandler = null;

	/**
	 * Excute things to do after save
	 * @var Helilabs\Capital\Helpers\CallbackHandler | null
	 */
	public $afterSaveCallbackHandler = null;

	public function __construct( array $attributes = [], $validator = null ){
		parent::__construct( $attributes );

		$this->validator = $validator ?? app()->make('validator');

	}

	/**
	 * Boot Validatable trait
	 */
	public static function bootValidatable()
    {
    	static::saving(function($model)
        {
            $result = $model->validate();

            if( $result && $this->beforeSaveCallback instanceof CallbackHandler ){
            	$this->beforeSaveCallback->passArguments( $model )->handle();
            }

            return $result;
        });

        static::saved(function($model){

        	if( $result && $this->afterSaveCallback instanceof CallbackHandler ){
            	$this->afterSaveCallback->passArguments( $model )->handle();
            }

        });
    }

    /**
     * disable validation
     * @return $this
     */
    public function disableValidation(){
    	$this->validate = false;
    	return $this;
    }

    /**
     * Validate model attributes based on rules
     * @return boolean validation passed or not
     */
    public function validate(){
    	$v = $this->validator->make( $this->model->getAttributes(), $this->rules(), $this->messages() );

    	if( $v->passes() ){
    		return true;
    	}

    	$this->errors = $v->errors();

    	return false;
    }

	/**
     * get Validation Errors if any.
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function errors(){
    	return $this->errors;
    }

    /**
     * if data is valid according to validation rules
     * @return boolean
     */
    public function isValid(){
    	return $this->errors->isEmpty();
    }

    /**
     * array of validation rules
     * @return array
     */
    public function rules(){
    	return [];
    }
    /**
     * array of validation messages
     * @return array
     */
    public function messages(){
    	return [];
    }

    /**
     * set before Save Callback;
     * @param Helilabs\Capital\Helpers\CallbackHandler $beforeSaveCallback
     */
    public function setBeforeSaveCallback( CallbackHandler $beforeSaveCallback ){
    	$this->beforeSaveCallbackHandler = $beforeSaveCallback;
    	return $this;
    }

    /**
     * set after save callback;
     * @param Helilabs\Capital\Helpers\CallbackHandler
     */
    public function setAfterSaveCallback( CallbackHandler $afterSaveCallback ){
    	$this->afterSaveCallbackHandler = $afterSaveCallback;
    	return $this;
    }

}

