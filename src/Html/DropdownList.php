<?php

namespace Helilabs\HDH\Html;

Class DropdownList extends HtmlHelper{
    /**
     * Dropdown select name
     * @var string
     */
    public $name;

    /**
     * dropdown select value
     * @var string|integer
     */
    public $value;

    /**
     * dropdown options
     * @var array
     */
    public $items = [];

    /**
     * dropdownList html options
     * @var array
     */
    public $options = [];

    public function setName( $name ){
        $this->name = $name;
        return $this;
    }

    public function setValue( $value ){
        $this->value = $value;
        
        if(is_numeric( $this->value )){
			$this->value = (int)$this->value;
		}

        return $this;
    }
    
    /**
     * set select options
     */
    public function setItems( $items ){
        $this->items = $items;
        return $this;
    }

    /**
     * set Html Options to be added to select
     */
    public function setOptions( $options ){
        $this->options = $options;
        return $this;
    }
    
    public function build(){

		echo '<select name="'.$this->name.'" '.$this->generateAttrs($this->options).'>';
		
        if( isset( $this->options['prompt'] ) ){
			echo '<option value="">'.$this->options['prompt'].'</option>';
		}elseif( isset( $this->options['empty_field']) && $this->options['empty_field']){
			echo '<option value=""></option>';
		}

        $this->generateOptions( $this->items );
		
		echo '</select>';
	}

    public function generateOptions( $items ){
        foreach( $items as $key=>$option ){

            if( is_array( $option ) ){
                
                echo '<optgroup label="'.$key.'">';
                
                $this->generateOptions( $option );
                
                echo '</optgroup>';

                continue;
            }
			
			$selected = '';
			if( ( is_array($this->value) && in_array($key,$this->value) ) ||  $this->value === $key ){
				$selected = 'selected';
			}

			echo '<option value="'.$key.'" '.$selected.' >'.$option.'</option>';
		}
    }

    public static function getInstance(){
        if( !is_null( self::$instance ) ){
            return self::$instance;
        }

        self::$instance = new self();

        return self::$instance;
    }

}