<?php

namespace Helilabs\Capital\Factory;

use Helilabs\Capital\Helpers\CallbackHandler;

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

	public function __construct(){
		$this->additionalArgs = collect([
				'action' => 'new',
				'id' => null
			]);

		$this->args = collect([]);
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
		$handler = $this->successHandler->passArguments([$this]);

		try{
			$this->theJob();
		}catch(\Exception $e){
			$handler = $this->failureHandler->passArguments([$this, $e]);
		}

		return $handler->handle();

	}

	public abstract function theJob();

}