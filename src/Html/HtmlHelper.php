<?php

namespace Helilabs\HDH\Html;

Class HtmlHelper{

    public static $instance = null;

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