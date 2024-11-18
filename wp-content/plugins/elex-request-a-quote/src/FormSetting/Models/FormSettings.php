<?php
namespace Elex\RequestAQuote\FormSetting\Models;

// use Elex\RequestAQuote\FormSetting\Models\Fluent;


class FormSettings {

	protected $data;


	public  function __construct( array $data = array() ) {
		$this->data = $data;
	}


	public static function load() {
		
		$self = new self();

		$self->data = get_option( 'request_a_quote_form_setting', self::get_default_form_data() );

		return $self;
	}

	public function save() {

		update_option( 'request_a_quote_form_setting' , $this->data );
		return $this;

	}

	public static function get_default_form_data() {
		
		$quote_recevied = get_page_by_path( '/quote-received-page' );
		$page_url       = get_permalink( $quote_recevied->ID ); 

		$defaults = array(
			'show_form'       => true,
			'title'           => __( 'Quote Request Form' , 'elex-request-a-quote' ),
			'redirection_url' => $page_url,
			'success_message' => __( 'Your request has been submitted.' , 'elex-request-a-quote' ),
			'fields'          => 
					array( 
						array(
							'name'              => __( 'First Name' , 'elex-request-a-quote' ),
							'slug'              => 'first_name',
							'type'              => 'text',
							'placeholder'       => __( 'Add your first name.', 'elex-request-a-quote' ),
							'connected_to'      => 'billing_first_name',
							'mandatory'         => true,
							'status'            => true,
							'deletable'         => false,
							'is_editing'        => false,
							'is_radio_checkbox' => false,
							'key'               => uniqid(),
							'options'           => array(),
						
						),
						array(
							'name'              => __( 'Last Name' , 'elex-request-a-quote' ),
							'slug'              => 'last_name',
							'type'              => 'text',
							'placeholder'       => __( 'Add your last name.' , 'elex-request-a-quote' ),
							'connected_to'      => 'billing_last_name',
							'mandatory'         => true,
							'status'            => true,
							'deletable'         => false,
							'is_editing'        => false,
							'is_radio_checkbox' => false,
							'key'               => uniqid(),
							'options'           => array(),

						),
						array(
							'name'              => __( 'Email' , 'elex-request-a-quote' ),
							'slug'              => 'email',
							'type'              => 'email',
							'placeholder'       => __( 'Add your email.' , 'elex-request-a-quote' ),
							'connected_to'      => 'billing_email',
							'mandatory'         => true,
							'status'            => true,
							'deletable'         => false,
							'is_editing'        => false,
							'is_radio_checkbox' => false,
							'key'               => uniqid(),
							'options'           => array(),

						),

						array(
							'name'              => __( 'Phone' , 'elex-request-a-quote' ),
							'slug'              => 'phone',
							'type'              => 'tel',
							'placeholder'       => __( 'Enter your Phone number' , 'elex-request-a-quote' ),
							'connected_to'      => 'billing_phone',
							'mandatory'         => true,
							'status'            => true,
							'deletable'         => false,
							'is_editing'        => false,
							'is_radio_checkbox' => false,
							'key'               => uniqid(),
							'options'           => array(),

						), 
					),
			
		);

		return $defaults;
	}
	
	public function to_array() {

		$data = $this->data;

		if ( is_array( $data['fields'] ) ) {

			$data['fields'] = array_map(
				function ( FormField $field ) {
				return  $field->to_array();

				},
				$data['fields']
			);
		}
		return $data;
	}


	public function merge( $new_options ) {

		$this->data = array_merge( $this->data, $new_options );

		return $this;
	}

	public function save_form( $new_options ) {

		$this->data = array_merge( $this->data, $new_options );

		return $this;
	}

   
	/**
	 * Find a field
	 *
	 * @param string $name
	 *
	 * @return FormField|bool
	 */
	public function find_field( $name ) {
		if ( ! array_key_exists( 'fields' , $this->data ) ) {
			$fields = array();
		}
		$fields = $this->data['fields'];

		foreach ( $fields as $existing_field ) {
			if ( $existing_field->name === $name ) {
				return $existing_field;
			}
		}

		return false;
	}

	public function find_field_with_key( $key ) {
		if ( ! array_key_exists( 'fields' , $this->data ) ) {
			$fields = array();
		}
		$fields = $this->data['fields'];

		foreach ( $fields as $existing_field ) {
			if ( $existing_field->key === $key ) {
				return $existing_field;
			}
		}

		return false;
	}


	public function get_default_count() {
		if ( ! array_key_exists( 'fields' , $this->data ) ) {
			$fields = array();
		}
		$fields = $this->data['fields'];

		$count = 0;
		foreach ( $fields as $existing_field ) {
			if ( 'default' === $existing_field->connected_to ) {
				$count++;
			}
		}

		return $count;
	}



	public function find_slug( $slug ) {
		if ( ! array_key_exists( 'fields' , $this->data ) ) {
			$fields = array();
		}
		$fields = $this->data['fields'];

		foreach ( $fields as $existing_field ) {
			if ( $existing_field->slug === $slug ) {
				return $existing_field;
			}
		}

		return false;
	}


		/**
	 * Remove a field
	 *
	 * @param string $name
	 *
	 * @return FormField|bool
	 */
	public function remove_field( FormField $field ) {

		$fields = self::get_fields();

		$fields = array_filter(
			$fields,
			function ( $existing_field ) use ( $field ) {
				return $existing_field->key !== $field->key;
			}
		);


		$this->data['fields'] = $fields;

		return $this;
	}

	public static function lower( $value ) {
		return mb_strtolower( $value, 'UTF-8' );
	}


	public static function slug( $title, $separator = '-', $language = 'en' ) {

		// Convert all dashes/underscores into separator
		$flip = '-' === $separator ? '_' : '-';

		$title = preg_replace( '![' . preg_quote( $flip ) . ']+!u', $separator, $title );

		// Replace @ with the word 'at'
		$title = str_replace( '@', $separator . 'at' . $separator, $title );

		// Remove all characters that are not the separator, letters, numbers, or whitespace.
		$title = preg_replace( '![^' . preg_quote( $separator ) . '\pL\pN\s]+!u', '', self::lower( $title ) );

		// Replace all separator characters and whitespace by a single separator
		$title = preg_replace( '![' . preg_quote( $separator ) . '\s]+!u', $separator, $title );

		return trim( $title, $separator );
	}

	/**
	 * Get all the fields
	 *
	 * @return FormField[]
	 */
	public function get_fields() {
		
		return isset( $this->data['fields'] ) ? $this->data['fields'] : array();
	}

	public function add_fields( array $fields ) {
		foreach ( $fields as $field ) {
			$this->add_field( FormField::build( $field ) );
		}

		return $this;
	}

	public static function build( array $attributes ) {

		$fields = array();
		if ( array_key_exists( 'fields' , $attributes ) ) {
				$fields = $attributes['fields'];
		}
		$attributes = array_diff_key( $attributes, array_flip( [ 'fields' ] ) );
	
		$self = new self( $attributes );

	
		foreach ( $fields as $field ) {
			$self->add_field( FormField::build( $field ) );
		}
		$self->data['fields'] = $self->data['fields'] ? $self->data['fields'] : array();
		return $self;
	}

	public function add_field( FormField $field ) {

		$fields = $this->get_fields();

		$fields[] = $field;

		$this->data['fields'] = $fields;
		return $this;
	}

	public function edit_field( FormField $field, $key ) {

		$fields = $this->get_fields();

		foreach ( $fields as $index => $existing_field ) {

			if ( $existing_field->key === $key ) {
				$this->data['fields'][ $index ] = $field;
			}
		}
		return $this;

	}

	public function rearrange( $source_index, $destination_index ) {

		$fields                                     = $this->get_fields();
		$temp                                       = $this->data['fields'][ $source_index ];
		$this->data['fields'][ $source_index ]      = $this->data['fields'][ $destination_index ];
		$this->data['fields'][ $destination_index ] = $temp;
		return $this;



	}

}
