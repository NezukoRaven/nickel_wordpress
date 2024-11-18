<?php
namespace Elex\RequestAQuote\Settings;

use Elex\RequestAQuote\Notification\NotificationController;
use Elex\RequestAQuote\TemplateSetting\TemplateController;

use Elex\RequestAQuote\Widget\WidgetController;
use Elex\RequestAQuote\TemplateSetting\Models\TemplateModel;


use Elex\RequestAQuote\Settings\Models\GeneralSettings;
use Elex\RequestAQuote\Widget\Models\WidgetModel;

class SettingsController {

	public static $settings = null;

	public static function init() {
		add_action( 'req_settings_tab_general', array( self::class, 'load_general' ) );
		NotificationController::init();
		WidgetController::init();
		TemplateController::init();

		add_action( 'req_settings_tab_go_premium', array( self::class, 'load_go_premium' ) );

		add_action( 'req_settings_tab_hide_cart', array( self::class, 'load_hide_cart' ) );

		add_action( 'wp_ajax_search_products_by_name', array( self::class, 'search_products_by_name' ) );
		add_action( 'wp_ajax_search_products_by_category', array( self::class, 'search_products_by_category' ) );
		add_action( 'wp_ajax_search_products_by_tag', array( self::class, 'search_products_by_tag' ) );
		add_action( 'wp_ajax_search_user_role', array( self::class, 'search_user_role' ) );

		add_filter( 'settings_saving_general', array( self::class, 'save_general' ) );
		add_filter( 'settings_saving_hide_cart', array( self::class, 'save_hide_cart' ) );

		add_action( 'req_settings_tab_button', array( self::class, 'load_button_customization' ) );


	}

	public static function load_button_customization() {
		include ELEX_RAQ_VIEW_PATH . 'button/add_to_quote.php';

	}
	public static function load_go_premium() {
		include ELEX_RAQ_VIEW_PATH . 'market.php';
		
	}

	public static function get_settings( $reload = false ) {


		$settings = GeneralSettings::load();
		$settings = $settings->to_array();
		$settings = apply_filters( 'request_a_quote_settings', $settings );

		return  $settings;

	}

	public static function get_widget_settings() {

		$settings = WidgetModel::load();

		self::$settings = apply_filters( 'request_a_quote_settings', $settings );

		return  self::$settings;

	}
	public static function get_user_roles() {
		global $wp_roles;
		$settings              = self::get_settings();
		$roles                 = $wp_roles->role_names;
		$roles['unregistered'] = 'Unregistered';
		$include_roles         = array();
		$exclude_roles         = array();
		$userroles             = array();
		foreach ( $roles as $key => $name ) {
			if ( in_array( $key, $settings['general']['role_based_filter']['include_roles'] ) ) {

				$include_roles[ $key ] = $name;
			}

			if ( in_array( $key, $settings['general']['role_based_filter']['exclude_roles'] ) ) {
				$exclude_roles[ $key ] = $name;

			}       
		}
		 $userroles['include'] = $include_roles;
		 $userroles['exclude'] = $exclude_roles;

		 return $userroles;

	}
	public static function get_excluded_product_list() {

		$settings = self::get_settings();

		$exclude_product_array    = array();
		$exclude_products_by_name = $settings['general']['exclude_products']['by_name'];
		if ( ! empty( $exclude_products_by_name ) ) {
			foreach ( $exclude_products_by_name as $product ) {
				$product_to_exclude['id']           = $product;
				$product_to_exclude['name']         = get_the_title( $product );
				$exclude_product_array['by_name'][] = $product_to_exclude;
			}
			$settings['general']['exclude_products']['by_name'] = $exclude_product_array['by_name'];
		}

			$exclude_products_by_category = $settings['general']['exclude_products']['by_category'];
		if ( ! empty( $exclude_products_by_category ) ) {
			foreach ( $exclude_products_by_category as $product ) {
				$product_to_exclude['id']               = $product;
				$product_to_exclude['name']             = get_the_title( $product );
				$exclude_product_array['by_category'][] = $product_to_exclude;
	
			}
			$settings['general']['exclude_products']['by_category'] = $exclude_product_array['by_category'];
	
		}
		
			$exclude_products_by_tag = $settings['general']['exclude_products']['by_tag'];
			
		if ( ! empty( $exclude_products_by_tag ) ) {
			
			foreach ( $exclude_products_by_tag as $product ) {
				$product_to_exclude['id']          = $product;
				$product_to_exclude['name']        = get_the_title( $product );
				$exclude_product_array['by_tag'][] = $product_to_exclude;
			}

			$settings['general']['exclude_products']['by_tag'] = $exclude_product_array['by_tag'];
		
		
		}
		return $exclude_product_array;
	}

	public static function load_categories() {
		$settings     = self::get_settings();
		$data         = array();
		$category_ids = array_merge(
			$settings['general']['limit_button_on_certain_products']['include_products_by_category'],
			$settings['general']['exclude_products']['by_category'] ,
			$settings['hide_add_to_cart']['exclude_products']['by_category']
		);
		if ( empty( $category_ids ) ) {
			return $data;
		}

			$terms = get_terms(
				array(
					'taxonomy' => 'product_cat',
					'include'  => $category_ids,
				)
			);


			$include_categories           = array();
			$exclude_hide_cart_categories = array();
			$exclude_categories           = array();

		foreach ( $terms as $term ) {
			if ( in_array( $term->term_id , $settings['general']['limit_button_on_certain_products']['include_products_by_category'] ) ) {
				$include_categories[] = array(
					'id'   => $term->term_id,
					'name' => $term->name,
				);
			}

			if ( in_array( $term->term_id , $settings['general']['exclude_products']['by_category'] ) ) {
				$exclude_categories[] = array(
					'id'   => $term->term_id,
					'name' => $term->name,
				);
			}
			if ( in_array( $term->term_id , $settings['hide_add_to_cart']['exclude_products']['by_category'] ) ) {
				$exclude_hide_cart_categories[] = array(
					'id'   => $term->term_id,
					'name' => $term->name,
				);
			}
		}

		$data['include_products_by_cat']           = $include_categories;
		$data['exclude_products_by_cat']           = $exclude_categories;
		$data['hide_cart_exclude_products_by_cat'] = $exclude_hide_cart_categories;

		return $data;

	}

	public static function load_products_by_tags() {
		$settings     = self::get_settings();
		$data         = array();
		$category_ids = array_merge(
			$settings['general']['limit_button_on_certain_products']['include_products_by_tag'],
			$settings['hide_add_to_cart']['exclude_products']['by_tag'] ,
			$settings['general']['exclude_products']['by_tag']
		);
		if ( empty( $category_ids ) ) {
			return $data;
		}

			$terms = get_terms(
				array(
					'taxonomy' => 'product_tag',
					'include'  => $category_ids,
				)
			);

			$include_tags           = array();
			$exclude_tags           = array();
			$exclude_hide_cart_tags = array();


		foreach ( $terms as $term ) {
			if ( in_array( $term->term_id , $settings['general']['limit_button_on_certain_products']['include_products_by_tag'] ) ) {
				$include_tags[] = array(
					'id'   => $term->term_id,
					'name' => $term->name,
				);
			}

			if ( in_array( $term->term_id , $settings['general']['exclude_products']['by_tag'] ) ) {
				$exclude_tags[] = array(
					'id'   => $term->term_id,
					'name' => $term->name,
				);
			}
			if ( in_array( $term->term_id , $settings['hide_add_to_cart']['exclude_products']['by_tag'] ) ) {
				$exclude_hide_cart_tags[] = array(
					'id'   => $term->term_id,
					'name' => $term->name,
				);
			}
		}
		
		$data['include_products_by_tag']           = $include_tags;
		$data['exclude_products_by_tag']           = $exclude_tags;
		$data['hide_cart_exclude_products_by_tag'] = $exclude_hide_cart_tags;

		return $data;

	}


	public static function load_settings_data() {
			$settings = self::get_settings();
		
			$mergerd_array = array_merge(
				$settings['general']['limit_button_on_certain_products']['include_products_by_name'],
				$settings['general']['exclude_products']['by_name'],
				$settings['hide_add_to_cart']['exclude_products']['by_name']
			);

			$args  = array(
				'post_type'       => 'product',
				'include'         => $mergerd_array,
				'supress_filters' => false,
			);
			$terms = get_posts( $args );

			$include_names           = array();
			$exclude_names           = array();
			$exclude_hide_cart_names = array();

			foreach ( $terms as $term ) {
				if ( in_array( $term->ID , $settings['general']['limit_button_on_certain_products']['include_products_by_name'] ) ) {
					$include_names[] = array(
						'id'   => $term->ID,
						'name' => $term->post_title,
					);
				}

				if ( in_array( $term->ID , $settings['general']['exclude_products']['by_name'] ) ) {
					$exclude_names[] = array(
						'id'   => $term->ID,
						'name' => $term->post_title,
					);
				}
				if ( in_array( $term->ID , $settings['hide_add_to_cart']['exclude_products']['by_name'] ) ) {
					$exclude_hide_cart_names[] = array(
						'id'   => $term->ID,
						'name' => $term->post_title,
					);
				}
			}

			$data['include_products_by_name']           = $include_names;
			$data['exclude_products_by_name']           = $exclude_names;
			$data['hide_cart_exclude_products_by_name'] = $exclude_hide_cart_names;

		return $data;

	}

	public static function load_general() {
		$settings = self::get_settings();
		
		$data = self::load_settings_data();
		
		$include_products_by_name = isset( $data['include_products_by_name'] ) ? $data['include_products_by_name'] : array();
		$exclude_products_by_name = array();

		$products_by_cat              = self::load_categories();
		$include_products_by_category = isset( $products_by_cat['include_products_by_cat'] ) ? $products_by_cat['include_products_by_cat'] : array();
		$exclude_products_by_category = array();


		$include_roles =array();
		$exclude_roles = array();
		
		$products_by_tag         = self::load_products_by_tags();
		$include_products_by_tag = isset( $products_by_tag['include_products_by_tag'] ) ? $products_by_tag['include_products_by_tag'] : array();
		$exclude_products_by_tag = array();

		$rest_api = $settings['rest_api']['enabled'];
		$api_key  = $settings['rest_api']['api_key'];
		include ELEX_RAQ_VIEW_PATH . 'settings/general.php';
		self::show_saved_toast();

	}
	public static function show_saved_toast() {
		if (isset($_SESSION['saved_settings_data'])) {
			include ELEX_RAQ_VIEW_PATH . 'saved_toast.php';
		}

	}

	public static function get_excluded_users_in_hidecart_settings() {

		global $wp_roles;
		$settings              = self::get_settings();
		$roles                 = $wp_roles->role_names;
		$roles['unregistered'] = 'Unregistered';
		$exclude_roles         = array();
		$userroles             = array();
		foreach ( $roles as $key => $name ) {
			if ( in_array( $key , $settings['hide_add_to_cart']['exclude_roles']['roles'] ) ) {
				$exclude_roles[ $key ] = $name;
			}
		}
		 $userroles['exclude'] = $exclude_roles;

		 return $userroles;
		
	}
	


	public static function load_hide_cart() {
		$settings = self::get_settings();

		$data = self::load_settings_data();
	
		$hide_cart_exclude_products_by_name = isset( $data['hide_cart_exclude_products_by_name'] ) ? $data['hide_cart_exclude_products_by_name'] : array();


		$roles         = self::get_excluded_users_in_hidecart_settings();
		$exclude_roles = isset( $roles['exclude'] ) && ! empty( $roles['exclude'] ) ? $roles['exclude'] : array();

		$products_by_cat              = self::load_categories();
		$exclude_products_by_category = isset( $products_by_cat['hide_cart_exclude_products_by_cat'] ) ? $products_by_cat['hide_cart_exclude_products_by_cat'] : array();

		$products_by_tag         = self::load_products_by_tags();
		$exclude_products_by_tag = isset( $products_by_tag['hide_cart_exclude_products_by_tag'] ) ? $products_by_tag['hide_cart_exclude_products_by_tag'] : array();
		
		include ELEX_RAQ_VIEW_PATH . 'settings/hide_addtocart_price.php';
		self::show_saved_toast();
		
	}

	public static function save_restapi_info( GeneralSettings $general_setting_options ) {
		
		check_admin_referer( 'save_settings', 'req_settings_nonce' );

		$new_settings = array();

		$new_settings['rest_api']['enabled'] = isset( $_POST['enabled'] ) ? true : false;
		$new_settings['rest_api']['api_key'] = isset( $_POST['api_key'] ) ? sanitize_text_field( $_POST['api_key'] ) : '';
		
		return $general_setting_options->merge( $new_settings );
	}

	
	public static function save_general( GeneralSettings $general_setting_options ) {
		
		check_admin_referer( 'save_settings', 'req_settings_nonce' );
		$settings = self::get_settings();

		$new_settings = array();

		$new_settings['general']['button_on_shop_page']          = isset( $_POST['general']['button_on_shop_page'] ) ? true : false;
		$new_settings['general']['button_on_product_page']       = isset( $_POST['general']['button_on_product_page'] ) ? true : false;
		$new_settings['general']['open_quote_form']              = isset( $_POST['general']['open_quote_form'] ) ? sanitize_text_field( $_POST['general']['open_quote_form'] ) : 'new_page';
		$new_settings['general']['add_to_quote_success_message'] = isset( $_POST['general']['add_to_quote_success_message'] ) && ! empty( $_POST['general']['add_to_quote_success_message'] ) ? __( sanitize_text_field( $_POST['general']['add_to_quote_success_message'] ) , 'elex-request-a-quote' ) : esc_html__( 'Product successfully added to the Quote List', 'elex-request-a-quote' );
		$new_settings['general']['button_label']                 = isset( $_POST['general']['button_label'] ) ? __( sanitize_text_field(  $_POST['general']['button_label'] ) , 'elex-request-a-quote' ) : esc_html__( 'Add to Quote', 'elex-request-a-quote' );

		$new_settings['general']['button_default_color']                        = isset( $_POST['general']['button_default_color'] ) ? sanitize_text_field( $_POST['general']['button_default_color'] ) : '#10518D';
		$new_settings['general']['limit_button_on_certain_products']['enabled'] = isset( $_POST['general']['limit_button_on_certain_products']['enabled'] ) ? true : false;
		$new_settings['general']['limit_button_on_certain_products']['include_products_by_category'] = isset( $_POST['general']['limit_button_on_certain_products']['include_products_by_category'] ) ? map_deep( $_POST['general']['limit_button_on_certain_products']['include_products_by_category'] , 'sanitize_text_field' ) : array();
		$new_settings['general']['limit_button_on_certain_products']['include_products_by_name']     = isset( $_POST['general']['limit_button_on_certain_products']['include_products_by_name'] ) ? map_deep( $_POST['general']['limit_button_on_certain_products']['include_products_by_name'] , 'sanitize_text_field' ) : array();
		$new_settings['general']['limit_button_on_certain_products']['include_products_by_tag']      = isset( $_POST['general']['limit_button_on_certain_products']['include_products_by_tag'] ) ? map_deep( $_POST['general']['limit_button_on_certain_products']['include_products_by_tag'] , 'sanitize_text_field' ) : array();
		$new_settings['general']['exclude_products']['enabled']                                      = false;
		$new_settings['general']['exclude_products']['by_category']                                  = array();
		$new_settings['general']['exclude_products']['by_name']                                      = array();
		$new_settings['general']['exclude_products']['by_tag']                                       = array();
		
		$new_settings['general']['role_based_filter']['enabled']       = false;
		$new_settings['general']['role_based_filter']['include_roles'] = array();
		$new_settings['general']['role_based_filter']['exclude_roles'] = array();
		
		$new_settings['general']['disable_quote_for_guest']        = false;
		$new_settings['general']['include_exclude_based_on_stock'] =  esc_html__( 'show_for_all_products' , 'elex-request-a-quote' );
		
		$new_settings['rest_api']['enabled'] = isset( $_POST['enabled'] ) ? true : false;
		$new_settings['rest_api']['api_key'] = isset( $_POST['api_key'] ) ? sanitize_text_field( $_POST['api_key'] ) : '';

		$new_settings['general']['button_position']    = isset( $settings['general']['button_position'] ) ? $settings['general']['button_position'] :  'below_add_to_cart';
		$new_settings['general']['quote_expiry_value'] = isset(  $settings['general']['quote_expiry_value']) ?  $settings['general']['quote_expiry_value'] : '';
		// quote_expiry
		$new_settings['general']['payment_methods']  = isset(  $settings['general']['payment_methods']) ?  $settings['general']['payment_methods'] : array();
		$new_settings['general']['button_behaviour'] = isset(  $settings['general']['button_behaviour']) ?  $settings['general']['button_behaviour'] : 'same_page';

		$_SESSION['saved_settings_data'] = true;
		return $general_setting_options->merge( $new_settings );
	}

	public static function save_hide_cart( GeneralSettings $general_setting_options ) {
		
		check_admin_referer( 'save_settings', 'req_settings_nonce' );

		$new_settings = array();

		$new_settings['hide_add_to_cart']['button_on_shop_page']    = isset( $_POST['hide_add_to_cart']['button_on_shop_page'] ) ? (bool) sanitize_text_field( $_POST['hide_add_to_cart']['button_on_shop_page'] ) : false;
		$new_settings['hide_add_to_cart']['button_on_product_page'] = isset( $_POST['hide_add_to_cart']['button_on_product_page'] ) ? (bool) sanitize_text_field( $_POST['hide_add_to_cart']['button_on_product_page'] ) : false;
		$new_settings['hide_add_to_cart']['hide_price']             = isset( $_POST['hide_add_to_cart']['hide_price'] ) ? (bool) sanitize_text_field( $_POST['hide_add_to_cart']['hide_price'] ) : false;
		
		if ( true === $new_settings['hide_add_to_cart']['hide_price'] ) {
			$new_settings['hide_add_to_cart']['button_on_shop_page']    = true;
			$new_settings['hide_add_to_cart']['button_on_product_page'] = true;
		}
		$new_settings['hide_add_to_cart']['exclude_products']['enabled']     = false;
		$new_settings['hide_add_to_cart']['exclude_products']['by_category'] = array();
		$new_settings['hide_add_to_cart']['exclude_products']['by_name']     = array();
		$new_settings['hide_add_to_cart']['exclude_products']['by_tag']      = array();

		$new_settings['hide_add_to_cart']['include_products']['enabled']     = false;
		$new_settings['hide_add_to_cart']['include_products']['by_category'] =  array();
		$new_settings['hide_add_to_cart']['include_products']['by_name']     =  array();
		$new_settings['hide_add_to_cart']['include_products']['by_tag']      =  array();
		$new_settings['hide_add_to_cart']['exclude_roles']['enabled']        = false;
		$new_settings['hide_add_to_cart']['exclude_roles']['roles']          = array();
		$new_settings['hide_add_to_cart']['include_roles']['roles']          = array();

		
		$_SESSION['saved_settings_data'] = true;

		return $general_setting_options->merge( $new_settings );
	}


	public static function load_view() {
		global $plugin_page;
		$sub_tabs    = self::get_menus();
		$active_tab  = self::get_active_tab();
		$active_page = $plugin_page;
	
		
		if ( isset( $_POST['submit'] ) ) {

			check_admin_referer( 'save_settings', 'req_settings_nonce' );

			if ( 'widget' === self::get_active_tab() ) {
				$settings = WidgetModel::load();
			} elseif ( 'template' === self::get_active_tab() ) {
				$settings = TemplateModel::load();
			} else {
				$settings = GeneralSettings::load();
			}
		
			$settings = apply_filters( 'settings_saving_' . self::get_active_tab(), $settings );
			$settings->save();
		
		}

		include ELEX_RAQ_VIEW_PATH . 'settings.php';
	}

	public static function search_user_role() {
		// Get User role Name.
			check_ajax_referer( 'request-a-quote-ajax-nonce', 'req_a_quote_nonce' );

			
			$search_key = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
			$roles      = GeneralSettings::get_user_role( $search_key );
			wp_send_json_success( $roles );

	}
	public static function search_products_by_name() {
		// Get Product Name.
			check_ajax_referer( 'request-a-quote-ajax-nonce', 'req_a_quote_nonce' );

			$search_key     = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
			$products_array = GeneralSettings::get_products( $search_key );
			wp_send_json_success( $products_array );


	}
	public static function search_products_by_category() {
		// Get Product Name.
			check_ajax_referer( 'request-a-quote-ajax-nonce', 'req_a_quote_nonce' );
			$search_key     = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
			$products_array = GeneralSettings::get_products_by_category( $search_key );
			wp_send_json_success( $products_array );


	}

	public static function search_products_by_tag() {
		// Get Product Name.
			check_ajax_referer( 'request-a-quote-ajax-nonce', 'req_a_quote_nonce' );
			$search_key     = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
			$products_array = GeneralSettings::get_products_by_tag( $search_key );
			wp_send_json_success( $products_array );


	}

	public static function get_menus() {

		$setting_menus = array(
			array(
				'title' => __( 'General Settings' , 'elex-request-a-quote' ),
				'slug'  => 'general',
			),
			
			array(
				'title' => __( 'Hide Add to Cart & Price' , 'elex-request-a-quote' ),
				'slug'  => 'hide_cart',
		
			),
			array(
				'title' => __( 'Notification Settings' , 'elex-request-a-quote' ),
				'slug'  => 'notification',
			),
			array(
				'title' => __( 'Template Settings', 'elex-request-a-quote' ),
				'slug'  => 'template',
			),

			array(
				'title' => __( 'Quote List Menu', 'elex-request-a-quote' ),
				'slug'  => 'widget',
			),
			array(
				'title' => __( 'Button Customization', 'elex-request-a-quote' ),
				'slug'  => 'button',
			),
			array(
				'title' => __( 'Go Premium!' , 'elex-request-a-quote' ),
				'slug'  => 'go_premium',
			),
		
		);

		return apply_filters( 'settings_tabs', $setting_menus );

		
	}
	public static function get_active_tab() {

		if ( isset( $_POST['_wpnonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ) ) ) {
			return;
		}

	   $tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : '';
		return ! empty( $tab ) ? $tab : self::get_default_tab();

	}

	public static function get_default_tab() {
		return apply_filters( 'request_a_quote_settings_default_tab', 'general' );
	}
}
