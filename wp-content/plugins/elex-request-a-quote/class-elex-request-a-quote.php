<?php
/**
 *
 * Main File.
 *
 * @package Elex Request a Quote
 */

/*
Plugin Name:          ELEX WooCommerce Request a Quote - Basic
Plugin URI:           https://elextensions.com/plugin/
Description:          Create Request a Quote option for your WooCommerce products. You can also create and customize request a quote forms to be displayed on the frontend. The plugin will also send automated email notifications for quote submissions, approvals, and rejections.
Version:              2.3.3
WC requires at least: 2.6.0
WC tested up to:      9.2
Author:               ELEXtensions
Author URI:           https://elextensions.com/
Developer:            ELEXtensions
Developer URI:        https://elextensions.com/
License:              GPLv2
Text Domain:          elex-request-a-quote
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$activated_plugins = (array) get_option( 'active_plugins' );
if ( is_multisite() ) {
	$activated_plugins = array_merge( $activated_plugins, get_site_option( 'active_sitewide_plugins' ), array() );
}
if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once  ABSPATH . 'wp-admin/includes/plugin.php' ;

}

// for Required functions
if ( ! function_exists( 'elex_raq_basic_is_woocommerce_active' ) ) {
	require_once  'elex-includes/elex-raq-basic-functions.php' ;
}
// to check woocommerce is active
if ( ! ( elex_raq_basic_is_woocommerce_active() ) ) {
	add_action( 'admin_notices', 'raq_woocommerce_activation_notice' );
	return;
}

function raq_woocommerce_activation_notice() {  ?>
	<div id="message" class="error">
		<p>
			<?php echo( esc_attr_e( 'WooCommerce plugin must be active for ELEX Request a Quote Plugin to work.', 'elex-request-a-quote' ) ); ?>
		</p>
	</div>
	<?php
}

/**
	 * To add settings url near plugin under installed plugin.
	 *
	 * @param array $links Array of Links.
	 */
function elex_request_a_quote_plugin_basic_action_links( $links ) {
	$plugin_links = array(
		'<a href="' . admin_url( 'admin.php?page=elex_raq_settings&tab=general' ) . '">' . __( 'Settings', 'elex-request-a-quote' ) . '</a>',
		'<a href="https://elextensions.com/knowledge-base/how-to-set-up-elex-woocommerce-request-a-quote-plugin/" target="_blank">' . __( 'Documentation', 'elex-request-a-quote' ) . '</a>',
		'<a href="https://wordpress.org/support/plugin/elex-request-a-quote/" target="_blank">' . __( 'Support', 'elex-request-a-quote' ) . '</a>',
	);
	return array_merge( $plugin_links, $links );
}

if ( defined( 'ELEX_REQUEST_QUOTE_MAIL_URL' ) ) {
	//check if premium version version is there

	$elex_raq_plugins   = array(
		'elex_request_a_quote_premium/class-elex-request-a-quote-premium.php' => esc_html__( 'The Premium version of ELEX Request a Quote is already installed and activated. Please deactivate it in order to activate the basic version. If you encounter any issues, kindly contact our ', 'elex-request-a-quote' ),
		'woomp-request-a-quote-for-woocommerce/class-elex-request-a-quote-premium.php' => esc_html__( 'The WooCommerce version of ELEX Request a Quote is already installed and activated. Please deactivate it in order to activate the basic version. If you encounter any issues, kindly contact our ', 'elex-request-a-quote' ),
	);
	$message            = esc_html__( 'for assistance.' , 'elex-request-a-quote' );
	$current_raq_plugin = plugin_basename( __FILE__ );
	foreach ( $elex_raq_plugins as $raq_plugin => $error_msg ) {

		if ( $current_raq_plugin === $raq_plugin ) {
			continue;

		}
		if ( array_key_exists( $raq_plugin, $activated_plugins ) ) {
			wp_die( wp_kses_post( $error_msg ) . "<a target='_blank' href='https://wordpress.org/support/plugin/elex-request-a-quote/'>" . esc_html__( 'Support ', 'elex-request-a-quote' ) . '</a>' . esc_html( $message ), '', array( 'back_link' => 1 ) );
		}

		if ( in_array( $raq_plugin, $activated_plugins ) ) {
			wp_die( wp_kses_post( $error_msg ) . "<a target='_blank' href='https://wordpress.org/support/plugin/elex-request-a-quote/'>" . esc_html__( 'Support ', 'elex-request-a-quote' ) . '</a>' . esc_html( $message ), '', array( 'back_link' => 1 ) );
		
		}
	}
} else {
	define( 'ELEX_RAQ_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
	define( 'ELEX_RAQ_VIEW_PATH', ELEX_RAQ_PLUGIN_PATH . 'view/' );
	define( 'ELEX_RAQ_SRC_PATH', ELEX_RAQ_PLUGIN_PATH . 'src/' );
	if ( ! defined( 'ELEX_REQUEST_QUOTE_MAIL_URL' ) ) {
	define( 'ELEX_REQUEST_QUOTE_MAIL_URL', plugin_dir_url( __FILE__ ) );
	}
	if ( ! defined( 'ELEX_RAQ_IMAGES' ) ) {
	define( 'ELEX_RAQ_IMAGES', ELEX_REQUEST_QUOTE_MAIL_URL . 'assets/images/' );
	}
	if (! defined('ELEX_REQUEST_A_QUOTE')) {
	define( 'ELEX_REQUEST_A_QUOTE', dirname( __FILE__ ) );
	}
	
	require_once __DIR__ . '/vendor/autoload.php';
	require ELEX_RAQ_SRC_PATH . 'wp-fluent/autoload.php';

	$request_a_quote = new \Elex\RequestAQuote\RequestAQuote();

	$request_a_quote->with_basename( plugin_basename( __FILE__ ) );

	$request_a_quote->boot();
	register_activation_hook( __FILE__, array( $request_a_quote, 'migrate' ) );
	register_activation_hook( __FILE__, array( $request_a_quote, 'elex_quote_request_add_navigation_menu' ) );
	register_deactivation_hook( __FILE__, array( $request_a_quote, 'elex_quote_request_flush_data' ) );
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'elex_request_a_quote_plugin_basic_action_links' );

	// review component
	if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once  ABSPATH . 'wp-admin/includes/plugin.php';
	}
	include_once __DIR__ . '/review_and_troubleshoot_notify/review-and-troubleshoot-notify-class.php';
	$data                      = get_plugin_data( __FILE__ );
	$data['name']              = $data['Name'];
	$data['basename']          = plugin_basename( __FILE__ );
	$data['rating_url']        = 'https://elextensions.com/plugin/elex-woocommerce-request-a-quote-plugin-free/#reviews';
	$data['documentation_url'] = 'https://elextensions.com/knowledge-base/how-to-set-up-elex-woocommerce-request-a-quote-plugin/';
	$data['support_url']       = 'https://wordpress.org/support/plugin/elex-request-a-quote/';

	new \Elex_Review_Components( $data );
	function load_language_files() {
		load_plugin_textdomain( 'elex-request-a-quote', false, basename( dirname( __FILE__ ) ) . '/lang/' );
	}
	add_action( 'plugins_loaded', 'load_language_files' );


	// High performance order tables compatibility.
	add_action( 'before_woocommerce_init', function() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	} );
		


}
