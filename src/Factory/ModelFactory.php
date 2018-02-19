<?php

namespace Helilabs\Capital\Factory;

use Helilabs\Capital\Helpers\CallbackHandler;
use Helilabs\Capital\Exceptions\JsonException;

abstract Class ModelFactory implements ModelFactoryContract{

	/**
	 * web or api
	 * @var string
	 */
	public $interface = 'web';

	/**
	 * Model that will be saved ( insert or update )
	 * @var Helilabs\Capital\AppBaseModel;
	 */
	protected $model;

	/**
	 * Main Args used by model to create
	 * @var Illuminate\Support\Collection
	 */
	public $args;

	/**
	 * Additional Args
	 * default are
	 * [
	 *		'action' => 'new', // determins whether to create a new Model or to find one other options are edit
	 *		'id' => null, // the id of the model you wanna find if action was set to edit
	 *	]
	 *
	 * @var Illuminate\Support\Collection
	 */
	public $additionalArgs;

	/**
	 * handler to handle things after success
	 * @var CallbackHandler
	 */
	protected $successHandler;

	/**
	 * handler to handle things after failure
	 * @var CallbackHandler
	 */
	protected $failureHandler;

	/**
	 * Validator Instance;
	 * @var  Illuminate\Validation\Factory
	 */
	private $validator;

	/**
	 * whether to enable or disable validation
	 * default to tue
	 * @var boot
	 */
	private $validate = true;

	/**
	 * Validation Errors Container
	 * @var \Illuminate\Support\MessageBag
	 */
	private $errors;

	public function __construct($validator = null){
		$this->additionalArgs = collect([
				'action' => 'new',
				'id' => null
			]);

		$this->args = collect([]);

		$this->validator = $validator ?? app()->make('validator');
	}


	public function setInterface($interface){
		$this->interface = $interface;
		return $this;
	}

	/**
	 * use this instead of findOrCreateModel to pass model from outsite Curd
	 * @param HeliLabs\Capital\AppBaseModel $model [description]
	 */
	public function setModel( $model ){
		$this->model = $model;
		return $this;
	}

	public function getModel(){
		return $this->model;
	}

	/**
	 * Main Args used with the factory
	 * @param array $args
	 */
	public function setArgs( array $args ){
		$this->args = $this->args->union( $args );
		return $this;
	}

	/**
	 * add Arg to the main arg
	 * has higher priority to keep args more than setArgs
	 * @param string $key
	 * @param mixed $value
	 */
	public function addArg( $key, $value ){
		$this->args->put($key, $value);
		return $this;
	}

	/**
	 * non-primary additional args used in the factory
	 * @param array $additionalArgs
	 */
	public function setAdditionalArgs( array $additionalArgs ){
		$this->additionalArgs = $this->additionalArgs->merge( $additionalArgs );
		return $this;
	}

	/**
	* add additional arg
	*/
	public function addAdditionalArg( $key, $value ){
		$this->additionalArgs->put( $key, $value );
		return $this;
	}


	/**
	 * set success handler
	 * @param Helilabs\Capital\Helpers\CallbackHandler $successHandler
	 */
	public function setSuccessHandler( CallbackHandler $successHandler ){
		$this->successHandler = $successHandler;
		return $this;
	}

	/**
	 * get success handler
	 * @return Helilabs\Capital\Helpers\CallbackHandler
	 */
	public function getSuccessHandler(){
		return $this->successHandler;
	}

	/**
	 * set failure handler
	 * @param Helilabs\Capital\Helpers\CallbackHandler $failureHandler
	 */
	public function setFailureHandler( CallbackHandler $failureHandler ){
		$this->failureHandler = $failureHandler;
		return $this;
	}

	/**
	 * get failure handler
	 * @return Helilabs\Capital\Helpers\CallbackHandler
	 */
	public function getFailureHandler(){
		return $this->failureHandler;
	}


	public function doTheJob(){

		//validation is considered a part of the creating/updaing process
		if( $this->validate && !$this->validate() ){
			return $this->failureHandler->passArguments([$this, new JsonException($this->errors())])->handle();
		}

		$handler = $this->successHandler->passArguments([$this]);

		try{
			$this->theJob();
		}catch(\Exception $e){
			$handler = $this->failureHandler->passArguments([$this, $e]);
		}

		return $handler->handle();

	}

	/**
	 * disable validation
	 * @return $this
	 */
	public function disableValidation()
	{
		$this->validate = false;
		return $this;
	}

	/**
	 * Validate model attributes based on rules
	 * @return boolean validation passed or not
	 */
	public function validate()
	{
		$v = $this->validator->make($this->args->all(), $this->rules(), $this->messages());

		if ($v->passes()) {
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
	public function errors()
	{
		return $this->errors;
	}

	/**
	 * if data is valid according to validation rules
	 * @return boolean
	 */
	public function isValid()
	{
		return $this->errors->isEmpty();
	}

	/**
	 * array of validation rules
	 * @return array
	 */
	public function rules()
	{
		return [];
	}
	/**
	 * array of validation messages
	 * @return array
	 */
	public function messages()
	{
		return [];
	}

	public abstract function theJob();

}