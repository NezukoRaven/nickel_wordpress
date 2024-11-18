<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Frontend;

use QuadLayers\IGG\Api\Rest\Endpoints\Base;
use QuadLayers\IGG\Models\Accounts as Models_Accounts;
use QuadLayers\IGG\Api\Fetch\Business\Hashtag_Media\Get as Api_Fetch_Business_Hashtag_Media;
use QuadLayers\IGG\Services\Cache;

class Hashtag_Media extends Base {

	protected static $route_path = 'frontend/hashtag-media';

	protected $media_cache_engine;
	protected $media_cache_key = 'feed';

	public function callback( \WP_REST_Request $request ) {

		$after                     = $request->get_param( 'after' );
		$account_id                = $request->get_param( 'account_id' );
		$limit                     = $request->get_param( 'limit' );
		$hide_items_with_copyright = $request->get_param( 'hide_items_with_copyright' );
		$hide_reels                = $request->get_param( 'hide_reels' );
		$pagination                = $request->get_param( 'pagination' );
		$tag                       = $request->get_param( 'tag' );
		$order_by                  = $request->get_param( 'order_by' );

		$feed_md5 = md5(
			wp_json_encode(
				array(
					'account_id'                => $account_id,
					'limit'                     => $limit,
					'hide_items_with_copyright' => $hide_items_with_copyright,
					'hide_reels'                => $hide_reels,
					'order_by'                  => $order_by,
					'tag'                       => $tag,
				)
			)
		);

		$cache_key                = "{$this->media_cache_key}_{$feed_md5}";
		$this->media_cache_engine = new Cache( 6, true, $cache_key );

		// Get cached hashtag media data.
		$media_complete_prefix = "{$this->media_cache_key}_{$feed_md5}_{$pagination}";

		$response = $this->media_cache_engine->get( $media_complete_prefix );

		// Check if $response has data, if it have return it.
		if ( ! QLIGG_DEVELOPER && ! empty( $response['response'] ) ) {
			return $response['response'];
		}

		$account = Models_Accounts::instance()->get( $account_id );

		// Check if exist an access_token and access_token_type related to id setted by param, if it is not return error.
		if ( ! isset( $account['access_token'], $account['access_token_type'] ) ) {
			return $this->handle_response(
				array(
					'code'    => 412,
					'message' => sprintf( esc_html__( 'Account id %s not found to fetch hashtag media.', 'insta-gallery' ), $account_id ),
				)
			);
		}

		$access_token = $account['access_token'];

		// Check if access_token_type is 'BUSINESS', else return error.
		if ( $account['access_token_type'] !== 'BUSINESS' ) {
			return $this->handle_response(
				array(
					'code'    => 403,
					'message' => esc_html__( 'The account must be business to show a hashtag feed.', 'insta-gallery' ),
				)
			);
		}

		// Query to Api_Fetch_Business_Hashtag_Media.
		// Get hashtag media data.
		$response = ( new Api_Fetch_Business_Hashtag_Media() )->get_data( $access_token, $account_id, $tag, $limit, $after, $order_by, $hide_items_with_copyright, $hide_reels );

		// Check if response is an error and return it.
		if ( isset( $response['message'], $response['code'] ) ) {
			return $this->handle_response( $response );
		}

		// Update user profile data cache and return it.
		if ( ! QLIGG_DEVELOPER ) {
			$this->media_cache_engine->update( $media_complete_prefix, $response );
		}

		return $this->handle_response( $response );
	}

	public static function get_rest_args() {
		return array(
			'account_id'                => array(
				'required'          => true,
				'sanitize_callback' => function ( $account_id ) {
					return sanitize_text_field( $account_id );
				},
				'validate_callback' => function ( $account_id ) {
					return is_numeric( $account_id );
				},
			),
			'limit'                     => array(
				'default'           => 12,
				'sanitize_callback' => function ( $limit ) {
					return (int) $limit;
				},
				'required'          => false,
			),
			'after'                     => array(
				'default'           => 0,
				'sanitize_callback' => function ( $after ) {
					return intval( $after );
				},
				'required'          => false,
			),
			'hide_items_with_copyright' => array(
				'default'           => false,
				'sanitize_callback' => function ( $hide_items_with_copyright ) {
					return filter_var( $hide_items_with_copyright, FILTER_VALIDATE_BOOLEAN );
				},
				'required'          => false,
			),
			'hide_reels'                => array(
				'default'           => false,
				'sanitize_callback' => function ( $hide_reels ) {
					return filter_var( $hide_reels, FILTER_VALIDATE_BOOLEAN );
				},
				'required'          => false,
			),
			'pagination'                => array(
				'default'           => 0,
				'sanitize_callback' => function ( $pagination ) {
					return intval( $pagination );
				},
				'required'          => false,
			),
			'tag'                       => array(
				'sanitize_callback' => function ( $tag ) {
					return sanitize_text_field( $tag );
				},
				'required'          => true,
			),
			'order_by'                  => array(
				'default'           => 'top_media',
				'sanitize_callback' => function ( $order_by ) {
					return sanitize_text_field( $order_by );
				},
				'required'          => false,
			),
		);
	}

	public static function get_rest_method() {
		return \WP_REST_Server::READABLE;
	}

	public function get_rest_permission() {
		return true;
	}
}
