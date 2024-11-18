<?php

/**
 * Plugin Name:          BlueX for WooCommerce
 * Plugin URI:           https://bluex.cl/
 * Description:          Add Blue Express shipping methods to your WooCommerce store.
 * Author:               Blue Express
 * Author URI:           https://bluex.cl/
 * Version:              2.1.4
 * License:              GPLv2 or later
 * Text Domain:          woocommerce-bluex
 * Domain Path:          /languages
 * WC requires at least: 4.5
 * WC tested up to:      6.4.1
 *
 */

defined('ABSPATH') || exit;

define('WC_CORREIOS_VERSION', '2.1.4');
define('WC_CORREIOS_PLUGIN_FILE', __FILE__);
//HPOS compatibility
use \Automattic\WooCommerce\Utilities\FeaturesUtil;

add_action('before_woocommerce_init', function () {
	if (!class_exists(FeaturesUtil::class))
		return;
	FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
});
if (!class_exists('WC_Correios')) {
	include_once dirname(__FILE__) . '/includes/class-wc-correios.php';

	add_action('plugins_loaded', array('WC_Correios', 'init'));
}
