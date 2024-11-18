<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_BlueX_EX extends WC_Correios_Shipping {
	protected $code = 'EX';
	public function __construct( $instance_id = 0 ) {
		$this->id           = 'bluex-ex';
		$this->method_title = __( 'BlueX - Express', 'woocommerce-correios' );
		$this->more_link    = '';

		parent::__construct( $instance_id );
	}
	protected function get_declared_value( $package ) {
		if ( 18 >= $package['contents_cost'] ) {
			return 0;
		}
		return $package['contents_cost'];
	}
}
class WC_BlueX_PY extends WC_Correios_Shipping {
	protected $code = 'PY';
	public function __construct( $instance_id = 0 ) {
		$this->id           = 'bluex-py';
		$this->method_title = __( 'BlueX - Prioritario', 'woocommerce-correios' );
		$this->more_link    = '';

		parent::__construct( $instance_id );
	}
	protected function get_declared_value( $package ) {
		if ( 18 >= $package['contents_cost'] ) {
			return 0;
		}
		return $package['contents_cost'];
	}
}
class WC_BlueX_MD extends WC_Correios_Shipping {
	protected $code = 'MD';
	public function __construct( $instance_id = 0 ) {
		$this->id           = 'bluex-md';
		$this->method_title = __( 'BlueX - SameDay', 'woocommerce-correios' );
		$this->more_link    = '';

		parent::__construct( $instance_id );
	}
	protected function get_declared_value( $package ) {
		if ( 18 >= $package['contents_cost'] ) {
			return 0;
		}
		return $package['contents_cost'];
	}
}