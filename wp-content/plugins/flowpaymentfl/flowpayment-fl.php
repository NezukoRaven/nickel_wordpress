<?php
/**
 * Plugin Name: Flow Payment
 * Plugin URI: http://flow.cl
 * Version: 3.0.8
 * Author: Flow
 * Requires PHP: 7.1
 * Author URI: http://flow.cl
 * Description: Flow WooCommerce Checkout 
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define('PLUGIN_DIR', dirname(__FILE__).'/');
define( 'WC_FLOWPAYMENT_MAIN_FILE', __FILE__ );
define( 'WC_GATEWAY_FLOWPAYMENT_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'WC_FLOWPAYMENT_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );
include PLUGIN_DIR."lib/FlowApiV2.class.php";

/**
 * WC Flow Payment gateway plugin class.
 *
 * @class WC_Flow_Payments
 */
class WC_Flow_Payments {

	/**
	 * Plugin bootstrapping.
	 */
	public static function init() {

		add_action( 'plugins_loaded', array( __CLASS__, 'includes' ), 90 );

		add_filter( 'woocommerce_payment_gateways', array( __CLASS__, 'add_gateway' ) );

		add_action( 'woocommerce_blocks_loaded', array( __CLASS__, 'woocommerce_flow_gateway_block_support' ) );

		add_action( 'woocommerce_checkout_order_review', array( __CLASS__, 'woocommerce_flow_error_checkout' ) );

		add_filter( 'woocommerce_gateway_icon', function ( $icon, $id ) {
			if ( $id === 'flowpayment' ) {
				$newStyle = 'height: 36px;';
				$icon = str_replace('<img ', '<img style="' . $newStyle . '" ', $icon);
			}
			return $icon;
		  }, 10, 2 );

	}

	/**
	 * message error checkout classic view.
	 */
	public static function woocommerce_flow_error_checkout() {		
		if(isset($_GET['cancel_order']) && $_GET['cancel_order'] === "true" && $_GET['error_message'] && is_checkout()){
			wc_add_notice( __($_GET['error_message'], 'woocommerce-gateway-flowpayment'), 'error' );
		}
		
	}

	/**
	 * Add the Dummy Payment gateway to the list of available methods.
	 *
	 * @param array
	 */
	public static function add_gateway( $methods ) {
		$options = get_option( 'woocommerce_flowpayment_settings', array() );
		$methods[] = 'WC_Flow_Gateway';
		return $methods;
	}

	/**
	 * Plugin includes.
	 */
	public static function includes() {

		if ( class_exists( 'WC_Payment_Gateway' ) ) {
			require_once 'includes/class-wc-gateway-flowpayment.php';
		}
	}

	/**
	 * Plugin url.
	 *
	 * @return string
	 */
	public static function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Plugin url.
	 *
	 * @return string
	 */
	public static function plugin_abspath() {
		return trailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Registers WooCommerce Blocks integration.
	 *
	 */
	public static function woocommerce_flow_gateway_block_support() {
		if ( class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
			require_once 'includes/blocks/class-wc-flow-payments-blocks.php';
			add_action(
				'woocommerce_blocks_payment_method_type_registration',
				function( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
					$payment_method_registry->register( new WC_Gateway_Flow_Blocks_Support() );
				}
			);
		}
	}
}

WC_Flow_Payments::init();
