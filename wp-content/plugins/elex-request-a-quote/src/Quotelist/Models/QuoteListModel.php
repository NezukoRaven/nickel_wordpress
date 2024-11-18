<?php

namespace Elex\RequestAQuote\Quotelist\Models;

use Elex\RequestAQuote\Quotelist\Models\QuoteListItems;

use Elex\RequestAQuote\Migrate;
use Elex\RequestAQuote\Settings\Models\GeneralSettings;
use Elex\RequestAQuote\Settings\SettingsController;
use Elex\RequestAQuote\QuoteList\QuoteListController;
use Elex\RequestAQuote\FormSetting\FormSettingController;
use Automattic\WooCommerce\Utilities\OrderUtil;
use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;



class QuoteListModel {
	
	public static $quote_status = array(
		'add_to_quote'    => 1,
		'quote_requested' => 2,

	);

	public static function get_the_quote_list_id( $user_id ) {
		$query = wpFluent()->table( Migrate::TABLE_QUOTE_LIST )
			->where( 'status_id', '=', 1 );
			

		if ( 0 === $user_id ) {
			$session_key = isset( $_COOKIE[ QuoteListController::SESSION_KEY_COOKIE_NAME ] ) ? sanitize_text_field( $_COOKIE[ QuoteListController::SESSION_KEY_COOKIE_NAME ] ) : '';

			if ( '' === $session_key ) {
				return;
			}
			$query->where( 'session_key', '=', $session_key );
		} else {
			$query->where( 'user_id', '=', $user_id );

		}

		$quote_list_id = $query->select( 'id' )->first();


		if ( null === $quote_list_id ) {
			return self::add_to_quote_list( $user_id );
		}

		return $quote_list_id->id;
	}


	public static function setCoockie_for_guest_user() {

		setcookie( QuoteListController::SESSION_KEY_COOKIE_NAME, uniqid() );

	}

	public static function quote_list_id_for_guest_through_api( $session_key ) {
		$query = wpFluent()->table( Migrate::TABLE_QUOTE_LIST )
			->where( 'status_id', '=', 1 );
		$query->where( 'session_key', '=', $session_key );
		$quote_list_id = $query->select( 'id' )->first();
		return $quote_list_id->id;
	}
	

	public static function find_product_in_quote( $quote_list_id, $product_data ) {
		
		$query = wpFluent()->table( Migrate::TABLE_QUOTE_PRODUCTS )
			->where( 'quote_list_id' , '=' , $quote_list_id )
			->where( 'product_id' , '=' , $product_data['id'] )
			->select( '*' );
		if ( isset( $product_data['variation_id'] ) && ! empty( $product_data['variation_id'] ) ) {
			$query = $query->where( 'variation_id' , '=' , $product_data['variation_id'] );
		}
		return  $query->first();
	}

	public static function find_variation_count_in_quote( $quote_list_id, $product_data ) {
		
		$query = wpFluent()->table(Migrate::TABLE_QUOTE_PRODUCTS)
				->where('quote_list_id', '=', $quote_list_id)
				->where('product_id', '=', $product_data['id'])
				->select(wpFluent()->raw( 'COUNT(*) as count' ));
		if (isset($product_data['variation_id']) && !empty($product_data['variation_id'])) {
			$query = $query->where('variation_id', '=', $product_data['variation_id']);
		}
			$result = $query->get();
			$count  = $result[0]->count;
			return $count;
	}

	public static function add_to_quote_list( $user_id ) {
		
		$session_key = isset( $_COOKIE[ QuoteListController::SESSION_KEY_COOKIE_NAME ] ) ? sanitize_text_field( $_COOKIE[ QuoteListController::SESSION_KEY_COOKIE_NAME ] ) : '';
		
		if ( '' === $session_key && ! is_user_logged_in() ) {
			return;
		}
		
		$quote_list_data = array(
			'user_id'     => $user_id,
			'session_key' => $session_key,
			'status_id'   => 1,
			'created_at'  => current_time( 'mysql' ),
			'updated_at'  => current_time( 'mysql' ),
		);
		
		return wpFluent()->table( Migrate::TABLE_QUOTE_LIST )
			->insert( $quote_list_data );
	}

	public static function add_products_to_quote( $quote_list_id, $product_data ) {

		$data = array(
			'product_id'    => $product_data['id'],
			'quantity'      => $product_data['quantity'],
			'quote_list_id' => $quote_list_id,
			'variation_id'  => $product_data['variation_id'],
		);

		wpFluent()->table( Migrate::TABLE_QUOTE_PRODUCTS )
			->insert( $data );

			$product = wc_get_product( $product_data['id'] );
		if ( $product->get_type() === 'composite' ) {
			self::save_composite_data( $product_data , $quote_list_id );

		}
		if ( $product->get_type() === 'variable' ) {
			self::save_attributes( $product_data , $quote_list_id );

		}
		self::update_quote_list_updated_time( $quote_list_id );
		return true;
	}

	public static function update_variation_quantity( $quote_list_id, $product_data ) {

		$existingQuantity = wpFluent()
					->table(Migrate::TABLE_QUOTE_PRODUCTS)
					->where('product_id', '=', $product_data['id'])
					->where('quote_list_id', '=', $quote_list_id)
					->where('variation_id', '=', $product_data['variation_id'])
					->select('quantity')
					->first();

		// Calculate the updated quantity
		$updatedQuantity = $existingQuantity->quantity + $product_data['quantity'];
		

		wpFluent()->table( Migrate::TABLE_QUOTE_PRODUCTS )
			->where( 'product_id', '=', $product_data['id'] )
			->where( 'quote_list_id', '=', $quote_list_id )
			->where( 'variation_id', '=', $product_data['variation_id'] )
			->update(
				array(
					'quantity' => $updatedQuantity,
				)
			);

		self::update_quote_list_updated_time( $quote_list_id );
		return true;
		
	}



	public static function save_composite_data( $product_data, $quote_list_id ) {
		$products_meta_array = array();
		$unescapedString     = stripslashes( $product_data['composite_data'] );

			$data_from_front_end = json_decode( $unescapedString , true );
			$product             = wc_get_product( $product_data['id'] );
		if ( $product->get_type() === 'composite' ) {
			$stord_composite_data = get_option( 'elex_composite_data', array() );
			$stord_composite_data[ $quote_list_id ][ $product_data['id'] ] = $data_from_front_end ;
			update_option( 'elex_composite_data', $stord_composite_data );

		}

	}

	public static function save_attributes( $product_data, $quote_list_id ) {
			$products_meta_array = array();
			$unescapedString     = stripslashes( $product_data['attributes'] );
			$data_from_front_end = json_decode( $unescapedString , true );
			$product             = wc_get_product( $product_data['id'] );
		if ( $product->get_type() === 'variable' ) {
			$stord_attribute_data = get_option( 'elex_variation_attributes', array() );
			$stord_attribute_data[ $quote_list_id ][ $product_data['id'] ] = $data_from_front_end ;
			update_option( 'elex_variation_attributes', $stord_attribute_data );

		}

	}

	public static function update_quote_list_updated_time( $quote_list_id ) {
		wpFluent()->table( Migrate::TABLE_QUOTE_LIST )
				  ->where( 'id' , '=', $quote_list_id )
				  ->update( array( 'updated_at' => current_time( 'mysql' ) ) );
	}

	public  static function update_quantity( $quote_list_id, $product_id, $quantity, $variation_id ) {

		if ( 0 === $quantity ) {
			self::delete_item_in_quote( $product_id, $quantity, $quote_list_id , $variation_id );
			return true;
		}
		wpFluent()->table( Migrate::TABLE_QUOTE_PRODUCTS )
			->where( 'product_id', '=', $product_id )
			->where( 'quote_list_id', '=', $quote_list_id )
			->where( 'variation_id', '=', $variation_id )
			->update(
				array(
					'quantity' => $quantity,
				)
			);

		self::update_quote_list_updated_time( $quote_list_id );
		return true;

	}

	public static function get_add_to_quote_success_msg() {
		$data = SettingsController::get_settings();
		
		return  $data['general']['add_to_quote_success_message'];

	}

	public static function update_list( $quote_list_id, $product_data ) {

		foreach ( $product_data as $item ) {
			self::update_quantity( $quote_list_id , $item['product_id'] , $item['quantity'] , $item['variation_id'] );
		}
		return true;

	}

	public static  function delete_item_in_quote( $quote_list_id, $product_id, $variation_id ) {

		if ( empty( $quote_list_id ) ) {
			return false;
		}

		wpFluent()->table( Migrate::TABLE_QUOTE_PRODUCTS )
			->where( 'product_id', '=', $product_id )
			->where( 'quote_list_id', '=', $quote_list_id )
			->where( 'variation_id', '=', $variation_id )
			->delete();

		self::update_quote_list_updated_time( $quote_list_id );
		return true;

	}

	public static  function clear_list( $quote_list_id ) {

		wpFluent()->table( Migrate::TABLE_QUOTE_PRODUCTS )
			->where( 'quote_list_id', '=', $quote_list_id )
			->delete();

		wpFluent()->table( Migrate::TABLE_QUOTE_LIST )
			->where( 'id', '=', $quote_list_id )
			->delete();

		return true;
	}

	public static function get_the_quote_list( $quote_list_id ) {
		
		$quote_list         = wpFluent()->table( Migrate::TABLE_QUOTE_PRODUCTS )
			->where( 'quote_list_id', '=', $quote_list_id )
			->select( 'product_id' , 'quantity' , 'variation_id' )
			->get();
			$quote_list_obj = new QuoteListItems( $quote_list , $quote_list_id );
			
			$quote_list_obj = $quote_list_obj->get_list();
			return $quote_list_obj;

	}

	public static function elex_raq_add_products_order( $order, $items ) {

		foreach ( $items as $key => $value ) {

			$args = array();

			if ( 'composite' === $value['type'] && true === $value['child'] ) {
				$args['subtotal'] = 0;
				$args['total']    = 0;

			}

			if ( ! empty( $value['product_id'] ) && ! empty( $value['variation_id'] ) && ! empty( $value['quantity'] ) ) {
				$product_variation = new \WC_Product_Variation( $value['variation_id'] );
				if ( empty( $product_variation ) ) { // If product is removed from shop dont add it to order.
					continue;
				}


				foreach ( $product_variation->get_variation_attributes()  as $attribute => $attribute_value ) {
					$args['variation'][ $attribute ] = $attribute_value;
				}

				
			

				$order->add_product( $product_variation, $value['quantity'], $args );


			} elseif ( ! empty( $value['product_id'] ) && ! empty( $value['quantity'] ) ) {
				$product_simple_external = wc_get_product( $value['product_id'] );
				if ( ! empty( $product_simple_external ) ) { // If product is removed from shop dont add it to order.
					$order->add_product( $product_simple_external, $value['quantity'] , $args );
				}
			}
		}

		
		$order->calculate_totals();
		$order->update_status( 'quote-requested', 'Quote Requested', true );


	}

	public static function update_the_quote_list( $quote_list_id, $status ) {

				wpFluent()->table( Migrate::TABLE_QUOTE_LIST )
				  ->where( 'id' , '=', $quote_list_id )
				->update(
					array(
						'updated_at' => current_time( 'mysql' ),
						'status_id'  => $status, 
					)
				);
	}

	public static function get_composite_product_data( $product_id, $order_id ) {
		$product_detals = array();
		$order          = new \WC_Order( $order_id );
		$quote_data     = $order->get_meta( 'elex_quote_data' );
		foreach ( $quote_data['items'] as $data ) {
			if ( $data['product_id'] === $product_id ) {
				$product_detals['product_id'] = $data['product_id'];
				$product_detals['title']      = $data['title'];
				$product_detals['quantity']   = $data['quantity'];
				$product_detals['image_url']  = $data['image_url'];
				$product_detals['item_cost']  = $data['item_cost'];
				$product_detals['item_total'] = $data['item_total'];

			}
		}


	}


	public static function is_button_on_shop_enabled() {
		$data = SettingsController::get_settings();
		return  true === $data['general']['button_on_shop_page'];
	}

	public static function is_button_on_product_enabled() {
		$data = SettingsController::get_settings();
		return  true === $data['general']['button_on_product_page'];
	}


	public static  function limit_on_product_enabled() {
		$data = SettingsController::get_settings();

		return  true === $data['general']['limit_button_on_certain_products']['enabled'];
	}

	public  static function exclude_products_enabled() {
		return false;
	}

	public  static function role_based_filter_enabled() {
		return false;
	}

	public  static  function get_excluded_users() {
		$data = SettingsController::get_settings();

		return $data['general']['role_based_filter']['exclude_roles'];
	}

	public static   function get_included_users() {
		$data = SettingsController::get_settings();

		return   $data['general']['role_based_filter']['include_roles'];
	}

	public static function is_guest_user_allowed() {
		return true;
	}

	public static function get_button_label() {
		$data = SettingsController::get_settings();

		return  $data['general']['button_label'];
	}

	public static  function get_button_color() {
		$data = SettingsController::get_settings();

		return   "background-color:{$data['general']['button_default_color']}";
	}

	public  static function include_exclude_based_on_stock() {
		return 'show_for_all_products';
	}


	public static function add_to_quote_success_message() {
		
		$data = SettingsController::get_settings();

		return   $data['general']['add_to_quote_success_message'];
	}

	public static  function is_hide_add_cart_on_shop_enabled() {
		$data = SettingsController::get_settings();
		return  true === $data['hide_add_to_cart']['button_on_shop_page'];
	}

	public static  function is_hide_add_cart_on_product_enabled() {
		$data = SettingsController::get_settings();

		return  true === $data['hide_add_to_cart']['button_on_product_page'];
	}

	public static function is_hide_price_enabled() {
		$data = SettingsController::get_settings();
		return  true === $data['hide_add_to_cart']['hide_price'];
	}


	public static function hide_cart_exclude_products() {
	return array();
	}

	public static function hide_cart_exclude_products_enabled() {
		return false;
	}

	public static function hide_cart_exclude_roles_enabled() {
		return false;
	}

	public static function hide_cart_exclude_roles() {
	return false;
	}
	
	

	public static function get_current_user_role() {
	
		if ( is_user_logged_in() ) {
			$user  = wp_get_current_user();
			$roles = ( array ) $user->roles;
			return $roles[0];
		} else {
			return 'unregistered';
		}

	}
	//include exclude for add to quote
	public static function get_include_exclude_items( $term ) {
	
		$settings                     = SettingsController::get_settings();
		$include_products_by_category = isset( $settings['general']['limit_button_on_certain_products']['include_products_by_category'] ) && ( ! empty( $settings['general']['limit_button_on_certain_products']['include_products_by_category'] ) ) ? $settings['general']['limit_button_on_certain_products']['include_products_by_category'] : array();
		$exclude_products_by_category = array();


		$include_products_by_name = isset( $settings['general']['limit_button_on_certain_products']['include_products_by_name'] ) && ! empty( $settings['general']['limit_button_on_certain_products']['include_products_by_name'] ) ? $settings['general']['limit_button_on_certain_products']['include_products_by_name'] : array();
		$exclude_products_by_name =  array();

		$include_products_by_tag = isset( $settings['general']['limit_button_on_certain_products']['include_products_by_tag'] ) && ! empty( $settings['general']['limit_button_on_certain_products']['include_products_by_tag'] ) ? $settings['general']['limit_button_on_certain_products']['include_products_by_tag'] : array();
		$exclude_products_by_tag = array();

		
		 $by_cat = ( 'include' === $term ) ? $include_products_by_category : $exclude_products_by_category;
		 
		 $by_name = ( 'include' === $term ) ? $include_products_by_name :
		 $exclude_products_by_name;
		 $by_tag  = ( 'include' === $term ) ? $include_products_by_tag :
		 $exclude_products_by_tag;

		$product_ids = self::get_product_ids( $by_cat , $by_name , $by_tag );
		return $product_ids;
		
	}



	public static function get_product_ids( $by_category, $by_name, $by_tag ) {

		$product_id_of_categories_selected = array();
		if ( ! empty( $by_category ) ) {

			$product_id_of_categories_selected = get_posts(
				array(
					'post_type'   => 'product',
					'numberposts' => -1,
					'post_status' => 'publish',
					'fields'      => 'ids',
					'tax_query'   => array(
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'id',
							'terms'    => $by_category,
							'operator' => 'IN',
						),
					),
				)
			);

		}

		// Include Tags.
		$product_id_of_tags_selected = array();
		if ( isset( $by_tag ) ) {

			$product_id_of_tags_selected = get_posts(
				array(
					'post_type'   => 'product',
					'numberposts' => -1,
					'post_status' => 'publish',
					'fields'      => 'ids',
					'tax_query'   => array(
						array(
							'taxonomy' => 'product_tag',
							'field'    => 'id',
							'terms'    => $by_tag,
							'operator' => 'IN',
						),
					),
				)
			);

		}
		$product_ids = array_unique( array_merge( $product_id_of_categories_selected, $product_id_of_tags_selected, $by_name ) );
		return $product_ids;

	}


//exclude products for hide add to cart
	public static function get_exclude_items_add_to_cart() {
	
		$settings    = SettingsController::get_settings();
		$by_category = isset( $settings['hide_add_to_cart']['exclude_products']['by_category'] ) && ! empty( $settings['hide_add_to_cart']['exclude_products']['by_category'] ) ? $settings['hide_add_to_cart']['exclude_products']['by_category'] : array();
		$by_name     = isset( $settings['hide_add_to_cart']['exclude_products']['by_name'] ) && ! empty( $settings['hide_add_to_cart']['exclude_products']['by_name'] ) ? $settings['hide_add_to_cart']['exclude_products']['by_name'] : array();
		$by_tag      = isset( $settings['hide_add_to_cart']['exclude_products']['by_tag'] ) && ! empty( $settings['hide_add_to_cart']['exclude_products']['by_tag'] ) ? $settings['hide_add_to_cart']['exclude_products']['by_tag'] : array();


		$product_ids = self::get_product_ids( $by_category , $by_name , $by_tag );
	
		return $product_ids;
		
	}

	public static function show_or_hide_price() {

		global $product;
		$is_hide_price_enabled = self::is_hide_price_enabled();
	
		if ( true !== $is_hide_price_enabled ) {
			return;
		}
		add_filter( 'woocommerce_variable_sale_price_html', array( self::class, 'remove_product_price' ), 9999, 2 );
 
		add_filter( 'woocommerce_variable_price_html', array( self::class, 'remove_product_price' ), 9999, 2 );

		add_filter( 'woocommerce_get_price_html', array( self::class, 'remove_product_price' ), 9999, 2 );
	}

	public static function remove_product_price( $price, $product ) {

		$is_hide_price_enabled = self::is_hide_price_enabled();

		if ( true !== $is_hide_price_enabled ) {
			return $price;
		}

		$is_exclude_products_enabled = self::hide_cart_exclude_products_enabled();
		if ( true === $is_exclude_products_enabled ) {
			$exclude_products_list = self::get_exclude_items_add_to_cart();

		}
		$is_role_based_enabled = self::hide_cart_exclude_roles_enabled();
		$exclude_users         = array();
		if ( true === $is_role_based_enabled ) {
			$exclude_users = self::hide_cart_exclude_roles();

		}

		if ( $is_exclude_products_enabled && ( in_array( $product->get_id(), $exclude_products_list ) )

		 ) {
			return $price;
			
		}
		if ( $is_role_based_enabled &&  in_array(
				self::get_current_user_role(),
				$exclude_users
			)) {
				return $price;
		}

		//To make it compatible with elementor
		wc_enqueue_js(
			"	
		//Fires whenever variation selects are changed
		jQuery( '.variations_form' ).on( 'woocommerce_variation_select_change', function () {
			// Fires when variation option isn't selected
			jQuery('form.variations_form').on('show_variation',function(event, data){
				jQuery('.single_variation').hide();
				
			});	
		});
	"
		);

		$price = '';
		return $price;
		
	}

	public static function show_or_hide_add_to_cart( $currentpage ) {
		global $product;
		$product_array = array();

		$is_button_enabled = ( 'shop' === $currentpage ) ? self::is_hide_add_cart_on_shop_enabled() : self::is_hide_add_cart_on_product_enabled();

		if ( false === $is_button_enabled ) {
			return false;
		}
		

		$is_exclude_products_enabled = self::hide_cart_exclude_products_enabled();
		if ( true === $is_exclude_products_enabled ) {
			$exclude_products_list = self::get_exclude_items_add_to_cart();

		}
		$is_role_based_enabled = self::hide_cart_exclude_roles_enabled();
		$exclude_users         = array();
		if ( true === $is_role_based_enabled ) {
			$exclude_users = self::hide_cart_exclude_roles();

		}
		if ( true === $is_button_enabled &&   false === $is_role_based_enabled && false === $is_exclude_products_enabled  ) {
			return true;
		}

		if ( $is_role_based_enabled && in_array( self::get_current_user_role(), $exclude_users ) ) {
			return false;
		}
		if (( $is_exclude_products_enabled && ( ! in_array( $product->get_id(), $exclude_products_list ) )

		 ) || ( $is_role_based_enabled &&  ! in_array( self::get_current_user_role(), $exclude_users ) ) ) {
			
			return true;
		}
		

		return false;
		

	}


	public  static function add_button_to_shop_product_page( $currentpage ) {
		global $product;
		if (null == $product) {
			return;
		}
		if ( 'composite' === $product->get_type() && 'shop' === $currentpage ) {
			return;
		}
		$product_array = array();

		$is_button_enabled = ( 'shop' === $currentpage ) ? self::is_button_on_shop_enabled() : self::is_button_on_product_enabled();
		if ( false === $is_button_enabled ) {
			return;
		}
		

		$include_products_list = array();
		$exclude_products_list = array();
		$include_users         = array();
		$exclude_users         = array();
	
		$is_include_products_enabled = self::limit_on_product_enabled();
		if ( true === $is_include_products_enabled ) {
			$include_products_list = self::get_include_exclude_items( 'include' );
		}

		$is_exclude_products_enabled = self::exclude_products_enabled();
		if ( true === $is_exclude_products_enabled ) {
			$exclude_products_list = self::get_include_exclude_items( 'exclude' );
		}
		$is_role_based_enabled = self::role_based_filter_enabled();
		if ( true === $is_role_based_enabled ) {
			$include_users = self::get_included_users();
			$exclude_users = self::get_excluded_users();
		}
		$is_guest_allowed = self::is_guest_user_allowed();

	
		$exist_in_both = false;
		if ( ( $is_exclude_products_enabled && in_array( $product->get_id(), $exclude_products_list ) &&
		( $is_include_products_enabled && in_array( $product->get_id(), $include_products_list ) ) ) ) {
			$index = array_search( $product->get_id() , $include_products_list );
			if ( false !== $index ) {
				unset( $include_products_list[ $index ] );
				$exist_in_both = true;
			}
		}

		if ( ( $is_exclude_products_enabled && in_array( $product->get_id(), $exclude_products_list ) ) 
		|| 
		
		( ! $exist_in_both && $is_include_products_enabled && ! in_array( $product->get_id(), $include_products_list ) )
		||
		( $exist_in_both )
			 ||
			 ( in_array( self::get_current_user_role(), $exclude_users ) && $is_role_based_enabled && count($exclude_users) > 0 )
			||
			 ( 'hide_for_out_of_stock' === self::include_exclude_based_on_stock() && 
			'outofstock' === $product->get_stock_status() ) 
			||
			( 'show_for_out_of_stock_only' === self::include_exclude_based_on_stock() && 
			'outofstock' !== $product->get_stock_status()
			 ) 
			 ||
			 ( $is_role_based_enabled && count($include_users) > 0 && ! in_array(self::get_current_user_role() , $include_users ) )
			 ) {

				
				 return;
		}
		if ( ( 'shop' === $currentpage ) && ( $product->get_type() === 'variable' || 'grouped' === $product->get_type() ) ) {
			return;
		}
		if ( 'grouped' === $product->get_type() ) {
			$get_post_ids                       = $product->get_children( '', $output = ARRAY_A );
			$product_array['child_product_ids'] = json_encode( $get_post_ids );
		}


			$product_array['id']           = $product->get_id();
			$product_array['type']         = $product->get_type();
			$product_array['button_color'] = self::get_button_color();
			$product_array['button_label'] = self::get_button_label();
			$product_array['redirection']  = 'shop';
			return $product_array;

	}

	public static function get_the_product_details( $data ) {

		$product_array = array();
			$product   = \wc_get_product( $data['id'] );

			$product_array['id']           = $product->get_id();
			$product_array['type']         = $product->get_type();
			$product_array['button_color'] = self::get_button_color();
			$product_array['button_label'] = self::get_button_label();
			$product_array['redirection']  = 'shop';
			$product_array['variation_id'] = $data['variation_id'];
			$product_array['quantity']     = $data['quantity'];


			return $product_array;

	}

	public static function elex_raq_create_order_address( $formdata, $files ) {
		$address = array();
		if ( isset( $formdata['billing_first_name'] ) ) {

			$address['first_name'] = $formdata['billing_first_name'];

		} elseif ( isset( $files['billing_first_name'] ) && ! empty( $files['billing_first_name']['name'] ) ) {

			if ( isset( $files['billing_first_name']['tmp_name'] ) && is_uploaded_file( $files['billing_first_name']['tmp_name'] ) ) {

				$elex_raq_upload_file_return = wp_upload_bits( $files['billing_first_name']['name'], null, file_get_contents( $files['billing_first_name']['tmp_name'] ) );

				$address['first_name'] = wp_slash( $elex_raq_upload_file_return['url'] );
			}
		}
		if ( isset( $formdata['billing_last_name'] ) ) {
			$address['last_name'] = $formdata['billing_last_name'];
		} elseif ( isset( $files['billing_last_name'] ) && ! empty( $files['billing_last_name']['name'] ) ) {

			if ( isset( $files['billing_last_name']['tmp_name'] ) && is_uploaded_file( $files['billing_last_name']['tmp_name'] ) ) {

				$elex_raq_upload_file_return = wp_upload_bits( $files['billing_last_name']['name'], null, file_get_contents( $files['billing_last_name']['tmp_name'] ) );

				$address['last_name'] = wp_slash( $elex_raq_upload_file_return['url'] );
			}
		}
		if ( isset( $formdata['billing_company'] ) ) {
			$address['company'] = $formdata['billing_company'];
		} elseif ( isset( $files['billing_company'] ) && ! empty( $files['billing_company']['name'] ) ) {

			if ( isset( $files['billing_company']['tmp_name'] ) && is_uploaded_file( $files['billing_company']['tmp_name'] ) ) {

				$elex_raq_upload_file_return = wp_upload_bits( $files['billing_company']['name'], null, file_get_contents( $files['billing_company']['tmp_name'] ) );

				$address['company'] = wp_slash( $elex_raq_upload_file_return['url'] );
			}
		}
		if ( isset( $formdata['billing_email'] ) ) {
			$address['email'] = $formdata['billing_email'];
		} elseif ( isset( $files['billing_email'] ) && ! empty( $files['billing_email']['name'] ) ) {

			if ( isset( $files['billing_email']['tmp_name'] ) && is_uploaded_file( $files['billing_email']['tmp_name'] ) ) {

				$elex_raq_upload_file_return = wp_upload_bits( $files['billing_email']['name'], null, file_get_contents( $files['billing_email']['tmp_name'] ) );

				$address['email'] = wp_slash( $elex_raq_upload_file_return['url'] );
			}
		}
		if ( isset( $formdata['billing_phone'] ) ) {
			$address['phone'] = $formdata['billing_phone'];
		} elseif ( isset( $files['billing_phone'] ) && ! empty( $files['billing_phone']['name'] ) ) {

			if ( isset( $files['billing_phone']['tmp_name'] ) && is_uploaded_file( $files['billing_phone']['tmp_name'] ) ) {

				$elex_raq_upload_file_return = wp_upload_bits( $files['billing_phone']['name'], null, file_get_contents( $files['billing_phone']['tmp_name'] ) );

				$address['phone'] = wp_slash( $elex_raq_upload_file_return['url'] );
			}
		}
		if ( isset( $formdata['billing_address_1'] ) ) {
			$address['address_1'] = $formdata['billing_address_1'];
		} elseif ( isset( $files['billing_address_1'] ) && ! empty( $files['billing_address_1']['name'] ) ) {

			if ( isset( $files['billing_address_1']['tmp_name'] ) && is_uploaded_file( $files['billing_address_1']['tmp_name'] ) ) {

				$elex_raq_upload_file_return = wp_upload_bits( $files['billing_address_1']['name'], null, file_get_contents( $files['billing_address_1']['tmp_name'] ) );

				$address['address_1'] = wp_slash( $elex_raq_upload_file_return['url'] );
			}
		}
		if ( isset( $formdata['billing_address_2'] ) ) {
			$address['address_2'] = $formdata['billing_address_2'];
		} elseif ( isset( $files['billing_address_2'] ) && ! empty( $files['billing_address_2']['name'] ) ) {

			if ( isset( $files['billing_address_2']['tmp_name'] ) && is_uploaded_file( $files['billing_address_2']['tmp_name'] ) ) {

				$elex_raq_upload_file_return = wp_upload_bits( $files['billing_address_2']['name'], null, file_get_contents( $files['billing_address_2']['tmp_name'] ) );

				$address['address_2'] = wp_slash( $elex_raq_upload_file_return['url'] );
			}
		}
		if ( isset( $formdata['billing_city'] ) ) {
			$address['city'] = $formdata['billing_city'];
		} elseif ( isset( $files['billing_city'] ) && ! empty( $files['billing_city']['name'] ) ) {

			if ( isset( $files['billing_city']['tmp_name'] ) && is_uploaded_file( $files['billing_city']['tmp_name'] ) ) {

				$elex_raq_upload_file_return = wp_upload_bits( $files['billing_city']['name'], null, file_get_contents( $files['billing_city']['tmp_name'] ) );

				$address['city'] = wp_slash( $elex_raq_upload_file_return['url'] );
			}
		}
		if ( isset( $formdata['billing_state'] ) ) {
			$address['state'] = $formdata['billing_state'];
		} elseif ( isset( $files['billing_state'] ) && ! empty( $files['billing_state']['name'] ) ) {

			if ( isset( $files['billing_state']['tmp_name'] ) && is_uploaded_file( $files['billing_state']['tmp_name'] ) ) {

				$elex_raq_upload_file_return = wp_upload_bits( $files['billing_state']['name'], null, file_get_contents( $files['billing_state']['tmp_name'] ) );

				$address['state'] = wp_slash( $elex_raq_upload_file_return['url'] );
			}
		}
		if ( isset( $formdata['billing_postcode'] ) ) {
			$address['postcode'] = $formdata['billing_postcode'];
		} elseif ( isset( $files['billing_postcode'] ) && ! empty( $files['billing_postcode']['name'] ) ) {

			if ( isset( $files['billing_postcode']['tmp_name'] ) && is_uploaded_file( $files['billing_postcode']['tmp_name'] ) ) {

				$elex_raq_upload_file_return = wp_upload_bits( $files['billing_postcode']['name'], null, file_get_contents( $files['billing_postcode']['tmp_name'] ) );

				$address['postcode'] = wp_slash( $elex_raq_upload_file_return['url'] );
			}
		}
		if ( isset( $formdata['billing_country'] ) ) {
			$address['country'] = $formdata['billing_country'];
		} elseif ( isset( $files['billing_country'] ) && ! empty( $files['billing_country']['name'] ) ) {

			if ( isset( $files['billing_country']['tmp_name'] ) && is_uploaded_file( $files['billing_country']['tmp_name'] ) ) {

				$elex_raq_upload_file_return = wp_upload_bits( $files['billing_country']['name'], null, file_get_contents( $files['billing_country']['tmp_name'] ) );

				$address['country'] = wp_slash( $elex_raq_upload_file_return['url'] );
			}
		}
		return $address;
	}


	/** Add comments in order */
	public static function elex_raq_add_order_comments( $order, $formdata ) {

		if ( isset( $formdata['order_comments'] ) ) {
			$order->add_order_note( $formdata['order_comments'] );
		} elseif ( isset( $_FILES['order_comments'] ) && ! empty( $_FILES['order_comments']['name'] ) ) {
			$filename = sanitize_file_name( $_FILES['order_comments']['name'] );
			$tmp_name = isset( $_FILES['order_comments']['tmp_name'] ) ? sanitize_file_name( $_FILES['order_comments']['tmp_name'] ) : '';

			if ( isset( $tmp_name ) && is_uploaded_file( $tmp_name ) ) {

				$elex_raq_upload_file_return = wp_upload_bits( $filename, null, file_get_contents( $tmp_name ) );
				$order->add_order_note( wp_slash( $elex_raq_upload_file_return['url'] ) );
			}
		}
	}


		/** Add custom fields in order meta */
	public static function elex_raq_add_custom_fields_meta( $order, $formdata, $files, $default_type_count ) {
		
		
		if ( 0 === $default_type_count ) {
					
			if ( isset( $formdata['default'] ) ) {
					
				// add_metadata('post', $order_id, '_elex_raq_default', $formdata['default']);
				$order->update_meta_data( '_elex_raq_default', $formdata['default'] );
					
			} elseif ( isset( $files['default'] ) && ! empty( $files['default']['name'] ) ) {
	
				if ( isset( $files['default']['tmp_name'] ) && is_uploaded_file( $files['default']['tmp_name'] ) ) {
	
					$elex_raq_upload_file_return = wp_upload_bits( $files['default']['name'], null, file_get_contents( $files['default']['tmp_name'] ) );
	
					$formdata['default'] = wp_slash( $elex_raq_upload_file_return['url'] );
				}
				// add_metadata('post', $order_id, '_elex_raq_default', $formdata['default']);
				$order->update_meta_data( '_elex_raq_default', $formdata['default'] );

			}
		} else {
	
			$checkbox_val = array();
			for ( $count = 0;$count <= $default_type_count;$count++ ) {
				if ( 0 === $count ) {
	
					if ( isset( $formdata['default'] ) && null !== ( $formdata['default'] ) ) {
						$order->update_meta_data( '_elex_raq_default', $formdata['default'] );

							
					} elseif ( isset( $files['default'] ) && ! empty( $files['default']['name'] ) ) {
		
						if ( isset( $files['default']['tmp_name'] ) && is_uploaded_file( $files['default']['tmp_name'] ) ) {
		
							$elex_raq_upload_file_return = wp_upload_bits( $files['default']['name'], null, file_get_contents( $files['default']['tmp_name'] ) );
		
							$formdata['default'] = wp_slash( $elex_raq_upload_file_return['url'] );
						}
						$order->update_meta_data( '_elex_raq_default', $formdata['default'] );

					}               
				} else {
	
					if ( isset( $formdata[ 'default_' . $count ] ) ) {

						$order->update_meta_data( '_elex_raq_default_' . $count, $formdata[ 'default_' . $count ] );
							
					} elseif ( isset( $files[ 'default_' . $count ] ) && ! empty( $files[ 'default_' . $count ]['name'] ) ) {
		
						if ( isset( $files[ 'default_' . $count ]['tmp_name'] ) && is_uploaded_file( $files[ 'default_' . $count ]['tmp_name'] ) ) {
		
							$elex_raq_upload_file_return = wp_upload_bits( $files[ 'default_' . $count ]['name'], null, file_get_contents( $files[ 'default_' . $count ]['tmp_name'] ) );
		
							$formdata[ 'default_' . $count ] = wp_slash( $elex_raq_upload_file_return['url'] );
						}
						$order->update_meta_data( '_elex_raq_default_' . $count, $formdata[ 'default_' . $count ] );

					}
				}           
			}

			$order->update_meta_data( '_elex_raq_default_count', $default_type_count );

		}
	}
	
	public static function elex_raq_meta_box() {

	
		global $post;

		$order_id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
		if ( ! $post && '' === $order_id ) {
			return;
		}
		if ( $post ) {
			$order_id = $post->ID;
		}
		$order = wc_get_order($order_id);


		$form_fields            = $order->get_meta( '_elex_raq_default_form_details' );
		$elex_raq_default_count = $order->get_meta( '_elex_raq_default_count' );
		
		$billing_fields = array(
			'first_name' => array(
				'label' => __( 'First name', 'elex-request-a-quote' ),
				'show'  => false,
			),
			'last_name'  => array(
				'label' => __( 'Last name', 'elex-request-a-quote' ),
				'show'  => false,
			),
			'company'    => array(
				'label' => __( 'Company', 'elex-request-a-quote' ),
				'show'  => false,
			),
			'address_1'  => array(
				'label' => __( 'Address line 1', 'elex-request-a-quote' ),
				'show'  => false,
			),
			'address_2'  => array(
				'label' => __( 'Address line 2', 'elex-request-a-quote' ),
				'show'  => false,
			),
			'city'       => array(
				'label' => __( 'City', 'elex-request-a-quote' ),
				'show'  => false,
			),
			'postcode'   => array(
				'label' => __( 'Postcode / ZIP', 'elex-request-a-quote' ),
				'show'  => false,
			),
			'country'    => array(
				'label'   => __( 'Country / Region', 'elex-request-a-quote' ),
				'show'    => false,
				'class'   => 'js_field-country select short',
				'type'    => 'select',
				'options' => array( '' => __( 'Select a country / region&hellip;', 'elex-request-a-quote' ) ) + WC()->countries->get_allowed_countries(),
			),
			'state'      => array(
				'label' => __( 'State / County', 'elex-request-a-quote' ),
				'class' => 'js_field-state select short',
				'show'  => false,
			),
			'email'      => array(
				'label' => __( 'Email address', 'elex-request-a-quote' ),
			),
			'phone'      => array(
				'label' => __( 'Phone', 'elex-request-a-quote' ),
			),
		);

		$shipping_fields = array(
			'first_name' => array(
				'label' => __( 'First name', 'elex-request-a-quote' ),
				'show'  => false,
			),
			'last_name'  => array(
				'label' => __( 'Last name', 'elex-request-a-quote' ),
				'show'  => false,
			),
			'company'    => array(
				'label' => __( 'Company', 'elex-request-a-quote' ),
				'show'  => false,
			),
			'address_1'  => array(
				'label' => __( 'Address line 1', 'elex-request-a-quote' ),
				'show'  => false,
			),
			'address_2'  => array(
				'label' => __( 'Address line 2', 'elex-request-a-quote' ),
				'show'  => false,
			),
			'city'       => array(
				'label' => __( 'City', 'elex-request-a-quote' ),
				'show'  => false,
			),
			'postcode'   => array(
				'label' => __( 'Postcode / ZIP', 'elex-request-a-quote' ),
				'show'  => false,
			),
			'country'    => array(
				'label'   => __( 'Country / Region', 'elex-request-a-quote' ),
				'show'    => false,
				'type'    => 'select',
				'class'   => 'js_field-country select short',
				'options' => array( '' => __( 'Select a country / region&hellip;', 'elex-request-a-quote' ) ) + WC()->countries->get_shipping_countries(),
			),
			'state'      => array(
				'label' => __( 'State / County', 'elex-request-a-quote' ),
				'class' => 'js_field-state select short',
				'show'  => false,
			),
		);

		
		?>

<div id="order_data" class="woocommerce-order-data postbox wrapper">

	<div class="order_data_column_container">
		<div class="order_data_row">
			<div class="order_data_column">
				<h3>
					<?php esc_html_e( 'Billing', 'elex-request-a-quote' ); ?>
				</h3>
				<div class="address">
					<?php
					if ( $order->get_formatted_billing_address() ) {
							echo '<p>' . wp_kses( $order->get_formatted_billing_address(), array( 'br' => array() ) ) . '</p>';
					} else {
						echo '<p class="none_set"><strong>' . esc_attr( 'Address:', 'elex-request-a-quote' ) . '</strong> ' . esc_attr( 'No billing address set.', 'elex-request-a-quote' ) . '</p>';
					}

					foreach ( $billing_fields as $key => $field ) {
						if ( isset( $field['show'] ) && false === $field['show'] ) {
							continue;
						}

						$field_name = 'billing_' . $key;

						if ( isset( $field['value'] ) ) {
							$field_value = $field['value'];
						} elseif ( is_callable( array( $order, 'get_' . $field_name ) ) ) {
							$field_value = $order->{"get_$field_name"}( 'edit' );
						} else {
							$field_value = $order->get_meta( '_' . $field_name );
						}

						if ( 'billing_phone' === $field_name ) {
							$field_value = wc_make_phone_clickable( $field_value );
						} elseif ( 'billing_email' === $field_name ) {
							$field_value = '<a href="' . esc_url( 'mailto:' . $field_value ) . '">' . $field_value . '</a>';
						} else {
							$field_value = make_clickable( esc_html( $field_value ) );
						}

						if ( $field_value ) {
							echo '<p><strong>' . esc_html( $field['label'] ) . ':</strong> ' . wp_kses_post( $field_value ) . '</p>';
						}
					}
					?>

				</div>


			</div>
			<div class="order_data_column">
				<h3>
					<?php esc_html_e( 'Shipping', 'elex-request-a-quote' ); ?>
				</h3>
				<div class="address">
					<?php

								// Display values.
					if ( $order->get_formatted_shipping_address() ) {
						echo '<p>' . wp_kses( $order->get_formatted_shipping_address(), array( 'br' => array() ) ) . '</p>';
					} else {
						echo '<p class="none_set"><strong>' . esc_attr( 'Address:', 'elex-request-a-quote' ) . '</strong> ' . esc_attr( 'No shipping address set.', 'elex-request-a-quote' ) . '</p>';
					}

					if ( ! empty( $shipping_fields ) ) {
						foreach ( $shipping_fields as $key => $field ) {
							if ( isset( $field['show'] ) && false === $field['show'] ) {
								continue;
							}

							$field_name = 'shipping_' . $key;

							if ( is_callable( array( $order, 'get_' . $field_name ) ) ) {
								$field_value = $order->{"get_$field_name"}( 'edit' );
							} else {
								$field_value = $order->get_meta( '_' . $field_name );
							}

							if ( $field_value ) {
								echo '<p><strong>' . esc_html( $field['label'] ) . ':</strong> ' . wp_kses_post( $field_value ) . '</p>';
							}
						}
					}

					if ( !empty($order->get_customer_note() ) ) {
						echo '<p class="order_note"><strong>' . esc_attr( 'Customer provided note:', 'elex-request-a-quote' ) . '</strong> ' . ( esc_html_e($order->get_customer_note()) ) . '</p>';
					}
					?>
				</div>

			</div>
		</div>
	</div>
	<div class="order_data_column_container">
		<div class="order_data_row">
			<h3>
				<?php esc_html_e( 'General', 'elex-request-a-quote' ); ?>
			</h3>
			<p>
				<?php 
							$elex_raq_fn =  $order->get_billing_first_name();

							$elex_raq_label_key = self::elex_raq_labelcheck( $form_fields, 'connected_to', 'billing_first_name', 0 );
				if ( 'elex_raq_success' !== $elex_raq_label_key || 0 === $elex_raq_label_key ) {

					if ( '' != $elex_raq_fn && filter_var( $elex_raq_fn, FILTER_VALIDATE_URL ) ) {
										
							echo '<strong>' . esc_attr( $form_fields[ $elex_raq_label_key ]['name'] ) . ' : </strong><a href="' . esc_attr( $elex_raq_fn ) . '" target="_blank">Click here to open</a>';

					} elseif ( '' != $elex_raq_fn ) {
										
							echo '<strong>' . esc_attr( $form_fields[ $elex_raq_label_key ]['name'] ) . ' : </strong>' . esc_attr( $elex_raq_fn );
					}
				}
							
				?>
			</p>
			<p>
				<?php 

							
							$elex_raq_ln        = $order->get_billing_last_name();
							$elex_raq_label_key = self::elex_raq_labelcheck( $form_fields, 'connected_to', 'billing_last_name', 0 );
				if ( 'elex_raq_success' != $elex_raq_label_key || 0 == $elex_raq_label_key ) {
					if ( '' != $elex_raq_ln && filter_var( $elex_raq_ln, FILTER_VALIDATE_URL ) ) {

							echo '<strong>' . esc_attr( $form_fields[ $elex_raq_label_key ]['name'] ) . ' : </strong><a href="' . esc_attr( $elex_raq_ln ) . '" target="_blank">Click here to open</a>';
											
					} elseif ( '' != $elex_raq_ln ) {

							echo '<strong>' . esc_attr( $form_fields[ $elex_raq_label_key ]['name'] ) . ' : </strong>' . esc_attr( $elex_raq_ln );
					}
				}
				?>
			</p>
			<p>
				<?php 

							$latest_notes       = wc_get_order_notes(
								array(
									'order_id' => $order_id,
									'orderby'  => 'date_created_gmt',
								) 
							);
							$latest_notes_array = (array) $latest_notes;

				if ( ! empty( $latest_notes_array ) ) {

					$elex_raq_label_key = self::elex_raq_labelcheck( $form_fields, 'connected_to', 'order_comments', 0 );
						
					if ( 'elex_raq_success' !== $elex_raq_label_key || 0 === $elex_raq_label_key ) {
									
						echo '<strong>' . esc_attr( $form_fields[ $elex_raq_label_key ]['name'] ) . ' : </strong>';       
									
						$count = 1;  
						foreach ( $latest_notes_array as $key => $value ) {
										
							if ( '' != $value->content && filter_var( $value->content, FILTER_VALIDATE_URL ) ) {
								echo esc_attr( $count ) . '<a href="' . esc_attr( $value->content ) . '" target="_blank">Click here to open</a>';
							} elseif ( '' != $value->content ) {
								echo '<p>' . esc_attr( $count ) . '.' . esc_attr( $value->content ) . '</p>';
							}

							$count++;
						}   
					}
				}
				?>
			</p>
			<?php 

			for ( $count = 0; $count < $elex_raq_default_count ; $count++ ) {
						
				echo'<p>';
				if ( 0 === $count ) {
					$elex_raq_default = $order->get_meta( '_elex_raq_default' );


					$elex_raq_label_key = self::elex_raq_labelcheck( $form_fields, 'connected_to', 'default', 0 );

					if ( 'elex_raq_success' !== $elex_raq_label_key || 0 === $elex_raq_label_key ) {

						if ( '' != $elex_raq_default && filter_var( $elex_raq_default, FILTER_VALIDATE_URL ) ) {

								echo  '<strong>' . esc_attr( $form_fields[ $elex_raq_label_key ]['name'] ) . ' : </strong><a href="' . esc_attr( $elex_raq_default ) . '" target="_blank">Click here to open</a>';

						} elseif ( '' != $elex_raq_default ) {
												
							echo  '<strong>' . esc_attr( $form_fields[ $elex_raq_label_key ]['name'] ) . ' : </strong>' . esc_attr( $elex_raq_default );
											
						} elseif ( '' === $elex_raq_default && 'checkbox' === $form_fields[ $elex_raq_label_key ]['type'] ) {

					echo  '<strong>' . esc_attr( $form_fields[ $elex_raq_label_key ]['name'] ) . ' : </strong>No';
						}
					}   
				} else {
					$elex_raq_default =$order->get_meta( '_elex_raq_default_' . $count );
								

					$elex_raq_label_key = self::elex_raq_labelcheck( $form_fields, 'connected_to', 'default', $count );

					if ( 'elex_raq_success' !== $elex_raq_label_key || 0 === $elex_raq_label_key ) {
						if ( '' != $elex_raq_default && filter_var( $elex_raq_default, FILTER_VALIDATE_URL ) ) {
								echo  '<strong>' . esc_attr( $form_fields[ $elex_raq_label_key ]['name'] ) . ' : </strong><a href="' . esc_attr( $elex_raq_default ) . '" target="_blank">Click here to open</a>';

						} elseif ( '' !== $elex_raq_default ) {
												
								echo  '<strong>' . esc_attr( $form_fields[ $elex_raq_label_key ]['name'] ) . ' : </strong>' . esc_attr( $elex_raq_default );
											
						} elseif ( '' === $elex_raq_default && 'checkbox' === $form_fields [ $elex_raq_label_key ] ['type'] ) {

							echo  '<strong>' . esc_attr( $form_fields[ $elex_raq_label_key ]['name'] ) . ' : </strong>No';
										
						} elseif ( '' === $elex_raq_default && 'radio' === $form_fields [ $elex_raq_label_key ] ['type'] ) {

							echo  '<strong>' . esc_attr( $form_fields[ $elex_raq_label_key ]['name'] ) . ' : </strong>No value selected';
						}
					}
				}
				echo'</p>';
			}
			?>
		</div>
	</div>
</div>

<?php
		
	}

	public static function elex_raq_labelcheck( $fields, $field, $value, $count ) {

		$field_count = $count;

		foreach ( $fields as $key => $field_val ) {
			if ( $field_val[ $field ] === $value ) {
				if ( 0 === $field_count ) {
					return $key;
				}
				 --$field_count;
			}       
		}

		

		return 'elex_raq_success';
	}

	public static function elex_raq_address_validation( $value, $request, $key ) {
		if ( ! empty( $value ) && is_array( $value ) ) {
			if ( isset( $formdata['billing_first_name'] ) ) {
				if ( ! is_string( $formdata['billing_first_name'] ) ) {
					return new WP_Error( 'rest_bad_request', esc_html__( 'First name should be a string.', 'elex-request-a-quote' ), array( 'status' => 400 ) );
				}
			} 
			if ( isset( $formdata['billing_last_name'] ) ) {
				
				if ( ! is_string( $formdata['billing_last_name'] ) ) {
					return new WP_Error( 'rest_bad_request', esc_html__( 'Last name should be a string.', 'elex-request-a-quote' ), array( 'status' => 400 ) );
				}
			}
			if ( isset( $formdata['billing_company'] ) ) {
				if ( ! is_string( $formdata['billing_company'] ) ) {
					return new WP_Error( 'rest_bad_request', esc_html__( 'Company name should be a string.', 'elex-request-a-quote' ), array( 'status' => 400 ) );
				}
			}
			if ( isset( $formdata['billing_email'] ) ) {
				if ( ! filter_var( $formdata['billing_email'], FILTER_VALIDATE_EMAIL ) ) {
					return new WP_Error( 'rest_bad_request', esc_html__( 'Email should be in valid format.', 'elex-request-a-quote' ), array( 'status' => 400 ) );
				}
			}
			if ( isset( $formdata['billing_phone'] ) ) {
				if ( ! preg_match( '/^[0-9]{10}+$/', $formdata['billing_phone'] ) ) {
					return new WP_Error( 'rest_bad_request', esc_html__( 'Email should be a int of length 10.', 'elex-request-a-quote' ), array( 'status' => 400 ) );
				}
			}
			if ( isset( $formdata['billing_address_1'] ) ) {
				if ( ! is_string( $formdata['billing_address_1'] ) ) {
					return new WP_Error( 'rest_bad_request', esc_html__( 'Address 1  should be a string.', 'elex-request-a-quote' ), array( 'status' => 400 ) );
				}
			}
			if ( isset( $formdata['billing_address_2'] ) ) {
				if ( ! is_string( $formdata['billing_address_2'] ) ) {
					return new WP_Error( 'rest_bad_request', esc_html__( 'Address 2  should be a string.', 'elex-request-a-quote' ), array( 'status' => 400 ) );
				}
			}
			if ( isset( $formdata['billing_city'] ) ) {
				if ( ! is_string( $formdata['billing_city'] ) ) {
					return new WP_Error( 'rest_bad_request', esc_html__( 'City name  should be a string.', 'elex-request-a-quote' ), array( 'status' => 400 ) );
				}
			}
			if ( isset( $formdata['billing_state'] ) ) {
				if ( ! is_string( $formdata['billing_state'] ) ) {
					return new WP_Error( 'rest_bad_request', esc_html__( 'state name  should be a string.', 'elex-request-a-quote' ), array( 'status' => 400 ) );
				}
			}
			if ( isset( $formdata['billing_postcode'] ) ) {
				if ( ! is_string( $formdata['billing_postcode'] ) ) {
					return new WP_Error( 'rest_bad_request', esc_html__( 'Postcode  should be a string.', 'elex-request-a-quote' ), array( 'status' => 400 ) );
				}
			}
			if ( isset( $formdata['billing_country'] ) ) {
				
				if ( ! is_string( $formdata['billing_country'] ) ) {
					return new WP_Error( 'rest_bad_request', esc_html__( 'Country name should be a string.', 'elex-request-a-quote' ), array( 'status' => 400 ) );
				}
			}
			return true;
		}
		return new WP_Error( 'rest_bad_request', esc_html__( 'Address array is empty', 'elex-request-a-quote' ), array( 'status' => 400 ) );

	}
	public static function elex_raq_array_empty( $value, $request, $key ) {
		if ( ! empty( $value ) && is_array( $value ) ) {
			return true;
		}
		if ( 'custom_fields' == $key ) {
			return new WP_Error( 'rest_bad_request', esc_html__( 'Custom Fields array is empty', 'elex-request-a-quote' ), array( 'status' => 400 ) );
		}
		return true;
	}
	public static function elex_raq_user_exists( $value, $request, $key ) {
	
		if ( is_int( $value ) ) {
			$userdata = get_userdata( $value );

			if ( ! empty( $userdata ) || 0 == $value ) {
				return true;
			}
		}

		return new WP_Error( 'rest_bad_request', esc_html__( 'User not found', 'elex-request-a-quote' ), array( 'status' => 400 ) );
	}
	public static function elex_raq_validate_token( $request ) {

		$settings = SettingsController::get_settings();

		$rest_api = $settings['rest_api']['enabled'];
		$api_key  = $settings['rest_api']['api_key'];

		if ( isset( $rest_api ) && isset( $api_key ) && '' !== $api_key ) {
			$headers = $request->get_headers();
			if ( $headers['elex_raq_token'][0] == $api_key ) {
				return true;
			}
		}
		return new WP_Error( 'rest_forbidden', esc_html__( 'Unauthorize Access', 'elex-request-a-quote' ), array( 'status' => 401 ) );
	}


	public static function elex_raq_place_order( $request ) {



		$formdata = $request->get_json_params();
		$files    = $request->get_file_params();
		//create address array.
		$address = self::elex_raq_create_order_address( $formdata['address'], $files );

		$current_user_id = get_current_user_id();
		
		$elex_raq_default_type_count = FormSettingController::get_custom_fields_count();

		$order = wc_create_order();
		if ( 0 !== $formdata['user_id'] ) {
			$order->set_customer_id( $formdata['user_id'] );
		}
		$session_key = $formdata['session_key'];

		$order->set_address( $address, 'billing' );
		$order->set_address( $address, 'shipping' );
		$order_id = $order->get_id();

		//Add comments to orders
		self::elex_raq_add_order_comments( $order, $formdata );
		self::elex_raq_add_custom_fields_meta( $order, $formdata, $_FILES, $elex_raq_default_type_count );


		if ( 0 !== $formdata['user_id'] || null !== $formdata['user_id'] ) {
		$quote_list_id = self::get_the_quote_list_id( (int) $formdata['user_id'] );
		} else {
			$quote_list_id = self::quote_list_id_for_guest_through_api( $formdata['session_key'] );

		}

		$quote_list_data = self::get_the_quote_list( $quote_list_id );
		if ( empty( $quote_list_data['items'] ) ) {
			$response = new \WP_REST_Response( array( 'failure' ) );
			$response->set_status( 200 );
			return rest_ensure_response( $response );
		}
		
		$form_settings = FormSettingController::get_settings();
		$form_settings = FormSettingController::converToArray( $form_settings );
		$form_fields   = $form_settings['fields'];
		// add_metadata('post', $order_id, '_elex_raq_default_form_details', $form_fields);
		// add_metadata('post', $order_id, '_elex_raq_unique_order_key', 'elex_raq_' . $order_id);
		// add_metadata('post', $order_id, 'elex_quote_list_id', $quote_list_id);
		// add_metadata('post', $order_id, 'elex_quote_data', $quote_list_id);

		$order->update_meta_data( '_elex_raq_default_form_details', $form_fields );
		$order->update_meta_data( '_elex_raq_unique_order_key', 'elex_raq_' . $order_id );

		$order->update_meta_data( 'elex_quote_list_id', $quote_list_id );
		$order->update_meta_data( 'elex_quote_data', $quote_list_data );

		$order->save(); 



		self::elex_raq_add_products_order( $order, $quote_list_data['items'] );
		self::update_the_quote_list( $quote_list_id, self::$quote_status['quote_requested'] );
	
		
		$response = new \WP_REST_Response( array( 'success' ) );
		$response->set_status( 200 );
		return rest_ensure_response( $response );
	}
}
