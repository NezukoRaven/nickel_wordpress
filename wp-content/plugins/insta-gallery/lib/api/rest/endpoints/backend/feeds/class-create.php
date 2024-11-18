<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Backend\Feeds;

use QuadLayers\IGG\Models\Feeds as Models_Feeds;
use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Base;

/**
 * Api_Rest_Feeds_Create Class
 */
class Create extends Base {

	protected static $route_path = 'feeds';

	public function callback( \WP_REST_Request $request ) {

		try {

			$body = json_decode( $request->get_body(), true );

			if ( empty( $body['feed'] ) ) {
				throw new \Exception( esc_html__( 'Feed not setted.', 'insta-gallery' ), 412 );
			}

			$feed = Models_Feeds::instance()->create( $body['feed'] );

			if ( ! $feed ) {
				throw new \Exception( esc_html__( 'Unknown error.', 'insta-gallery' ), 412 );
			}

			return $this->handle_response( $feed );

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
