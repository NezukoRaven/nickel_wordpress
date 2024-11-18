<?php

namespace Elex\RequestAQuote\FormSetting;

use Elex\RequestAQuote\FormSetting\Models\FormSettings;
use Elex\RequestAQuote\FormSetting\Models\FormField;


class FormSettingController {

	const ELEX_RAQ_FORM_SETTINGS_KEY = 'request_a_quote_form_setting';

	public static $form_settings = null;
	public $form;
	
	public static function init() {

		add_action( 'req_settings_tab_form', array( self::class, 'load_formsettings' ) );

		add_action( 'wp_ajax_req_frm_add_field', array( self::class, 'elex_raq_add_field' ) );
		add_action( 'wp_ajax_req_frm_edit_field', array( self::class, 'elex_raq_form_edit_field' ) );

		add_action( 'wp_ajax_req_frm_toggle_field', array( self::class, 'elex_raq_toggle_field' ) );
		add_action( 'wp_ajax_req_frm_delete_field', array( self::class, 'elex_raq_delete_field' ) );
		add_action( 'wp_ajax_req_frm_submit', array( self::class, 'form_submit' ) );
		add_action( 'wp_ajax_nopriv_req_frm_submit', array( self::class, 'form_submit' ) );
		add_action( 'settings_saving_form', array( self::class, 'save_form_settings' ) );
		add_action( 'wp_ajax_elex_raq_rearrange_fields', array( self::class, 'rearrange_field' ) );
		add_action( 'wp_ajax_elex_raq_save_form_settings_data', array( self::class, 'elex_raq_save_form_settings_data' ) );

		
	}

	
	
	public function get_form() {

		$conf = get_option( self::ELEX_RAQ_FORM_SETTINGS_KEY, FormSettings::get_default_form_data() );
		if (is_bool($conf) || is_string($conf) || '' === $conf ) {
			$conf =  FormSettings::get_default_form_data();
		}

		$this->form = FormSettings::build( $conf );

		return $this->form;
	}

	public static function get_settings() {
		
		$conf = get_option( self::ELEX_RAQ_FORM_SETTINGS_KEY, FormSettings::get_default_form_data() );
		if (is_bool($conf) || is_string($conf) || '' === $conf ) {
			$conf =  FormSettings::get_default_form_data();
		}
		$form_settings = FormSettings::build( $conf );

		$form_settings = apply_filters( 'request_a_quote_form_settings', $form_settings );

		return  $form_settings;

	}

	public static function get_custom_fields_count() {
	
		$self  = new self();
		$form  = $self->get_form();
		$count = $form->get_default_count();
		return $count;

	}

	public static function get_stored_field() {
		# code...
		$self   = new self();
		$form   = $self->get_form();
		$fields = $form->get_fields();

		return $fields;


	}

	public static function rearrange_field() {

		check_ajax_referer( 'raq-formsetting-ajax-nonce', 'ajax_raq_nonce' );

		if ( ! isset( $_POST['source_index'] ) || ! isset( $_POST['destination_index'] ) ) {
			wp_send_json_error(
				array(
					'msg' => __( 'Name and type Can not be empty', 'elex-request-a-quote' ),
				) 
			);
				die();
		}

		$source_index      = isset( $_POST['source_index'] ) ? (int) sanitize_text_field( $_POST['source_index'] ) : '';
		$destination_index = isset( $_POST['destination_index'] ) ? (int) sanitize_text_field( $_POST['destination_index'] ) : '';

		$self = new self();
		$form = $self->get_form();
		$form->rearrange( $source_index , $destination_index );
		$self->save_form( $form );
		wp_send_json_success(
			array(
				'msg' => __( 'Field has been rearranged successfully', 'elex-request-a-quote' ),
			)
		);

	}



	
	public static  function elex_raq_add_field() {
		check_ajax_referer( 'raq-formsetting-ajax-nonce', 'ajax_raq_nonce' );


		if ( ! isset( $_POST['data']['name'] ) || empty( $_POST['data']['name'] ) || empty( $_POST['data']['type'] ) || ! isset( $_POST['data']['type'] ) ) {
			wp_send_json_error(
				array(
					'msg' => __( 'Name and type Can not be empty', 'elex-request-a-quote' ),
				) 
			);
				die();
		}
		$data                 = array();
		$data['name']         = isset( $_POST['data']['name'] ) ? sanitize_text_field( $_POST['data']['name'] ) : '';
		$data['slug']         = FormSettings::slug( $data['name'] );
		$data['type']         = isset( $_POST['data']['type'] ) ? sanitize_text_field( $_POST['data']['type'] ) : '';
		$data['connected_to'] = isset( $_POST['data']['connected_to'] ) ? sanitize_text_field( $_POST['data']['connected_to'] ) : '';
		$data['mandatory']    = isset( $_POST['data']['mandatory'] ) ? (bool) sanitize_text_field( $_POST['data']['mandatory'] ) : false;
		$data['placeholder']  = isset( $_POST['data']['placeholder'] ) ? map_deep( $_POST['data']['placeholder'] , 'sanitize_text_field' ) : array();
		$data['is_editing']   = false;
		$data['deletable']    = isset( $_POST['data']['deletable'] ) ? (bool) sanitize_text_field( $_POST['data']['deletable'] ) : false;

		$data['options'] = array();
		$data['key']     = uniqid();

		if ( 'radio' === $data['type'] || 'checkbox' === $data['type'] ) {
			$data['is_radio_checkbox'] = true;
			
			if ( ! isset( $_POST['data']['options'] ) || ! is_array( $_POST['data']['options'] ) || ( 'checkbox' === $data['type'] && count( $_POST['data']['options'] ) < 1 ) || ( 'radio' === $data['type'] &&  count( $_POST['data']['options'] ) <= 1 ) ) {	
				wp_send_json_error(
					array(
						'msg' => __( 'Enter options for the selcted types', 'elex-request-a-quote' ),
					) 
				);
					die();
			}
			$data['options'] = isset( $_POST['data']['options'] ) ? map_deep( $_POST['data']['options'], 'sanitize_text_field' ) : array();
			
		}

		$field = FormField::build( $data );
		
		if ( $field->must_have_options() && $field->has_options() === false ) {
			wp_send_json_error(
				array(
					'msg'  => __( 'Atleast one option is required', 'elex-request-a-quote' ),
					'code' => 4,
				) 
			);
				die();
		}

		$self     = new self();
		$form     = $self->get_form();
		$is_exist = $form->find_field( $data['name'] );
		if ( $is_exist ) {
			wp_send_json_error(
				array(
					'msg'  => __( 'Already exist', 'elex-request-a-quote' ),
					'code' => 3,
				) 
			);
				die();

		}

		$form->add_field( $field );

		$self->save_form( $form );
		wp_send_json_success(
			array(
				'msg'  => __( 'Field has been added successfully', 'elex-request-a-quote' ),
				'code' => 1,
			)
		);

	}


	public static function save_form_data( $new_settings ) {

		$form_settings = self::get_settings();
		$form_settings = self::converToArray( $form_settings );
	
		$form_settings['show_form'] = (bool) $new_settings['show_form'];


		$form_settings['title']           = $new_settings['title'];
		$form_settings['redirection_url'] = $new_settings['redirection_url'];
		$form_settings['success_message'] = $new_settings['success_message'];
		$new_array                        = array_merge( $form_settings , $new_settings );

		update_option( 'request_a_quote_form_setting' , $new_array );

	}

	/**
	 * Function which accepts multiple field array and strips out the slashed added in the field
	 *
	 * @param [type] $field
	 * @return void
	 */
	public static function clean_up_special_char( $fields) {
		$result = array_map(
			function( $field ) {
			$field['name']        = stripslashes($field['name']);
			$field['placeholder'] =stripslashes($field['placeholder']);
				if (!empty($field['options'] )) {
					for ($i = 0 ;$i< count($field['options']);$i++) {
						if (is_array($field['options'][$i])) {
							$field['options'][$i] = isset($field['options'][$i]['value']) ? stripslashes( $field['options'][$i]['value'] ) :stripslashes( $field['options'][$i] );
						} else {
							$field['options'][$i] =stripslashes($field['options'][$i]);
						}
					}

				}
				
				
			return $field;
			},
			$fields
		);
		return $result;

	}

	/**
	 * Function which accepts single field array and strips out the slashed added in the field
	 *
	 * @param [type] $field
	 * @return void
	 */
	public static function strip_slashes_from_field( $field) {
		
			$field['name']        = stripslashes($field['name']);
			$field['placeholder'] =stripslashes($field['placeholder']);
		if (!empty($field['options'] )) {
			for ($i = 0 ;$i< count($field['options']);$i++) {
				if (is_array($field['options'][$i])) {
					$field['options'][$i] = isset($field['options'][$i]['value']) ? stripslashes( $field['options'][$i]['value'] ) : stripslashes( $field['options'][$i] );
				} else {
					$field['options'][$i] =stripslashes($field['options'][$i]);
				}
			}

		}
			return $field;

	}


	public static function elex_raq_save_form_settings_data() {
		check_ajax_referer( 'raq-formsetting-ajax-nonce', 'ajax_raq_nonce' );

		$new_settings   = array();
		$quote_recevied = get_page_by_path( '/quote-received-page' );
		$page_url       = get_permalink( $quote_recevied->ID ); 

		$new_settings['title'] = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : __('Quote Request Form', 'elex-request-a-quote');
		// $new_settings['show_form']       =  isset( $_POST['show_form'] ) ? sanitize_text_field($_POST['show_form']) : false;
		$new_settings['redirection_url'] = isset( $_POST['redirection_url'] ) && ! empty( $_POST['redirection_url'] ) ? sanitize_text_field( $_POST['redirection_url'] ) : $page_url;
		$new_settings['success_message'] = isset( $_POST['success_message'] ) ? sanitize_text_field( $_POST['success_message'] ) : __('Your request has been submitted.', 'elex-request-a-quote');
		if ( isset( $_POST['show_form'] ) ) {
			$new_settings['show_form'] = sanitize_text_field( $_POST['show_form'] );
		} else {
			$new_settings['show_form'] = false;
		}
	

		self::save_form_data( $new_settings );


		$fields = isset( $_POST['form_fields'] ) ? map_deep( $_POST['form_fields'] , 'sanitize_text_field' ) : array();
		$fields = self::clean_up_special_char($fields);

		if ( ! empty( $fields ) ) {
			foreach ( $fields as $field ) {
			
				if ( isset( $field['id'] ) && ! empty( $field['id'] ) ) {
					self::elex_raq_edit_field( $field );

				} else {
					self::elex_raq_add_new_field( $field );

				}           
			}
		}
		wp_send_json_success(
			array(
				'msg'  => __( 'Saved Successfully.', 'elex-request-a-quote' ),
				'code' => 1,
			) 
		);
		
		


	}

	public static function elex_raq_add_new_field( $field ) {
		self::check_if_field_name_exist( $field );

		if ( 'radio' === $field['type'] || 'checkbox' === $field['type'] ) {
			
			self::check_radio_checkbox_values( $field );
			$field['options']           = isset( $field['options'] ) ? map_deep( $field['options'], 'sanitize_text_field' ) : array();
			$field['is_radio_checkbox'] = true;
			
		}
		$field['key']         = uniqid();
		$field['is_editing']  = false;
		$field['slug']        = FormSettings::slug( $field['name'] );
		$field['mandatory']   = isset( $field['mandatory'] ) && ! empty( $field['mandatory'] ) ? true : false;
		$field['placeholder'] = isset( $field['placeholder'] ) ? map_deep( $field['placeholder'] , 'sanitize_text_field' ) : array();
		$field['deletable']   = isset( $field['deletable'] ) && ! empty( $field['deletable'] ) ? true : false;
		$field                = self::strip_slashes_from_field($field);
		
		self::build_form_for_new_fields( $field );


	}

	public static function check_radio_checkbox_values( $field ) {
		if ( ! isset( $field['options'] ) || ! is_array( $field['options'] ) || count( $field['options'] ) <= 1 ) {
			wp_send_json_error(
				array(
					'msg'  => __( 'Enter options for the selcted types', 'elex-request-a-quote' ),
					'code' => 4,
				) 
			);
				die();
		}

		

	}

	public static function check_if_field_name_exist( $field ) {
		if ( ! isset( $field['name'] ) || empty( $field['name'] ) || empty( $field['type'] ) || ! isset( $field['type'] ) ) {
			wp_send_json_error(
				array(
					'msg' => __( 'Name and type Can not be empty', 'elex-request-a-quote' ),
				) 
			);
				die();
		}
	}
	public static function elex_raq_edit_field( $field ) {
		
		self::check_if_field_name_exist( $field );
		if ( 'radio' === $field['type'] || 'checkbox' === $field['type'] ) {
			
			self::check_radio_checkbox_values( $field );
			$field['options']           = isset( $field['options'] ) ? map_deep( $field['options'], 'sanitize_text_field' ) : array();
			$field['is_radio_checkbox'] = true;
			
		}
		$field['is_editing']  = false;
		$field['mandatory']   = ( 'true' === $field['mandatory'] || true === $field['mandatory'] ) ? true : false;
		$field['placeholder'] = isset( $field['placeholder'] ) ? map_deep( $field['placeholder'] , 'sanitize_text_field' ) : array();
		$field['deletable']   = ( 'true' === $field['deletable'] || true === $field['deletable'] ) ? true : false;
		$field                = self::strip_slashes_from_field($field);
		
		self::build_form( $field );

	}

	public static function build_form_for_new_fields( $data ) {
		$field = FormField::build( $data );
		
		if ( $field->must_have_options() && $field->has_options() === false ) {
			wp_send_json_error(
				array(
					'msg'  => __( 'Atleast one option is required', 'elex-request-a-quote' ),
					'code' => 4,
				) 
			);
				die();
		}

		$self     = new self();
		$form     = $self->get_form();
		$is_exist = $form->find_field( $data['name'] );
		if ( $is_exist ) {
			wp_send_json_error(
				array(
					'msg'  => __( 'Already exist', 'elex-request-a-quote' ),
					'code' => 3,
				) 
			);
				die();

		}

		$form->add_field( $field );

		$self->save_form( $form );
	}

	public static function build_form( $data ) {

		$field = FormField::build( $data );
		
		if ( $field->must_have_options() && $field->has_options() === false ) {
			wp_send_json_error(
				array(
					'msg' => __( 'Atleast one option is required', 'elex-request-a-quote' ),
				) 
			);
				die();
		}

		$self         = new self();
		$form         = $self->get_form();
		$is_key_exist = $form->find_field_with_key( $data['key'] );

		if ( false === $is_key_exist ) {
			wp_send_json_error(
				array(
					'msg' => __( 'No Field Exist', 'elex-request-a-quote' ),
				) 
			);
				die();

		}

		$form->edit_field( $field , $data['key'] );
		$self->save_form( $form );

	}

	public static  function elex_raq_form_edit_field() {
		check_ajax_referer( 'raq-formsetting-ajax-nonce', 'ajax_raq_nonce' );


		if ( ! isset( $_POST['data']['name'] ) || empty( $_POST['data']['name'] ) || empty( $_POST['data']['type'] ) || ! isset( $_POST['data']['type'] ) ) {
			wp_send_json_error(
				array(
					'msg' => __( 'Name and type Can not be empty', 'elex-request-a-quote' ),
				) 
			);
				die();
		}
		$data                 = array();
		$data['name']         = isset( $_POST['data']['name'] ) ? sanitize_text_field( $_POST['data']['name'] ) : '';
		$data['slug']         = FormSettings::slug( $data['name'] );
		$data['type']         = isset( $_POST['data']['type'] ) ? sanitize_text_field( $_POST['data']['type'] ) : '';
		$data['connected_to'] = isset( $_POST['data']['connected_to'] ) ? sanitize_text_field( $_POST['data']['connected_to'] ) : '';
		$data['mandatory']    = isset( $_POST['data']['mandatory'] ) ? (bool) ( sanitize_text_field( $_POST['data']['mandatory'] ) ) : false;
		$data['placeholder']  = isset( $_POST['data']['placeholder'] ) ? map_deep( $_POST['data']['placeholder'] , 'sanitize_text_field' ) : array();
		$data['key']          = isset( $_POST['data']['key'] ) ? sanitize_text_field( $_POST['data']['key'] ) : '';
		$data['deletable']    = isset( $_POST['data']['deletable'] ) ? (bool) sanitize_text_field( $_POST['data']['deletable'] ) : false;
		$data['is_editing']   = false;
		
		
		$data['options'] = array();
		if ( 'radio' === $data['type'] || 'checkbox' === $data['type'] ) {
			if ( ! isset( $_POST['data']['options'] ) || ! is_array( $_POST['data']['options'] ) || ( 'checkbox' === $data['type'] && count( $_POST['data']['options'] ) < 1 ) || ( 'radio' === $data['type'] &&  count( $_POST['data']['options'] ) <= 1 ) ) {	
				wp_send_json_error(
					array(
						'msg'  => __( 'Enter options for the selcted types', 'elex-request-a-quote' ),
						'code' => 4,
					) 
				);
					die();
			}
			$data['options']       = isset( $_POST['data']['options'] ) ? map_deep( $_POST['data']['options'], 'sanitize_text_field' ) : array();
		$data['is_radio_checkbox'] = true;
			
		}
		$data = self::strip_slashes_from_field($data);

		$field = FormField::build( $data );
		
		if ( $field->must_have_options() && $field->has_options() === false ) {
			wp_send_json_error(
				array(
					'msg' => __( 'Atleast one option is required', 'elex-request-a-quote' ),
				) 
			);
				die();
		}

		$self         = new self();
		$form         = $self->get_form();
		$is_key_exist = $form->find_field_with_key( $data['key'] );

		if ( false === $is_key_exist ) {
			wp_send_json_error(
				array(
					'msg' => __( 'No Field Exist', 'elex-request-a-quote' ),
				) 
			);
				die();

		}

		$form->edit_field( $field , $data['key'] );
		$self->save_form( $form );
		wp_send_json_success(
			array(
				'msg'  => __( 'Field has been edited successfully', 'elex-request-a-quote' ),
				'code' => 2,
			)
		);

	}

	public function save_form( FormSettings $form ) {
		update_option( self::ELEX_RAQ_FORM_SETTINGS_KEY, $form->to_array() );
	}

	public static function converToArray( FormSettings $form ) {
		return $form->to_array();
	}

	public static function elex_raq_toggle_field() {
		check_ajax_referer( 'raq-formsetting-ajax-nonce', 'ajax_raq_nonce' );


		$key = isset( $_POST['key'] ) ? sanitize_text_field( $_POST['key'] ) : '';

		$settings = new self();
		$form     = $settings->get_form();

		$field = $form->find_field_with_key( $key );
		if ( false === $field ) {
			wp_send_json_error(
				array(
					'msg' => __( 'No Field Exist', 'elex-request-a-quote' ),
				) 
			);
				die();

		}
		if ( $field->active() === true ) {
			$field->deactivate();
		} else {
			$field->activate();
		}

		$settings->save_form( $form );

		wp_send_json_success(
			array(
				'message' => __( 'Field has been ' . ( $field->active() ? 'activated' : 'deactivated' ), 'wschat' ),
				'status'  => $field->status,
				'active'  => $field->active(),
				'code'    => 200,
			)
		);
	}


	public static function elex_raq_delete_field() {

		check_ajax_referer( 'raq-formsetting-ajax-nonce', 'ajax_raq_nonce' );


		$key = isset( $_POST['key'] ) ? sanitize_text_field( $_POST['key'] ) : '';

		$self = new self();
		$form = $self->get_form();

		$field = $form->find_field_with_key( $key );

		if ( false === $field ) {
			wp_send_json_error(
				array(
					'message' => __( 'Invalid field', 'wschat' ),
				)
			);
		}

		$form->remove_field( $field );
		$self->save_form( $form );
	

		wp_send_json_success(
			array(
				'message' => __( 'Field has been deleted', 'elex' ),
				'code'    => 200,
			),
			200
		);
	}
	public static function load_formsettings() {

		$form_settings = self::get_settings();
		// $form_settings = self::get_form();


		include ELEX_RAQ_VIEW_PATH . 'listpage_form_setting/form_settings.php';
	}

	
	
	public static function get_active_tab() {
		
		if ( isset( $_POST['_wpnonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ) ) ) {
			return;
		}

	   $tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : '';
		return ! empty( $tab ) ? $tab : self::get_default_tab();

	}

	public static function get_default_tab() {
		return apply_filters( 'request_a_quote_list_settings_default_tab', 'listpage' );
	}

	public static  function save_form_settings( FormSettings $form_setting_options ) {

		$nonce = isset( $_POST['elex_form_settings_nonce'] ) ? sanitize_text_field( $_POST['elex_form_settings_nonce'] ) : '';
		if ( ! wp_verify_nonce( $nonce, 'raq-formsetting-ajax-nonce' ) ) {
			return;
		}

		$new_settings   = array();
		$quote_recevied = get_page_by_path( '/quote-received-page' );
		$page_url       = get_permalink( $quote_recevied->ID ); 

		$new_settings['title']           = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) :__('Quote Request Form', 'elex-request-a-quote');
		$new_settings['show_form']       = isset( $_POST['show_form'] ) ? (bool) sanitize_text_field( $_POST['show_form'] ) : false;
		$new_settings['redirection_url'] = isset( $_POST['redirection_url'] ) && ! empty( $_POST['redirection_url'] ) ? sanitize_text_field( $_POST['redirection_url'] ) : $page_url;
		$new_settings['success_message'] = isset( $_POST['success_message'] ) ? sanitize_text_field( $_POST['success_message'] ) :  __('Your request has been submitted.', 'elex-request-a-quote');
		
		return $form_setting_options->merge( $new_settings );
	}

	public static  function set_default_field_count() {
		
	}
	public static function form_submit() {

		check_ajax_referer( 'form_nonce', 'req_form_submit_nonce' );
		$self = new self();

		$form  = $self->get_form();
		$rules = array();
		$data  = array();

		foreach ( $form->get_fields() as $field ) {
			$field_rules = array();

			$data[ $field->slug ] = array(
				'name' => $field->name,
			);

			if ( FormField::TYPE_CHECKBOX === $field->type ) {
				$data[ $field->slug ]['value'] = isset( $_POST[ $field->slug ] ) ? map_deep( $_POST[ $field->slug ]  , 'sanitize_text_field' ) : array();
			} 

			if ( FormField::TYPE_EMAIL === $field->type ) {
				$data[ $field->slug ]['value'] = isset( $_POST[ $field->slug ] ) ? sanitize_text_field( $_POST[ $field->slug ] ) : '';
				if ( ! filter_var( $data[ $field->slug ]['value'], FILTER_VALIDATE_EMAIL ) ) {
					$field_rules['invalid'] = __( 'The ' . $field->slug . ' is invalid email' );
				}
			} else {
				$data[ $field->slug ]['value'] = isset( $_POST[ $field->slug ] ) ? sanitize_text_field( $_POST[ $field->slug ] ) : '';
			}


			if ( $field->mandatory() && empty( $data[ $field->slug ]['value'] ) ) {
				$field_rules['required'] = __( 'The ' . $field->slug . ' is required' );
			}

			if ( count( $field_rules ) ) {
				$rules[ $field->slug ] = $field_rules;
			}
		}
		if ( count( $rules ) ) {
			wp_send_json_error(
				array(
					'message' => $rules,
				)
			);
		}
		
	}



}
