<?php

namespace Helilabs\Capital\Repository;

use Illuminate\Database\Eloquent\Model;

Class BaseRepository implements RepositoryContract{

	public $query;

	public function __construct( Model $model ){
		$this->query = $model;
	}

	/**
	 *
	 * @param  string $method the method that was called
	 * @param  string $args   arguments of the functions
	 * @return Helilabs\Capital\BaseRepository | Illuminate\Database\Eloquent\Collection   BaseRepository instance or Result of the query
	 */
	public function __call( $method, $args ){


		// methods that are used to fetch the results of the query not for building
		$fetchMethods = collect([
			'first', 'get', 'paginate', 'count', 'min', 'max', 'find', 'findOrFail','toSql','pluck'
		]);


		if( $fetchMethods->contains( $method ) ){
			return $this->query->{$method}(...$args);
		}

		if( $method == 'where' ){
			$this->query->{$method}(...$args);
		}

		return $this;
	}

	/**
	 * to recreate a new Object from the current Repository
	 * @return new static
	 */
	public function refresh(){
		return new static;
	}

	/**
	 * Excute callback when the condition is true
	 * This function was kept because here you want to excute Repository Functions not the query functions
	 * @param  boolean $condition condition to check before excuting the callback the callback must return the query
	 * @param  lambda $callback  a callback anonymous function to excute when condition is true
	 * @return Repository            $this
	 */
	public function when( $condition, $callback ){
		if( !$condition ){
			return $this;
		}

		$callback( $this );
		return $this;
	}

	/**
	 * Get All Models and arrange them in an array of key=>$value
	 * @return Helilabs\Capital\BaseRepository
	 */
	public function getAllModels( $value = 'name', $key = 'id' ){
		return $this->query->pluck( $value, $key );
	}

}