<?php

namespace Helilabs\HDH\Html;

Class HtmlHelper{

    public static $instance = null;

    public static function getInstance(){
    	$class = get_called_class();
    	if( !is_null( $class::$instance ) ){
    		return $class::$instance;
    	}

    	$class::$instance = new $class;
    	return $class::$instance;
    }

	public function generateAttrs($attrs){
		$attrsString = '';

		if(is_array($attrs)){
			foreach($attrs as $key=>$value){
				if($key == 'prompt' ||  $key == 'empty_field'){
					continue;
				}
				$attrsString .= ' '.$key.'="'.$value.'"';
			}
		}
		return $attrsString;
	}

}