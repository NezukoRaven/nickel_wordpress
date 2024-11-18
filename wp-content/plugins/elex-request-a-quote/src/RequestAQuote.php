<?php
namespace Elex\RequestAQuote;

use Elex\RequestAQuote\Settings\Models\GeneralSettings;
use Elex\RequestAQuote\Settings\SettingsController;
use Elex\RequestAQuote\FormSetting\FormSettingController;
use Elex\RequestAQuote\HelpAndSupport\HelpAndSupportController;
use Elex\RequestAQuote\Quotelist\ListPageController;
use Elex\RequestAQuote\Quotelist\QuoteListController;
use Elex\RequestAQuote\Quotelist\Models\ListPageSettings;
use Elex\RequestAQuote\Widget\WidgetController;


class RequestAQuote {


	const VERSION  = '2.3.3';
	const INSTANCE = 'RAQ_BASIC';

	public $plugin_basename;

	public function with_basename( $basename ) {
		$this->plugin_basename = $basename;

		return $this;
	}

	public function boot() {

		$this->register_hooks();
	}

	public function register_hooks() {

		add_action( 'admin_init', array( $this, 'migrate' ) );

		add_action( 'init', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front_end_scripts' ) , 999 );

		add_action( 'init', array( $this, 'add_menu' ) );

		add_action( 'widgets_init', array( self::class, 'wp_custom_widget' ) );

		add_action( 'elementor/widgets/register', array( self::class, 'register_elex_raq_add_to_quote_widget' ) );
	
		add_action( 'wp', array( $this, 'check_if_shop_product_page' ) );
		$this->register_routes();
		add_action(
			'woocommerce_init',
			function() {
				wc_enqueue_js(
					"	
				jQuery('a[href*=\"quote-received-page\"]').closest('li').remove();
				//Fires whenever variation selects are changed
				jQuery( '.variations_form' ).on( 'woocommerce_variation_select_change', function () {
					// Fires when variation option isn't selected
					jQuery('form.variations_form').on('hide_variation',function(event, data){
						jQuery('.add_to_quote').addClass('disabled');
						jQuery('.add_to_quote').css('opacity','0.5');
						jQuery('.add_to_quote').attr('disabled', true); 
					});	
				});
			"
				);
			}
		);
	add_action( 'init', array( self::class, 'hide_add_to_cart_inclusion_feature' ));


	}



	/**
	 * Save the page url to local storage only in the shop and product page
	 *
	 * @return void
	 */
	public function check_if_shop_product_page() {

		if (is_product() || is_shop()) {

			wp_enqueue_script( 'raq_save_page', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/save_page_name.js' ), array(), self::VERSION , true );
			self::raq_page_localize_script();
		
		}
	}
		/**
	 * Function to update the hide add to cart and price section default setting values for the newly added inclusion feature.
	 * We can not directly update the values in GeneralSettings::get_default_values() function because which function will be executed only once during first time instalation.
	 *
	 * @return void
	 */
	public static function hide_add_to_cart_inclusion_feature() {
		
		$migration = get_option( 'save_hide_cart_new_fields', false );
		if ( $migration ) {
			return;
		}

		$new_settings = get_option( 'request_a_quote_general_settings', GeneralSettings::get_default_values() );
		
		if ( array_key_exists( 'include_products' , $new_settings['hide_add_to_cart'] ) ) {
			return;
		}
		
		$new_settings['hide_add_to_cart']['include_products']['enabled']     = false;
		$new_settings['hide_add_to_cart']['include_products']['by_category'] = array();
		$new_settings['hide_add_to_cart']['include_products']['by_name']     = array();
		$new_settings['hide_add_to_cart']['include_products']['by_tag']      = array();
		$new_settings['hide_add_to_cart']['include_roles']['roles']          = array();

		update_option( 'request_a_quote_general_settings', $new_settings );
		update_option( 'save_hide_cart_new_fields', true );

	}


	//To load the settings data o the front end to show the add to quote button based on the variation change
	public static  function single_variation() {
		
		$settings = SettingsController::get_settings();
		wp_localize_script(
			'variation_js',
			'variation_js_obj',
			array(
				'settings'    => $settings,
			)
		);

	}


	public static function wp_custom_widget() {

		include_once 'CustomWidget/Custom_Widget.php';
		register_widget( 'Custom_Widget' );
	}



	public static function add_minicart_to_header( $items, $args) {
		$list_page_settings = ListPageSettings::load();
		$settings           = WidgetController::get_settings();
		$list_page_settings = $list_page_settings->to_array();
		$title              = $list_page_settings[ 'quote_list_page' ]['title'];

		$quote_list_page     = get_page_by_path( '/add-to-quote-product-list' );
		$quote_list_page_url = isset($quote_list_page->ID ) ? get_permalink( $quote_list_page->ID ) : ''; 
		// Create a new menu item
		$quote_list_page = '<li class="menu-item"><a href="' . esc_url($quote_list_page_url) . '">' . __( $title , 'elex_request_quote_premium' ) . '</a></li>';

		// Append the custom page to the menu items
		if ( isset($settings['show_widget_icon']) &&  '' !== $quote_list_page_url && true !== $settings['show_widget_icon'] ) {
					$items .= $quote_list_page;
		}

		return $items;
	}

	/** Elementor Add to Quote Widget */
	public static function register_elex_raq_add_to_quote_widget( $widgets_manager ) {

		require_once  __DIR__ . '/ElementorWidget/Eraq_add_to_quote_widget.php' ;
	
		$widgets_manager->register( new \Eraq_Add_To_Quote_Widget() );
	}

	public function register_routes() {
		SettingsController::init();
		FormSettingController::init();
		HelpAndSupportController::init();
		ListPageController::init();
		QuoteListController::init();
		
		include_once 'create_order_status.php';
		
	}
	
	public function migrate() {

		Migrate::run();
	}

	public function elex_quote_request_add_navigation_menu() {

		$quote_request_page = array(
			'post_title'   => __( 'Quotes List', 'elex-request-a-quote' ),
			'post_content' => '[elex_quote_request_list]',
			'post_status'  => 'publish',
			'post_name'    => 'add-to-quote-product-list',
			'post_type'    => 'page',
		);

			wp_insert_post( $quote_request_page );

		// Create post object.
		$quote_received_page = array(
			'post_title'   => __( 'Quote Received', 'elex-request-a-quote' ),
			'post_content' => '[elex_quote_received_page]',
			'post_status'  => 'publish',
			'post_name'    => 'quote-received-page',
			'post_type'    => 'page',
		);
			wp_insert_post( $quote_received_page );


	}

	

	/** Flush data on deactivation. */
	public function elex_quote_request_flush_data() {
		$path_object_1 = get_page_by_path( '/add-to-quote-product-list' );
		wp_delete_post( $path_object_1->ID );
		$path_object_2 = get_page_by_path( '/quote-received-page' );
		wp_delete_post( $path_object_2->ID );
	}


	public function enqueue_scripts() {
		$page = isset($_REQUEST['page']) ? sanitize_text_field($_REQUEST['page']):'';

		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : '';
		if ( 'form' === $tab ) {
			wp_enqueue_script( 'request_a_quote_formsetting', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/components/form_settings.min.js' ), array( 'jquery', 'wp-element', 'wp-i18n' ), self::VERSION );
			self::form_settings_localize_script();
		}
	

		wp_enqueue_script( 'request_a_quote_select_2_js', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/select2-min.js' ), array( 'jquery', 'underscore' ), self::VERSION, true );
		wp_enqueue_style( 'request_a_quote_select_2_css', plugins_url( dirname( $this->plugin_basename ) . '/assets/css/select-2-min.css' ), array(), self::VERSION );
		wp_enqueue_script( 'request_a_quote_script', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/req_script.js' ), array( 'jquery' ), self::VERSION , true );
		
		wp_enqueue_script( 'request_a_quote_popper_script', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/popper.js' ), array(), self::VERSION , true );
		wp_enqueue_script( 'request_a_quote_fontawesome', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/fontawesome.js' ), array(), self::VERSION , true );
		wp_enqueue_script( 'request_a_quote_chosen', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/settings.js' ), array(), self::VERSION , true );

		//Do not load the bootstrap scripts when the page is from b2bking plugin.Its causing conflict only with 'b2bkingcore' However for safer side we have added all the pages of b2bking plugin.
		$b2bking_pages = array('b2bking_customers','b2bking_dashboard' ,'b2bkingcore','b2bking_groups','b2bking_reports','b2bking_businessregistration','b2bking_tieredpricing','b2bking_rule');
		$theme         = wp_get_theme();	// $theme = wp_get_theme();
		if (!empty($theme) && ( 'Woodmart' !==  $theme->name && 'Woodmart Child' !== $theme->name ) && !in_array($page , $b2bking_pages) ) {
		wp_enqueue_script( 'request_a_quote_bootstrap_script', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/bootstrap.js' ), array(), self::VERSION , true );

		}

		$page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : $page;
		$tab  = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'button';


		
		//Do not Load the front end scripts in admin pages
		$pages = array_merge(array( 'settings', 'product_importer', 'product_exporter','b2bkingcore','wc-status' ) , $b2bking_pages);
		if ( ! in_array( $page, $pages ) ) {
			wp_enqueue_script( 'quote_list', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/components/quote_list/quote_list.min.js' ), array( 'jquery', 'underscore', 'wp-element', 'wp-i18n' ), self::VERSION );
			wp_enqueue_script( 'mini_quote_list', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/components/mini_quote_list/render_mini_quote_list.min.js' ), array( 'jquery', 'underscore', 'wp-element', 'wp-i18n' ), self::VERSION );
			wp_enqueue_script( 'mini_quote', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/miniquote.js' ), array( 'jquery', 'underscore', 'wp-element', 'wp-i18n' ), self::VERSION );
			wp_enqueue_script( 'add_to_quote', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/components/quote_list/add_to_quote.js' ), array( 'jquery', 'underscore', 'wp-element', 'wp-i18n' ), self::VERSION , true );
			self::localize_add_to_quote();
		}
		wp_enqueue_style( 'request_a_quote_front_style', plugins_url( dirname( $this->plugin_basename ) . '/assets/css/app.css' ), array(), self::VERSION );
		wp_enqueue_style( 'request_a_quote_mini_float_widget_style', plugins_url( dirname( $this->plugin_basename ) . '/assets/css/mini-quote-float-widget.css' ), array(), self::VERSION );    
		wp_enqueue_script( 'quote_items', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/components/quote_list/quote_items.min.js' ), array( 'jquery', 'underscore', 'wp-element', 'request_a_quote_script', 'wp-i18n' ), self::VERSION , true );

		$plugin_pages = array( 'elex_raq_settings', 'elex_listpage' ,'elex_raq_dashboad' ,'elex_helpandsupport');

		if ( in_array( $page, $plugin_pages ) ) {
			wp_enqueue_script( 'request_a_quote_bootstrap_script', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/bootstrap.js' ), array(), self::VERSION , true );
		}
		self::quote_list_localize_script();
		self::localize_script();
		
	}
	public static function localize_add_to_quote() {
		$theme = wp_get_theme();
		if (!empty($theme)) {
			$theme = $theme->name;
		}
		
		wp_localize_script(
			'add_to_quote',
			'add_to_quote_obj',
			array(
				'theme' => $theme,
				'shop' => get_permalink( wc_get_page_id( 'shop' ) )
			)
			);
		
	}
	public function enqueue_front_end_scripts() {
		
		$activated_plugins = (array) get_option( 'active_plugins' );
		if ( is_multisite() ) {
			$activated_plugins = array_merge( $activated_plugins, get_site_option( 'active_sitewide_plugins' ), array() );
		}
		//get_option( 'woocommerce_enable_ajax_add_to_cart' ) must be true when the composite product is enabled otherwise,Add to quote will be enabled even before the variation is selected
		if ( ( ( in_array( 'woocommerce-composite-products/woocommerce-composite-products.php', $activated_plugins ) || array_key_exists( ' woocommerce-composite-products/woocommerce-composite-products.php', $activated_plugins ) ) )  ) {
			if ( 'yes' === get_option( 'woocommerce_enable_ajax_add_to_cart' ) ) {
		wp_enqueue_script( 'request_a_quote_front_script', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/front_script.min.js' ), array(), self::VERSION , true );
		wp_enqueue_script( 'variation_js', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/single_variation.js' ), array( 'jquery', 'underscore', 'wp-element', 'wp-i18n' ), self::VERSION );
		self::single_variation();
	
			}

		} else {
			wp_enqueue_script( 'request_a_quote_front_script', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/front_script.min.js' ), array(), self::VERSION , true );
			wp_enqueue_script( 'variation_js', plugins_url( dirname( $this->plugin_basename ) . '/assets/js/single_variation.js' ), array( 'jquery', 'underscore', 'wp-element', 'wp-i18n' ), self::VERSION );
			self::single_variation();

		}

	}
	/**
	 * Function to send the quote page url to script file when we set the current page to localstorage to use later
	 *
	 * @return void
	 */
	public static function raq_page_localize_script() {
		$quote_list_page     = get_page_by_path( '/add-to-quote-product-list' );
		$quote_list_page_url = isset( $quote_list_page->ID ) ? get_permalink( $quote_list_page->ID ) : ''; 
		wp_localize_script(
			'raq_save_page',
			'raq_save_page_obj',
			array(
				'quote_page' => $quote_list_page_url
			)
			);
		
	}

	public function form_settings_localize_script() {

		$form_settings = FormSettingController::get_settings();
		$form_settings = $form_settings->to_array();
		wp_localize_script(
			'request_a_quote_formsetting',
			'raq_formsetting_ajax_object',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'raq-formsetting-ajax-nonce' ),
				'form_settings' => $form_settings,
				
			)
		);
	}

	public function localize_script() {
		wp_localize_script(
			'request_a_quote_script',
			'request_a_quote_ajax_obj',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'request-a-quote-ajax-nonce' ),
			)
		);
	}

	public static  function get_translations() {
		$locale       = get_locale();
		$translations = array();
		if ( 'en-US' !== $locale ) {

			$lang_folder_path = ELEX_REQUEST_A_QUOTE . '/lang/';
			$file_path        = sprintf( '%s%s-%s.po', $lang_folder_path, 'elex-request-a-quote', $locale );

			if ( file_exists( $file_path ) ) {
				$po = file( $file_path );

				$current = null;
				foreach ( $po as $line ) {

					if ( substr( $line, 0, 5 ) === 'msgid' ) {
						
						$current = trim( substr( trim( substr( $line, 5 ) ), 1, -1 ) );
					}
					if ( substr( $line, 0, 6 ) === 'msgstr' ) {
						$translations[ $current ] = trim( substr( trim( substr( $line, 6 ) ), 1, -1 ) );
					}
				}
			}
		}

		return $translations;
	}
	
	public function quote_list_localize_script() {
		$translations = self::get_translations();

		wp_localize_script(
			'quote_list',
			'elex_raq_translations',
			$translations
		);

		wp_localize_script(
			'mini_quote_list',
			'elex_raq_translations',
			$translations
		);
		wp_localize_script(
			'add_to_quote',
			'elex_raq_translations',
			$translations
		);
		wp_localize_script(
			'quote_items',
			'elex_raq_translations',
			$translations
		);
		wp_localize_script(
			'mini_quote',
			'elex_raq_translations',
			$translations
		);
		wp_localize_script(
			'quote_list',
			'quote_list_ajax_obj',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'request-a-quote-ajax-nonce' ),
			)
		);
	}


	public function add_menu() {
		add_action( 'admin_menu', array( $this, 'add_admin_main_menu' ) );

	}

	public function add_admin_main_menu() {

		$parent_slug = 'elex-request-a-quote';

		add_menu_page(
			__( 'Request a Quote', 'elex-request-a-quote' ),
			__( 'Request a Quote', 'elex-request-a-quote' ),
			'manage_options',
			$parent_slug,
			array( SettingsController::class, 'load_general_settings' ),
			esc_url( plugins_url() . '/elex-request-a-quote/assets/images/ELEX-grey-logo-forsidebar.svg' ),
			57
		);

		add_submenu_page(
			$parent_slug,
			__( 'Settings', 'elex-request-a-quote' ),
			__( 'Settings', 'elex-request-a-quote' ),
			'manage_options',
			'elex_raq_settings',
			array( SettingsController::class, 'load_view' )
		);

		add_submenu_page(
			$parent_slug,
			__( 'Customize Quote List & Form', 'elex-request-a-quote' ),
			__( 'Customize Quote List & Form', 'elex-request-a-quote' ),
			'manage_options',
			'elex_listpage',
			array( ListPageController::class, 'load_view' )
		);
		add_submenu_page(
			$parent_slug,
			__( 'Help & Support', 'elex-request-a-quote' ),
			__( 'Help & Support', 'elex-request-a-quote' ),
			'manage_options',
			'elex_helpandsupport',
			array( HelpAndSupportController::class, 'load_view' )
		);
		remove_submenu_page( $parent_slug, $parent_slug ); 

	}

}
