<?php
/**
 * Correios Leve Internacional shipping method.
 *
 * @package WooCommerce_Correios/Classes/Shipping
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Leve Internacional shipping method class.
 */
class WC_Correios_ShippingLeveInternacional extends WC_Correios_ShippingInternational {

	/**
	 * Service code.
	 * 209 - Leve Internacional.
	 *
	 * @var string
	 */
	protected $code = '209';

	/**
	 * Initialize Leve Internacional.
	 *
	 * @param int $instance_id Shipping zone instance.
	 */
	public function __construct( $instance_id = 0 ) {
		$this->id           = 'correios-leve-internacional';
		$this->method_title = __( 'Leve Internacional', 'woocommerce-correios' );
		$this->more_link    = '';

		parent::__construct( $instance_id );
	}
}
