<?php
namespace Elex\RequestAQuote\Widget;

use Elex\RequestAQuote\Widget\Models\WidgetModel;
use Elex\RequestAQuote\Settings\SettingsController;

class WidgetController {

	public static $settings = null;

	public static function init() {
		add_action( 'req_settings_tab_widget', array( self::class, 'load_widget' ) );
		add_filter( 'settings_saving_widget', array( self::class, 'save_widget' ) );

	}

	public static function get_settings( $reload = false ) {

		$settings       = WidgetModel::load();
		$settings       = $settings->to_array();
		self::$settings = apply_filters( 'request_a_quote_settings', $settings );

		return  self::$settings;

	}
	
	public static function save_general( WidgetModel $widget_setting_options ) {
		
		check_admin_referer( 'save_settings', 'req_settings_nonce' );

		$new_settings = array();

		$new_settings['show_widget_icon']         = isset( $_POST['show_widget_icon'] ) ? sanitize_text_field( $_POST['show_widget_icon'] ) : false;
		$new_settings['quote_list_icon_position'] = isset( $_POST['quote_list_icon_position'] ) ? sanitize_text_field( $_POST['quote_list_icon_position'] ) : 'float';
		$new_settings['widget_color']             = isset( $_POST['widget_color'] ) ? sanitize_text_field( $_POST['widget_color'] ) : false;
		$new_settings['show_button_label']        = isset( $_POST['show_button_label'] ) ? sanitize_text_field( $_POST['show_button_label'] ) : false;
		$new_settings['button_label']             = isset( $_POST['button_label'] ) ? sanitize_text_field( $_POST['button_label'] ) : __( 'Quote List' , 'elex-request-a-quote' );
		$new_settings['show_list_popup_on_hover'] = isset( $_POST['show_list_popup_on_hover'] ) ? sanitize_text_field( $_POST['show_list_popup_on_hover'] ) : '';
	
		$_SESSION['saved_settings_data'] = true;
		return $widget_setting_options->merge( $new_settings );
	}

	public static function load_widget() {

		$settings = self::get_settings();
		include ELEX_RAQ_VIEW_PATH . 'settings/widget.php';
		SettingsController::show_saved_toast();
	}

	public static function save_widget( WidgetModel $setting_options ) {
	   check_admin_referer( 'save_settings', 'req_settings_nonce' );


		$new_settings = array();

		$new_settings['show_widget_icon']         = isset( $_POST['show_widget_icon'] ) ? (bool) sanitize_text_field( $_POST['show_widget_icon'] ) : false;
		$new_settings['quote_list_icon_position'] = isset( $_POST['quote_list_icon_position'] ) ? sanitize_text_field( $_POST['quote_list_icon_position'] ) : 'fixed';
		$new_settings['widget_color']             = isset( $_POST['widget_color'] ) ? sanitize_text_field( $_POST['widget_color'] ) : '';
		$new_settings['widget_color']             = isset( $_POST['widget_color'] ) ? sanitize_text_field( $_POST['widget_color'] ) : '';
		$new_settings['show_button_label']        = isset( $_POST['show_button_label'] ) ? (bool) sanitize_text_field( $_POST['show_button_label'] ) : false;
		$new_settings['button_label']             = isset( $_POST['button_label'] ) ? sanitize_text_field( $_POST['button_label'] ) : __( 'Quote List' , 'elex-request-a-quote' );
		$new_settings['show_list_popup_on_hover'] = isset( $_POST['show_list_popup_on_hover'] ) ? (bool) sanitize_text_field( $_POST['show_list_popup_on_hover'] ) : false;
		$_SESSION['saved_settings_data']          = true;
		return $setting_options->merge( $new_settings );
	}

}
