<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Backend\Accounts;

use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Base;
use QuadLayers\IGG\Models\Accounts as Models_Accounts;
use QuadLayers\IGG\Services\Cache;

/**
 * Api_Rest_Accounts_Delete Class
 */
class Delete extends Base {

	protected static $route_path = 'accounts';

	protected $cache_engine;

	public function callback( \WP_REST_Request $request ) {

		try {
			$account_id = trim( $request->get_param( 'id' ) );

			$response = Models_Accounts::instance()->delete( $account_id );

			if ( ! $response ) {
				throw new \Exception( sprintf( esc_html__( 'Can\'t delete account, account_id not found.', 'insta-gallery' ), $account_id ), 412 );
			}

			// Clear cache

			$cache_key = "profile_{$account_id}";

			$cache_engine = new Cache( 6, true, $cache_key );

			$cache_engine->delete_key( $cache_key );

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
			'id' => array(
				'required' => true,

				/*
				'validate_callback' => function( $param, $request, $key ) {
					return is_numeric( $param );
				},
				*/
			),
		);
	}

	public static function get_rest_method() {
		return \WP_REST_Server::DELETABLE;
	}
}
