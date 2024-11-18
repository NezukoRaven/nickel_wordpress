<?php

/**
 * Correios Webhook.
 *
 * @package WooCommerce_Correios/Classes/webhook
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Correios webhook class
 */
class WC_Correios_Webhook
{



	/**
	 * Addresses webservice URL.
	 *
	 * @var string
	 */
	protected $_basePathUrl;
	protected $_configData;
	protected $_blueStatus;
	protected $_statusCheck;
	protected $_blueApikey;
	protected $_devMode;


	/**
	 * Constructor for the webhook class.
	 *
	 * Initializes the webhook by setting up the configuration and adding necessary actions based on the status check.
	 */
	public function __construct()
	{
		$this->setupConfig();
		add_action('init', array($this, 'init'));
	}
	/**
	 * Sets up the configuration from WooCommerce settings.
	 */
	private function setupConfig()
	{
		// Fetch configuration data from WooCommerce settings
		$this->_configData = get_option('woocommerce_correios-integration_settings');
		$this->_blueStatus = $this->_configData['noBlueStatus'] ?? 'wc-shipping-progress';
		$this->_statusCheck = $this->_configData['noBlueOnCreate'] === "yes";
		// Comprobación con isset para evitar el error:
		$this->_devMode = isset($this->_configData['devOptions']) && $this->_configData['devOptions'] === "yes";

		$this->_blueApikey = $this->_configData['tracking_bxkey'];
		// Decide the base path URL based on the devMode status
		if ($this->_devMode && !empty($this->_configData['alternativeBasePath'])) {
			$this->_basePathUrl = $this->_configData['alternativeBasePath'];
		} else {
			$this->_basePathUrl = 'https://apigw.bluex.cl';
		}
	}
	/**
	 * init configuration.
	 */
	public function init()
	{


		add_action('woocommerce_checkout_order_processed', array($this, 'send_on_create'), 10, 1);
		add_action('woocommerce_order_status_changed', array($this, 'order_status_change'), 10, 3);
	}

	/**
	 * Maps the WooCommerce order to the required format for the external service.
	 *
	 * @param int $order_id The order ID.
	 * @return string JSON encoded string representing the mapped order.
	 */
	public function map_order($order_id)
	{

		$order = wc_get_order($order_id);
		$order_data = $order->get_data();
		$shipping_lines = $order->get_items('shipping');
		$method_id = "";
		foreach ($shipping_lines as $shipping_line) {
			$shipping_data = $shipping_line->get_data();
			$method_id = $shipping_data['method_id'];
		}

		$product_ids = array();
		foreach ($order->get_items() as $item) {
			$productMetadata = $item->get_product();
			$product = $item->get_data();

			// Acceder a los atributos
			$productMetadata->attributes = $productMetadata->get_attributes();

			// Acceder a las dimensiones
			$productMetadata->dimensions = array(
				'length' => $productMetadata->get_length(),
				'width' => $productMetadata->get_width(),
				'height' => $productMetadata->get_height(),
			);

			// Acceder al peso
			$productMetadata->weight = $productMetadata->get_weight();
			$product['medatada'] = $productMetadata;

			$product_ids[] = $product;
		}
		$agencyId = $order->get_meta('agencyId');
		if ($agencyId) {
			$order_data['agencyId'] = $agencyId;
		}
		$order_data['shipping_lines'] = $method_id;
		$order_data['line_items'] = $product_ids;
		$order_data['seller'] = $this->_configData;
		$order_data['storeId'] = home_url() . '/';

		$order_json = json_encode([$order_data]);
		return $order_json;
	}
	/**
	 * Retrieves the current URL details.
	 * 
	 * This function extracts various components of the current URL, such as
	 * the HTTP/HTTPS method, home folder path, full URL, and domain name.
	 * It also considers different server configurations to determine if 
	 * the current connection is secure (HTTPS).
	 * 
	 * Additionally, this function utilizes a static memory cache to 
	 * store and return the parsed URL details, ensuring efficient 
	 * subsequent calls without re-parsing the URL.
	 * 
	 * @return array Associative array containing:
	 *               - 'method'    => HTTP/HTTPS method
	 *               - 'home_fold' => Relative path of the home directory
	 *               - 'url'       => Full current URL
	 *               - 'domain'    => Domain of the current URL
	 */
	public function get_url()
	{
		// Start memory cache
		static $parse_url;
		// Return cache
		if ($parse_url) {
			return $parse_url;
		}
		// Check is SSL
		$is_ssl = (
			(is_admin() && defined('FORCE_SSL_ADMIN') && FORCE_SSL_ADMIN === true)
			|| (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
			|| (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
			|| (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on')
			|| (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
			|| (isset($_SERVER['HTTP_X_FORWARDED_PORT']) && $_SERVER['HTTP_X_FORWARDED_PORT'] == 443)
			|| (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https')
		);
		// Get protocol HTTP or HTTPS
		$http = 'http' . ($is_ssl ? 's' : '');
		// Get domain
		$domain = preg_replace('%:/{3,}%i', '://', rtrim($http, '/') . '://' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''));
		$domain = rtrim($domain, '/');
		// Combine all and get full URL
		$url = preg_replace('%:/{3,}%i', '://', $domain . '/' . (isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI']) ? ltrim($_SERVER['REQUEST_URI'], '/') : ''));
		//Save to cache
		$parse_url = array(
			'method'    =>  $http,
			'home_fold' =>  str_replace($domain, '', home_url()),
			'url'       =>  $url,
			'domain'    =>  $domain,
		);
		// Return
		return $parse_url;
	}

	/**
	 * Sends the mapped order data to the external service.
	 *
	 * @param string $mappedOrder JSON encoded string of the mapped order.
	 */
	private function send_order($mapedOrder)
	{

		$url = $this->_basePathUrl . '/api/integr/woocommerce-wh/v1/order';
		$request_args = array(
			'method'  => 'POST',
			'headers' => array(
				'Content-Type' => 'application/json',
				'apikey' => $this->_blueApikey,
			),
			'body'        => $mapedOrder
		);
		$returnwebhook = wp_remote_post($url, $request_args);
		if (is_wp_error($returnwebhook)) {
			$this->send_log_if_error($returnwebhook->get_error_message(), $mapedOrder);
			return;
		}
	}
	public function send_log_if_error($error, $payload)
	{
		try {
			$dataError = array(
				'error' => $error,
				'order' => $payload
			);
			$logs = wp_remote_post($this->_basePathUrl . '/api/ecommerce/custom/logs/v1', array(
				'method'  => 'POST',
				'headers' => array(
					'Content-Type' => 'application/json',
					'apikey' =>  $this->_blueApikey
				),
				'body'        => json_encode($dataError)
			));
			if (is_wp_error($logs)) {
				throw new Exception('Error enviando mensaje a servicio de logs');
			}
		} catch (Exception $e) {
			return;
		}
	}

	/**
	 * Handles the order status change event.
	 *
	 * Sends the mapped order to the external service when the order status changes.
	 *
	 * @param int $order_id The order ID.
	 * @param string $old_status The old order status.
	 * @param string $new_status The new order status.
	 */
	public function order_status_change($order_id, $old_status, $new_status)
	{
		if ($this->_statusCheck) {
			return;
		}

		$formatedStatus = "wc-" . $new_status;
		if (($old_status != $new_status) && ($formatedStatus == $this->_blueStatus && $this->_statusCheck == false)) {
			$mapedOrder = $this->map_order($order_id);
			$this->send_order($mapedOrder);
		}
	}

	/**
	 * Sends the order when created.
	 *
	 * Invoked when an order is created and sends the mapped order to the external service.
	 *
	 * @param int $order_id The order ID.
	 */
	public function send_on_create($order_id)
	{
		if (!$this->_statusCheck) {
			return;
		}
		$mapedOrder = $this->map_order($order_id);
		$this->send_order($mapedOrder);
	}
}

new WC_Correios_Webhook();
