<?php

namespace Helilabs\Capital\Helpers;

use Illuminate\Translation\Translator;

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

	public function toHtmlUlList( $preTextNotice = '' ){
		if( !$this->errors ){
			return '';
		}

		$html = '<ul>';
		foreach($this->errors->all() as $err){
			$html .= '<li> '.$err.' </li>';
		}
		$html .= '</ul>';
		return $html;
	}

	public function toSimpleArray(){
		return $this->errors->all();
	}

}