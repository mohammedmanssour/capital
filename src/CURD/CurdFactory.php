<?php

namespace Helilabs\Capital\CURD;

use Illuminate\Support\Collection;
use Helilabs\Capital\Exceptions\JsonException;
use Helilabs\Capital\Repository\RepositoryContract;

abstract Class CurdFactory implements CurdFactoryContract{

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
	protected $args;

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
	protected $additionalArgs;

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
		return $model;
	}

	/**
	 * Main Args used with the factory
	 * @param Collection $args [description]
	 */
	public function setArgs( Collection $args ){
		$this->args = $args;
		return $this;
	}

	/**
	 * add Arg to the main arg
	 * @param string $key
	 * @param mixed $value
	 */
	public function addArg( $key, $value ){
		$this->arg->put($key, $value);
		return $this;
	}

	/**
	 * non-primary additional args used in the factory
	 * @param array $additionalArgs
	 */
	public function setAdditionalArgs( array $additionalArgs ){
		$this->additionalArgs->merge( $additionalArgs );
		return $this;
	}

	/**
	* add additional arg
	*/
	public function addAdditionalArg( $key, $value ){
		$this->additionalArgs->put( $key, $value );
	}



	public function setSuccessHandler( CallbackHandler $successHandler ){
		$this->successHandler = $successHandler;
		return $this;
	}

	public function setFailureHandler( CallbackHandler $failureHandler ){
		$this->failureHandler = $failureHandler;
		return $this;
	}


	public function doTheJob(){
		$handler = $this->successHandler;

		try{
			$this->theJob();
		}catch(\Exception $e){
			$handler = $this->failureHandler;
		}

		return $handler->passArguments([$this])->handle();

	}

	public abstract function theJob();

}