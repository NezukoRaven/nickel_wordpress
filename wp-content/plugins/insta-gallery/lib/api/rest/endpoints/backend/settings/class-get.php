<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Backend\Settings;

use QuadLayers\IGG\Models\Settings as Models_Settings;
use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Base;

/**
 * Api_Rest_Setting_Get Class
 */

class Get extends Base {

	protected static $route_path = 'settings';

	public function callback( \WP_REST_Request $request ) {

		try {
			$response = Models_Settings::instance()->get();

			if ( null === $response || 0 === count( $response ) ) {
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

	public static function get_rest_method() {
		return \WP_REST_Server::READABLE;
	}
}
