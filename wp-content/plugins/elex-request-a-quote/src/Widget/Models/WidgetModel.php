<?php
namespace Elex\RequestAQuote\Widget\Models;

class WidgetModel {
	protected $data;

	const SETTINGS_KEY = 'request_a_quote_widget_settings';

	public static function load() {
		$self = new self();

		$self->data = get_option( self::SETTINGS_KEY, self::get_default_values() );

		return $self;
	}

	public function save() {
		 update_option( self::SETTINGS_KEY , $this->data );
		return $this;
	}
   
	public static function get_default_values() {
	  
		$data = array(
			'show_widget_icon'         => false,
			'quote_list_icon_position' => 'float',
			'widget_color'             => '#0f0f0f',
			'show_button_label'        => false,
			'button_label'             => __( 'Quote List' , 'elex-request-a-quote' ),
			'show_list_popup_on_hover' => false,
		);
	  return $data;

	}
	public function merge( $new_options ) {
		$this->data = array_merge( $this->data, $new_options );
		return $this;
	}

	public  function to_array() {
		return $this->data;
	}

	
}
