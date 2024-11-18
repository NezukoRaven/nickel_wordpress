<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Backend\Feeds;

use QuadLayers\IGG\Models\Feeds as Models_Feeds;
use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Base;


/**
 * Api_Rest_Feeds_Edit Class
 */
class Edit extends Base {

	protected static $route_path = 'feeds';

	public function callback( \WP_REST_Request $request ) {

		try {

			$body = json_decode( $request->get_body(), true );

			if ( ! isset( $body['feed']['id'], $body['feed'] ) ) {
				throw new \Exception( esc_html__( 'Feed not setted.', 'insta-gallery' ), 412 );
			}

			$response = Models_Feeds::instance()->update( $body['feed']['id'], $body['feed'] );

			if ( ! $response ) {
				throw new \Exception( esc_html__( 'Feed cannot be updated.', 'insta-gallery' ), 412 );
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
		return \WP_REST_Server::EDITABLE;
	}
}
