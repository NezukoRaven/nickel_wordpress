<?php

class Custom_Widget extends WP_Widget {
 
	public function __construct() {
	   // Widget Details

	   $widget_options = array( 
		   'classname'   => 'widget_class',
		   'description' => 'This is a Custom Widget',
	   );
	   parent::__construct( 'elex_mini_quote', 'elex_mini_quote', $widget_options );
	}
 
	public function form( $instance ) {
		// Backend Form
		$title = 'elex_mini_quote';
	}
 
	public function update( $new_instance, $old_instance ) {  
		return $new_instance;
	}
 
	/*
	Function to add the MiniQuote Widget to the front end.
	*/
	public function widget( $args, $instance ) {
		// Frontend display HTML

		$title = 'elex_mini_quotelist';
		ob_start();
		include ELEX_RAQ_VIEW_PATH . 'quote/mini_quote_list.php';
		echo wp_kses_post( ob_get_clean() );

	}
 
}
