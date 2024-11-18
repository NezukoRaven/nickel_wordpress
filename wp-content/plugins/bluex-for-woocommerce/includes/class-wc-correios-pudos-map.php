<?php

/**
 * Correios Pudos Map.
 *
 * @package WooCommerce_Correios/Classes/pudos
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Correios pudos map class
 */
class WC_Correios_PudosMap
{



	protected $_basePathUrl;
	protected $_googleKey;
	protected $_devMode;

	/**
	 * Initialize actions.
	 */
	public function __construct()
	{
		$configData = get_option('woocommerce_correios-integration_settings');
		if (isset($configData['pudoEnable']) && $configData['pudoEnable'] == "yes") {
			add_action('init', array($this, 'init'));
		}
		// Comprobación con isset para evitar el error:
		$this->_devMode = isset($configData['devOptions']) && $configData['devOptions'] === "yes";

		// Decide the base path URL based on the devMode status
		if ($this->_devMode && !empty($configData['alternativeBasePath'])) {
			$this->_basePathUrl = $configData['alternativeBasePath'];
		} else {
			$this->_basePathUrl = 'https://apigw.bluex.cl';
		}
		$this->_googleKey = $configData['googleKey'] ?? '';
	}

	/**
	 * Hook into various actions for frontend functionalities.
	 */
	public function init()
	{
		add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'));
		add_action('woocommerce_review_order_before_shipping', array($this, 'render_map_component'), 20); // Show on checkout
		add_action('woocommerce_after_order_notes',  array($this, 'render_custom_input'));
		add_action('woocommerce_checkout_update_order_meta', array($this, 'save_custom_input_to_order_meta'));
		add_action('wp_ajax_clear_shipping_cache', array($this, 'clear_shipping_cache'));
		add_action('wp_ajax_nopriv_clear_shipping_cache', array($this, 'clear_shipping_cache'));
	}

	/**
	 * Enqueue scripts on checkout page.
	 */
	public function frontend_scripts()
	{
		if (is_checkout()) {
			$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
			wp_enqueue_script('custom-checkout-map', plugins_url('assets/js/frontend/custom-checkout-map' . $suffix . '.js', WC_Correios::get_main_file()), array('jquery', 'jquery-blockui'), "", true);
		}
	}
	/**
	 * Render custom hidden input fields.
	 */
	function render_custom_input()
	{
?>
		<input type="hidden" name="agencyId" id="agencyId" placeholder="" value='' />
		<input type="hidden" name="isPudoSelected" id="isPudoSelected" placeholder="" value='' />
	<?php
	}
	/**
	 * Save custom input values to order meta.
	 *
	 * @param int $order_id The order ID.
	 */

	function save_custom_input_to_order_meta($order_id)
	{
		// Retrieve the order object using WooCommerce function
		$order = wc_get_order($order_id);
		// Define the fields you want to check and update
		$fields_to_update = ['agencyId', 'isPudoSelected'];

		foreach ($fields_to_update as $field) {
			// Check if the field exists in the POST request
			if (isset($_POST[$field])) {
				// Sanitize the 'agencyId' field to ensure clean data; other fields are directly used
				$value = $field === 'agencyId' ? sanitize_text_field($_POST[$field]) : $_POST[$field];
				// Check if the order object has the 'update_meta_data' method for compatibility
				if (method_exists($order, 'update_meta_data')) {
					// Update the order meta data with the field value
					$order->update_meta_data($field, $value);
					// Save the changes to the order
					$order->save();
				} else {
					// Fallback for older WooCommerce versions: directly update post meta
					update_post_meta($order_id, $field, $value);
				}
			}
		}
	}


	/**
	 * Render the map component on checkout page.
	 */

	function render_map_component()
	{

		$postData = $_POST['post_data'] ?? '';
		$output = [];
		parse_str($postData, $output);
		$isPudoSelected = isset($output['isPudoSelected']) && $output['isPudoSelected'] == "pudoShipping";
		$agencyId = (isset($output['agencyId']) && $output['agencyId'] != "") ? $output['agencyId'] : null;
		$this->render_shipping_method_selection($isPudoSelected);

		if ($isPudoSelected) {
			$this->render_pudo_iframe($agencyId);
		}
	}
	/**
	 * Function to render the shipping method selection interface.
	 *
	 * @param bool $isPudoSelected Indicates if PUDO shipping is selected.
	 */
	function render_shipping_method_selection($isPudoSelected)
	{
	?>
		<tr id="map" class="woocommerce-billing-fields__field-wrapper">
			<td colspan="2">
				<span>Shipping Method</span><br>
				<!-- Render radio button for normal shipping. -->
				<input type="radio" id="normalShipping" name="shippingBlue" value="normalShipping" <?php checked(!$isPudoSelected); ?> onclick="selectShipping('normalShipping')">
				<label for="normalShipping" style="padding-left:10px;">Envio a Domicilio</label><br>

				<!-- Render radio button for PUDO shipping. -->
				<input type="radio" id="pudoShipping" name="shippingBlue" value="pudoShipping" <?php checked($isPudoSelected); ?> onclick="selectShipping('pudoShipping')">
				<label for="pudoShipping" style="padding-left:10px;">Retiro en Punto Blue Express</label>
			</td>
		</tr>
	<?php
	}
	/**
	 * Function to render the PUDO iframe component.
	 */
	function render_pudo_iframe($agencyId)
	{
		$widgetUrl = $this->getWidgetURL($this->_basePathUrl, $agencyId);
	?>
		<tr>
			<td colspan="2">
				<!-- Render the PUDO widget iframe. -->
				<div id="componenteOculto" class="componente">
					<iframe id="i" src="<?= $widgetUrl ?>" frameborder="0" style="width:100%; height:757px;"></iframe>
				</div>
			</td>
		</tr>
<?php
	}

	/**
	 * Clear the shipping cache.
	 */

	function clear_shipping_cache()
	{
		$packages = WC()->cart->get_shipping_packages();

		foreach ($packages as $package_key => $package) {
			WC()->session->__unset('shipping_for_package_' . $package_key);
		}

		wp_send_json_success('Shipping cache cleared.');
	}

	/**
	 * Retrieves the widget URL based on the provided domain, appending additional parameters if present.
	 * 
	 * The function analyzes the domain to determine if it belongs to the 'qa' or 'dev' environments.
	 * It also appends the Google key and/or agency ID as query parameters if they are not empty.
	 *
	 * @param string $domain The domain to analyze.
	 * @param string|null $agencyId The agency ID to append to the URL as a parameter.
	 * @return string The URL of the widget corresponding to the environment with additional parameters if applicable.
	 */
	function getWidgetURL($domain, $agencyId = null)
	{
		// Define a regular expression to detect 'qa' or 'dev' in the domain
		$pattern = '/https:\/\/(qa|dev)?apigw\.bluex\.cl/';

		// Use preg_match to extract the environment part if it matches the pattern
		preg_match($pattern, $domain, $matches);

		// Determine the environment; default to production if not 'qa' or 'dev'
		$environment = $matches[1] ?? '';

		// Map environment to respective base URL
		$urls = [
			'qa'  => 'https://widget-pudo.qa.blue.cl',
			'dev' => 'https://widget-pudo.dev.blue.cl',
			''    => 'https://widget-pudo.blue.cl', // Default case
		];

		// Start with the base URL
		$url = $urls[$environment];

		// Initialize query parameters array
		$queryParams = [];

		// Append the Google key if it is not empty
		if (!empty($this->_googleKey)) {
			$queryParams['key'] = $this->_googleKey;
		}

		// Append the agency ID if it is not null or empty
		if (!empty($agencyId)) {
			$queryParams['id'] = $agencyId;
		}

		// Append query parameters to the URL if any exist
		if (!empty($queryParams)) {
			$url .= '?' . http_build_query($queryParams);
		}

		return $url;
	}
}

new WC_Correios_PudosMap();
