<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Backend\Feeds;

use QuadLayers\IGG\Models\Feeds as Models_Feeds;
use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Base;
/**
 * Api_Rest_Feeds_Get Class
 */
class Get extends Base {

	protected static $route_path = 'feeds';

	public function callback( \WP_REST_Request $request ) {
		try {

			$feed_id = $request->get_param( 'feed_id' );

			if ( null === $feed_id ) {
				$response = Models_Feeds::instance()->get_all();
				if ( null !== $response && 0 !== count( $response ) ) {
					return $this->handle_response( $response );
				}
				return $this->handle_response( array() );
			}

			$response = Models_Feeds::instance()->get( $feed_id );

			if ( ! $response ) {
				throw new \Exception( sprintf( esc_html__( 'Feed %s not found', 'insta-gallery' ), $feed_id ), 412 );
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
		return array(
			'feed_id' => array(
				'validate_callback' => function ( $param, $request, $key ) {
					return is_numeric( $param );
				},
			),
		);
	}

	public static function get_rest_method() {
		return \WP_REST_Server::READABLE;
	}
}
