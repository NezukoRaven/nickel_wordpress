<?php
/**
 *
 * Order Statuses Related Settings.
 *
 * @package Elex Request a Quote
 */

add_action( 'init', 'elex_register_order_status' );
/** Register new order statuses. */
function elex_register_order_status() {
	register_post_status(
		'wc-quote-requested',
		array(
			'label'                     => __( 'Quote Requested', 'elex-request-a-quote' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Quote Requested (%s)', 'Quote Requested (%s)' ),
		)
	);
	register_post_status(
		'wc-quote-approved',
		array(
			'label'                     => __( 'Quote Approved', 'elex-request-a-quote' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Quote Approved (%s)', 'Quote Approved (%s)' ),
		)
	);
	register_post_status(
		'wc-quote-rejected',
		array(
			'label'                     => __( 'Quote Rejected', 'elex-request-a-quote' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Quote Rejected (%s)', 'Quote Rejected (%s)' ),
		)
	);
}

add_filter( 'wc_order_statuses', 'elex_add_order_statuses' );
/** Add to list of WC Order statuses.
 *
 * @param var $order_statuses order statuses.
 */
function elex_add_order_statuses( $order_statuses ) {
	$new_order_statuses = array();
	// Add new order status after processing.
	foreach ( $order_statuses as $key => $status ) {
		$new_order_statuses[ $key ] = $status;
		if ( 'wc-processing' === $key ) {
			$new_order_statuses['wc-quote-requested'] = 'Quote Requested';
			$new_order_statuses['wc-quote-approved']  = 'Quote Approved';
			$new_order_statuses['wc-quote-rejected']  = 'Quote Rejected';
		}
	}
	return $new_order_statuses;
}


add_filter( 'woocommerce_valid_order_statuses_for_payment', 'elex_order_status_valid_for_payment_and_cancel', 10, 2 );
add_filter( 'woocommerce_valid_order_statuses_for_cancel', 'elex_order_status_valid_for_payment_and_cancel', 10, 2 );
/** Register the approved status as valid for payment.
 *
 * @param var $statuses order.
 * @param var $order order.
 */
function elex_order_status_valid_for_payment_and_cancel( $statuses, $order ) {
	$statuses[] = 'quote-approved';
	return $statuses;
}


add_filter( 'wc_order_is_editable', 'elex_order_status_is_editable', 10, 2 );
/** Make a particular order status as editable.
 *
 * @param var $editable editable.
 * @param var $order order.
 */
function elex_order_status_is_editable( $editable, $order ) {
	if ( $order->get_status() === 'quote-requested' ) {
		$editable = true;
	}
	return $editable;
}
