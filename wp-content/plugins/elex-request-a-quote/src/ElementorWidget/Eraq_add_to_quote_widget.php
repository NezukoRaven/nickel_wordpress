<?php
use Elex\RequestAQuote\QuoteList\QuoteListController;

class Eraq_Add_To_Quote_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'Add to Quote';
	}

	public function get_title() {
		return esc_html__( 'Add to Quote', 'elex-request-a-quote' );
	}

	public function get_icon() {
		return 'eicon-cart-light';
	}

	public function get_categories() {
		return [ 'product' ];
	}

	public function get_keywords() {
		return [ 'Add to Quote' ];
	}

	public function render() {
		if ( is_product() ) {
			QuoteListController::add_button_to_product_page();
		}
		
	}
}
