<?php

namespace QuadLayers\IGG\Controllers;

use QuadLayers\IGG\Helpers;
use QuadLayers\IGG\Models\Feeds as Models_Feeds;
use QuadLayers\IGG\Models\Settings as Models_Settings;
use QuadLayers\IGG\Frontend\Load as Frontend;

use QuadLayers\IGG\Api\Rest\Endpoints\Frontend\User_Profile as Api_Rest_User_Profile;
use QuadLayers\IGG\Api\Rest\Endpoints\Frontend\User_Media as Api_Rest_User_Media;
use QuadLayers\IGG\Api\Rest\Endpoints\Frontend\Hashtag_Media as Api_Rest_Hashtag_Media;

class Gutenberg {

	protected static $instance;

	private function __construct() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'register_scripts' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_scripts' ) );
		add_action( 'init', array( $this, 'register_block' ) );
	}

	public function register_scripts() {
		$gutenberg = include QLIGG_PLUGIN_DIR . 'build/gutenberg/js/index.asset.php';
		wp_register_style( 'qligg-gutenberg-editor', plugins_url( '/build/gutenberg/css/editor.css', QLIGG_PLUGIN_FILE ), array(), QLIGG_PLUGIN_VERSION );
		wp_register_script( 'qligg-gutenberg', plugins_url( '/build/gutenberg/js/index.js', QLIGG_PLUGIN_FILE ), $gutenberg['dependencies'], $gutenberg['version'], true );
		wp_localize_script(
			'qligg-gutenberg',
			'qligg_gutenberg',
			array(
				'image_url'            => plugins_url( '/assets/backend/img', QLIGG_PLUGIN_FILE ),
				'QLIGG_PERSONAL_LINK'  => Helpers::get_personal_access_token_link(),
				'QLIGG_BUSSINESS_LINK' => Helpers::get_business_access_token_link(),
			)
		);
		/**
		 * Fix missing qligg_frontend object in gutenberg script
		 * Frontend is loaded in the gutenberg editor script directly
		 */
		wp_localize_script(
			'qligg-gutenberg',
			'qligg_frontend',
			array(
				'settings'       => Models_Settings::instance()->get(),
				'restRoutePaths' => array(
					'username'    => Api_Rest_User_Media::get_rest_url(),
					'tag'         => Api_Rest_Hashtag_Media::get_rest_url(),
					'userprofile' => Api_Rest_User_Profile::get_rest_url(),
				),
			)
		);
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'qligg-gutenberg-editor' );
		wp_enqueue_script( 'qligg-gutenberg' );
	}

	public function register_block() {
		register_block_type(
			'qligg/box',
			array(
				'attributes'      => $this->get_attributes(),
				'render_callback' => array( $this, 'render_callback' ),
				'style'           => array( 'qligg-swiper', 'qligg-frontend', 'qligg-backend' ),
				'script'          => array( 'qligg-swiper', 'masonry' ),
				'editor_style'    => array( 'qligg-swiper', 'qligg-frontend', 'qligg-backend' ),
				'editor_script'   => array( 'qligg-swiper', 'masonry' ),
			)
		);
	}

	public function render_callback( $attributes, $content, $block = array() ) {
		return Frontend::instance()->create_shortcode( $attributes );
	}

	private function get_attributes() {

		$feed_arg = Models_Feeds::instance()->get_args();

		$attributes = array();

		foreach ( $feed_arg as $id => $value ) {
			$attributes[ $id ] = array(
				'type'    => array( 'string', 'object', 'array', 'boolean', 'number', 'null' ),
				'default' => $value,
			);
			if ( $id === 'account_id' ) {
				$attributes[ $id ] = array(
					'type'    => array( 'string', 'object', 'array', 'boolean', 'number', 'null' ),
					'default' => '',
				);
			}
		}

		return $attributes;
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
