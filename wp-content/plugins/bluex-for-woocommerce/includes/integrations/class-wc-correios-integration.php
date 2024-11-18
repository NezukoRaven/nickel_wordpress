<?php

/**
 * Correios integration.
 *
 * @package WooCommerce_Correios/Classes/Integration
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Correios integration class.
 */
class WC_Correios_Integration extends WC_Integration
{

	/**
	 * Initialize integration actions.
	 */
	public function __construct()
	{
		$this->id           = 'correios-integration';
		$this->method_title = __('Blue Express', 'woocommerce-correios');

		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		// Define user set variables.
		$this->tracking_enable         = $this->get_option('tracking_enable');
		$this->tracking_login          = $this->get_option('tracking_login');
		$this->tracking_password       = $this->get_option('tracking_password');
		$this->tracking_token          = $this->get_option('tracking_token');
		$this->tracking_bxkey          = $this->get_option('tracking_bxkey');
		$this->districtCode          	= $this->get_option('districtCode');
		$this->noBlueStatus          	= $this->get_option('noBlueStatus');
		$this->noBlueOnCreate          	= $this->get_option('noBlueOnCreate');
		$this->pudoEnable          = $this->get_option('pudoEnable');
		$this->googleKey        = $this->get_option('googleKey');
		$this->districtsEnable          = $this->get_option('districtsEnable');
		$this->devOptions          = $this->get_option('devOptions');
		$this->alternativeBasePath        = $this->get_option('alternativeBasePath');
		$this->tracking_debug          = $this->get_option('tracking_debug');
		$this->autofill_enable         = $this->get_option('autofill_enable');
		$this->autofill_validity       = $this->get_option('autofill_validity');
		$this->autofill_force          = $this->get_option('autofill_force');
		$this->autofill_empty_database = $this->get_option('autofill_empty_database');
		$this->autofill_debug          = $this->get_option('autofill_debug');


		// Actions.
		add_action('woocommerce_update_options_integration_' . $this->id, array($this, 'process_admin_options'));

		// Tracking history actions.
		add_filter('woocommerce_correios_enable_tracking_history', array($this, 'setup_tracking_history'), 10);
		add_filter('woocommerce_correios_tracking_user_data', array($this, 'setup_tracking_userData'), 10);
		add_filter('woocommerce_correios_enable_tracking_debug', array($this, 'setup_tracking_debug'), 10);

		// Autofill address actions.
		add_filter('woocommerce_correios_enable_autofill_addresses', array($this, 'setup_autofill_addresses'), 10);
		add_filter('woocommerce_correios_enable_autofill_addresses_debug', array($this, 'setup_autofill_addressesDebug'), 10);
		add_filter('woocommerce_correios_autofill_addresses_validity_time', array($this, 'setup_autofill_addressesValidityTime'), 10);
		add_filter('woocommerce_correios_autofill_addresses_force_autofill', array($this, 'setup_autofill_addressesForceAutofill'), 10);
		add_action('wp_ajax_correios_autofill_addresses_empty_database', array($this, 'ajax_empty_database'));
	}

	protected function get_tracking_log_link()
	{
		return ' <a href="' . esc_url(admin_url('admin.php?page=wc-status&tab=logs&log_file=correios-tracking-history-' . sanitize_file_name(wp_hash('correios-tracking-history')) . '.log')) . '">' . __('View logs.', 'woocommerce-correios') . '</a>';
	}

	public function init_form_fields()
	{
		$this->form_fields = array(
			'tracking_login'          => array(
				'title'       => __('Administrative UserCode', 'woocommerce-correios'),
				'type'        => 'text',
				'description' => __('Your BlueX User Code', 'woocommerce-correios'),
				'desc_tip'    => true,
				'default'     => '',
			),
			'tracking_password'       => array(
				'title'       => __('Administrative ClientAccount', 'woocommerce-correios'),
				'type'        => 'text',
				'description' => __('Your Bluex ClientAccount.', 'woocommerce-correios'),
				'desc_tip'    => true,
				'default'     => '',
			),
			'tracking_token'       => array(
				'title'       => __('Administrative Token', 'woocommerce-correios'),
				'type'        => 'text',
				'description' => __('Your BlueX token.', 'woocommerce-correios'),
				'desc_tip'    => true,
				'default'     => '',
			),
			'tracking_bxkey'       => array(
				'title'       => __('Administrative Api Key', 'woocommerce-correios'),
				'type'        => 'text',
				'description' => __('Your BlueX Key.', 'woocommerce-correios'),
				'desc_tip'    => true,
				'default'     => '',
			),
			'districtCode'       => array(
				'title'       => __('Store District Code', 'woocommerce-correios'),
				'type'        => 'text',
				'description' => __('Your Store District Code. Ex:ARI', 'woocommerce-correios'),
				'desc_tip'    => true,
				'default'     => '',
			),
			'noBlueStatus'       => array(
				'title'       => __('OS Emition status', 'woocommerce-correios'),
				'type'        => 'select',
				'description' => __('OS Emition status. Ex: Pending', 'woocommerce-correios'),
				'desc_tip'    => true,
				'default'     => 'wc-shipping-progress',
				'options'      => wc_get_order_statuses(),
			),
			'noBlueOnCreate'       => array(
				'title'       => __('Send order when created', 'woocommerce-correios'),
				'type'        => 'checkbox',
				'description' => __('Send order when created. Ex: Check = Yes', 'woocommerce-correios'),
				'desc_tip'    => true,
				'label' => 'check to enable',
				'default'     => 'no',

			),
			'pudoEnable'       => array(
				'title'       => __('Enable pickup points functionality', 'woocommerce-correios'),
				'type'        => 'checkbox',
				'description' => __('Enable pickup points functionality. Ex: Check = Yes', 'woocommerce-correios'),
				'desc_tip'    => true,
				'label' => 'check to enable',
				'default'     => 'no',

			),
			'googleKey'       => array(
				'title'       => __('google Api Key', 'woocommerce-correios'),
				'type'        => 'text',
				'description' => __('Your google Key.', 'woocommerce-correios'),
				'desc_tip'    => true,
				'default'     => '',
			),
			'districtsEnable'       => array(
				'title'       => __('Enable districts functionality', 'woocommerce-correios'),
				'type'        => 'checkbox',
				'description' => __('Enable districts functionality. Ex: Check = Yes', 'woocommerce-correios'),
				'desc_tip'    => true,
				'label' => 'check to enable',
				'default'     => 'no',

			),

		);
		if (defined('DEV_OPTIONS') && DEV_OPTIONS) {
			$this->form_fields['devOptions'] = array(
				'title'       => __('Enable dev options', 'woocommerce-correios'),
				'type'        => 'checkbox',
				'description' => __('Enable dev options functionality. Ex: Check = Yes', 'woocommerce-correios'),
				'desc_tip'    => true,
				'label' => 'check to enable',
				'default'     => 'no',
			);

			$this->form_fields['alternativeBasePath'] = array(
				'title'       => __('Alternative Base Path', 'woocommerce-correios'),
				'type'        => 'text',
				'description' => __('Your Alternative Base Path.', 'woocommerce-correios'),
				'desc_tip'    => true,
				'default'     => 'https://apigw.bluex.cl',
			);
		}
	}

	/**
	 * Correios options page.
	 */
	public function admin_options()
	{
		echo '<h2>' . esc_html($this->get_method_title()) . '</h2>';
		echo wp_kses_post(wpautop($this->get_method_description()));

		include WC_Correios::get_plugin_path() . 'includes/admin/views/html-admin-help-message.php';

		if (class_exists('SoapClient')) {
			echo '<div><input type="hidden" name="section" value="' . esc_attr($this->id) . '" /></div>';
			echo '<table class="form-table">' . $this->generate_settings_html($this->get_form_fields(), false) . '</table>'; // WPCS: XSS ok.
		} else {
			$GLOBALS['hide_save_button'] = true; // Hide save button.
			/* translators: %s: SOAP documentation link */
			echo '<div class="notice notice-error inline"><p>' . sprintf(esc_html__('It\'s required have installed the %s on your server in order to integrate with the services of the Correios!', 'woocommerce-correios'), '<a href="https://secure.php.net/manual/book.soap.php" target="_blank" rel="nofollow noopener noreferrer">' . esc_html__('SOAP module', 'woocommerce-correios') . '</a>') . '</p></div>';
		}

		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script($this->id . '-admin', plugins_url('assets/js/admin/integration' . $suffix . '.js', WC_Correios::get_main_file()), array('jquery', 'jquery-blockui'), WC_CORREIOS_VERSION, true);
		wp_localize_script(
			$this->id . '-admin',
			'WCCorreiosIntegrationAdminParams',
			array(
				'i18n_confirm_message' => __('Are you sure you want to delete all postcodes from the database?', 'woocommerce-correios'),
				'empty_database_nonce' => wp_create_nonce('woocommerce_correios_autofill_addresses_nonce'),
			)
		);
	}

	/**
	 * Generate Button Input HTML.
	 *
	 * @param string $key  Input key.
	 * @param array  $data Input data.
	 * @return string
	 */
	public function generate_button_html($key, $data)
	{
		$field_key = $this->get_field_key($key);
		$defaults  = array(
			'title'       => '',
			'label'       => '',
			'desc_tip'    => false,
			'description' => '',
		);

		$data = wp_parse_args($data, $defaults);

		ob_start();
?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr($field_key); ?>"><?php echo wp_kses_post($data['title']); ?></label>
				<?php echo $this->get_tooltip_html($data); // WPCS: XSS ok. 
				?>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post($data['title']); ?></span></legend>
					<button class="button-secondary" type="button" id="<?php echo esc_attr($field_key); ?>"><?php echo wp_kses_post($data['label']); ?></button>
					<?php echo $this->get_description_html($data); // WPCS: XSS ok. 
					?>
				</fieldset>
			</td>
		</tr>
<?php

		return ob_get_clean();
	}

	/**
	 * Enable tracking history.
	 *
	 * @return bool
	 */
	public function setup_tracking_history()
	{
		return 'yes' === $this->tracking_enable && class_exists('SoapClient');
	}

	/**
	 * Setup tracking user data.
	 *
	 * @param array $user_data User data.
	 * @return array
	 */
	public function setup_tracking_userData($user_data)
	{
		if ($this->tracking_login && $this->tracking_password) {
			$user_data = array(
				'login'    => $this->tracking_login,
				'password' => $this->tracking_password,
			);
		}

		return $user_data;
	}

	/**
	 * Set up tracking debug.
	 *
	 * @return bool
	 */
	public function setup_tracking_debug()
	{
		return 'yes' === $this->tracking_debug;
	}

	/**
	 * Enable autofill addresses.
	 *
	 * @return bool
	 */
	public function setup_autofill_addresses()
	{
		return 'yes' === $this->autofill_enable && class_exists('SoapClient');
	}

	/**
	 * Set up autofill addresses debug.
	 *
	 * @return bool
	 */
	public function setup_autofill_addressesDebug()
	{
		return 'yes' === $this->autofill_debug;
	}

	/**
	 * Set up autofill addresses validity time.
	 *
	 * @return string
	 */
	public function setup_autofill_addressesValidityTime()
	{
		return $this->autofill_validity;
	}

	/**
	 * Set up autofill addresses force autofill.
	 *
	 * @return string
	 */
	public function setup_autofill_addressesForceAutofill()
	{
		return $this->autofill_force;
	}

	/**
	 * Ajax empty database.
	 */
	public function ajax_empty_database()
	{
		global $wpdb;

		if (!isset($_POST['nonce'])) { // WPCS: input var okay, CSRF ok.
			wp_send_json_error(array('message' => __('Missing parameters!', 'woocommerce-correios')));
			exit;
		}

		if (!wp_verify_nonce(sanitize_key(wp_unslash($_POST['nonce'])), 'woocommerce_correios_autofill_addresses_nonce')) { // WPCS: input var okay, CSRF ok.
			wp_send_json_error(array('message' => __('Invalid nonce!', 'woocommerce-correios')));
			exit;
		}

		$table_name = $wpdb->prefix . WC_Correios_AutofillAddresses::$table;
		$wpdb->query("DROP TABLE IF EXISTS $table_name;"); // @codingStandardsIgnoreLine

		WC_Correios_AutofillAddresses::create_database();

		wp_send_json_success(array('message' => __('Postcode database emptied successfully!', 'woocommerce-correios')));
	}
}
