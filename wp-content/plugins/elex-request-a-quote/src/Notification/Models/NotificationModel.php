<?php
namespace Elex\RequestAQuote\Notification\Models;

class NotificationModel {
	protected $data;

	const SETTINGS_KEY = 'request_a_quote_notification_settings';

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
			'general'     => array(
				'email_address' => array( get_bloginfo( 'admin_email' ) ),
				'order_status'  => array( 'quote-requested', 'quote-rejected', 'quote-approved' ),
				'debug_log'     => false,
			),
			'google_chat' => array(
				'enabled'     => false,
				'webhook_url' => '',
			),
			'sms'         => array(
				'enabled'             => false,
				'twillio_sid'         => '',
				'twillio_token'       => '',
				'twillio_mobile'      => '',
				'notification_mobile' => '',
			),
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

	public function get( $key ) {
		return $this->data[ $key ];
	}

	

	

	
}
