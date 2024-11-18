<?php

namespace Elex\RequestAQuote\Quotelist;

use Elex\RequestAQuote\Migrate;
use Elex\RequestAQuote\Quotelist\Models\QuoteListModel;
use Elex\RequestAQuote\Settings\Models\GeneralSettings;
use Elex\RequestAQuote\Settings\SettingsController;
use Elex\RequestAQuote\FormSetting\FormSettingController;
use Elex\RequestAQuote\FormSetting\Models\FormSettings;


use Elex\RequestAQuote\Quotelist\Models\ListPageSettings;
use Elex\RequestAQuote\Widget\WidgetController;
use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;



class QuoteListController {

	const SESSION_KEY_COOKIE_NAME = 'request_a_quote_user_coockie';
	public static $api_namespace  = 'elex-raq';
	public static $api_version    = 'v1';

	
	public static function init() {

		$activated_plugins = (array) get_option( 'active_plugins' );
		if ( is_multisite() ) {
			$activated_plugins = array_merge( $activated_plugins, get_site_option( 'active_sitewide_plugins' ), array() );
		}

		$theme = wp_get_theme(); // gets the current theme
		if (!empty($theme)) {
			if (in_array($theme->name , array ('Twenty Twenty-Four' , 'Twenty Twenty-Three' , 'Twenty Twenty-Two') )) {
				add_action( 'woocommerce_after_shop_loop_item_title', array( self::class, 'add_button_to_shop_page' )  );
			} else {
				add_action( 'woocommerce_after_shop_loop_item', array( self::class, 'add_button_to_shop_page' )  );
			}
		} else {
			add_action( 'woocommerce_after_shop_loop_item', array( self::class, 'add_button_to_shop_page' ) );

		}
		
		add_action( 'init', array( self::class, 'set_guest_user_cookie' ) );

		add_action( 'wp_ajax_elex_raq_submit_form', array( self::class, 'elex_place_order' ) );
		add_action( 'wp_ajax_nopriv_elex_raq_submit_form', array( self::class, 'elex_place_order' ) );

		add_action( 'wp_ajax_elex_raq_add_to_quote', array( self::class, 'elex_raq_add_to_quote' ) );
		add_action( 'wp_ajax_nopriv_elex_raq_add_to_quote', array( self::class, 'elex_raq_add_to_quote' ) );

		add_action( 'wp_ajax_elex_raq_update_quantity', array( self::class, 'elex_raq_update_quantity' ) );
		add_action( 'wp_ajax_nopriv_elex_raq_update_quantity', array( self::class, 'elex_raq_update_quantity' ) );


		add_action( 'wp_ajax_elex_raq_delete_item', array( self::class, 'eleX_raq_delete_item' ) );
		add_action( 'wp_ajax_nopriv_elex_raq_delete_item', array( self::class, 'eleX_raq_delete_item' ) );

		add_action( 'wp_ajax_elex_raq_clear_list', array( self::class, 'elex_raq_clear_list' ) );
		add_action( 'wp_ajax_nopriv_elex_raq_clear_list', array( self::class, 'elex_raq_clear_list' ) );

		add_action( 'wp_ajax_elex_raq_update_quote_list', array( self::class, 'update_list' ) );
		add_action( 'wp_ajax_nopriv_elex_raq_update_quote_list', array( self::class, 'update_list' ) );


		add_action( 'wp_ajax_get_the_quote_list', array( self::class, 'get_the_quote_list' ) );
		add_action( 'wp_ajax_nopriv_get_the_quote_list', array( self::class, 'get_the_quote_list' ) );

		add_shortcode( 'elex_quote_request_list', array( self::class, 'elex_quote_request_list_shortcode' ) );
		add_shortcode( 'elex_quote_received_page', array( self::class, 'elex_quote_received_page_shortcode' ) );

		if ( ( ( in_array( 'elementor/elementor.php', $activated_plugins ) || array_key_exists( 'elementor/elementor.php', $activated_plugins ) ) ) && ( ( in_array($theme->name , array ('Twenty Twenty-Four' , 'Twenty Twenty-Three' , 'Twenty Twenty-Two') ) ) )) {
			add_action( 'woocommerce_product_meta_start', array( self::class, 'add_button_to_out_of_stock_product' ) );
		}

		//To make Compatible with Avada Astra theme
		if ( in_array( 'fusion-builder/fusion-builder.php', get_option( 'active_plugins' ) ) ) {
			add_action( 'awb_after_woo_add_to_cart_content', array( self::class, 'add_button_to_product_page' ) );
		} elseif ( ( in_array( 'elementor/elementor.php', $activated_plugins ) || array_key_exists( 'elementor/elementor.php', $activated_plugins ) ) ) {
		//To make it compatible with Elementor "woocommerce_product_meta_start" has been replaced with "woocommerce_after_add_to_cart_form" hook.
		 add_action( 'woocommerce_after_add_to_cart_form', array( self::class, 'add_button_to_product_page' ) );
		 add_action( 'woocommerce_product_meta_start', array( self::class, 'add_button_to_product_page' ) );

		} elseif ('Divi' === $theme->name) {
			add_action( 'woocommerce_after_single_product_summary', array( self::class, 'add_button_to_product_page' ) );

		} else {
			
			//In Some site "woocommerce_product_meta_start" will not be fired. By adding 2 different hooks 2 buttons will nt be fired because
			//- in the add_button_to_product_page function we are checking whether the button alreday exists or not
			add_action( 'woocommerce_after_add_to_cart_form', array( self::class, 'add_button_to_product_page' ) );
			add_action( 'woocommerce_product_meta_start', array( self::class, 'add_button_to_product_page' ) );

		}
		if ( ( ( in_array( 'elementor/elementor.php', $activated_plugins ) || array_key_exists( 'elementor/elementor.php', $activated_plugins ) ) ) && ( 'Twenty Twenty-Three' !== $theme->name ) && ( 'Twenty Twenty-Two' !== $theme->name ) && ( 'Twenty Twenty-Four' !== $theme->name ) ) {
			add_action( 'woocommerce_single_product_summary', array( self::class, 'add_button_to_out_of_stock_product' ) );
		}

		add_filter( 'woocommerce_loop_add_to_cart_link', array( self::class, 'show_or_hide_add_to_cart' ), 10, 2 );

		//To make it compatible with elementor use woocommerce_before_add_to_cart_form hook
		add_filter( 'woocommerce_before_add_to_cart_form', array( self::class, 'show_or_hide_add_to_cart_on_product_page' ) );
		
		add_action( 'woocommerce_single_product_summary', array( self::class, 'show_or_hide_add_to_cart_on_product_page' ) );
		

		//to make compatible with twenty twenty 3 (hide price feature)
		add_filter( 'woocommerce_variable_sale_price_html', array( QuoteListModel::class, 'remove_product_price' ), 9999, 2 );
	
		add_filter( 'woocommerce_variable_price_html', array( QuoteListModel::class, 'remove_product_price' ), 9999, 2 );

		add_filter( 'woocommerce_get_price_html', array( QuoteListModel::class, 'remove_product_price' ), 9999, 2 );

			
		add_action( 'add_meta_boxes', array( self::class, 'elex_raq_add_meta_box_to_order' ) );

		// registering 
		add_action( 'rest_api_init', array( self::class, 'elex_raq_order_routes_register' ) );

		
		
		//To fix compatibility issue with seasidetms content composer plugin
		if ( ( in_array( 'seasidetms-content-composer/seasidetms-content-composer.php', $activated_plugins ) || array_key_exists( 'seasidetms-content-composer/seasidetms-content-composer.php', $activated_plugins ) ) ) {
			add_action('loop_start', array( self::class, 'add_elex_wrapper') );
		}

	}

	public static function add_elex_wrapper() {

		global $page;

		$quote_list_page     = get_page_by_path( '/add-to-quote-product-list' );
		$quote_list_page_url = isset( $quote_list_page->ID ) ? get_permalink( $quote_list_page->ID ) : ''; 
		if (is_page($quote_list_page->ID )) {
		// custom HTML content goes here
			echo '<div class="elex-rqst-quote-front-wrap">
			<div class="container" id="quote_list">
			</div>
			</div>';
		}
	}

	/** Rest API routes register callback*/
	public static function elex_raq_order_routes_register() {
		register_rest_route(
			self::$api_namespace . '/' . self::$api_version,
			'/request-quote',
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( QuoteListModel::class, 'elex_raq_place_order' ),
				'args'                => array(
					'address' => array(
						'required'          => true,
						'validate_callback' => array( QuoteListModel::class, 'elex_raq_address_validation' ),
					),
					'user_id' => array(
						'default'           => 0,
						'required'          => true,
						'validate_callback' => array( QuoteListModel::class, 'elex_raq_user_exists' ),
					),
				),
				'permission_callback' => array( QuoteListModel::class, 'elex_raq_validate_token' ),
			) 
		);
	}


	public static function elex_quote_received_page_shortcode() {

			$form_settings = FormSettingController::get_settings();
			$form_settings = FormSettingController::converToArray( $form_settings );
			$message       = $form_settings['success_message'];
		if ( '' !== $message ) {

			return '<h3>' . __( $message, 'elex-request-a-quote' ) . '</h3>';
		   
		} else {
		   
			return '<h3>' . __( 'Your request has been sent successfully', 'elex-request-a-quote' ) . '</h3>';
		   
		}   
	}
		/** Custom Meta box feature */
	public static function elex_raq_add_meta_box_to_order() {
		global $post;

		$order_id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
		if ( ! $post && '' === $order_id ) {
			return;
		}
		if ( $post ) {
			$order_id = $post->ID;
		}
		$order = wc_get_order($order_id);
			
		$screen = wc_get_container()->get(CustomOrdersTableController::class)->custom_orders_table_usage_is_enabled() ? wc_get_page_screen_id('shop-order') : 'shop_order';

		if ( ! empty( $order ) ) {
	
			$elex_raq_unqiue_key =  $order->get_meta( '_elex_raq_unique_order_key' );
			if ( 'elex_raq_' . $order_id === $elex_raq_unqiue_key ) {
	
				add_meta_box( 'woocommerce-order-meta-elex-raq', __( 'ELEX Quote Request Details', '' ), array( QuoteListModel::class, 'elex_raq_meta_box' ), $screen, 'normal', 'low' );
			}
		}   
	}

	
	public static function elex_place_order() {
		check_ajax_referer( 'request-a-quote-ajax-nonce', 'ajax_raq_nonce' );

		$post_data = map_deep( $_POST , 'sanitize_text_field' );

		if ( isset( $_POST['cart_item'] ) ) {
			$cart_items = map_deep( $_POST['cart_item'] , 'sanitize_text_field' );

		}

		parse_str( $cart_items, $formdata );// This will convert the string to array.
		
		foreach ( $formdata as $key => $value ) {

			if ( array_key_exists( $key , $post_data ) ) {
				$formdata[ $key ] = $post_data[ $key ];
			}
		}
	
		$address = QuoteListModel::elex_raq_create_order_address( $formdata, $_FILES );
		
		$elex_raq_default_type_count = FormSettingController::get_custom_fields_count();

		$order           = wc_create_order();
		$current_user_id = get_current_user_id();
		if ( 0 !== $current_user_id ) {
			$order->set_customer_id( $current_user_id );
		}
	
			$order->set_address( $address, 'billing' );
			$order->set_address( $address, 'shipping' );
			$order_id = $order->get_id();

			//Add comments to orders
			QuoteListModel::elex_raq_add_order_comments( $order, $formdata );
			QuoteListModel::elex_raq_add_custom_fields_meta( $order, $formdata, $_FILES, $elex_raq_default_type_count );

			$quote_list_id   = QuoteListModel::get_the_quote_list_id( $current_user_id );
			$quote_list_data = QuoteListModel::get_the_quote_list( $quote_list_id );
		if ( empty( $quote_list_data['items'] ) ) {
			wp_send_json_error( array( 'msg' => 'No item in the quote List' ) );
			die();
		}
			
			$form_settings = FormSettingController::get_settings();
			$form_settings = FormSettingController::converToArray( $form_settings );
			$form_fields   = $form_settings['fields'];

			$order->update_meta_data( '_elex_raq_default_form_details', $form_fields );
			$order->update_meta_data( '_elex_raq_unique_order_key', 'elex_raq_' . $order_id );

			$order->update_meta_data( 'elex_quote_list_id', $quote_list_id );
			$order->update_meta_data( 'elex_quote_data', $quote_list_data );

			$order->save(); 

			QuoteListModel::elex_raq_add_products_order( $order, $quote_list_data['items'] );
			QuoteListModel::update_the_quote_list( $quote_list_id, QuoteListModel::$quote_status['quote_requested'] );
			
			wp_send_json_success(
				array(
					'message'         => $form_settings['success_message'],
					'redirection_url' => $form_settings['redirection_url'],
				)
			);
			
	}



	public  static function set_guest_user_cookie() {
		
		if ( ! headers_sent() && ! isset( $_COOKIE[ self::SESSION_KEY_COOKIE_NAME ] ) && ( empty( $_COOKIE[ self::SESSION_KEY_COOKIE_NAME ] ) ) ) {
			QuoteListModel::setCoockie_for_guest_user();

		}

	}


	public static function elex_raq_add_to_quote() {

		check_ajax_referer( 'request-a-quote-ajax-nonce', 'ajax_raq_nonce' );

		if ( ! isset( $_POST['data'] ) || empty( $_POST['data'] ) ) {
			wp_send_json_error( array( 'msg' => 'select product' ) );
			die();
		}
		$product_data = ( isset( $_POST['data'] ) && ! empty( $_POST['data'] ) ) ? map_deep( $_POST['data'], 'sanitize_text_field' ) : array();
		
		
		$product_data_temp = array();

		$user_id = get_current_user_id();

		$quote_list_id = QuoteListModel::get_the_quote_list_id( $user_id );

		$check_if_product_exist = QuoteListModel::find_product_in_quote( $quote_list_id, $product_data );

		if ( null === $check_if_product_exist ) {

			QuoteListModel::add_products_to_quote( $quote_list_id, $product_data );
			
		}
		if ( !empty( $check_if_product_exist ) ) {

			QuoteListModel::update_variation_quantity( $quote_list_id, $product_data );
		}

			ob_start();
			$product_data = QuoteListModel::get_the_product_details( $product_data );
		
			self::add_quote_button( $product_data );
			$html_content = ob_get_clean();
			wp_send_json_success(
				array(
					'success_toast'   => self::get_success_toast(),
					'html'            => $html_content,
					'quote_list_data' => self::get_the_quote_list_data(),
					'type' => $product_data['type'],
				)
			);


	}

	public static function get_success_toast() {
		$settings        = SettingsController::get_settings();
		$success_message = $settings['general']['add_to_quote_success_message'];
		ob_start();
		include ELEX_RAQ_VIEW_PATH . 'quote/success_toast.php';
		return ob_get_clean();

	}
	public static function elex_raq_update_quantity() {

		check_ajax_referer( 'request-a-quote-ajax-nonce', 'ajax_raq_nonce' );
		if ( ! isset( $_POST['product_id'] ) || empty( $_POST['product_id'] ) ) {
			wp_send_json_error( array( 'msg' => 'select product to to update' ) );
			die();
		}

		if ( ! isset( $_POST['quantity'] ) ) {
			wp_send_json_error( array( 'msg' => 'could not update' ) );
			die();
		}

		
		$product_id   = isset( $_POST['product_id'] ) && ! empty( $_POST['product_id'] ) ? sanitize_text_field( $_POST['product_id'] ) : '';
		$quantity     = isset( $_POST['quantity'] ) && ( null !== $_POST['quantity'] ) ? sanitize_text_field( $_POST['quantity'] ) : '';
		$variation_id = isset( $_POST['variation_id'] ) && ( ! empty( $_POST['variation_id'] ) ) ? sanitize_text_field( $_POST['variation_id'] ) : 0;

		$user_id       = get_current_user_id();
		$quote_list_id = QuoteListModel::get_the_quote_list_id( $user_id );
		$result        = QuoteListModel::update_quantity( $quote_list_id , $product_id, $quantity, $variation_id );
		
		if ( $result ) {
			wp_send_json_success(
				array(
					'msg'             => 'update',
					'quote_list_data' => self::get_the_quote_list_data(),
				)
			);
		}

	}

	public static function update_list() {


		check_ajax_referer( 'request-a-quote-ajax-nonce', 'ajax_raq_nonce' );

		$product_data = ( isset( $_POST['data'] ) && ( ! empty( $_POST['data'] ) ) ) ? map_deep( $_POST['data'] , 'sanitize_text_field' ) : array();
		
		if ( empty( $product_data ) ) {
			wp_send_json_error( array( 'msg' => 'No Products' ) );
			die();
		}
	
		$user_id       = get_current_user_id();
		$quote_list_id = QuoteListModel::get_the_quote_list_id( $user_id );
		$result        = QuoteListModel::update_list( $quote_list_id , $product_data );
		
		if ( $result ) {
			wp_send_json_success(
				array(
					'msg'             => 'update',
					'quote_list_data' => self::get_the_quote_list_data(),
				)
			);
		}


	}
	public static function eleX_raq_delete_item() {

		check_ajax_referer( 'request-a-quote-ajax-nonce', 'ajax_raq_nonce' );

		if ( ! isset( $_POST['product_id'] ) || empty( $_POST['product_id'] ) ) {
			wp_send_json_error( array( 'msg' => 'select product to to delete' ) );
			die();
		}
		
		$product_id   = isset( $_POST['product_id'] ) && ! empty( $_POST['product_id'] ) ? sanitize_text_field( $_POST['product_id'] ) : '';
		$variation_id = isset( $_POST['variation_id'] ) && ! empty( $_POST['variation_id'] ) ? sanitize_text_field( $_POST['variation_id'] ) : 0;
		
		$user_id       = get_current_user_id();
		$quote_list_id = QuoteListModel::get_the_quote_list_id( $user_id );

		$result = QuoteListModel::delete_item_in_quote( $quote_list_id , $product_id , $variation_id );

		if ( $result ) {
			wp_send_json_success(
				array(
					'msg'             => 'deleted',
					'quote_list_data' => self::get_the_quote_list_data(),
				)
			);
		}

		
	}
	public static function elex_raq_clear_list() {

		check_ajax_referer( 'request-a-quote-ajax-nonce', 'ajax_raq_nonce' );

		
		$user_id       = get_current_user_id();
		$quote_list_id = QuoteListModel::get_the_quote_list_id( $user_id );

		$result = QuoteListModel::clear_list( $quote_list_id );
		if ( $result ) {
			wp_send_json_success(
				array(
					'msg'             => 'clear',
					'quote_list_data' => self::get_the_quote_list_data(),
				)
			);
		}
		
	}
	public static function get_the_quote_list_data() {


		$quote_data    = array();
		$user_id       = get_current_user_id();
		$quote_list_id = QuoteListModel::get_the_quote_list_id( $user_id );

		$quote_data['quote_list']          = QuoteListModel::get_the_quote_list( $quote_list_id );
		$quote_data['settings']            = ListPageController::get_settings( 'quote_list_page' , '' );
		$quote_data['additional_settings'] = ListPageController::get_settings( 'additional_options', '' );
		$quote_data['widget']              = WidgetController::get_settings();
		$quote_data['general']             = SettingsController::get_settings();
		$quote_data['page_url']            = $quote_data['settings']['selected_page'];
		if ( 0 === $user_id &&  ( false === QuoteListModel::is_guest_user_allowed() ) ) {
			$quote_data['page_url'] = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		}
		return $quote_data;

	}

	
	public static function get_the_quote_list() {

		$quote_data = array();
		$user_id    = get_current_user_id();

		$quote_list_id = QuoteListModel::get_the_quote_list_id( $user_id );
		$quote_list_id = QuoteListModel::get_the_quote_list_id( $user_id );
		$form_settings = FormSettingController::get_settings();
		$form_settings = $form_settings->to_array();

		$quote_data['quote_list']          = QuoteListModel::get_the_quote_list( $quote_list_id );
		$quote_data['settings']            = ListPageController::get_settings( 'quote_list_page' , '' );
		$quote_data['additional_settings'] = ListPageController::get_settings( 'additional_options', '' );
		$quote_data['widget']              = WidgetController::get_settings();
		$quote_data['general']             = SettingsController::get_settings();
		$quote_data['empty_image']         = ELEX_RAQ_IMAGES . 'Frontend empty Quote list illustration.svg';
		$quote_data['default_image']       = ELEX_RAQ_IMAGES . 'Dummy-Person.jpg';
		$quote_data['form_settings']       = $form_settings;
		$quote_data['page_url']            = $quote_data['settings']['selected_page'];
		if ( 0 === $user_id &&  ( false === QuoteListModel::is_guest_user_allowed() ) ) {
			$quote_data['page_url'] = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		}

		wp_send_json_success( $quote_data );

	}

	public static function elex_quote_request_list_shortcode() {

		$quote_data      = ListPageController::get_settings( 'quote_list_page', '' );
		$show_powered_by = $quote_data['show_prowered_by'];
		ob_start();

		include_once ELEX_RAQ_VIEW_PATH . 'quote/quote_list_main.php';
		return ob_get_clean();
	}

	public static function add_button_to_shop_page() {
		

		$product_info = QuoteListModel::add_button_to_shop_product_page( 'shop' );
		

		if ( empty( $product_info ) && ! isset( $product_info ) ) {
				return;         
		}
		$product_info['success_message'] = QuoteListModel::get_add_to_quote_success_msg();

		self::add_quote_button( $product_info );

	}


/**
 * Function is to decide whether we need to add the add to quote button to the variaable product if all the variations are added to the quote list it will return false else true.
 *
 * @param [array] $product_data
 * @param [int] $quote_list_id
 * @return boolean
 */
	public static function is_button_needed( $product_data, $quote_list_id) {

		if ('variable' == $product_data['type']) {
			$product        = wc_get_product($product_data['id']);
			$variations     = $product->get_available_variations();
			$variations_ids = wp_list_pluck( $variations, 'variation_id' );

			$variations_count_in_quotelist = QuoteListModel::find_variation_count_in_quote( $quote_list_id, $product_data);
			if ($variations_count_in_quotelist
			 < count( $variations_ids ) ) {
				return true;
			}

		}
		return false;
	}

	public static function add_quote_button( $product_data ) {

		
		$list_page_settings = ListPageSettings::load();

		$list_page_settings = $list_page_settings->to_array();
		$list_page_settings = $list_page_settings[ 'quote_list_page' ];
		$page_url           = $list_page_settings['selected_page'];

		$user_id = get_current_user_id();
	
		$quote_list_id = QuoteListModel::get_the_quote_list_id( $user_id );

		if ( 0 === $user_id && ( false === QuoteListModel::is_guest_user_allowed() ) ) {
			$page_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		}
		$theme      = wp_get_theme(); // gets the current theme
		$theme_name ='';
		if (!empty($theme)) {
			$product_data['theme'] =$theme->name;
		}
		$product_data['button_label'] = __($product_data['button_label'] , 'elex-request-a-quote');

		$settings                        = SettingsController::get_settings();
		$success_message                 = $settings['general']['add_to_quote_success_message'];
		$product_data['success_message'] = $success_message;


		$check_if_product_exist = QuoteListModel::find_product_in_quote( $quote_list_id, $product_data );
		
		if ( null !== $check_if_product_exist && ( 'variable' === $product_data['type'] || 'composite' === $product_data['type'] ) ) {
			include ELEX_RAQ_VIEW_PATH . 'quote/view_quote_list.php';
				include ELEX_RAQ_VIEW_PATH . 'quote/add_to_quote.php';

			return;
		} elseif ( ! empty( $check_if_product_exist ) && 'simple' === $product_data['type'] ) {
			include ELEX_RAQ_VIEW_PATH . 'quote/view_quote_list.php';
			return;
		} elseif ( 'external' !== $product_data['type'] ) {
			include ELEX_RAQ_VIEW_PATH . 'quote/add_to_quote.php';
		}
		
	}

	/**
	 * To add quote button to out of stock when elementor plugin is activated.
	 *
	 * @return void
	 */

	public static function add_button_to_out_of_stock_product() {
		global $product;
	
		if ('outofstock' !== $product->get_stock_status()) {
			return;
		}
		if ('hide_for_out_of_stock' === QuoteListModel::include_exclude_based_on_stock()) {
			return;
		}
		$product_data = QuoteListModel::add_button_to_shop_product_page( 'product' );

		if ( empty( $product_data ) && ! isset( $product_data ) ) {
			return;         
		}
		if ( true === self::check_if_button_exists($product_data['id'])  ) {
			return;
		}
		self::add_quote_button( $product_data );

	}

	/**
	 * To Check if the Add to Quote button is already added to the product page.To avoid duplicate or No Button issue with elementor.
	 *
	 * @return boolean
	 */

	public static function  check_if_button_exists( $product_id) {
		if ( isset($_SESSION['product_page_button_' . $product_id]) ) {
			return true;
		}
		$_SESSION['product_page_button_' . $product_id] = true;
		return false;

	}

	public static function add_button_to_product_page() {
	
		global $product;
		$product_data = QuoteListModel::add_button_to_shop_product_page( 'product' );

		if ( empty( $product_data ) && ! isset( $product_data )) {
			return;         
		}
		if ( true === self::check_if_button_exists($product_data['id'])   ) {
			return;
		}
		
		self::add_quote_button( $product_data );

	}


	public static function show_or_hide_add_to_cart( $add_to_cart_html, $product ) {

		
		$result = QuoteListModel::show_or_hide_add_to_cart( 'shop' );
		QuoteListModel::show_or_hide_price();
		
		if ( true === $result ) {
			return '';
		}
		return $add_to_cart_html;
		
	}


	public static function show_or_hide_add_to_cart_on_product_page() {
		global $post;
		$product = wc_get_product( $post->ID );
		if (is_bool($product)) {
			return;
		}


		$result = QuoteListModel::show_or_hide_add_to_cart( 'product' );
		QuoteListModel::show_or_hide_price();

		
		if ( true === $result ) {

			if ( $product->get_type() == 'variable' ) {
				remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
				add_action( 'woocommerce_single_variation', 'woocommerce_quantity_input', 10 );
			} else {
				wc_enqueue_js(
					"
						jQuery('button[name=add-to-cart]').remove();
						jQuery('button.single_add_to_cart_button:nth-of-type(2)').remove();
						"
				);
			}
		}
			
		
	}

	
}
