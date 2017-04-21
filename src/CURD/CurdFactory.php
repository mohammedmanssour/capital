<?php
 
namespace Helilabs\HDH\CURD;

use Illuminate\Http\Request;

abstract Class CurdFactory implements CurdFactoryInterface{

	/**
	 * Interface User used to make the request
	 * @var string
	 */
	public $interface = 'web';
	/**
	 * Request User Send through interface
	 * @var Request
	 */
	public $request;

	/**
	 * Additional Args that controls the request
	 * default are 
	 * [
	 *		'action' => 'new', // determins whether to create a new Model or to find one other options are edit
	 *		'id' => null, // the id of the model you wanna find if action was set to edit
	 *	]
	 *	
	 * @var array
	 */
	public $args;

	/**
	 * Success Message
	 * @var string
	 */
	public $message = 'success';

	public function __construct(Request $request,$args = [])
	{
		$this->request = $request;
		$this->args = $args;
	}

	public function setInterface($interface){
		$this->interface = $interface;
		return $this;
	}

	public function setSuccessMessage( $message ){
		$this->message = $message;
		return $this;
	}

	public function setRequest( Request $request ){
		$this->request = $request;
		return $this;
	}

	public function setArgs( $args = array() ){
		$this->args = $args;
		return $this;
	}

	public abstract function doTheJob();

}