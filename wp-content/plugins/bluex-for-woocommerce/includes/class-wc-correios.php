<?php

/**
 * Correios
 *
 * @package WooCommerce_Correios/Classes
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Plugins main class.
 */
class WC_Correios
{

	/**
	 * Initialize the plugin public actions.
	 */
	public static function init()
	{
		add_action('init', array(__CLASS__, 'load_plugin_textdomain'), -1);

		// Checks with WooCommerce is installed.
		if (class_exists('WC_Integration')) {
			self::includes();

			if (is_admin()) {
				self::admin_includes();
			}

			add_filter('woocommerce_integrations', array(__CLASS__, 'include_integrations'));
			add_filter('woocommerce_shipping_methods', array(__CLASS__, 'include_methods'));
			add_filter('woocommerce_email_classes', array(__CLASS__, 'include_emails'));
		} else {
			add_action('admin_notices', array(__CLASS__, 'woocommerce_missing_notice'));
		}
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public static function load_plugin_textdomain()
	{
		load_plugin_textdomain('woocommerce-correios', false, dirname(plugin_basename(WC_CORREIOS_PLUGIN_FILE)) . '/languages/');
	}

	/**
	 * Includes.
	 */
	private static function includes()
	{
		include_once dirname(__FILE__) . '/wc-correios-functions.php';
		include_once dirname(__FILE__) . '/class-wc-correios-install.php';
		include_once dirname(__FILE__) . '/class-wc-correios-package.php';
		include_once dirname(__FILE__) . '/class-wc-correios-webservice.php';
		include_once dirname(__FILE__) . '/class-wc-correios-webservice-international.php';
		include_once dirname(__FILE__) . '/class-wc-correios-autofill-addresses.php';
		include_once dirname(__FILE__) . '/class-wc-correios-tracking-history.php';
		include_once dirname(__FILE__) . '/class-wc-correios-rest-api.php';
		include_once dirname(__FILE__) . '/class-wc-correios-orders.php';
		include_once dirname(__FILE__) . '/class-wc-correios-cart.php';
		include_once dirname(__FILE__) . '/class-wc-correios-pudos-map.php';
		include_once dirname(__FILE__) . '/class-wc-correios-webhook.php';
		include_once dirname(__FILE__) . '/class-wc-correios-custom-order-status.php';
		// Districts
		include_once dirname(__FILE__) . '/districts/class-wc-districts.php';

		// Integration.
		include_once dirname(__FILE__) . '/integrations/class-wc-correios-integration.php';



		// Shipping methods.
		if (defined('WC_VERSION') && version_compare(WC_VERSION, '2.6.0', '>=')) {
			include_once dirname(__FILE__) . '/abstracts/class-wc-correios-shipping.php';
			include_once dirname(__FILE__) . '/abstracts/class-wc-correios-shipping-carta.php';
			include_once dirname(__FILE__) . '/abstracts/class-wc-correios-shipping-impresso.php';
			include_once dirname(__FILE__) . '/abstracts/class-wc-correios-shipping-international.php';
			foreach (glob(plugin_dir_path(__FILE__) . '/shipping/*.php') as $filename) {
				include_once $filename;
			}

			// Update settings to 3.0.0 when using WooCommerce 2.6.0.
			WC_Correios_Install::upgrade_300_fromWc260();
		} else {
			include_once dirname(__FILE__) . '/shipping/class-wc-correios-shipping-legacy.php';
		}

		// Update to 3.0.0.
		WC_Correios_Install::upgrade_300();
	}

	/**
	 * Admin includes.
	 */
	private static function admin_includes()
	{
		include_once dirname(__FILE__) . '/admin/class-wc-correios-admin-orders.php';
	}

	/**
	 * Include Correios integration to WooCommerce.
	 *
	 * @param  array $integrations Default integrations.
	 *
	 * @return array
	 */
	public static function include_integrations($integrations)
	{
		$integrations[] = 'WC_Correios_Integration';

		return $integrations;
	}

	/**
	 * Include Correios shipping methods to WooCommerce.
	 *
	 * @param  array $methods Default shipping methods.
	 *
	 * @return array
	 */
	public static function include_methods($methods)
	{
		// Legacy method.
		$methods['correios-legacy'] = 'WC_Correios_ShippingLegacy';

		// New methods.
		if (defined('WC_VERSION') && version_compare(WC_VERSION, '2.6.0', '>=')) {
			$methods['bluex-ex']					= 'WC_BlueX_EX';
			$methods['bluex-py']					= 'WC_BlueX_PY';
			$methods['bluex-md']					= 'WC_BlueX_MD';
			$old_options = get_option('woocommerce_correios_settings');
			if (empty($old_options)) {
				unset($methods['correios-legacy']);
			}
		}

		return $methods;
	}

	/**
	 * Include emails.
	 *
	 * @param  array $emails Default emails.
	 *
	 * @return array
	 */
	public static function include_emails($emails)
	{
		if (!isset($emails['WC_Correios_TrackingEmail'])) {
			$emails['WC_Correios_TrackingEmail'] = include dirname(__FILE__) . '/emails/class-wc-correios-tracking-email.php';
		}

		return $emails;
	}

	/**
	 * WooCommerce fallback notice.
	 */
	public static function woocommerce_missing_notice()
	{
		include_once dirname(__FILE__) . '/admin/views/html-admin-missing-dependencies.php';
	}

	/**
	 * Get main file.
	 *
	 * @return string
	 */
	public static function get_main_file()
	{
		return WC_CORREIOS_PLUGIN_FILE;
	}

	/**
	 * Get plugin path.
	 *
	 * @return string
	 */
	public static function get_plugin_path()
	{
		return plugin_dir_path(WC_CORREIOS_PLUGIN_FILE);
	}

	/**
	 * Get templates path.
	 *
	 * @return string
	 */
	public static function get_templates_path()
	{
		return self::get_plugin_path() . 'templates/';
	}
}
