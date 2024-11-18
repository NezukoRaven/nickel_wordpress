<?php
namespace Elex\RequestAQuote\HelpAndSupport;

use Elex\RequestAQuote\HelpAndSupport\Browser;


class HelpAndSupportController {

	public static $settings = null;

	public static function init() {
		add_action( 'req_settings_tab_faqs', array( self::class, 'load_faqs' ) );
		add_action( 'req_settings_tab_ticket', array( self::class, 'load_raise_ticket' ) );
		add_action( 'admin_init', array( self::class, 'download_info' ) );
	}

	public static function load_faqs() {

		include ELEX_RAQ_VIEW_PATH . 'help_support/faqs.php';

	}

	public static function load_raise_ticket() {

		include ELEX_RAQ_VIEW_PATH . 'help_support/tickets.php';
		
	}


	public static function load_view() {

		global $plugin_page;
		$sub_tabs    = self::get_menus();
		$active_tab  = self::get_active_tab();
		$active_page = $plugin_page;

		include ELEX_RAQ_VIEW_PATH . 'help_support.php';
	}

	public static function get_menus() {

		$setting_menus = array(
			array(
				'title' => __( 'FAQs' , 'elex-request-a-quote' ),
				'slug'  => 'faqs',
			),
			
			array(
				'title' => __( 'Raise a Ticket' , 'elex-request-a-quote' ),
				'slug'  => 'ticket',
		
			),
		);
		return apply_filters( 'settings_tabs', $setting_menus );
		
	}
	public static function get_active_tab() {

		if ( isset( $_POST['_wpnonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ) ) ) {
			return;
		}
	   $tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : '';
		return ! empty( $tab ) ? $tab : self::get_default_tab();

	}



	public static function get_default_tab() {
		return apply_filters( 'request_a_quote_settings_default_tab', 'faqs' );
	}

	public static function display_output( $browser, $theme, $host, $WP_REMOTE_POST ) {
		global $wpdb;
		ob_start();
		$path = ELEX_RAQ_VIEW_PATH . 'output.php';

		$path = apply_filters( 'ssi_view_path_output', $path );
		include  $path ;
		return ob_get_clean();

	}
	public static function let_to_num( $v ) {
		$l   = substr( $v, -1 );
		$ret = substr( $v, 0, -1 );

		switch ( strtoupper( $l ) ) {
			case 'P': // fall-through
			case 'T': // fall-through
			case 'G': // fall-through
			case 'M': // fall-through
			case 'K': // fall-through
				$ret *= 1024;
				break;
			default:
				break;
		}

		return $ret;
	}
	public static function display() {
		
		include_once 'Browser.php';

		// $browser = new Browser();
		if ( get_bloginfo( 'version' ) < '3.4' ) {
			$theme_data = wp_get_theme( get_stylesheet_directory() . '/style.css' );
			$theme      = $theme_data['Name'] . ' ' . $theme_data['Version'];
		} else {
			$theme_data = wp_get_theme();
			$theme      = $theme_data->Name . ' ' . $theme_data->Version;
		}

		// Try to identify the hosting provider
		$host = false;
		if ( defined( 'WPE_APIKEY' ) ) {
			$host = 'WP Engine';
		} elseif ( defined( 'PAGELYBIN' ) ) {
			$host = 'Pagely';
		}

		$request['cmd'] = '_notify-validate';

		$params = array(
			'sslverify' => false,
			'timeout'   => 60,
			'body'      => $request,
		);

		$response = wp_remote_post( 'https://www.paypal.com/cgi-bin/webscr', $params );

		if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
			$WP_REMOTE_POST = 'wp_remote_post() works' . "\n";
		} else {
			$WP_REMOTE_POST = 'wp_remote_post() does not work' . "\n";
		}

		return self::display_output( Browser::class, $theme, $host, $WP_REMOTE_POST );
		
		
	}
	public  static function download_info() {

		
		
		if ( isset( $_POST['_wpnonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ) ) ) {
			return;
		}
		
		if ( ! isset( $_POST['system_info_nonce'] ) ) {
			return;
		}

		if ( isset( $_POST['action'] ) ) {
			if ( 'raq_download_system_info' !== sanitize_text_field( $_POST['action'] ) ) {
				return;
			}
		}

		if ( ! isset( $_POST['send-system-info-textarea-raq'] ) || empty( $_POST['send-system-info-textarea-raq'] ) ) {
			return;
		}

		header( 'Content-type: text/plain' );
		//Text file name marked with Unix timestamp
		header( 'Content-Disposition: attachment; filename=system_info_' . time() . '.txt' );
		echo ( wp_kses_post( $_POST['send-system-info-textarea-raq'] ) );
		die();
	}
}
