<?php

/**
 * Correios custom-order-status.
 *
 * @package WooCommerce_Correios/Classes/custom-order-status
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Correios custom status class
 */
class WC_Custom_Order_Status_Handler
{

	/**
	 * Class constructor.
	 */
	public function __construct()
	{
		add_action('init', array($this, 'registerPostStatuses'));
		add_filter('wc_order_statuses', array($this, 'addOrderStatuses'));
		add_action('load-edit.php', array($this, 'handleBulkActionEditShopOrderStatus'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_custom_bulk_action_script'));
	}

	/**
	 * Registers the custom order status.
	 */
	public function registerPostStatuses()
	{
		register_post_status('wc-shipping-progress', array(
			'label' => _x('Listo para enviar', 'WooCommerce Order status', 'text_domain'),
			'public' => true,
			'exclude_from_search' => false,
			'show_in_admin_all_list' => true,
			'show_in_admin_status_list' => true,
			'label_count' => _n_noop('Listo para enviar (%s)', 'Listo para enviar (%s)', 'text_domain')
		));
	}

	/**
	 * Adds the custom status to WooCommerce's list of order statuses.
	 */
	public function addOrderStatuses($order_statuses)
	{
		$order_statuses['wc-shipping-progress'] = _x('Listo para enviar', 'WooCommerce Order status', 'text_domain');
		return $order_statuses;
	}

	/**
	 * Enqueue custom script for adding bulk action in WooCommerce orders.
	 */
	public function enqueue_custom_bulk_action_script($hook)
	{
		$screen = get_current_screen();

		if (
			('edit-shop_order' === $screen->id) ||  // old 
			('woocommerce_page_wc-orders' === $screen->id) // HPOS
		) {
			$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
			wp_enqueue_script('custom-bulk-action', plugins_url('assets/js/admin/custom-bulk-action' . $suffix . '.js', WC_Correios::get_main_file()), array('jquery', 'jquery-blockui'), "", true);
		}
	}



	/**
	 * Handles the bulk action to change order statuses.
	 */
	public function handleBulkActionEditShopOrderStatus()
	{
		global $typenow;

		if ('shop_order' === $typenow) {
			$wp_list_table = _get_list_table('WP_Posts_List_Table');
			$action = $wp_list_table->current_action();

			if ('mark_shipping-progress' === $action) {
				check_admin_referer('bulk-posts');

				$order_ids = array_map('absint', $_REQUEST['post']);
				$changed = 0;

				foreach ($order_ids as $order_id) {
					if ('shop_order' != get_post_type($order_id)) {
						continue;
					}

					$order = wc_get_order($order_id);
					$order->update_status('shipping-progress', __('Estado actualizado a Listo para enviar', 'text_domain'), true);
					$changed++;
				}

				$sendback = add_query_arg(array(
					'post_type' => 'shop_order',
					'changed'   => $changed,
					'ids'       => join(',', $order_ids),
					'post_status' => 'all'
				), '');
				wp_redirect(esc_url_raw($sendback));
				exit;
			}
		}
	}
}

// Instantiate the class.
new WC_Custom_Order_Status_Handler();
