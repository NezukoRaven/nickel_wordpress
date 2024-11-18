<?php

/**
 * Correios Webservice.
 *
 * @package WooCommerce_Correios/Classes/Webservice
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Correios Webservice integration class.
 */
class WC_Correios_Webservice
{


	/**
	 * Shipping method ID.
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 * Shipping zone instance ID.
	 *
	 * @var int
	 */
	protected $instance_id = 0;

	/**
	 * ID from Correios service.
	 *
	 * @var string|array
	 */
	protected $service = '';

	/**
	 * WooCommerce package containing the products.
	 *
	 * @var array
	 */
	protected $package = null;

	/**
	 * Origin postcode.
	 *
	 * @var string
	 */
	protected $origin_postcode = '';

	/**
	 * Destination postcode.
	 *
	 * @var string
	 */
	protected $destination_postcode = '';

	/**
	 * Login.
	 *
	 * @var string
	 */
	protected $login = '';

	/**
	 * Password.
	 *
	 * @var string
	 */
	protected $password = '';

	/**
	 * Package height.
	 *
	 * @var float
	 */
	protected $height = 0;

	/**
	 * Package width.
	 *
	 * @var float
	 */
	protected $width = 0;

	/**
	 * Package diameter.
	 *
	 * @var float
	 */
	protected $diameter = 0;

	/**
	 * Package length.
	 *
	 * @var float
	 */
	protected $length = 0;

	/**
	 * Package weight.
	 *
	 * @var float
	 */
	protected $weight = 0;

	/**
	 * Minimum height.
	 *
	 * @var float
	 */
	protected $minimum_height = 0.1;

	/**
	 * Minimum width.
	 *
	 * @var float
	 */
	protected $minimum_width = 0.1;

	/**
	 * Minimum length.
	 *
	 * @var float
	 */
	protected $minimum_length = 0.1;

	/**
	 * Extra weight.
	 *
	 * @var float
	 */
	protected $extra_weight = 0;

	/**
	 * Declared value.
	 *
	 * @var string
	 */
	protected $declared_value = '0';

	/**
	 * Own hands.
	 *
	 * @var string
	 */
	protected $own_hands = 'N';

	/**
	 * Receipt notice.
	 *
	 * @var string
	 */
	protected $receipt_notice = 'N';

	/**
	 * Package format.
	 *
	 * 1 – box/package
	 * 2 – roll/prism
	 * 3 - envelope
	 *
	 * @var string
	 */
	protected $format = '1';

	/**
	 * Debug mode.
	 *
	 * @var string
	 */
	protected $debug = 'no';

	/**
	 * Logger.
	 *
	 * @var WC_Logger
	 */
	protected $log = null;
	protected $_basePathUrl;
	protected $_configData;
	protected $_blueApikey;
	protected $_pudoEnabled;
	protected $_devMode;

	/**
	 * Initialize webservice.
	 *
	 * @param string $id Method ID.
	 * @param int    $instance_id Instance ID.
	 */
	public function __construct($id = 'correios', $instance_id = 0)
	{
		$this->id           = $id;
		$this->instance_id  = $instance_id;
		$this->log          = new WC_Logger();
		$this->setupConfig();
	}
	private function setupConfig()
	{
		// Fetch configuration data from WooCommerce settings
		$this->_configData = get_option('woocommerce_correios-integration_settings');
		$this->_blueApikey = $this->_configData['tracking_bxkey'];
		// Comprobación con isset para evitar el error:
		$this->_devMode = isset($this->_configData['devOptions']) &&  $this->_configData['devOptions'] === "yes";

		// Decide the base path URL based on the devMode status
		if ($this->_devMode && !empty($this->_configData['alternativeBasePath'])) {
			$this->_basePathUrl = $this->_configData['alternativeBasePath'];
		} else {
			$this->_basePathUrl = 'https://apigw.bluex.cl';
		}
		$this->_pudoEnabled = $this->_configData['pudoEnable'] ?? 'no';
	}

	/**
	 * Set the service
	 *
	 * @param string|array $service Service.
	 */
	public function set_service($service = '')
	{
		if (is_array($service)) {
			$this->service = implode(',', $service);
		} else {
			$this->service = $service;
		}
	}

	/**
	 * Set shipping package.
	 *
	 * @param array $package Shipping package.
	 */
	public function set_package($package = array())
	{
		$this->package = $package;
		$correios_package = new WC_Correios_Package($package);

		if (!is_null($correios_package)) {
			$data = $correios_package->get_data();

			$this->set_height($data['height']);
			$this->set_width($data['width']);
			$this->set_length($data['length']);
			$this->set_weight($data['weight']);
		}

		if ('yes' === $this->debug) {
			if (!empty($data)) {
				$data = array(
					'weight' => $this->get_weight(),
					'height' => $this->get_height(),
					'width'  => $this->get_width(),
					'length' => $this->get_length(),
				);
			}

			$this->log->add($this->id, 'Weight and cubage of the order: ' . print_r($data, true));
		}
	}

	/**
	 * Set origin postcode.
	 *
	 * @param string $postcode Origin postcode.
	 */
	public function set_origin_postcode($postcode = '')
	{
		$this->origin_postcode = $postcode;
	}

	/**
	 * Set destination postcode.
	 *
	 * @param string $postcode Destination postcode.
	 */
	public function set_destination_postcode($postcode = '')
	{
		$this->destination_postcode = $postcode;
	}

	/**
	 * Set login.
	 *
	 * @param string $login User login.
	 */
	public function set_login($login = '')
	{
		$this->login = $login;
	}

	/**
	 * Set password.
	 *
	 * @param string $password User login.
	 */
	public function set_password($password = '')
	{
		$this->password = $password;
	}

	/**
	 * Set shipping package height.
	 *
	 * @param float $height Package height.
	 */
	public function set_height($height = 0)
	{
		$this->height = (float) $height;
	}

	/**
	 * Set shipping package width.
	 *
	 * @param float $width Package width.
	 */
	public function set_width($width = 0)
	{
		$this->width = (float) $width;
	}

	/**
	 * Set shipping package diameter.
	 *
	 * @param float $diameter Package diameter.
	 */
	public function set_diameter($diameter = 0)
	{
		$this->diameter = (float) $diameter;
	}

	/**
	 * Set shipping package length.
	 *
	 * @param float $length Package length.
	 */
	public function set_length($length = 0)
	{
		$this->length = (float) $length;
	}

	/**
	 * Set shipping package weight.
	 *
	 * @param float $weight Package weight.
	 */
	public function set_weight($weight = 0)
	{
		$this->weight = (float) $weight;
	}

	/**
	 * Set minimum height.
	 *
	 * @param float $minimum_height Package minimum height.
	 */
	public function set_minimum_height($minimum_height = 1)
	{
		$this->minimum_height = 1 <= $minimum_height ? $minimum_height : 1;
	}

	/**
	 * Set minimum width.
	 *
	 * @param float $minimum_width Package minimum width.
	 */
	public function set_minimum_width($minimum_width = 1)
	{
		$this->minimum_width = 1 <= $minimum_width ? $minimum_width : 1;
	}

	/**
	 * Set minimum length.
	 *
	 * @param float $minimum_length Package minimum length.
	 */
	public function set_minimum_length($minimum_length = 1)
	{
		$this->minimum_length = 1 <= $minimum_length ? $minimum_length : 1;
	}

	/**
	 * Set extra weight.
	 *
	 * @param float $extra_weight Package extra weight.
	 */
	public function set_extra_weight($extra_weight = 0)
	{
		$this->extra_weight = (float) wc_format_decimal($extra_weight);
	}

	/**
	 * Set declared value.
	 *
	 * @param string $declared_value Declared value.
	 */
	public function set_declared_value($declared_value = '0')
	{
		$this->declared_value = $declared_value;
	}

	/**
	 * Set own hands.
	 *
	 * @param string $own_hands Use 'N' for no and 'S' for yes.
	 */
	public function set_own_hands($own_hands = 'N')
	{
		$this->own_hands = $own_hands;
	}
	public function set_receipt_notice($receipt_notice = 'N')
	{
		$this->receipt_notice = $receipt_notice;
	}
	public function set_format($format = '1')
	{
		$this->format = $format;
	}
	public function set_debug($debug = 'no')
	{
		$this->debug = $debug;
	}
	public function get_origin_postcode()
	{
		return apply_filters('woocommerce_correios_origin_postcode', $this->origin_postcode, $this->id, $this->instance_id, $this->package);
	}
	public function get_height()
	{
		return $this->float_to_string($this->minimum_height <= $this->height ? $this->height : $this->minimum_height);
	}
	public function get_width()
	{
		return $this->float_to_string($this->minimum_width <= $this->width ? $this->width : $this->minimum_width);
	}
	public function get_diameter()
	{
		return $this->float_to_string($this->diameter);
	}
	public function get_length()
	{
		return $this->float_to_string($this->minimum_length <= $this->length ? $this->length : $this->minimum_length);
	}
	public function get_weight()
	{
		return $this->float_to_string($this->weight + $this->extra_weight);
	}
	protected function float_to_string($value)
	{
		$value = str_replace('.', ',', $value);
		return $value;
	}
	/**
	 * Get the shipping details.
	 * 
	 * @return object|null The shipping details or null if an error occurs.
	 */
	public function get_shipping()
	{
		global $wp;
		// Convert POST data string into an associative array.
		if (isset($_POST['post_data'])) {
			parse_str($_POST['post_data'], $output);
		}


		// Extract the 'agencyId' value if present.
		$agencyId = (isset($output['agencyId']) && $output['agencyId'] != "") ? $output['agencyId'] : null;

		$result = json_decode('{"cServico":{"Codigo":"EX","Valor":"0,00","PrazoEntrega":"0","ValorSemAdicionais":"0,00","ValorMaoPropria":"0,00","ValorAvisoRecebimento":"0,00","ValorValorDeclarado":"0,00","EntregaDomiciliar":{},"EntregaSabado":{},"obsFim":{},"Erro":"-888","MsgErro":"Erro ao calcular tarifa. Tente novamente mais tarde. Servidores indispon\u00edveis."}}');
		if (!$result || !isset($result->cServico)) {
			return null;
		}
		$shipping = $result->cServico;
		// Check if package contents exist
		if (!isset($this->package['contents'])) {
			return null;
		}
		$bultos = [];
		// Loop through package contents.
		$price = 0;
		foreach ($this->package['contents'] as $indice => $items) {
			// Extract item data.
			$data = $items['data'];
			$ancho = $data->get_width();
			$largo = $data->get_length();
			$alto = $data->get_height();
			$peso = $data->get_weight();
			$price += (float)$data->get_regular_price() * (int)$items['quantity'] ?? 0;


			$ancho = $this->isEmptyOrZero($ancho) ? 10 : $ancho;
			$largo = $this->isEmptyOrZero($largo) ? 10 : $largo;
			$alto = $this->isEmptyOrZero($alto) ? 10 : $alto;
			$pesoFisico = $this->isEmptyOrZero($peso) ? '0.010' : $peso;

			// Add to 'bultos' array.
			$bultos[] = [
				"ancho" => (int) $ancho,
				"largo" => (int) $largo,
				"alto" => (int) $alto,
				"pesoFisico" => floatval($pesoFisico),
				"cantidad" => (int) $items['quantity']
			];
		}
		// Get user data
		$userData = get_option('woocommerce_correios-integration_settings');
		if (!$userData) {
			return null;
		}
		$regionCodeToFormat = $this->package['destination']['state'];
		$regionCode = '';
		$siglas = 'CL-';
		if (strpos($regionCodeToFormat, $siglas) === 0) {
			$regionCode = substr($regionCodeToFormat, strlen($siglas));
		} else {
			$regionCode = $regionCodeToFormat;
		}
		//Busco la comuna seleccionada por el cliente 
		// Normalize city string
		$city_normalized = $this->normalizeString($this->package['destination']['city']);
		$current_url = home_url();
		$bxGeo = $this->getComunasGeo($city_normalized, $regionCode, $agencyId, $current_url);

		if (!$bxGeo) {
			return null;
		}
		if (isset($bxGeo["porcentageDeExito"])) {
			$percentage = rtrim($bxGeo["porcentageDeExito"], "%");
			$percentage = intval($percentage);
			if ($percentage < 80) {
				return null;
			}
		}


		$dadosGeo = [];

		$dadosGeo['regionCode'] 	= $bxGeo['regionCode'];
		$dadosGeo['cidadeName'] 	= $bxGeo['cidadeName'];
		$dadosGeo['cidadeCode'] 	= $bxGeo['cidadeCode'];
		$dadosGeo['districtCode']   = $bxGeo['districtCode'];

		if (empty($dadosGeo)) {
			return null;
		}

		// Fetch the price for the selected comuna
		$familiaProducto = 'PAQU';
		$nameService = "";
		if ($agencyId) {
			$familiaProducto = 'PUDO';
			if (empty($bxGeo['pickupInfo']['agency_name'])) {
				return null; // Early return if agency_name does not exist or is empty
			}
			$nameService = $bxGeo['pickupInfo']['agency_name'];
		}
		$response = $this->fetchPrice($userData, $dadosGeo, $bultos, $current_url, $familiaProducto, $price);

		if (!$response) {
			return null;
		}
		// Update shipping details based on response
		if ($response->code == "00" || $response->code == "01") {
			$shipping->Codigo = $this->service;
			$shipping->Valor = (int) $response->data->total;
			$shipping->PrazoEntrega = $response->data->promiseDay;
			$shipping->nameService = (empty($nameService)) ? $response->data->nameService : $nameService;
			$shipping->isShipmentFree = $response->data->isShipmentFree;
			$shipping->Erro = 0;
			$shipping->MsgErro = '';

			if ($shipping->isShipmentFree) {
				$shipping->nameService .= " - Envío gratis";
			}
		}

		// Cleanup
		unset(
			$shipping->EntregaDomiciliar,
			$shipping->EntregaSabado,
			$shipping->obsFim,
			$shipping->ValorSemAdicionais,
			$shipping->ValorMaoPropria,
			$shipping->ValorAvisoRecebimento,
			$shipping->ValorValorDeclarado
		);

		return $shipping;
	}
	/**
	 * Normalize a string by replacing specific characters.
	 * 
	 * @param string $string The original string.
	 * @return string The normalized string.
	 */
	private function normalizeString($string)
	{
		$from = ['Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª', 'É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê', 'Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î', 'Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô', 'Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û', 'Ñ', 'ñ', 'Ç', 'ç'];
		$to = ['A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a', 'E', 'E', 'E', 'E', 'e', 'e', 'e', 'e', 'I', 'I', 'I', 'I', 'i', 'i', 'i', 'i', 'O', 'O', 'O', 'O', 'o', 'o', 'o', 'o', 'U', 'U', 'U', 'U', 'u', 'u', 'u', 'u', 'N', 'n', 'C', 'c'];
		return str_replace($from, $to, $string);
	}
	/**
	 * Fetch the price for a given shipping.
	 * 
	 * @param array $userData User-specific settings/data.
	 * @param array $dadosGeo Geographical details.
	 * @param array $bultos Package contents.
	 * @param string $current_url Current URL.
	 * @param string $familiaProducto Product family type.
	 * @return object|null The fetched price or null if an error occurs.
	 */
	private function fetchPrice($userData, $dadosGeo, $bultos, $current_url, $familiaProducto, $price)
	{
		$headers = [
			'Content-Type' => 'application/json',
			'apikey' => $userData['tracking_bxkey'],
			'BX-TOKEN' => $userData['tracking_token'],
			'price' => $price
		];
		$body = json_encode([
			"from" => [
				"country" => "CL",
				"district" => $userData['districtCode']
			],
			"to" => [
				"country" => "CL",
				"state" => $dadosGeo['regionCode'],
				"district" => $dadosGeo['districtCode']
			],
			"serviceType" => $this->service,
			"domain" => $current_url . "/",
			"datosProducto" => [
				"producto" => "P",
				"familiaProducto" => $familiaProducto,
				"bultos" => $bultos
			]
		]);
		$postUrl = $this->_basePathUrl . '/api/ecommerce/pricing/v1';
		$postPrice = wp_remote_post($postUrl, [
			'method'  => 'POST',
			'headers' => $headers,
			'body'    => $body
		]);

		if (is_wp_error($postPrice)) {
			return null;
		}

		$response = json_decode($postPrice['body']);
		if (!$response || !property_exists($response, 'data')) {
			return null;
		}

		return $response;
	}
	/**
	 * Get geographical details for a 'comuna' (community or district).
	 * 
	 * @param string $city_normalized Normalized city name.
	 * @param string $regionCode Region code.
	 * @param string $agencyId Agency ID.
	 * @return array|null The geographical details or null if an error occurs.
	 */
	private function getComunasGeo($city_normalized, $regionCode, $agencyId,  $current_url)
	{
		$geoEndpoint = '/api/ecommerce/comunas/v1/bxgeo';
		if ($this->_pudoEnabled === 'yes') {
			$geoEndpoint .= '/v2';
		}
		$postUrl = $this->_basePathUrl . $geoEndpoint;
		$comunasGeo = wp_remote_post($postUrl, array(
			'method'  => 'POST',
			'headers' => array(
				'Content-Type' => 'application/json'
			),
			'body'        => '{
            "address": "' . $city_normalized . '",
            "type": "woocommerce",
            "shop": "' . $current_url . '/",
            "regionCode": "' . $regionCode . '",
            "agencyId": "' . $agencyId . '"
        }'
		));

		if (is_wp_error($comunasGeo)) {
			return null;
		}

		return json_decode(wp_remote_retrieve_body($comunasGeo), true);
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
	function get_url()
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
	 * Checks if a given value is empty or equivalent to zero.
	 * This includes checks for "0", "0.0", "0.000", etc., in both string and float formats.
	 * 
	 * @param mixed $value The value to check.
	 * @return bool True if the value is empty or equivalent to zero, false otherwise.
	 */
	function isEmptyOrZero($value)
	{
		return empty($value) || floatval($value) == 0;
	}
}
