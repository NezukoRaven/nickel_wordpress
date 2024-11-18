<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Backend\Feeds;

use QuadLayers\IGG\Models\Feeds as Models_Feeds;
use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Base;
use QuadLayers\IGG\Services\Cache;

/**
 * Api_Rest_Feeds_Delete Class
 */
class Delete extends Base {

	protected static $route_path = 'feeds';

	public function callback( \WP_REST_Request $request ) {

		try {

			$feed = json_decode( wp_json_encode( $request->get_param( 'feed_settings' ) ), true );

			if ( ! isset( $feed['id'] ) ) {
				throw new \Exception( esc_html__( 'Feed id not found.', 'insta-gallery' ), 412 );
			}
			$feed_id = $feed['id'];

			$response = Models_Feeds::instance()->delete( $feed_id );

			if ( ! $response ) {
				throw new \Exception( esc_html__( 'Can\'t delete feed, feed_id not found.', 'insta-gallery' ), 412 );
			}

			if ( ! isset( $feed['account_id'], $feed['limit'], $feed['copyright']['hide'], $feed['reel']['hide'], $feed['source'] ) ) {
				throw new \Exception( esc_html__( 'Bad Request, feed settings attributes not setted.', 'insta-gallery' ), 412 );
			}
			// Clear cache.
			$feed_min_data = array(
				'account_id'                => $feed['account_id'],
				'limit'                     => $feed['limit'],
				'hide_items_with_copyright' => $feed['copyright']['hide'],
				'hide_reels'                => $feed['reel']['hide'],
			);

			if ( 'tag' === $feed['source'] ) {
				if ( ! isset( $feed['tag'] ) ) {
					throw new \Exception( esc_html__( 'Bad Request, feed tag not setted.', 'insta-gallery' ), 412 );
				}
				if ( ! isset( $feed['order_by'] ) ) {
					throw new \Exception( esc_html__( 'Bad Request, feed order by not setted.', 'insta-gallery' ), 412 );
				}
				$feed_min_data['order_by'] = $feed['order_by'];
				$feed_min_data['tag']      = $feed['tag'];
			}

			$feed_md5 = md5( wp_json_encode( $feed_min_data ) );

			$cache_key = "feed_{$feed_md5}";

			$cache_engine = new Cache( 6, true, $cache_key );

			$cache_engine->delete( $cache_engine );

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
			'feed_settings' => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					return is_array( json_decode( wp_json_encode( $param ), true ) );
				},
			),
		);
	}

	public static function get_rest_method() {
		return \WP_REST_Server::DELETABLE;
	}
}
