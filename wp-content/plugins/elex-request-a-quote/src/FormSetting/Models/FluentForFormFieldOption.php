<?php 
namespace Elex\RequestAQuote\FormSetting\Models;

use Elex\RequestAQuote\FormSetting\Models\Fluent;

class FluentForFormFieldOptions {
	
	protected $attributes = array();

	public function __construct( array $attributes ) {   
		$this->attributes = $attributes;
	}
	

	public function __get( $key ) {
		return isset( $this->attributes[ $key ] ) ? $this->attributes[ $key ] : null;
	}

	public function __set( $key, $value ) {
		$this->attributes[ $key ] = $value;
	}

	public function to_array() {
		return $this->attributes;
	}
	

}
