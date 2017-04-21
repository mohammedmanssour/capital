<?php 
namespace Helilabs\HDH\Repository;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract

class BaseRepository implements RepositoryInterface{

	public $query;

	public function __construct(){
		
	}

	public function get( $postsPerPage = 20 ){
		$models = $this->query->paginate( $postsPerPage );
		return $models;
	}

	public function getAll(){
		return $this->query->get();
	}

	public function getWhere( array $where , $postsPerPage = 20){
		$models = $this->query->where( $where )->paginate( $postsPerPage );
		return $models;
	}

	public function getOne( $id , $api = false){
		$model = $this->query->where( 'id', $id )->first();


		if(!$model){
			
			if($api){
				return false;
			}

			abort(404);
		}

		$this->_rule( $model );

		return $model;
	}

	public function getOneWhere( array $where , $api = false ){
		$model = $this->query->where( $where )->first();

		if(!$model){

			if($api){
				return false;
			}

			abort(404);
		}

		$this->_rule( $model );

		return $model;
	}

	public function where( array $where ){
		$this->query->where( $where );
		return $this;
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
	 * to recreate a new Object fron the current Controller
	 * @return new self
	 */
	public function flush(){
		$class = get_called_class();
		
		$newInstance = new $class;
		return $newInstance;
	}


	public function forUser( AuthenticatableContract $user ){
		$this->query->where('user_id', $user_id);
		return $this;
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