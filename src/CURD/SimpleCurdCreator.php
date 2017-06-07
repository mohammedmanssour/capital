<?php

namespace Helilabs\Capital\CURD;

/**
 * Use this simple classes for simple curd where you don't need complex things when creatings
 */
Class SimpleCurdCreator extends CurdCreator{

	public function doAction(){
		$this->model->fill( $this->request->only( $this->model->fillable ) );

		if( !$this->model->save() ){
			if( $this->interface == 'api' ){
				throw new \Exception( json_encode( $this->model->getErrorsAsArray() ) );
			}

			throw new \Exception( $this->model->getErrors() );
		}
	}

}