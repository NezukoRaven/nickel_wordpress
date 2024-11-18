<?php

namespace Elex\RequestAQuote\Quotelist\Models;

class ListPageSettings {

	protected $data;

	public static function load() {
		$self = new self();

		$self->data = get_option( 'request_a_quote_quotelist_settings', self::get_default_values() );

		return $self;
	}

	public function save() {
		update_option( 'request_a_quote_quotelist_settings', $this->data );
		return $this;
	}

	
	public static function get_default_values() {

		$data = array(
			'quote_list_page'    => array(
				'selected_page'         => self::get_page_url( 'add-to-quote-product-list' ),
				'title'                 => __( 'Quote List' , 'elex-request-a-quote' ),
				'layout'                => array(
					'product_on_left_form_on_right' => false,
					'product_on_top_form_on_bottom' => true,
				),
				'show_if_list_empty'    => array(
					'illustration'           => true,
					'empty_text'             => array(
						'enabled' => true,
						'text'    => __('Your Quote List is Empty' , 'elex-request-a-quote' ),
					),
					'go_to_shop_page_button' => true,
					'quote_request_form'     => false,
				),
				'show_on_product_table' => array(
					'product_image'         => true,
					'product_price'         => true,
					'quantity'              => true,
					'each_product_subtotal' => true,
					'product_sku'           => false,
					'taxes'                 => false,
				),
				'show_prowered_by'      => true,
			
			),
			'additional_options' => array(
				'update_list_button'                => true,
				'clear_list'                        => true,
				'add_more_items_button'             => true,
				'add_more_items_button_label'       => __( 'Add More Items' , 'elex-request-a-quote' ),
				'add_more_items_button_redirection' => get_permalink( wc_get_page_id( 'shop' ) ),
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

	public static function get_page_url( $postname ) {
		global $wpdb;
		$shop_page_url = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT  guid FROM $wpdb->posts WHERE post_type = %s AND post_status = %s AND post_name= %s",
				'page',
				'publish',
				$postname
			)
		);
		return $shop_page_url;
	}

	public  function get_quote_page_url() {
		global $wpdb;
		$shop_page_url = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT  guid FROM $wpdb->posts WHERE post_type = %s AND post_status = %s AND post_title= %s",
				'page',
				'publish',
				'Shop'
			)
		);
		return $shop_page_url;
	}


}
