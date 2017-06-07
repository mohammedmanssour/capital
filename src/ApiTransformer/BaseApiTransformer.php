<?php

namespace Helilabs\Capital\ApiTransformer;

abstract class BaseApiTransformer{

	/**
	 * message to show when the collection or resource is empty 
	 * @var string
	 */
	public $failureMessage;

	/**
	 * transformer Output
	 * @var array
	 */
	public $output;

	/**
	 * function to transform collection of models
	 * @param  Illuminate\support\collection $models collection of Illuminate\Database\Eloquent\Model
	 * @return Helilabs\Capital\ApiTransformer\BaseApiTransformer    $this
	 */
	public function collection( $models ){
		$this->output = [
            'code'=>0, 
            'message'=>$this->failureMessage,
            'data'=>[] 
        ];
		if( $models->isEmpty() ){
			return $this;
		}

		$this->output['code'] = 1;
		$this->output['message'] = 'success';
		$models->each(function($model){
			$this->output['data'][] = $this->transformer( $model );
		});

		return $this;
	}

	/**
	 * function to transform single model
	 * @param  Illuminate\Database\Eloquent\Model $model model to be transformed
	 * @return Helilabs\Capital\ApiTransformer\BaseApiTransformer $this
	 */
	public function resource( $model ){
		$this->output = [
            'code'=>0,
            'message'=>$this->failureMessage,
            'data'=>[]
        ];
        
		if( !$model ){
			return $this;
		}

		$this->output['code'] = 1;
		$this->output['message'] = 'success';
		$this->output['data'] = $this->transformer( $model );

		return $this;
	}

	public function setFailureMessage( $failureMessage ){
		$this->failureMessage = $failureMessage;
		return $this;
	}

	/**
	 * get the output
	 */
	public function transform(){
		return response()->json($this->output);
	}

	public abstract function transformer( $model );

}