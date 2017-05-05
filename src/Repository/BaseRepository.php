<?php 
namespace Helilabs\HDH\Repository;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

Class BaseRepository implements RepositoryInterface{

	public $query;

	public function __construct(){
		
	}

	/**
	 * to recreate a new Object fron the current Controller
	 * @return new self
	 */
	public function flush(){
		$class = get_called_class();
		
		$newInstance = new $class;
		return $newInstance;
	}

	/**
	 * A Simple where statement
	 * @param  array  $where conditons as key => valye pairs
	 * @return $this        this
	 */
	public function where( array $where ){
		$this->query->where( $where );
		return $this;
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

	/**
	 * Excute callback when the condition is true
	 * @param  boolean $condition condition to check before excuting the callback the callback must return the query
	 * @param  lambda $callback  a callback anonymous function to excute when condition is true
	 * @return Repository            $this
	 */
	public function when( $condition, $callback ){
		if( !$condition ){
			return $this;
		}

		return $callback( $this->query );
	}

	/**
	 * Get With Pagination
	 * @param  integer $postsPerPage number of posts to show in pages
	 * @return mixed   models of the repository
	 */
	public function get( $postsPerPage = 20 ){
		$models = $this->query->paginate( $postsPerPage );
		return $models;
	}

	/**
	 * Get All Models of Repository
	 * @return mixed   models of the repository
	 */
	public function getAll(){
		return $this->query->get();
	}

	/**
	 * Get modeks of the reopository where condition
	 * @param  array   $where        condition to check
	 * @param  integer $postsPerPage number of posts to show
	 * @return mixed                [description]
	 */
	public function getWhere( array $where , $postsPerPage = 20){
		$models = $this->query->where( $where )->paginate( $postsPerPage );
		return $models;
	}

	/**
	 * Get One model by id
	 * @param  integer  $id  model id
	 * @return mixes       instance of model
	 */
	public function getOne( $id){
		return $this->query->where( 'id', $id )->first();
	}

	public function getOneWhere( array $where , $api = false ){
		$model = $this->query->where( $where )->first();
		return $model;
	}


	/**
	 * get first query result
	 */
	public function first(){
		return $this->query->first( );
	}

	/**
	 * Used for debug puroises
	 *
	 */
	public function toSql(){
		return $this->query->toSql();
	}

	/**
	 * Get All Models and arrange them in a category
	 * @return [type] [description]
	 */
	public static function getAllModels( $nameAttr = 'name' ){
		$class = get_called_class();
		$models = (new $class)->getAll();
		$m = [];
		if( $models ){
			foreach( $models as $model ){
				$m[$model->id] = $model->$nameAttr;
			}
		}
		return $m;
	}

}