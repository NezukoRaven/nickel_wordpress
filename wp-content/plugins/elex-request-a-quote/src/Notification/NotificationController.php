<?php
namespace Elex\RequestAQuote\Notification;

use Elex\RequestAQuote\Notification\Models\NotificationModel;
use Illuminate\Support\Facades\Notification;

use Elex\RequestAQuote\TemplateSetting\Models\TemplateModel;
use Elex\RequestAQuote\TemplateSetting\TemplateController;
use Elex\RequestAQuote\Settings\SettingsController;
use Elex\RequestAQuote\Quotelist\Models\QuoteListItems;

class NotificationController {

	public static $settings = null;

	public static function init() {
		add_action( 'req_settings_tab_notification', array( self::class, 'load_notification' ) );
		add_action( 'req_settings_tab_notification_general', array( self::class, 'load_notification_general' ) );
		add_action( 'req_settings_tab_notification_googlechat', array( self::class, 'load_notification_google_chat' ) );
		add_action( 'req_settings_tab_notification_sms', array( self::class, 'load_notification_sms' ) );

		add_filter( 'settings_saving_notification_general', array( self::class, 'save_general' ) );
		add_filter( 'settings_saving_notification_googlechat', array( self::class, 'save_google_chat' ) );
		add_filter( 'settings_saving_notification_sms', array( self::class, 'save_sms' ) );

		add_action( 'woocommerce_order_status_quote-requested', array( self::class, 'send_email_to_admin' ) );
		add_action( 'woocommerce_order_status_quote-requested', array( self::class, 'send_email_customer' ) );
		add_action( 'woocommerce_order_status_quote-rejected', array( self::class, 'send_email_customer' ) );
		add_action( 'woocommerce_order_status_quote-approved', array( self::class, 'send_email_customer' ) );
		add_action( 'init', array( self::class, 'elex_data_from_contact_form' ) );

		add_action( 'woocommerce_order_status_quote-requested', array( self::class, 'google_chat_notifications' ) );
		add_action( 'woocommerce_order_status_quote-requested', array( self::class, 'sms_notifications' ) );
	
	}


	public static function elex_data_from_contact_form() {

		$settings = SettingsController::get_settings();

		$rest_api = $settings['rest_api']['enabled'];
		$api_key  = $settings['rest_api']['api_key'];

		if ( !isset( $rest_api ) || !isset( $api_key ) || '' === $api_key ) {
			return;
		}
		add_action( 'wpcf7_before_send_mail', 'elex_raq_submit_quote_with_api' );
	}
	
	/**
	 * Function to send the email notification to admin when theere is Quote Request from the Customer
	 * and also store the email logs based if the debug mode is enabled
	 *
	 * @param [int] $order_id
	 * @return void
	 */
	public static function send_email_to_admin( $order_id ) {
	
		$order      = new \WC_Order( $order_id );
		$quote_data = $order->get_meta('elex_quote_data' );
		if (empty($quote_data)) {
			return;
		}
		
		$settings      = self::get_notification_settings();
		$email_address = ! empty( $settings['general']['email_address'] ) ? $settings['general']['email_address'] : get_bloginfo( 'admin_email' );
		$email_body    = TemplateModel::get_email_body( $order_id );
		$subject       = TemplateModel::get_subject( 'sent_to_admin' , 'quote_requested_email_template' );
		$subject       = str_replace( TemplateModel::get_literals(), array_values( TemplateModel::get_order_info( $order_id ) ), $subject );

		$headers       = array();
		$headers[]     = 'Content-Type: text/html; charset=UTF-8';
		$support_email = get_bloginfo( 'admin_email' );
		$headers[]     = 'From:<' . $support_email . '>';

		wp_mail( $email_address , $subject , $email_body , $headers , '' );
		
		if ( ( true !== $settings['general']['debug_log'] ) || '' == $settings['general']['debug_log'] ) {
			return;
		}
			// Save data in woocommerce logs.
		$log      = wc_get_logger();
		$head     = "<------------------- Request a Quote Email Log ------------------->\n";
		$body     = array(
			'recepients' => $email_address,
			'subject'    => $subject,
			'message'    => $email_body,
		);
		$log_text = $head . print_r( (object) $body, true );
		$context  = array( 'source' => 'elex_request_a_quote_email_log' );
		$log->log( 'debug', $log_text, $context );

	}

	/**
	 * Function to send the SMS notification to admin when theere is Quote Request from the Customer
	 * and also store the email logs based if the debug mode is enabled
	 *
	 * @param [int] $order_id
	 * @return void
	 */
	public static function sms_notifications( $order_id ) {

		$settings = self::get_notification_settings();
		if ( ( true !== $settings['sms']['enabled'] ) || 
			empty( $settings['sms']['twillio_sid'] ) ||
			empty( $settings['sms']['twillio_token'] ) ||
			empty( $settings['sms']['twillio_mobile'] ) ||
			empty( $settings['sms']['notification_mobile'] )
			) {

			return;
		}
		$message = TemplateModel::get_sms_chat_body( $order_id );
		// Send SMS.
		$params = array(
			'method'      => 'POST',
			'timeout'     => 45,
			'httpversion' => '1.0',
			'redirection' => 5,  // added.
			'blocking'    => true,
			'sslverify'   => false,
			'headers'     => array(
				'Content-Type'  => 'application/x-www-form-urlencoded',
				'Authorization' => 'Basic ' . base64_encode( "{$settings['sms']['twillio_sid']}:{$settings['sms']['twillio_token']}" ),
			),
			'body'        => array(
				'To'   => '+' . $settings['sms']['notification_mobile'],
				'From' => '+' . $settings['sms']['twillio_mobile'],
				'Body' => $message,
			),
		);
		$url    = 'https://api.twilio.com/2010-04-01/Accounts/' . $settings['sms']['twillio_sid'] . '/Messages';
		
		$response = wp_remote_post( $url, $params );

		if ( ( true !== $settings['general']['debug_log'] ) || '' == $settings['general']['debug_log'] ) {
			return;
		}
		//save data in woocommerce logs
		$log      = wc_get_logger();
		$head     = "<------------------- Request a Quote SMS Log ------------------->\n";
		$body     = array(
			'body' => array(
				'To'   => '+' . $settings['sms']['notification_mobile'],
				'From' => '+' . $settings['sms']['twillio_mobile'],
				'Body' => $message,
			),
		);
		$log_text = $head . print_r( (object) $body, true );
		$context  = array( 'source' => 'elex_request_a_quote_sms_log' );
		$log->log( 'debug', $log_text, $context );



	}

	/**
	 * Function to get the stored notification setting.
	 *
	 * @return void
	 */
	public static function get_notification_settings() {

		$template_settings = self::get_settings();
		$settings          = self::get_settings();
		return  $settings->to_array();
	}


	/**
	 * Function to send the SMS notification to admin when theere is Quote Request from the Customer
	 *
	 * @param [int] $order_id
	 * @return void
	 */
	public static function google_chat_notifications( $order_id ) {

		$settings = self::get_notification_settings();
		if ( ( true !== $settings['google_chat']['enabled'] ) || empty( $settings['google_chat']['webhook_url'] ) ) {
			return;
		}
		$message = TemplateModel::get_sms_chat_body( $order_id );

		$response = wp_remote_post(
			$settings['google_chat']['webhook_url'],
			array(
				'method'      => 'POST',
				'timeout'     => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => array( 'Content-Type' => 'application/json' ),
				'body'        => wp_json_encode( array( 'text' => $message ) ),
			)
		);
	}

	/**
	 * Function to send the notification emails to the customer to let them quote about their quote status whether its approved , rejected.
	 * 
	 */
	public static function send_email_customer( $order_id ) {

		$order = new \WC_Order( $order_id );

		$quote_data = $order->get_meta('elex_quote_data' );
		if (empty($quote_data)) {
			return;
		}

		if ( empty( $order->get_billing_email() ) || null === $order->get_billing_email() ) {
			return;
		}
		$items      =array();
		$quote_data = $order->get_meta( 'elex_quote_data' );
		//update the quote data incase quote has been updated by the admin i,e if extra items were added by the admin.
		if (count($order->get_items()) !== count($quote_data['items'])) {
			foreach ($order->get_items() as $item_id => $item ) {
				$product      = $item->get_product();
				$variation_id = 0;
				$id           = $product->get_id();
				if ( $item->is_type('variable') ) {
					$variation_id = $item->get_variation_id();
				}
				$obj               = new \stdClass();
				$obj->product_id   = $product->get_id();
				$obj->quantity     = $item->get_quantity();
				$obj->variation_id = $variation_id;
					
				array_push($items, $obj);

			}
			
			$quote_list_obj = new QuoteListItems( $items , $quote_data['id'] );
			$quote_list_obj = $quote_list_obj->get_list();
			$order->update_meta_data( 'elex_quote_data' , $quote_list_obj);
			$order->save();
		}
		$order = new \WC_Order( $order_id );

		$email = $order->get_billing_email();

		$template_settings = TemplateController::get_settings();
		$settings          = self::get_settings();
		$settings          = $settings->to_array();

		$order_status = ! empty( $settings['general']['order_status'] ) ? $settings['general']['order_status'] : array() ;

		if ( ! in_array( $order->get_status() , $order_status ) ) {
			return;
		}


		$email_body    = TemplateModel::get_email_body_for_customer( $order_id );
		$key           = ( $order->has_status( 'quote-requested' ) ) ? 'quote_requested_email_template' : ( $order->has_status( 'quote-rejected' ) ? 'quote_rejected_email_template' : ( $order->has_status( 'quote-approved' ) ? 'quote_approved_email_template' : '' ) );
		$subject       = TemplateModel::get_subject( 'sent_to_customer' , $key );
		$subject       = str_replace( TemplateModel::get_literals(), array_values( TemplateModel::get_order_info( $order_id ) ), $subject );
		$headers       = array();
		$headers[]     = 'Content-Type: text/html; charset=UTF-8';
		$support_email = get_bloginfo( 'admin_email' );
		$headers[]     = 'From:<' . $support_email . '>';
		wp_mail( $email , $subject , $email_body , $headers , '' );

	}

	

	public static function load_notification_general() {

		$settings = self::get_settings();
		$settings = $settings->to_array();
		$settings = $settings['general'];

		include ELEX_RAQ_VIEW_PATH . 'notification_settings/general.php';
		SettingsController::show_saved_toast();

	}
	public static function load_notification_google_chat() {

		$settings = self::get_settings();
		$settings = $settings->to_array();
		$settings = $settings['google_chat'];

		SettingsController::show_saved_toast();
		include ELEX_RAQ_VIEW_PATH . 'notification_settings/google_chat.php';
		
	}
	public static function load_notification_sms() {
		$settings = self::get_settings();
		$settings = $settings->to_array();

		$settings = $settings['sms'];
		SettingsController::show_saved_toast();
		include ELEX_RAQ_VIEW_PATH . 'notification_settings/sms.php';
		
	}

	
	public static function get_settings( $reload = false ) {

		if ( self::$settings && false === $reload ) {
			return self::$settings;
		}

		
		$settings = NotificationModel::load();
		
		self::$settings = apply_filters( 'request_a_quote_settings', $settings );
		return  self::$settings;

	}


	public static function load_notification() {
		global $plugin_page;
		$sub_tabs      = self::get_menus();
		$active_subtab = self::get_active_subtab();
		$active_tab    = self::get_active_tab();
		$active_page   = $plugin_page;
		if ( isset( $_POST['submit'] ) ) {

				check_admin_referer( 'save_settings', 'req_settings_nonce' );

			
			$setting_options = NotificationModel::load();
			$setting_options = apply_filters( 'settings_saving_notification_' . self::get_active_subtab(), $setting_options );

			$setting_options->save();
		
		}
		include ELEX_RAQ_VIEW_PATH . 'notifications.php';
	}

	public static function save_general( NotificationModel $setting_options ) {

		check_admin_referer( 'save_settings', 'req_settings_nonce' );
		$new_settings = array();

		$email_address                            = isset( $_POST['email_address'] ) ? sanitize_text_field( $_POST['email_address'] ) : array();
		$new_settings['general']['email_address'] = explode( ',' , $email_address );
		$new_settings['general']['order_status']  = isset( $_POST['order_status'] ) ? map_deep( $_POST['order_status'] , 'sanitize_text_field' ) : array();
		$new_settings['general']['debug_log']     = isset( $_POST['debug_log'] ) ? sanitize_text_field( $_POST['debug_log'] ) : false;
		$_SESSION['saved_settings_data']          = true;
		return $setting_options->merge( $new_settings );
	}

	public static function save_google_chat( NotificationModel $setting_options ) {

		check_admin_referer( 'save_settings', 'req_settings_nonce' );


		$new_settings = array();

		$new_settings['google_chat']['enabled']     = isset( $_POST['enabled'] ) ? (bool) sanitize_text_field( $_POST['enabled'] ) : false;
		$new_settings['google_chat']['webhook_url'] = isset( $_POST['webhook_url'] ) ? sanitize_text_field( $_POST['webhook_url'] ) : '';
		$_SESSION['saved_settings_data']            = true;
		
		return $setting_options->merge( $new_settings );
		
		
	}
	public static function save_sms( NotificationModel $setting_options ) {
		check_admin_referer( 'save_settings', 'req_settings_nonce' );

		$new_settings = array();

		$new_settings['sms']['enabled']             = isset( $_POST['enabled'] ) ? (bool) sanitize_text_field( $_POST['enabled'] ) : false;
		$new_settings['sms']['twillio_sid']         = isset( $_POST['twillio_sid'] ) ? sanitize_text_field( $_POST['twillio_sid'] ) : '';
		$new_settings['sms']['twillio_token']       = isset( $_POST['twillio_token'] ) ? sanitize_text_field( $_POST['twillio_token'] ) : '';
		$new_settings['sms']['twillio_mobile']      = isset( $_POST['twillio_mobile'] ) ? sanitize_text_field( $_POST['twillio_mobile'] ) : false;
		$new_settings['sms']['notification_mobile'] = isset( $_POST['notification_mobile'] ) ? sanitize_text_field( $_POST['notification_mobile'] ) : false;
		$_SESSION['saved_settings_data']            = true;
		
		return $setting_options->merge( $new_settings );
	
	}

	public static function get_menus() {
		$submenus = array(
			array(
				'title' => __( 'General notification' ),
				'slug'  => 'general',
			),
			
			array(
				'title' => __( 'SMS Notification' ),
				'slug'  => 'sms',
		
			),
			array(
				'title' => __( 'Google Chat Notification' ),
				'slug'  => 'googlechat',
			),
		
		
		);
		return apply_filters( 'request_a_quote_notification_menus', $submenus );
		
	}
	public static function get_active_subtab() {
		
	   $tab = isset( $_GET['subtab'] ) ? sanitize_text_field( $_GET['subtab'] ) : '';
		return ! empty( $tab ) ? $tab : self::get_default_tab();

	}

	public static function get_active_tab() {
		
		if ( isset( $_POST['_wpnonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ) ) ) {
			return;
		}
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : '';
		 return ! empty( $tab ) ? $tab : 'notification';
 
	}
	
	public static function get_default_tab() {
		
		return apply_filters( 'request_a_quote_notification_settings_default_tab', 'general' );
	}
	
}
