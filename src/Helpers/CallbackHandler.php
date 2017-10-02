<?php

namespace Helilabs\Capital\Helpers;

use Illuminate\Support\Collection;

Class CallbackHandler{

	/**
	 * Collection of callbacks to be executed
	 * @var Illuminate\Support\Collection
	 */
	protected $callbacks;

	/**
	 * Callable to be executed after done
	 * @var callable
	 */
	protected $doneCallback;

	/**
	 * $argument to pass to callbacks
	 * @var mixed
	 */
	protected $arguments = [];

	public function __construct(){
		$this->callbacks = collect([]);
	}

	public function getCallbacks(){
		return $this->callbacks;
	}

	/**
	 * register new callback to the collection callbacks
	 * @param  callable $callback
	 * @return $this
	 */
	public function registerCallback( $callback ){
		if( !is_callable( $callback ) ){
			return $this;
		}

		$this->callbacks->push( $callback );
		return $this;
	}

	public function registerDoneCallback( $callback ){
		if( !is_callable( $callback ) ){
			return $this;
		}

		$this->doneCallback = $callback;

		return $this;
	}


	/**
	 * arguments to be passed to callbacks
	 * @param  array  $arguments
	 * @return $this
	 */
	public function passArguments( array $arguments ){
		$this->arguments = $arguments;
		return $this;
	}

	public function handle( ){
		$this->callbacks->each(function( $callback ){
			$callback( ...$this->arguments );
		});

		$doneCallback = $this->doneCallback;
		if( !is_callable($doneCallback) ){
			return $this;
		}
		return $doneCallback( ...$this->arguments );
	}

}