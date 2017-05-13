<?php

namespace Helilabs\HDH\CURD;

use Illuminate\Http\Request;

abstract Class CurdCreator implements CurdCreatorContract{
	
	public $args;

	public $model;

	public $request;

	public $interface = 'web';

	public function setArgs( $args ){
		$this->args = array_merge($this->defaultArgs(), $args );
		return $this;
	}

	public function setRequest( $request ){
		$this->request = $request;
		return $this;
	}

	public function setInterface( $interface ){
		$this->interface = $interface;
		return $this;
	}

	public function defaultArgs(){
		return [
			'action' => 'new',
			'id' => null,
		];
	}

	/**
	 * The Reason behind this is to make creater only creates and leave the job of finding for the proxy class "CurdFactory"
	 * @param Helilabs\HDH\AppBaseModel $model
	 */
	public function setModel( $model ){
		$this->model = $model;
		return $this;
	}

	abstract function doAction();
}