<?php

namespace Helilabs\Capital\Helpers;

Class ErrorParser{
	public static function __callStatic($method, $args){
		return (new ErrorParserWrapper)->{$method}(...$args);
	}
}

Class ErrorParserWrapper{

	/**
	 * Error Bag to parse
	 */
	private $errors;

	public function parse( $errors ){
		$this->errors = $errors;
		return $this;
	}

	public function toHtmlUlList(){
		if( !$this->errors ){
			return '';
		}

		$html = '<p>'.trans('messages.fix_errors').'</p>';
		$html .= '<ul>';
		foreach($this->errors as $attrs_errors){
			if(empty($attrs_errors)){
				continue;
			}
			foreach($attrs_errors as $err){
				$html .= '<li> '.$err.' </li>';
			}
		}
		$html .= '</ul>';
		return $html;
	}

	public function toSimpleArray(){
		if( !$this->errors ){
			return []; 
		}

		$errors = [];
		foreach($this->errors as $attrs_errors){
			if(empty($attrs_errors)){
				continue;
			}
			foreach($attrs_errors as $err){
				$errors[] = $err;
			}
		}

		return $errors;
	}

}