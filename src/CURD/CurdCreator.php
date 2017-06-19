<?php

namespace Helilabs\Capital\CURD;

abstract Class CurdCreator implements CurdCreatorContract{
	
	public $args;

	public $model;

	public $request;

	public $interface = 'web';

	public function setArgs( $args ){
		$this->args = array_merge($this->defaultArgs(), $args );
		return $this;
	}

	/**
	 * set Request Date as Collection of Data
	 * @param Illuminate\Support\Collection $request request data
	 */
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
	 * @param Helilabs\Capital\AppBaseModel $model
	 */
	public function setModel( $model ){
		$this->model = $model;
		return $this;
	}

	public function getModel(){
		return $this->model;
	}

	abstract function doAction();
}