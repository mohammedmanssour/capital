<?php

namespace Helilabs\HDH\CURD;

use Illuminate\Http\Request;

abstract Class CurdCreator{
	
	public $args;

	public $model;

	public $request;

	public $interface;

	public function __construct(Request $request, array $args = array(), $interface = 'web' ){

		$this->args = array_merge($this->defaultArgs(), $args );

		$this->request = $request;

		$this->interface = $interface;

		$this->doAction();
	}

	public function defaultArgs(){
		return [
			'action' => 'new',
			'id' => null,
		];
	}

	public function setInterface( $interface ){
		$this->interface = $interface;
		return $this;
	}

	abstract function doAction();

	public abstract function doTheRest();

}