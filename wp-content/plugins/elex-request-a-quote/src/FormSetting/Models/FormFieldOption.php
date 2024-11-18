<?php
namespace Elex\RequestAQuote\FormSetting\Models;

use Elex\RequestAQuote\FormSetting\Models\FormSettings;
use Elex\RequestAQuote\FormSetting\Models\Fluent;


class FormFieldOption extends Fluent {

	public function value() {
		return $this->value ? $this->value : FormSettings::slug( $this->label );
	}

	public function label() {
		return $this->label;
	}
	public function setValue( $value ) {
		$this->value = $value ? $value : FormSettings::slug( $this->label );
	}

	public function setLabel( $label ) {
		$this->label = $label;
	}


	/**
	 * Build FormField statically
	 *
	 * @param string|array|self $value
	 * @param string $label
	 *
	 * @return self
	 */
	public static function build( $label, $value = null ) {


		if ( $label instanceof self ) {
			return $label;
		}

		if ( is_array( $label ) ) {
			return new self( $label );
		}

		$self = new self();
		$self->setLabel( $label );
		$self->setValue( $value );

		return $self;
	}
}
