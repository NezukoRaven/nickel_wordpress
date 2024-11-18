<?php
/**
 * Uninstall
 *
 * @package WooCommerce_Correios/Uninstaller
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}correios_postcodes" );
