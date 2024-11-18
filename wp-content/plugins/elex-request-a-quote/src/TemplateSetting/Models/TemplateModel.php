<?php

namespace Elex\RequestAQuote\TemplateSetting\Models;

use Elex\RequestAQuote\FormSetting\FormSettingController;
use Elex\RequestAQuote\FormSetting\Models\FormSettings;
use Elex\RequestAQuote\Quotelist\Models\QuoteListModel;
use Elex\RequestAQuote\Quotelist\ListPageController;
use Elex\RequestAQuote\Quotelist\QuoteListController;


class TemplateModel {

	protected $data;
	public static $templates = array(
		'non_logo'       => 1,
		'squared_logo'   => 2,
		'rectangle_logo' => 3,
	);

	public static function load() {
		$self = new self();

		$self->data = get_option( 'request_a_quote_template_settings', self::get_default_values() );

		return $self;
	}

	public function save() {
		update_option( 'request_a_quote_template_settings', $this->data );
		return $this;
	}

	public static function get_default_values() {

		$data = array(
			'predefined_template' => false,
			'template_id'         => 1,
			'company_logo'        => '',
			'is_terms_enabled'   => false,
			'terms_conditions'   => '',
			'sent_to_admin'       => array(
				'quote_requested_email_template'    => array(
					'subject' => 'Quote Request',
					'heading' => 'You have received a new Quote Request.',
					'body'    => '<h3>Customer Details<h3>'
								 . "\r\n"
								 . '<p><strong>Username:</strong> @billing_first_name @billing_last_name</p>'
								 . "\r\n"
								 . '<p><strong>Email:</strong> @billing_email</p>'
								 . "\r\n"
								 . '<p><strong>Phone:</strong> @billing_phone</p>'
								 . "\r\n"
								 . '<p><strong>Comments:</strong> @customer_note</p>'
								 . "\r\n"
								 . '<h3>The customer has requested a quote for the following products:</h3>'
								 . "\r\n"
								 . '@order_items',
					
				),
				'quote_requested_sms_chat_template' => array(
					'body' => 'You have received a new product quote from the customer'
					. "\r\n"
					. 'User details are given below:'
					. "\r\n" .
					'Username: @billing_first_name @billing_last_name'
					. "\r\n" .
					'Email: @billing_email'
					. "\r\n" .
					'Phone: @billing_phone'
					. "\r\n" .
					'Comments: @customer_note'
					. "\r\n\r\n" .
					'@order_items',
				),
			),
			'sent_to_customer'    => array(
				'quote_requested_email_template' => array(
					'subject' => 'Quote Received',
					'heading' => 'Your Quote Request has been received.',
					'body'    => '<p>Hi @billing_first_name @billing_last_name,</p>'
					. "\r\n"
					. '<p>Your quote request is as follows:</p>'
					. "\r\n"
					. '@order_items',

				),
				'quote_approved_email_template'  => array(
					'subject' => 'Quote Approved',
					'heading' => 'Your Quote Request has been approved.',
					'body'    => '<p>Hi @billing_first_name @billing_last_name,</p>'
					. "\r\n"
					. '<p>Your quote request has been approved, To pay for this order please use the following link:</p>'
					. "\r\n"
					. '@order_items'
					. "\r\n"
					. '<p><center>@payment_link</center></p>',
				),
				'quote_rejected_email_template'  => array(
					'subject' => 'Quote Rejected',
					'heading' => 'Your Quote Request has been rejected.',
					'body'    => '<p>Hi @billing_first_name @billing_last_name,</p>'
					. "\r\n"
					. '<p>Your quote request for the following order has been rejected.</p>'
					. "\r\n" .
					'@order_items',

				),
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

	public static function get_key( $key ) {
		$settings = self::load();
		$settings = $settings->to_array();

		return $settings[ $key ];
	}
	public static function get_settings() {
		$settings = self::load();
		$settings = $settings->to_array();
		return $settings;
	}

	public static function default_template_enabled() {
		
		return true === self::get_key( 'predefined_template' );
	}

	public static function get_logo() {
		
		return self::get_key( 'company_logo' );
	}

	
	public static function get_terms_and_conditions() {
		
		return self::get_key( 'terms_conditions' );
	}

	public static function get_term_status() {
		
		return true === self::get_key( 'is_terms_enabled' );
	}


	public static  function get_template_id() {
		return  self::get_key( 'template_id' );
	}

	public  static function get_template() {

		if ( self::default_template_enabled() ) {

			$template_id = self::get_template_id();
			$template    = array_search( $template_id , self::$templates );
			return $template;
		}
		return false;
	}

	public static function get_stored_template_email_data( $send_to, $key ) {
		$settings = self::get_settings();
		
		$email_body = $settings[ $send_to ][ $key ]['body'];
		
		return $email_body;

	}

	public static function get_subject( $send_to, $key ) {
		$settings = self::get_settings();
		
		$subject = $settings[ $send_to ][ $key ]['subject'];
		
		return $subject;

	}

	public static function get_heading( $send_to, $key ) {
		$settings = self::get_settings();
		
		$header = $settings[ $send_to ][ $key ]['heading'];
		return $header;

	}
	public static function get_template_key( $status) {

		switch ($status) {
			case 'quote-requested':
				return 'quote_requested_email_template';
			case 'quote-rejected':
				return 'quote_rejected_email_template';
			case 'quote-approved':
				return 'quote_approved_email_template';
			case 'under-negotiation':
				return 'negotiation';
			case 'quote-expired':
				return 'expiry';
			default:
				return '';

		}

	}

	public static function get_email_body_for_customer( $order_id ) {
		$order   = new \WC_Order( $order_id );
		$key     = self::get_template_key($order->get_status());
		$heading = self::get_heading( 'sent_to_customer' , $key );
		if (  $order->has_status( 'quote-requested' ) ) {
			$template_body = self::get_stored_template_email_data( 'sent_to_customer' , 'quote_requested_email_template' );
		}

		if ( $order->has_status( 'quote-rejected' ) ) {
			$template_body = self::get_stored_template_email_data( 'sent_to_customer' , 'quote_rejected_email_template' );
		}

		if (  $order->has_status( 'quote-approved' ) ) {
			$template_body = self::get_stored_template_email_data( 'sent_to_customer' , 'quote_approved_email_template' );
		}

		$template_body = '<h3><center>' . $heading . '</center></h3>' . $template_body;
		$order_data    = array_values( self::get_order_info( $order_id ) );
		$data          = self::add_custom_data( $order_id , $order_data , self::get_literals() , '' );
		$message       = str_replace( $data['template_literals']  , $data['template_values']   , $template_body );
		return $message;

	}

	public static function get_sms_chat_body( $order_id ) {
			
			$order                    = new \WC_Order( $order_id );
			$template_body            = self::get_stored_template_email_data( 'sent_to_admin' , 'quote_requested_sms_chat_template' );
			$order_data               = self::get_order_info( $order_id );
			$order_data['order_item'] = self::elex_raq_generate_order_table_sms_chat( $order_id );
			$data                     = self::add_custom_data( $order_id , array_values( $order_data ) , self::get_literals() , 'chat' );

			$message = str_replace( $data['template_literals']  , $data['template_values']   , $template_body );
			
			return $message;
	}

		/** Generate order table SMS Chat. */
	public static function elex_raq_generate_order_table_sms_chat( $order_id ) {
		$order      = new \WC_Order( $order_id );
		$items      = $order->get_items();
		$order_item = __( 'Product Name: Price * Quantity = Subtotal' , 'elex-request-a-quote' ) . "\r\n";

		foreach ( $order->get_items() as $item_id => $item ) {
			$name              = $item->get_name();
			$quantity          = $item->get_quantity();
			$subtotal          = $item->get_subtotal();
			$individual_amount = floatval( $subtotal ) / floatval( $quantity );
			$order_item       .= "{$name}: {$individual_amount} * {$quantity} = {$subtotal}\r\n";
		}
		$order_item .= "\r\n";
		$order_item .= __( 'Total :' , 'elex-request-a-quote' ) . "{$order->get_total()}";

		return $order_item;
	}

	public static function get_email_body( $order_id ) {

		$order = new \WC_Order( $order_id );

		if ( $order->has_status( 'quote-requested' ) ) {
			$template_body = self::get_stored_template_email_data( 'sent_to_admin' , 'quote_requested_email_template' );
			$header        = self::get_heading( 'sent_to_admin' , 'quote_requested_email_template');
			$template_body = '<h3><center>' . $header . '</center></h3>' . $template_body;


			$order_data = array_values( self::get_order_info( $order_id ) );
			$data       = self::add_custom_data( $order_id , $order_data , self::get_literals() , '' );
			$message    = str_replace( $data['template_literals']  , $data['template_values']   , $template_body );
			return $message;
		}


	}

	public static function get_literals() {
		
		return array(
			'@billing_first_name',
			'@billing_last_name',
			'@billing_company',
			'@billing_country',
			'@billing_address_1',
			'@billing_address_2',
			'@billing_postcode',
			'@billing_city',
			'@billing_state',
			'@billing_phone',
			'@billing_email',
			'@customer_note',
			'@payment_link',
			'@order_items',
			'@order_id',
			
		);

	}
	public static function get_price_with_currency( $price) {
		$currency_position = get_option( 'woocommerce_currency_pos' );
		$currency          = get_woocommerce_currency_symbol();

		switch ($currency_position) {
			case 'left':
				return $currency . $price;
			case 'left_space':
				return $currency . ' ' . $price;
			case 'right':
				return $price . $currency;
			case 'right_space':
				return $price . ' ' . $currency;	  	  

		}


	}

	public static  function get_order_info( $order_id ) {

		$order_data                       = array();
		$order                            = wc_get_order( $order_id );
		$order_data['customer_note']      = ! empty( self::custom_get_order_notes( $order_id ) ) ? self::custom_get_order_notes( $order_id ) : '';
		$order_data['billing_first_name'] = ! empty( $order->get_billing_first_name() ) ? $order->get_billing_first_name() : '';
		$order_data['billing_last_name']  = ! empty( $order->get_billing_last_name() ) ? $order->get_billing_last_name() : '';
		$order_data['billing_company']    = ! empty( $order->get_billing_company() ) ? $order->get_billing_company() : '';
		$order_data['billing_country']    = ! empty( $order->get_billing_country() ) ? $order->get_billing_country() : '';
		$order_data['billing_address_1']  = ! empty( $order->get_billing_address_1() ) ? $order->get_billing_address_1() : '';
		$order_data['billing_address_2']  = ! empty( $order->get_billing_address_2() ) ? $order->get_billing_address_2() : '';
		$order_data['billing_postcode']   = ! empty( $order->get_billing_postcode() ) ? $order->get_billing_postcode() : '';
		$order_data['billing_city']       = ! empty( $order->get_billing_city() ) ? $order->get_billing_city() : '';
		$order_data['billing_state']      = ! empty( $order->get_billing_state() ) ? $order->get_billing_state() : '';
		$order_data['billing_phone']      = ! empty( $order->get_billing_phone() ) ? $order->get_billing_phone() : '';
		$order_data['billing_email']      = ! empty( $order->get_billing_email() ) ? $order->get_billing_email() : '';
		$order_data['order_id']           = $order_id;
		$message                          = __('Accept & Pay' , 'elex-request-a-quote');
		$order_data['payment_link']       = '<button style="padding: 15px; margin: 0 5px; border-radius: 6px; color:#fff; background-color: #10518D; border: 1px solid #10518D;box-shadow: 3px 3px 16px #10518D80;">
		<a style="color:#fff;" href=' . $order->get_checkout_payment_url() . '>' . $message . '</a></button>';
		
		$order_data['order_item'] = '';
		$is_hide_price_enabled    = QuoteListModel::is_hide_price_enabled();
		$quote_page_settings      = ListPageController::get_settings( 'quote_list_page', false );
		$terms                    = self::get_terms_and_conditions();
		$terms_enabled            = self::get_term_status();
		


		ob_start();
		include ELEX_RAQ_SRC_PATH . 'Templates/order_items.php';
		$order_data['order_item'] = ob_get_clean();

		return array(
			'billing_first_name' => $order_data['billing_first_name'],
			'billing_last_name'  => $order_data['billing_last_name'],
			'billing_company'    => $order_data['billing_company'],
			'billing_country'    => $order_data['billing_country'],
			'billing_address_1'  => $order_data['billing_address_1'],
			'billing_address_2'  => $order_data['billing_address_2'],
			'billing_postcode'   => $order_data['billing_postcode'],
			'billing_city'       => $order_data['billing_city'],
			'billing_state'      => $order_data['billing_state'],
			'billing_phone'      => $order_data['billing_phone'],
			'billing_email'      => $order_data['billing_email'],
			'customer_note'      => $order_data['customer_note'],
			'payment_link'       => $order_data['payment_link'],
			'order_item'         => $order_data['order_item'],
			'order_id'         => $order_id,

		);
	
	}
	/**
	 * Function to get the url without anchor tag.
	 *
	 * @param [type] $url
	 * @return string
	 */
	public static function get_url( $url) {

		$url = strip_tags($url, '<a>');
		$url = preg_replace('~<a href="(https?://[^"]+)".*?>(.*?)</a>~', '$2 ($1)', $url);
		return $url;
	}

	public static function add_custom_data( $order_id, $template_values, $template_literals, $chat_or_email = '' ) {
		$order         = wc_get_order( $order_id );
		$data          = array();
		$form_settings = FormSettingController::get_settings();
		$form_settings = FormSettingController::converToArray( $form_settings );
		$form_fields   = $form_settings['fields'];

		$form_fields_of_order   = $order->get_meta( '_elex_raq_default_form_details' );
		$elex_raq_default_count = $order->get_meta( '_elex_raq_default_count' );
		$count                  = 0;

		foreach ( $form_fields as $k => $v ) {
	
			if ( 'default' === $v ['connected_to'] ) {
				array_push( $template_literals, '@' . $v['slug'] );
				$label_check = QuoteListModel::elex_raq_labelcheck( $form_fields_of_order, 'slug', $v['slug'], 0 );

				if ( 'elex_raq_success' !== $label_check ) {
					if ( 0 === $count ) {
						$elex_raq_default_values =  $order->get_meta( '_elex_raq_default' );
						if ( filter_var( $elex_raq_default_values, FILTER_VALIDATE_URL ) ) {
						$url = '<a href="' . esc_attr( $elex_raq_default_values ) . '" target="_blank">Click here to open</a>';

						$url = ( '' !== $chat_or_email ) ? self::get_url($url) : $url;
						array_push( $template_values, $url);

						} elseif ( '' === $elex_raq_default_values && 'checkbox' === $form_fields_of_order [ $label_check ] ['type'] ) {
							array_push( $template_values, 'No' );
						} elseif ( '' === $elex_raq_default_values && 'radio' === $form_fields_of_order [ $label_check ] ['type'] ) {
							array_push( $template_values, 'No value selected' );
						} elseif ( '' != $elex_raq_default_values ) {
							array_push( $template_values, $elex_raq_default_values );
						}                   
					} else {
						$elex_raq_default_values =  $order->get_meta( '_elex_raq_default_' . $count );

						if ( filter_var( $elex_raq_default_values, FILTER_VALIDATE_URL ) ) {
							$url = '<a href="' . esc_attr( $elex_raq_default_values ) . '" target="_blank">Click here to open</a>';
							$url = ( '' !== $chat_or_email ) ? self::get_url($url) : $url;
							array_push( $template_values, $url);
						} elseif ( '' === $elex_raq_default_values && 'checkbox' === $form_fields_of_order [ $label_check ] ['type'] ) {
							array_push( $template_values, 'No' );
						} elseif ( '' === $elex_raq_default_values && 'radio' === $form_fields_of_order [ $label_check ] ['type'] ) {
array_push( $template_values, 'No value selected' );
						} elseif ( '' != $elex_raq_default_values ) {
						array_push( $template_values, $elex_raq_default_values );
						}
					}
				} else {
					array_push( $template_values, '' );
				}
				$count++;
			}       
		}

		$data['template_values']   = $template_values;
		$data['template_literals'] = $template_literals;
		return $data;

	
	}


/** Generate the order table. */
	public static function elex_raq_generate_order_table( $order_id ) {
		$order  = new \WC_Order( $order_id );
		$date   = gmdate( 'M d, Y' );
		$table  = '';
		$table .= "<h2 style='color:#557da1;display:block;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:16px 0 8px;text-align:left'>
				Order # $order_id ( $date )
			</h2>";
		if ( count( $order->get_items() ) > 0 ) {
			$table .= "<table cellspacing='0' cellpadding='6' style='width:100%;border:1px solid #eee' border='1'>
						<thead>
							<tr>
								<th scope='col' style='text-align:left;border:1px solid #eee;padding:12px'>Product</th>
								<th scope='col' style='text-align:left;border:1px solid #eee;padding:12px'>Quantity</th>
								<th scope='col' style='text-align:left;border:1px solid #eee;padding:12px'>Price</th>
							</tr>
						</thead>
						<tbody>";
			$table .= wc_get_email_order_items( $order );

			$table .= '</tbody><tfoot>';
			$totals = $order->get_order_item_totals();
			if ( $totals ) {
				$i = 0;
				foreach ( $totals as $total ) {
					$i++;
					$label  = $total['label'];
					$value  = $total['value'];
					$table .= "<tr>
							<th scope='row' colspan='2' style='text-align:left; border: 1px solid #eee;'>$label</th>
							<td style='text-align:left; border: 1px solid #eee;'>$value</td>
						</tr>";
				}
			}
			$table .= '</tfoot>
				</table>';
		}
		return $table;
	}
/** Get order note. */
	public static function custom_get_order_notes( $order_id ) {
		remove_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );
		$comments = get_comments(
			array(
				'post_id' => $order_id,
				'orderby' => 'comment_ID',
				'order'   => 'DESC',
				'approve' => 'approve',
				'type'    => 'order_note',
			)
		);
		$notes    = wp_list_pluck( $comments, 'comment_content' );
		add_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ) );
		return end( $notes );
	}


}
