<?php
// to check whether accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//check class dependencies exist or not
if ( ! class_exists( 'ELEX_RAQ_BASIC_Dependencies' ) ) {
	require_once  'elex-raq-basic-dependencies.php' ;
}

//check woocommerce is active function exist
if ( ! function_exists( 'elex_raq_basic_is_woocommerce_active' ) ) {

	function elex_raq_basic_is_woocommerce_active() {
		return ELEX_RAQ_BASIC_Dependencies::woocommerce_active_check();
	}
}
