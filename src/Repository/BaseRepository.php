<?php 
namespace Helilabs\Capital\Repository;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

Class BaseRepository implements RepositoryContract{

	public $query;

	/**
	 * if you want to use more complicated query other than customizations added to simple query enable this variable
	 * @var boolean
	 */
	public $customizaQuery = false;

	/**
	 * Because Eleqount has alot of methods that we can't override here
	 * we made this magic function to excute Eleqount methods on Repository with affecting on $query
	 * @param  string $method the method that was called 
	 * @param  string $args   arguments of the functions
	 * @return Helilabs\Capital\BaseRepository    this
	 */
	public function __call( $method, $args ){

		/**
		 * if customize Query isn't enable then add simpleQuery
		 */
		if( !$this->customizaQuery ){
			$this->simpleQuery();
		}

		// methods that are used to fetch the results of the query not for building
		$fetchMethods = collect([
			'first', 'get', 'paginate', 'count', 'min', 'max', 'find', 'findOrFail'
		]);

		if( $fetchMethods->contains( $method ) ){
			return $this->query->{$method}(...$args);
		}

		$this->query->{$method}(...$args);
		return $this;
	}

	public function enableCustomization(){
		$this->customizaQuery = true;
		return $this;
	}

	/**
	 * A constrains you add to the query every time you use it
	 * @return $this
	 */
	public function simpleQuery(){
		return $this;
	}

	/**
	 * to recreate a new Object fron the current Controller
	 * @return new self
	 */
	public function refresh(){
		$class = get_called_class();
		
		$newInstance = new $class;
		return $newInstance;
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

		return $callback( $this );
	}

	/**
	 * Get All Models and arrange them in an array of key=>$value
	 * @return Helilabs\Capital\BaseRepository
	 */
	public function getAllModels( $value = 'name', $key = 'id' ){
		return $this->query->pluck( $value, $key );
	}


	/**
	 * attach user condition to the query
	 * @param  AuthenticatableContract $user      user to compare with
	 * @param  string                  $fieldName user_id column name in the database
	 */
	public function forUser( AuthenticatableContract $user, $fieldName = 'user_id' ){
		$this->query->where($fieldName, $user->id);
		return $this;
	}

}