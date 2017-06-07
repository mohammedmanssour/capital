<?php

namespace Helilabs\Capital\Helpers;

/**
 * Most of Developers Don't like to work with arrays so we made this simple Class to
 * convert array into stdClass
 */
Class StdClass{
	static function __callStatic($method, $args)
    {
        return (new StdClassGenerator)->{$method}(...$args);
    }
}

Class StdClassGenerator{

	public $stdClass;

	public function __construct(){
		$this->stdClass = new \StdClass;
	}

	public function fromArray( $sourceData ){

		if( !$sourceData ){
			return $this;
		}

		foreach( $sourceData as $key => $value ){
			if( is_numeric($key) ){
				$key .= 'attr-'.$key;
			}

			if( is_array( $value ) ){
				$value = StdClass::fromArray( $value )->generate();
			}

			$this->stdClass->{$key} = $value;
		}

		return $this;
	}

	public function addAttribute( $key, $value ){
		$this->stdClass->{$key} = $value;
		return $this;
	}

	public function generate(){
		return $this->stdClass;
	}
}