<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Backend\Settings;

use QuadLayers\IGG\Models\Settings as Models_Settings;
use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Base;

/**
 * Api_Rest_Setting_Save Class
 */
class Save extends Base {

	protected static $route_path = 'settings';

	public function callback( \WP_REST_Request $request ) {

		try {

			$body = json_decode( $request->get_body(), true );

			if ( ! is_array( $body ) ) {
				throw new \Exception( esc_html__( 'Settings not saved.', 'insta-gallery' ), 412 );
			}

			$response = Models_Settings::instance()->save( $body );

			if ( ! $response ) {
				throw new \Exception( esc_html__( 'Unknown error', 'insta-gallery' ), 412 );
			}

			return $this->handle_response( $response );

		} catch ( \Exception $e ) {
			$response = array(
				'code'    => $e->getCode(),
				'message' => $e->getMessage(),
			);
			return $this->handle_response( $response );
		}
	}

	public static function get_rest_args() {
		return array();
	}

	public static function get_rest_method() {
		return \WP_REST_Server::CREATABLE;
	}
}
