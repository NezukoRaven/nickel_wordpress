<?php
namespace Elex\RequestAQuote\FormSetting\Models;

use Elex\RequestAQuote\FormSetting\Models\FormFieldOptions;
use Elex\RequestAQuote\FormSetting\Models\Fluent;

class FormField extends Fluent {
	const STATUS_INACTIVE = false;
	const STATUS_ACTIVE   = true;
	const TYPE_TEXT       = 'text';
	const TYPE_TEXTAREA   = 'textarea';
	const TYPE_CHECKBOX   = 'checkbox';
	const TYPE_RADIO      = 'radio';
	const TYPE_URL        = 'url';
	const TYPE_IMAGE      = 'image';
	const TYPE_NUMBER     = 'number';
	const TYPE_DATE       = 'date';
	const TYPE_COLOR      = 'color';
	const TYPE_EMAIL      = 'email';

	public function activate() {

		
		$this->mandatory = self::STATUS_ACTIVE;



		return $this;
	}

	public function deactivate() {
		$this->mandatory = self::STATUS_INACTIVE;

		return $this;
	}

	public function active() {

		return  self::STATUS_ACTIVE === $this->mandatory;
	}

	public function deactive() {
		return ! $this->active();
	}

	/**
	 * Check whether this field is mandatory or not
	 *
	 * @return bool
	 */
	public function mandatory( FormSettings $data ) {
		if ( array_key_exists( 'mandatory' , $data ) ) {
			return $data['mandatory'];

		}
		return true;
	}

	public static function build( array $attributes ) {

		$options = array();
		if ( array_key_exists( 'options' , $attributes ) ) {
			$options = $attributes['options'];
		}

		$attributes = array_diff_key( $attributes, array_flip( [ 'options' ] ) );
		
		$self = new self( $attributes );
		foreach ( $options as $option ) {
			
			$self->add_option( FormFieldOption::build( $option ) );


		}

		return $self;
	}


	public function add_option( FormFieldOption $option ) {
		$options = isset( $this->attributes['options'] ) ? $this->attributes['options'] : array();


		$options[] = $option;

		$this->attributes['options'] = $options;

		return $this;
	}

	public function must_have_options() {
		return in_array( $this->type, array( self::TYPE_CHECKBOX, self::TYPE_RADIO ), true ) === true;
	}

	public function has_options() {
		return count( $this->options ) > 0;
	}


	public function to_array() {
		$data = parent::to_array();

		$options = isset( $data['options'] ) ? $data['options'] : array();

		$data['options'] = array();

		foreach ( $options as $option ) {
			$data['options'][] = $option->to_array();
		}

		return $data;
	}



}
