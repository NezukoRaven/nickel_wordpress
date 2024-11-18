<?php

namespace Elex\RequestAQuote\Settings\Models;

class GeneralSettings {

	protected $data;

	public static function load() {
		$self = new self();

		$self->data = get_option( 'request_a_quote_general_settings', self::get_default_values() );

		return $self;
	}
	

	public function save() {
		update_option( 'request_a_quote_general_settings', $this->data );
		update_option( 'save_hide_cart_new_fields', true );

		return $this;
	}

	public static function get_products( $search_key ) {

		$product_data      = array();
		$product_data_temp = array();
		$args              = array(
			's' => $search_key,
		);
		$products          = wc_get_products( $args );
		foreach ( $products as $product ) {
			$product_data['id']   = $product->get_id();
			$product_data['text'] = $product->get_name();
			$product_data_temp[]  = $product_data;
		}
		return $product_data_temp;
	}

	public static function get_products_by_category( $search_key ) {
		$product_category_data      = array();
		$product_category_data_temp = array();
		$product_category           = get_terms(
			array(
				'taxonomy' => 'product_cat',
				'search'   => $search_key,
			)
		);
		foreach ( $product_category as $category ) {
			$product_category_data['id']   = $category->term_id;
			$product_category_data['text'] = $category->name;
			$product_category_data_temp[]  = $product_category_data;
		}
		return $product_category_data_temp;
	}


	public static function get_user_role( $search_key ) {
		global $wp_roles;

		$user_roles                 = $wp_roles->role_names;
		$user_roles['unregistered'] = 'Unregistered';
		return $user_roles;
	}


	public static function get_products_by_tag( $search_key ) {
		$product_category_data      = array();
		$product_category_data_temp = array();
		$product_category           = get_terms(
			array(
				'taxonomy' => 'product_tag',
				'search'   => $search_key,

			)
		);
		foreach ( $product_category as $category ) {
			$product_category_data['id']   = $category->term_id;
			$product_category_data['text'] = $category->name;
			$product_category_data_temp[]  = $product_category_data;
		}
		return $product_category_data_temp;
	}
	public static function get_default_values() {

		$data = array(
			'general'          => array(
				'button_on_shop_page'              => true,
				'button_on_product_page'           => true,
				'open_quote_form'                  => 'new_page',
				'add_to_quote_success_message'     => __( 'Product successfully added to the Quote List', 'elex-request-a-quote' ),
				'button_label'                     => __( 'Add to Quote', 'elex-request-a-quote' ),
				'button_default_color'             => '#10518D',
				'limit_button_on_certain_products' => array(
					'enabled'                      => false,
					'include_products_by_category' => array(),
					'include_products_by_name'     => array(),
					'include_products_by_tag'      => array(),
				),
				'exclude_products'                 => array(
					'enabled'     => false,
					'by_category' => array(),
					'by_name'     => array(),
					'by_tag'      => array(),
				),
				'role_based_filter'                => array(
					'enabled'       => false,
					'include_roles' => array(),
					'exclude_roles' => array(),

				),
				'disable_quote_for_guest'          => false,
				'include_exclude_based_on_stock'   => 'show_for_all_products',
			),
			'hide_add_to_cart' => array(
				'button_on_shop_page'    => false,
				'button_on_product_page' => false,
				'hide_price'             => false,
				'exclude_products'       => array(
					'enabled'     => false,
					'by_category' => array(),
					'by_name'     => array(),
					'by_tag'      => array(),
				),
				'include_products'       => array(
					'enabled'     => false,
					'by_category' => array(),
					'by_name'     => array(),
					'by_tag'      => array(),
				),
				'exclude_roles'          => array(
					'enabled' => false,
					'roles'   => array(),
				),
				'include_roles'          => array(
					'enabled' => false,
					'roles'   => array(),
				),
			),
			'rest_api'         => array(
				'enabled' => false,
				'api_key' => '',
			),
		);
		return $data;
	}
	public function merge( $new_options ) {
		$this->data = array_merge( $this->data, $new_options );
		return $this;
	}

	public  function to_array() {
		return $this->data;
	}

	public function get( $key ) {
		return $this->data[ $key ];
	}


	


}
