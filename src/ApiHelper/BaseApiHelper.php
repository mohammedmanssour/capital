<?php

namespace Helilabs\HDH\ApiHelper;

abstract class BaseApiHelper{

	public $message;
	public $models;

	public function arrangeOutput(){
		$output = [
            'code'=>0, 
            'message'=>$this->message,
            'data'=>[] 
        ];
		if( !count($this->models) ){
			return $output;
		}

		$output['code'] = 1;
		$output['message'] = 'success';
		foreach($this->models as $model){
			$output['data'][] = $this->arrangeModelOutput( $model );
		}

		return $output;
	}

	public function arrangeSingleOutput(){
		$output = [
            'code'=>0,
            'message'=>$this->message,
            'data'=>[]
        ];
        
		if( !$this->models ){
			return $output;
		}

		$output['code'] = 1;
		$output['message'] = 'success';
		$output['data'] = $this->arrangeModelOutput( $this->models );

		return $output;
	}

	public abstract function arrangeModelOutput( $model );

	public function setMessage( $message ){
		$this->message = $message;
		return $this;	
	}

	public function setModels( $models ){
		$this->models = $models;
		return $this;
	}

}